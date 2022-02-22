<?php
/**
 * Command Initialize
 *
 * CLI Command initialization
 *
 * @access public
 * @author LH & Creatives
 * @version 0.0.1
 */

// System Definition
$rootpath = realpath(dirname(__FILE__).'/..');
define('FEGG_CODE_DIR', $rootpath . '/code');
define('FEGG_BIN_DIR', $rootpath . '/bin');
define('FEGG_DIR', $rootpath . '/fegg');

// Should be change, when you change htdocs directory
define('FEGG_HTML_DIR', $rootpath.'/htdocs');

// Load User Definition
if (file_exists(FEGG_CODE_DIR . '/config/define.php')) {
    require_once(FEGG_CODE_DIR . '/config/define.php');
}

// define('FEGG_REWRITEBASE', '');
// define('FEGG_APP_BASE', '');

/**
 * Get Fegg Instance (Application Class)
 */
function FEGG_getInstance() {
    global $classInstance;
    $instance = $classInstance->getInstance();
    return $instance;
}

/**
 * Echo CLI
 */
function FEGG_print($message)
{
    if(is_array($message) || is_object($message)) {
        foreach($message as $msg) {
            FEGG_print($msg);
        }
    } else {
        echo $message.PHP_EOL;
    }
}

/**
 * Command Error
 */
function FEGG_commandError($error)
{
    FEGG_print($error);
    exit();
}

// Get Command
array_shift($argv);
$command = array_shift($argv);

if(! $command) {
    FEGG_commandError('Command Not found');
}

// Get Parameter
$parameter = $argv;

// Require Abstract Command Class
require(FEGG_DIR.'/CommandApplication.php');

// Separate Command String
// foo         -> Foo.php / index function
// foo:bar     -> Foo.php bar function / Foo/Bar.php index function
$commands   = explode(':', $command);
$methodName = array_pop($commands);
$className  = end($commands);

// Search Command file path
$commands = array_map('ucfirst', $commands);
$commandPath = implode('/', $commands);
if(! file_exists(FEGG_BIN_DIR.'/'.$commandPath.'.php')) {
    $className    = ucfirst($methodName);
    $commandPath .= '/' . $className;
    $methodName   = 'index';
}

if(! file_exists(FEGG_BIN_DIR.'/'.$commandPath.'.php')) {
    FEGG_commandError(array(
        'Command Not found : '.$command,
        'Try load :'.$commandPath,
    ));
}

// Require Command class
require(FEGG_BIN_DIR.'/'.$commandPath.'.php');

$classInstance = new $className;

// Initialize
if (method_exists($classInstance, '__init')) {
    call_user_func_array(array($classInstance, '__init'), array());
}

// Execute
if (method_exists($classInstance, $methodName)) {
    call_user_func_array(array($classInstance, $methodName), $parameter);
} else {
    FEGG_commandError('Method Not found : "'.$methodName.'" of "'.$className.'"');
}
/* End of file Command.php */
