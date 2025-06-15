<?php

namespace Config;

use CodeIgniter\Events\Events;
use CodeIgniter\Exceptions\FrameworkException;
use CodeIgniter\HotReloader\HotReloader;

/*
 * --------------------------------------------------------------------
 * Application Events
 * --------------------------------------------------------------------
 * Events allow you to tap into the execution of the program without
 * modifying or extending core files. This file provides a central
 * location to define your events, though they can always be added
 * at run-time, also, if needed.
 *
 * You create code that can execute by subscribing to events with
 * the 'on()' method. This accepts any form of callable, including
 * Closures, that will be executed when the event is triggered.
 *
 * Example:
 *      Events::on('create', [$myInstance, 'myMethod']);
 */

Events::on('pre_system', static function (): void {

    /**
     * ------------------------------------------------------------------
     *  Load SMAPP dynamic configuration EARLY for both web and CLI.
     *  This ensures CLI commands like `php spark migrate` receive the
     *  database credentials pulled from public_html/smapp_config.php.
     * ------------------------------------------------------------------
     */
        // ------------------------------------------------------------------
        // Ensure the helper function is available during very early CLI boots
        // where app/Common.php might not yet be included.
        // ------------------------------------------------------------------
        if (! function_exists('load_smapp_config')) {
            $commonPath = APPPATH . 'Common.php';
            if (is_file($commonPath)) {
                require_once $commonPath;
            }
        }
        if (function_exists('load_smapp_config')) {
            load_smapp_config();
        }
    if (ENVIRONMENT !== 'testing') {
        if (ini_get('zlib.output_compression')) {
            throw FrameworkException::forEnabledZlibOutputCompression();
        }

        while (ob_get_level() > 0) {
            ob_end_flush();
        }

        ob_start(static fn ($buffer) => $buffer);
    }

    /*
     * --------------------------------------------------------------------
     * Debug Toolbar Listeners.
     * --------------------------------------------------------------------
     * If you delete, they will no longer be collected.
     */
    if (CI_DEBUG && ! is_cli()) {
        // ------------------------------------------------------------------
        //  Ensure SMAPP dynamic configuration (e.g. updated baseURL) is
        //  applied *before* the Debug Toolbar builds its internal URLs.
        // ------------------------------------------------------------------
        if (function_exists('load_smapp_config')) {
            load_smapp_config();
        }

        Events::on('DBQuery', 'CodeIgniter\Debug\Toolbar\Collectors\Database::collect');
        service('toolbar')->respond();
        // Hot Reload route - for framework use on the hot reloader.
        if (ENVIRONMENT === 'development') {
            service('routes')->get('__hot-reload', static function (): void {
                (new HotReloader())->run();
            });
        }
    }
});
