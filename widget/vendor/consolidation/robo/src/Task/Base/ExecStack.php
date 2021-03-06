<?php
namespace Robo\Task\Base;

use Robo\Task\CommandStack;
use Robo\Task\Base;

/**
 * Execute commands one by one in stack.
 * Stack can be stopped on first fail if you call `stopOnFail()`.
 *
 * ```php
 * <?php
 * $this->taskExecStack()
 *  ->stopOnFail()
 *  ->exec('mkdir site')
 *  ->exec('cd site')
 *  ->run();
 *
 * ?>
 * ```
 *
 * @method $this stopOnFail()
 */
class ExecStack extends CommandStack
{
}
