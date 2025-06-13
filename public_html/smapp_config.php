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
// Helper fonksiyonları yükle
$helper_file = __DIR__ . '/../smapp/app/Helpers/smapp_helper.php';
if (file_exists($helper_file)) {
    require_once $helper_file;
}

// Config dosyasının yüklendiğini belirt
define('SMAPP_CONFIG_LOADED', true);

?>