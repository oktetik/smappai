<?php

use CodeIgniter\Boot;
use Config\Paths;

/*
 *---------------------------------------------------------------
 * CHECK PHP VERSION
 *---------------------------------------------------------------
 */

$minPhpVersion = '8.1'; // If you update this, don't forget to update `spark`.
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    $message = sprintf(
        'Your PHP version must be %s or higher to run CodeIgniter. Current version: %s',
        $minPhpVersion,
        PHP_VERSION,
    );

    header('HTTP/1.1 503 Service Unavailable.', true, 503);
    echo $message;

    exit(1);
}

/*
 *---------------------------------------------------------------
 * SET THE CURRENT DIRECTORY
 *---------------------------------------------------------------
 */

// Path to the front controller (this file)
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

// Ensure the current directory is pointing to the front controller's directory
if (getcwd() . DIRECTORY_SEPARATOR !== FCPATH) {
    chdir(FCPATH);
}

/*
 *---------------------------------------------------------------
 * LOAD SMAPP CUSTOM CONFIG
 *---------------------------------------------------------------
 */

// SMAPP özel config dosyasını yükle
/**
 * Load SMAPP settings and capture the returned array so we can
 * reference it immediately (before any helper has a chance to
 * populate $GLOBALS).
 *
 * The file returns an associative array like:
 *   [ 'ci_environment' => 'development', ... ]
 */
$smapp_config = require FCPATH . 'smapp_config.php';
// Load SMAPP helper functions early so front-controller logic can access check_url_redirects()
require_once FCPATH . '../smapp/app/Helpers/smapp_helper.php';

// ---------------------------------------------------------------
//  Sync CodeIgniter runtime ENVIRONMENT with smapp_config.php
// ---------------------------------------------------------------
if (isset($smapp_config['ci_environment']) && is_string($smapp_config['ci_environment'])) {
    // Make it available for Boot::defineEnvironment()
    putenv('CI_ENVIRONMENT=' . $smapp_config['ci_environment']);  // <- Boot::defineEnvironment() kullanır
    $_SERVER['CI_ENVIRONMENT'] = $smapp_config['ci_environment']; // eski alışkanlık
    $_ENV['CI_ENVIRONMENT']    = $smapp_config['ci_environment']; // modern yol
    
    // Frameworkün aslında kullandığı sabit ENVIRONMENT'tır.
    // Boot::defineEnvironment() bunu tanımlamadan önce biz erken tanımlarız
    // ki doğru bootstrap dosyası (Config/Boot/development.php vs) yüklensin.
    if (! defined('ENVIRONMENT')) {
        define('ENVIRONMENT', $smapp_config['ci_environment']);
    }
}

error_reporting(-1);
ini_set('display_errors', '1');

// URL yönlendirme kontrolü yap
check_url_redirects();

/*
 *---------------------------------------------------------------
 * BOOTSTRAP THE APPLICATION
 *---------------------------------------------------------------
 * This process sets up the path constants, loads and registers
 * our autoloader, along with Composer's, loads our constants
 * and fires up an environment-specific bootstrapping.
 */

// LOAD OUR PATHS CONFIG FILE
// This is the line that might need to be changed, depending on your folder structure.
require FCPATH . '../smapp/app/Config/Paths.php';
// ^^^ Change this line if you move your application folder

$paths = new Paths();

// LOAD THE FRAMEWORK BOOTSTRAP FILE
require $paths->systemDirectory . '/Boot.php';

exit(Boot::bootWeb($paths));
