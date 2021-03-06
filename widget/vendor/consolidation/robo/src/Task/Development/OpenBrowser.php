<?php
namespace Robo\Task\Development;

use Robo\Task\BaseTask;
use Symfony\Component\Process\ProcessUtils;
use Robo\Result;

/**
 * Opens the default's user browser
 * code inspired from openBrowser() function in https://github.com/composer/composer/blob/master/src/Composer/Command/HomeCommand.php
 *
 * ``` php
 * <?php
 * // open one browser window
 * $this->taskOpenBrowser('http://localhost')
 *  ->run();
 *
 * // open two browser windows
 * $this->taskOpenBrowser([
 *     'http://localhost/mysite',
 *     'http://localhost/mysite2'
 *   ])
 *   ->run();
 * ```
 */
class OpenBrowser extends BaseTask
{
    protected $urls = [];

    public function __construct($url)
    {
        $this->urls = (array) $url;
    }

    public function run()
    {
        $openCommand = $this->getOpenCommand();

        if (empty($openCommand)) {
            return Result::error($this, 'no suitable browser opening command found');
        }

        foreach ($this->urls as $url) {
            passthru(sprintf($openCommand, ProcessUtils::escapeArgument($url)));
            $this->printTaskInfo("Opened <info>{$url}</info>");
        }

        return Result::success($this);
    }

    private function getOpenCommand()
    {
        if (defined('PHP_WINDOWS_VERSION_MAJOR')) {
            return 'start "web" explorer "%s"';
        }

        passthru('which xdg-open', $linux);
        passthru('which open', $osx);

        if (0 === $linux) {
            return 'xdg-open %s';
        }

        if (0 === $osx) {
            return 'open %s';
        }
    }
}
