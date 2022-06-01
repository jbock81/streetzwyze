<?php

/**
 * This is project's console commands configuration for Robo task runner.
 *
 * @see http://robo.li/
 */
require __DIR__ . '/_init.php';
require_once 'vendor/intelworx/ideo-framework/framework.php';

use intelworx\dbvc\DBMigration;
use intelworx\dbvc\DBMigrationMode;
use Robo\Tasks;

class RoboFile extends Tasks {


    private static $configs = array(
        'test' => array(
            'server' => ['staging.cashvault.com.ng'],
            'branch' => 'master',
        ),
        'staging' => array(
            'server' => ['staging.cashvault.com.ng'],
            'branch' => 'master',
        ),
        'live' => array(
            'server' => ['liveserver.com'],
            'branch' => 'master'
        )
    );
    private static $defaultConfig = array(
        'app_path' => '~/public_html',
        'port' => 22,
        'branch' => 'master',
        'user' => 'tripica1',
        'composer_path' => null,
    );

    /**
     * 
     * Performs migration of database using revisions in /dbversions
     * @option $current-version the version to use as the base version, if the applied revisions 
     *  is higher than this, it will be used, but no version less than this will be considered
     * 
     * @option $mode The mode to run the migration in, see documentation for info on modes available.
     * @option $out-of-order when specified, all versions greater that --current-version 
     * that are yet to be applied will be applied during migration, use this option when
     * your dabtabase is out of sync, or there are multiple branches.
     */
    public function dbMigrate($opt = ['out-of-order' => false, 'current-version' => 0, 'mode' => DBMigrationMode::NON_INTERACTIVE]) {
        SystemConfig::init(SYSTEM_ENV);
        $sysConfig = SystemConfig::getInstance();
        $dbConfig = $sysConfig->db;
        $dbConfig['adapter'] = 'MySQL';
        $dbConfig['port'] = isset($dbConfig['port']) ? $dbConfig['port'] : 3306;
        $dbConfig['username'] = $sysConfig->db['user'];

        $migration = new DBMigration(APP_ROOT . 'dbversions', $dbConfig);
        $migration->runMigration($opt['mode'], $opt['current-version'], $opt['out-of-order']);
    }

    private function getDeploymentConfig($env) {
        return array_merge(self::$defaultConfig, (array) self::$configs[$env]);
    }

    public function gitPush($branch = 'master') {
        $this->say('Commiting unstaged changes');
        $result1 = $this->taskGitStack()
                ->add('-A')
                ->commit('"Auto-commit before deployment"')
                ->pull('origin', $branch)
                ->run();
        /* @var $result Result */
        if (!$result1 || !$result1->wasSuccessful()) {
            $this->yell('Git PUSH failed!');
            return;
        }

        try {
            //migrate db
            $this->dbMigrate();
        } catch (Exception $ex) {
            $this->yell('DB Migration failed: [' . $ex->getMessage() . ']');
            return;
        }

        $result = $this->taskGitStack()
                ->push('origin', $branch)
                ->run();

        if (!$result || !$result->wasSuccessful()) {
            $this->yell('Git PUSH failed!');
            return;
        }

        return $result;
    }

    public function gitPull($branch = null) {
        return $this->taskGitStack()
                        ->pull('origin', $branch)
                        ->run();
    }

    private function getComposer($install = true) {
        $local = __DIR__ . '/composer.phar';
        if (file_exists($local)) {
            return $local;
        }

        $baseCommand = strncasecmp(PHP_OS, 'WIN', 3) == 0 ? 'where' : 'which';
        $result = $this->taskExec("{$baseCommand} composer")
                ->run();

        $data = $result->getMessage();
        if ($data) {
            return preg_split('/[\r\n]+/', trim($data))[0];
        }

        if ($install) {
            $this->say('Composer was not found, will attempt to install');
            $this->taskExec("curl -sS https://getcomposer.org/installer | php")
                    ->run();
            if (file_exists($local)) {
                return $local;
            }
        }

        return null;
    }

