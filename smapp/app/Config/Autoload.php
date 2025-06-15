<?php

namespace Config;

use CodeIgniter\Config\AutoloadConfig;

class Autoload extends AutoloadConfig
{
    /**
     * -------------------------------------------------------------------
     * Class Maps
     * -------------------------------------------------------------------
     * This is a key/value array to map class names and paths.
     * The keys are the class names and the values are the location of the corresponding file.
     *
     * Does not need to be complete. Only set the classes that are not following the naming defaults.
     *
     * @var array
     */
    public $psr4 = [
        // Default namespaces for the application
        'App'    => APPPATH,            // Allows App\* classes (default application namespace)
        'Config' => APPPATH . 'Config', // Explicit mapping for configuration classes
    ];
    
    /**
     * -------------------------------------------------------------------
     * Helpers
     * -------------------------------------------------------------------
     * This array lists the helpers that should be automatically loaded.
     *
     * @var array
     */
    // Auto-load helpers across the app
    // Added project helper 'smapp' so admin_lang() is available in views
    public $helpers = ['form', 'language', 'smapp'];
}
