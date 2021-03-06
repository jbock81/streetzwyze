<?php
namespace Robo\Task\Bower;

use Robo\Task\Bower;

/**
 * Bower Update
 *
 * ``` php
 * <?php
 * // simple execution
 * $this->taskBowerUpdate->run();
 *
 * // prefer dist with custom path
 * $this->taskBowerUpdate('path/to/my/bower')
 *      ->noDev()
 *      ->run();
 * ?>
 * ```
 */
class Update extends Base
{
    protected $action = 'update';

    public function run()
    {
        $this->printTaskInfo('Update Bower packages: ' . $this->arguments);
        return $this->executeCommand($this->getCommand());
    }
}