    protected function taskDeploy($env, $identityFile, array $options = ['skip-push' => false]) {
        $config = $this->getDeploymentConfig($env);

        if (!$options['skip-push']) {
            $this->gitPush($config['branch']);
        } else {
            $this->say('Skipping push');
        }

        $servers = $config['server'];
        if (empty($servers)) {
            $this->yell('Deployment fail, no server specified for : [' . $env . ']');
            return -1;
        }

        if (!is_array($servers)) {
            $servers = [$servers];
        }

        $remoteTasks = array();
        //change to app folder
        $remoteTasks[] = "export CONFIG='{$env}'";
        $remoteTasks[] = "cd {$config['app_path']}";
        $remoteTasks[] = "git pull";
        $remoteTasks[] = "git checkout {$config['branch']}";
        //run upgrade
        $upgradeTask = 'vendor/bin/robo upgrade';
        if (isset($options['tasks']) && trim($options['tasks'])) {
            $upgradeTask .= ' --tasks=' . escapeshellarg($options['tasks']);
        }
        $remoteTasks[] = $upgradeTask;

        if (!$identityFile || !file_exists($identityFile)) {
            $this->yell('No valid identity file was found!');
            $identityFile = null;
        }

        foreach ($servers as $server) {
            $parts = explode(':', $server);
            $port = count($parts) > 1 ? $parts[1] : $config['port'];
            $this->say('Deploying to server: ' . $parts[0] . ' via port : ' . $port);
            $taskSsh = $this->taskSshExec($parts[0], isset($options['user']) ? $options['user'] : $config['user']);

            /* @var $taskSsh SshExecTask */
            $taskSsh->port($port)->printed(true);
            if ($identityFile) {
                $taskSsh->identityFile($identityFile);
            }

            //add tasks to run on server
            foreach ($remoteTasks as $serverCmd) {
                $taskSsh->exec($serverCmd);
            }
            //fix for windows:
            $command = str_replace('\'', '"', $taskSsh->getCommand());
            $this->say('Converted command to : `' . $command . '`');
            $result = $this->taskExec($command)
                    ->run();
            //run as raw command
            if (!$result || !$result->wasSuccessful()) {
                $this->yell('Deployment failed to server!');
            } else {
                $this->say('Deployment done');
            }
        }

        return $result;
    }

    public function deployStaging($identityFile = '', array $opt = ['skip-push' => false, 'tasks' => '']) {
        return $this->taskDeploy('staging', $identityFile, $opt);
    }

    public function deployTest($identityFile = '', array $opt = ['skip-push' => false, 'tasks' => '']) {
        return $this->taskDeploy('test', $identityFile, $opt);
    }

    public function deployLive($identityFile = '', array $opt = ['skip-push' => false, 'tasks' => '']) {
        return $this->taskDeploy('live', $identityFile, $opt);
    }

    public function composerUpdate() {
        $composer = $this->getComposer(true);
        if (!$composer) {
            $this->say('Composer was not found, exiting,');
            exit(-1);
        }

        $this->say("Composer Path: {$composer}");
        return $this->taskComposerUpdate($composer)
                        ->run();
    }

    public function upgrade($opt = ['tasks' => '']) {
        $tasks = array_filter(preg_split('/[,\s]+/', $opt['tasks']));
        $upgradeTasks = [
            'git' => 'gitPull',
            'db' => 'dbMigrate',
            'assets' => 'buildAll',
            'composer' => 'composerUpdate',
            'scripts' => 'upgradeScripts',
        ];

        if (empty($tasks)) {
            $tasks = array_keys($upgradeTasks);
        }

        $tasksToPerform = [];
        copyElementsAtKey($tasks, $upgradeTasks, $tasksToPerform, true);
        foreach ($tasksToPerform as $k => $task) {
            $this->say('Task being performed: ' . $k . ' [' . $task . ']');
            $this->$task();
        }
        return true;
    }

    public function upgradeScripts($opt = ['params' => '']) {
        $pattern = __DIR__ . '/cron/upgrade_*.php';
        $files = glob($pattern);
        $this->say('Found ' . count($files) . ' files');

        foreach ($files as $upgScript) {
            $logFile = $upgScript . '.run.log';
            $errorLog = $upgScript . '.error.log';
            if (file_exists($logFile)) {
                $this->say($logFile . ' Has been run');
                continue;
            }

            $this->say('Running ' . $logFile);
            $params = isset($opt['params']) && $opt['params'] ? $opt['params'] : '';
            $result = $this->taskExec("php {$upgScript} $params")
                    ->run();

            if ($result->getExitCode() == 0) {
                $this->say('Run successfully, writing log : ' . $logFile);
                file_put_contents($logFile, $result->getMessage());
            } else {
                $this->say('Run failed, writing to error log: ' . $errorLog);
                file_put_contents($errorLog, $result->getMessage());
            }
        }
    }

}
