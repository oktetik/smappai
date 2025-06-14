<?php
/**
 * SMAPP System Configuration File
 * 
 * Bu dosya sistemin temel ayarlarını içerir.
 * Ayarları değiştirdikten sonra cache'i temizlemeyi unutmayın.
 */

// Sistem ayarlarını tanımla
$smapp_config = array();

/**
 * Admin Panel Rotası
 * 
 * Admin panelinin erişim yolunu belirler.
 * Varsayılan: 'smapp'
 * Örnek: domain.com/smapp
 * 
 * Güvenlik için bu değeri değiştirmeniz önerilir.
 */$smapp_config['admin_route'] = 'smapp';

/**
 * Force HTTPS
 *
 * Tüm istekleri HTTPS'e yönlendirir.
 * true: HTTPS zorunlu
 * false: HTTP/HTTPS serbest
 */
$smapp_config['force_https'] = false;
/**
 * Force WWW
 *
 * Tüm istekleri www subdomain'i ile yönlendirir.
 * true: www zorunlu (www.domain.com)
 * false: www olmadan (domain.com)
 */
$smapp_config['force_www'] = false;
/**
 * Varsayılan Dil
 *
 * Sistemin varsayılan dili
 */
$smapp_config['default_language'] = 'tr';
/**
 * Desteklenen Diller
 *
 * Sistemde kullanılabilir diller
 * Format: 'kod' => 'Ad'
 */
$smapp_config['supported_languages'] = [
    'tr' => 'Türkçe',
    'en' => 'English'
];

/**
 * Dil Rotası Zorunlu
 *
 * true: URL'de dil kodu zorunlu (domain.com/tr/sayfa)
 * false: Varsayılan dil için dil kodu opsiyonel (domain.com/sayfa)
 */
$smapp_config['force_language_route'] = true;
/**
 * Smart Language Detection
 */
$smapp_config['language_detect_use_ip'] = false;

$smapp_config['language_detect_use_browser'] = false;


$smapp_config['language_detect_use_user'] = false;


$smapp_config['language_detect_priority'] = ['user','ip','browser','default'];


/**
 * Environment Settings
 */
$smapp_config['ci_environment'] = 'production';


$smapp_config['app_baseURL'] = 'http://localhost/';


$smapp_config['db_hostname'] = 'localhost';
$smapp_config['db_database'] = 'ci4';
$smapp_config['db_username'] = 'root';
$smapp_config['db_password'] = 'root';
$smapp_config['db_driver']   = 'MySQLi';
$smapp_config['db_prefix']   = '';
$smapp_config['db_port']     = 3306;


$smapp_config['logger_threshold'] = 4;
























// Helper fonksiyonları yükle
$helper_file = __DIR__ . '/../smapp/app/Helpers/smapp_helper.php';
if (file_exists($helper_file)) {
    require_once $helper_file;
}

// Config dosyasının yüklendiğini belirt (redeclare guard)
if (!defined('SMAPP_CONFIG_LOADED')) {
    define('SMAPP_CONFIG_LOADED', true);
}

// ---------------------------------------------------------------
//  Return the array so bootstrap can import settings dynamically
// ---------------------------------------------------------------
return $smapp_config;

?>