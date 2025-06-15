<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// SMAPP Config dosyasını yükle (eğer henüz yüklenmemişse)
$config_file = FCPATH . 'smapp_config.php';
if (file_exists($config_file) && !defined('SMAPP_CONFIG_LOADED')) {
    require_once $config_file;
}

// Desteklenen dilleri al
$supported_languages = function_exists('get_supported_languages') ? get_supported_languages() : ['tr' => 'Türkçe'];
$language_codes = array_keys($supported_languages);
$default_language = function_exists('get_default_language') ? get_default_language() : 'tr';

// Admin rotasını belirle
$admin_route = 'smapp'; // Varsayılan değer
if (function_exists('get_smapp_config')) {
    $admin_route = get_smapp_config('admin_route', 'smapp');
}

// Ana sayfa rotaları (dil desteği ile)
$routes->get('/', 'Home::index'); // Varsayılan dil

// Force language route ayarını kontrol et
$force_language_route = function_exists('get_smapp_config') ? get_smapp_config('force_language_route', true) : true;

// Dilli rotalar
foreach ($language_codes as $lang) {
    // Eğer force_language_route false ise ve bu varsayılan dil ise, dilli rotaları ekleme
    if (!$force_language_route && $lang === $default_language) {
        continue; // Varsayılan dil için dilli rotaları atla
    }
    
    // Ana sayfa
    $routes->get($lang, 'Home::index', ['filter' => 'language:' . $lang]);
    $routes->get($lang . '/', 'Home::index', ['filter' => 'language:' . $lang]);
    
    // Admin panel rotaları (dil desteği ile)
    $routes->group($lang . '/' . $admin_route, ['filter' => 'language:' . $lang], function($routes) {
        // Admin ana sayfası
        $routes->get('/', 'Admin::index');
        
        // Admin alt sayfaları
        $routes->get('dashboard', 'Admin::dashboard');
        $routes->get('users', 'Admin::users');
        $routes->match(['get', 'post'], 'settings', 'Admin::settings');
        $routes->match(['get', 'post'], 'debug', 'Admin::debug');

        // Language management
        $routes->group('languages', ['namespace' => 'App\Controllers\Admin'], function($routes){
            $routes->get('/',               'Languages::index');
            // Filtered lists: /languages/active, /languages/pending, etc.
            $routes->get('(:segment)',      'Languages::index/$1');
            $routes->get('create',          'Languages::create');
            $routes->post('store',          'Languages::store');
            $routes->get('edit/(:num)',     'Languages::edit/$1');
            $routes->post('update/(:num)',  'Languages::update/$1');
            $routes->post('delete/(:num)',  'Languages::delete/$1');
            $routes->post('restore/(:num)', 'Languages::restore/$1');
            $routes->post('purge/(:num)',   'Languages::purge/$1');
            $routes->post('default/(:num)', 'Languages::setDefault/$1');
        });
        
        // Admin API rotaları
        $routes->group('api', function($routes) {
            $routes->get('stats', 'Admin\Api::stats');
            $routes->post('save', 'Admin\Api::save');
            $routes->get('health', 'Admin\Api::health');
        });
    });
    
    // Admin login sayfaları (dil desteği ile)
    $routes->get($lang . '/' . $admin_route . '-login', 'Admin::login', ['filter' => 'language:' . $lang]);
    $routes->post($lang . '/' . $admin_route . '-login', 'Admin::doLogin', ['filter' => 'language:' . $lang]);
    $routes->get($lang . '/' . $admin_route . '-logout', 'Admin::logout', ['filter' => 'language:' . $lang]);
    
    // Genel sayfa rotaları (örnek)
    $routes->get($lang . '/hakkimizda', 'Pages::about', ['filter' => 'language:' . $lang]);
    $routes->get($lang . '/iletisim', 'Pages::contact', ['filter' => 'language:' . $lang]);
    $routes->get($lang . '/about', 'Pages::about', ['filter' => 'language:' . $lang]);
    $routes->get($lang . '/contact', 'Pages::contact', ['filter' => 'language:' . $lang]);
}

// Varsayılan dil için admin rotaları (dil kodu olmadan)
if (!$force_language_route) {
    $routes->group($admin_route, ['filter' => 'language:' . $default_language], function($routes) {
        $routes->get('/', 'Admin::index');
        $routes->get('dashboard', 'Admin::dashboard');
        $routes->get('users', 'Admin::users');
        $routes->match(['get', 'post'], 'settings', 'Admin::settings');
        $routes->match(['get', 'post'], 'debug', 'Admin::debug');

        // Language management
        $routes->group('languages', ['namespace' => 'App\Controllers\Admin'], function($routes){
            $routes->get('/',               'Languages::index');
            // Filtered lists for default-language (no locale in URL)
            $routes->get('(:segment)',      'Languages::index/$1');
            $routes->get('create',          'Languages::create');
            $routes->post('store',          'Languages::store');
            $routes->get('edit/(:num)',     'Languages::edit/$1');
            $routes->post('update/(:num)',  'Languages::update/$1');
            $routes->post('delete/(:num)',  'Languages::delete/$1');
            $routes->post('restore/(:num)', 'Languages::restore/$1');
            $routes->post('purge/(:num)',   'Languages::purge/$1');
            $routes->post('default/(:num)', 'Languages::setDefault/$1');
        });
        
        $routes->group('api', function($routes) {
            $routes->get('stats', 'Admin\Api::stats');
            $routes->post('save', 'Admin\Api::save');
            $routes->get('health', 'Admin\Api::health');
        });
    });
    
    $routes->get($admin_route . '-login', 'Admin::login', ['filter' => 'language:' . $default_language]);
    $routes->post($admin_route . '-login', 'Admin::doLogin', ['filter' => 'language:' . $default_language]);
    $routes->get($admin_route . '-logout', 'Admin::logout', ['filter' => 'language:' . $default_language]);
}
