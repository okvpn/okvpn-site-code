<?php
$loader = require_once __DIR__ . '/../application/autoload.php';

require_once __DIR__ . '/../application/AppBoot.php';


$application = 'application';

$modules = 'modules';


$system = 'system';


define('EXT', '.php');

error_reporting(E_ALL | E_STRICT);


// Set the full path to the docroot
define('DOCROOT', realpath(dirname(__FILE__).'/..').DIRECTORY_SEPARATOR);

// Make the application relative to the docroot, for symlink'd index.php
if ( ! is_dir($application) AND is_dir(DOCROOT.$application))
    $application = DOCROOT.$application;

// Make the modules relative to the docroot, for symlink'd index.php
if ( ! is_dir($modules) AND is_dir(DOCROOT.$modules))
    $modules = DOCROOT.$modules;

// Make the system relative to the docroot, for symlink'd index.php
if ( ! is_dir($system) AND is_dir(DOCROOT.$system)) {
    $system = DOCROOT.$system;
} elseif (is_dir(DOCROOT . 'vendor/kohana/core')) {
    $system = DOCROOT . 'vendor/kohana/core/';
}

// Define the absolute paths for configured directories
define('APPPATH', realpath($application).DIRECTORY_SEPARATOR);
define('MODPATH', realpath($modules).DIRECTORY_SEPARATOR);
define('SYSPATH', realpath($system).DIRECTORY_SEPARATOR);

// Clean up the configuration vars
unset($application, $modules, $system);

/**
 * Define the start time of the application, used for profiling.
 */
if ( ! defined('KOHANA_START_TIME'))
{
    define('KOHANA_START_TIME', microtime(TRUE));
}

/**
 * Define the memory usage at the start of the application, used for profiling.
 */
if ( ! defined('KOHANA_START_MEMORY'))
{
    define('KOHANA_START_MEMORY', memory_get_usage());
}


// Bootstrap the application
$application = new AppBoot();
$application->boot('dev');

$response = Request::factory(true, [], false)->execute()->send_headers(true)->body();
echo $response;
