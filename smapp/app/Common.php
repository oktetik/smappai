<?php

/**
 * The goal of this file is to allow developers a location
 * where they can overwrite core procedural functions and
 * replace them with their own. This file is loaded during
 * the bootstrap process and is called during the framework's
 * execution.
 *
 * This can be looked at as a `master helper` file that is
 * loaded early on, and may also contain additional functions
 * that you'd like to use throughout your entire application
 *
 * @see: https://codeigniter.com/user_guide/extending/common.html
 */

/**
 * ------------------------------------------------------------------
 *  SMAPP dynamic configuration bridge
 * ------------------------------------------------------------------
 *  Loads the custom `public_html/smapp_config.php` file (already
 *  required in the front-controller) and propagates its values to
 *  CodeIgniter’s main configuration objects so that runtime behaviour
 *  immediately reflects any changes performed via the admin panel.
 *
 *  Recognised keys (case–insensitive):
 *    ci_environment   → cannot mutate ENVIRONMENT at runtime
 *    app_baseURL      → \Config\App::$baseURL
 *    logger_threshold → \Config\Logger::$threshold
 *
 *  All keys are additionally exposed through the central config()
 *  repository under `smapp` so they can be fetched with:
 *      $value = config('smapp')->key;
 */
if (! function_exists('load_smapp_config')) {
    /**
     * Pushes values from smapp_config.php into CI4 config service.
     *
     * @return void
     */
    function load_smapp_config(): void
    {
        // Bail-out until the framework core functions are available
        if (! function_exists('config')) {
            return;
        }

        // Determine absolute path to the config file.
        //  - Web requests (front-controller) define FCPATH
        //  - CLI commands (php spark …) do NOT, so we must locate the file
        //    relative to the project’s workspace. Because the CodeIgniter
        //    application itself is stored in "{workspace}/smapp/" while
        //    smapp_config.php is in "{workspace}/public_html/", probing only
        //    ROOTPATH/public_html will fail when ROOTPATH already ends with
        //    "/smapp/". Therefore we try multiple candidate locations and
        //    pick the first one that exists.
        if (defined('FCPATH')) {
            $path = FCPATH . 'smapp_config.php';
        } else {
            $candidates = [
                // Workspace root == ROOTPATH (monolithic layout)
                rtrim(ROOTPATH, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'public_html' . DIRECTORY_SEPARATOR . 'smapp_config.php',
                // Workspace root is the parent of ROOTPATH (nested app layout)
                dirname(rtrim(ROOTPATH, DIRECTORY_SEPARATOR)) . DIRECTORY_SEPARATOR . 'public_html' . DIRECTORY_SEPARATOR . 'smapp_config.php',
            ];
            $path = null;
            foreach ($candidates as $candidate) {
                if (is_file($candidate)) {
                    $path = $candidate;
                    break;
                }
            }
            // Fall back to first candidate so subsequent error handling
            // (is_file check below) continues to work.
            $path ??= $candidates[0];
        }
        if (! is_file($path)) {
            return;
        }

        /**
         * Retrieve settings:
         *  – If the front-controller (index.php) has already loaded the file
         *    we reuse the global array to avoid redefining constants.
         *  – Otherwise we include_once so that the file is parsed only once.
         *
         * @var array $settings
         */
        $settings = $GLOBALS['smapp_config'] ?? include_once $path;
        if (! is_array($settings)) {
            return;
        }

        // Settings are now available in the global $smapp_config variable.
        // If you need to access them elsewhere, you can `require FCPATH . 'smapp_config.php'`
        // or reference the global array directly via `$GLOBALS['smapp_config']`.

        // --- Bind selected settings to their respective config classes ----
        // 1) Base URL
        if (array_key_exists('app_baseURL', $settings)) {
            $newBase = rtrim($settings['app_baseURL'], '/') . '/';
            $app     = config('App');
            $app->baseURL = $newBase;
            
            // Refresh URL- and Request-related services so helpers like base_url()
            // immediately reflect the updated setting without invoking deprecated methods.
            if (class_exists('\Config\Services')) {
                // Drop stale shared instances so they rebuild with the new baseURL
                \Config\Services::resetSingle('url');
                \Config\Services::resetSingle('request');
                \Config\Services::resetSingle('toolbar'); // Debug Bar must use updated baseURL
                // Recreate them so they pick up the modified App config
                service('url');
                $req = service('request');
                
                // ------------------------------------------------------------------
                // Force-refresh the SiteURI held inside the CURRENT request instance
                // so that base_url() immediately outputs the new value everywhere,
                // including inside already-instantiated controllers/views.
                // ------------------------------------------------------------------
                if (is_object($req) && method_exists($req, 'getUri')) {
                    $oldUri = $req->getUri();
                    
                    // Preserve current route while updating baseURL
                    $route  = $oldUri instanceof \CodeIgniter\HTTP\SiteURI
                        ? $oldUri->getRoutePath()
                        : ltrim($oldUri->getPath(), '/');
                    
                    // Build a fresh SiteURI with UPDATED baseURL
                    // Do NOT override host/scheme so they come from the new baseURL.
                    $newUri = new \CodeIgniter\HTTP\SiteURI($app, $route, null, null);
                    
                    // Inject the rebuilt URI back into the existing request instance
                    // IncomingRequest has no public mutator, so we use reflection.
                    try {
                        $ref = new \ReflectionObject($req);
                        if ($ref->hasProperty('uri')) {
                            $prop = $ref->getProperty('uri');
                            $prop->setAccessible(true);
                            $prop->setValue($req, $newUri);
                        }
                    } catch (\Throwable $e) {
                        // Ignore; fallback will still use old baseURL
                    }
                }
            }
        }

        // 2) Logger threshold
        if (array_key_exists('logger_threshold', $settings)) {
            $logger = config('Logger');
            $logger->threshold = $settings['logger_threshold'];
        }

        // 3) Database credentials moved to smapp_config.php
        $dbKeys = [
            'db_hostname' => 'hostname',
            'db_database' => 'database',
            'db_username' => 'username',
            'db_password' => 'password',
            'db_driver'   => 'DBDriver',
            'db_prefix'   => 'DBPrefix',
            'db_port'     => 'port',
        ];
        $dbConfigChanged = false;
        foreach ($dbKeys as $key => $field) {
            if (array_key_exists($key, $settings)) {
                $db = config('Database');
                if (! isset($db->default[$field]) || $db->default[$field] !== $settings[$key]) {
                    $db->default[$field] = $settings[$key];
                    $dbConfigChanged = true;
                }
            }
        }
        if ($dbConfigChanged && class_exists('\Config\Services')) {
            // Reset shared DB connection so new settings take effect immediately
            \Config\Services::resetSingle('db');
            // Attempt eager connection so Debug Toolbar's Database panel appears
            try {
                service('db');
            } catch (\Throwable $e) {
                log_message('error', 'SMAPP dynamic DB connect error: ' . $e->getMessage());
            }
        }

        // 4) ci_environment is read too early to overwrite at this stage,
        //    but we still keep it under config('smapp') for reference.
    }
}

// (Execution of load_smapp_config is now triggered from
//  BaseController::initController() once the framework core is ready.)
