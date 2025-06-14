<?php

/**
 * SMAPP Helper Functions
 * 
 * Bu dosya SMAPP sisteminin yardımcı fonksiyonlarını içerir.
 */

/**
 * Config değerini al
 * 
 * @param string $key Config anahtarı
 * @param mixed $default Varsayılan değer
 * @return mixed
 */
function get_smapp_config($key, $default = null) {
    global $smapp_config;
    
    return isset($smapp_config[$key]) ? $smapp_config[$key] : $default;
}

/**
 * Config değerini ayarla
 * 
 * @param string $key Config anahtarı
 * @param mixed $value Değer
 */
function set_smapp_config($key, $value) {
    global $smapp_config;
    
    $smapp_config[$key] = $value;
}

/**
 * Tüm config değerlerini al
 *
 * @return array
 */
function get_all_smapp_config() {
    global $smapp_config;
    
    return $smapp_config;
}

/**
 * Desteklenen dilleri al
 *
 * @return array
 */
function get_supported_languages() {
    return get_smapp_config('supported_languages', ['tr' => 'Türkçe']);
}

/**
 * Varsayılan dili al
 *
 * @return string
 */
function get_default_language() {
    return get_smapp_config('default_language', 'tr');
}

/**
 * Dil kodunun geçerli olup olmadığını kontrol et
 *
 * @param string $lang
 * @return bool
 */
function is_valid_language($lang) {
    $supported = get_supported_languages();
    return array_key_exists($lang, $supported);
}

/**
 * URL'den dil kodunu çıkar
 *
 * @param string $uri
 * @return array ['language' => 'tr', 'path' => 'remaining/path']
 */
function extract_language_from_uri($uri) {
    $segments = explode('/', trim($uri, '/'));
    $first_segment = $segments[0] ?? '';
    
    if (is_valid_language($first_segment)) {
        // İlk segment geçerli bir dil kodu
        array_shift($segments); // Dil kodunu kaldır
        return [
            'language' => $first_segment,
            'path' => implode('/', $segments)
        ];
    }
    
    // Dil kodu bulunamadı, varsayılan dil kullan
    return [
        'language' => get_default_language(),
        'path' => $uri
    ];
}

/**
 * Dilli URL oluştur
 *
 * @param string $path
 * @param string $language
 * @return string
 */
function create_language_url($path = '', $language = null) {
    if ($language === null) {
        $language = get_default_language();
    }
    
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    
    // Force language route kontrolü
    $force_language_route = get_smapp_config('force_language_route', true);
    $default_language = get_default_language();
    
    if ($force_language_route || $language !== $default_language) {
        $base_url = $protocol . $host . '/' . $language;
    } else {
        $base_url = $protocol . $host;
    }
    
    if (!empty($path)) {
        $base_url .= '/' . ltrim($path, '/');
    }
    
    return $base_url;
}

/**
 * Admin Panel URL'ini oluştur
 * 
 * Bu fonksiyon admin panelinin tam URL'ini döndürür
 */
function get_admin_url($path = '') {
    // Protokolü belirle (HTTP/HTTPS)
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    
    // Host adını al
    $host = $_SERVER['HTTP_HOST'];
    
    // Admin rotasını al
    $admin_route = get_smapp_config('admin_route', 'smapp');
    
    // URL'i oluştur
    $base_url = $protocol . $host . '/' . $admin_route;
    
    // Eğer ek path varsa ekle
    if (!empty($path)) {
        $base_url .= '/' . ltrim($path, '/');
    }
    
    return $base_url;
}

/**
 * Admin URL'ini dil desteği ile oluştur
 *
 * @param string $path
 * @param string $language
 * @return string
 */
function get_admin_url_with_language($path = '', $language = null) {
    if ($language === null) {
        $language = get_default_language();
    }
    
    $admin_route = get_smapp_config('admin_route', 'smapp');
    $admin_path = $admin_route;
    
    if (!empty($path)) {
        $admin_path .= '/' . ltrim($path, '/');
    }
    
    return create_language_url($admin_path, $language);
}


/**
 * URL yönlendirme kontrolü
 *
 * Force HTTPS ve Force WWW ayarlarına göre gerekli yönlendirmeleri yapar
 */
function check_url_redirects() {
    $force_https = get_smapp_config('force_https', false);
    $force_www = get_smapp_config('force_www', false);
    
    // Mevcut URL bilgilerini al
    $is_https = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
    $has_www = strpos($_SERVER['HTTP_HOST'], 'www.') === 0;
    $host = $_SERVER['HTTP_HOST'];
    $request_uri = $_SERVER['REQUEST_URI'];
    
    $redirect_needed = false;
    $new_protocol = $is_https ? 'https' : 'http';
    $new_host = $host;
    
    // HTTPS kontrolü
    if ($force_https && !$is_https) {
        $new_protocol = 'https';
        $redirect_needed = true;
    }
    
    // WWW kontrolü
    if ($force_www && !$has_www) {
        $new_host = 'www.' . $host;
        $redirect_needed = true;
    } elseif (!$force_www && $has_www) {
        $new_host = substr($host, 4); // "www." kısmını kaldır
        $redirect_needed = true;
    }
    
    // Yönlendirme gerekiyorsa yap
    if ($redirect_needed) {
        $new_url = $new_protocol . '://' . $new_host . $request_uri;
        header('Location: ' . $new_url, true, 301);
        exit;
    }
}
if (!function_exists('admin_lang')) {
    /**
     * Admin dil anahtarını view dosyalarında kullanmak için yardımcı fonksiyon.
     * 
     * @param string $key Admin dil anahtarı (örn: 'title')
     * @param array $data Değişken verileri
     * @param string|null $locale Locale (opsiyonel)
     * @return string
     */
    function admin_lang(string $key, array $data = [], string $locale = null): string
    {
        // Try the regular flattened key first (CI4 default)
        $value = lang('Admin.' . $key, $data, $locale);
    
        // If CI4 returns the key unchanged, try to resolve dot-notation in nested arrays
        if ($value === 'Admin.' . $key) {
            // Determine locale to use
            $locale = $locale ?? service('request')->getLocale();
            $file   = APPPATH . 'Language/' . $locale . '/Admin.php';
            if (is_file($file)) {
                $arr = require $file;           // load array once
                // Traverse dot-separated segments (title -> welcome etc.)
                foreach (explode('.', $key) as $segment) {
                    if (is_array($arr) && array_key_exists($segment, $arr)) {
                        $arr = $arr[$segment];
                    } else {
                        // Fallback to original key if any segment missing
                        return $value;
                    }
                }
                if (is_string($arr)) {
                    return $arr;
                }
            }
        }
    
        return $value;
    }
}
/* ============================================================
 * Advanced Language Detection Helpers
 * ============================================================
 */

/**
 * Detect language from logged-in user preference.
 * Placeholder implementation reads session('user_language') if set.
 */
function detect_user_language(): ?string
{
    $lang = session()->get('user_language');
    return is_string($lang) && is_valid_language($lang) ? $lang : null;
}

/**
 * Detect language from browser’s Accept-Language header.
 */
function detect_browser_language(): ?string
{
    if (! isset($_SERVER['HTTP_ACCEPT_LANGUAGE'])) {
        return null;
    }
    $accepted = strtolower($_SERVER['HTTP_ACCEPT_LANGUAGE']);
    // Split by comma and quality value
    $parts = array_map(fn ($p) => trim(explode(';', $p)[0]), explode(',', $accepted));
    foreach ($parts as $code) {
        // keep only primary subtag (en-US -> en)
        $primary = explode('-', $code)[0];
        if (is_valid_language($primary)) {
            return $primary;
        }
    }
    return null;
}

/**
 * Detect language from IP address country code (very rough).
 * Uses a tiny mapping table for demo purposes.
 */
function detect_ip_language(): ?string
{
    if (! isset($_SERVER['REMOTE_ADDR'])) {
        return null;
    }
    $ip = $_SERVER['REMOTE_ADDR'];
    // In production you’d call a GeoIP service. Here we stub with simple check:
    // 127.0.0.1 => default, else fall through.
    $countryToLang = [
        'tr' => 'tr',
        'us' => 'en',
        'gb' => 'en',
        'de' => 'de',
        'fr' => 'fr',
    ];
    // Dummy country code derive
    $cc = strtolower(substr($ip, -2)); // obviously wrong, just placeholder
    return $countryToLang[$cc] ?? null;
}

/**
 * Determine the best language according to priority list and config flags.
 *
 * @return string Best language code
 */
function get_best_language(): string
{
    $default = get_default_language();

    $priority = get_smapp_config('language_detect_priority', ['user','ip','browser','default']);

    // Ensure priority is array
    if (! is_array($priority)) {
        $priority = explode(',', (string) $priority);
    }

    $useUser    = get_smapp_config('language_detect_use_user', true);
    $useIP      = get_smapp_config('language_detect_use_ip', true);
    $useBrowser = get_smapp_config('language_detect_use_browser', true);

    foreach ($priority as $source) {
        $source = trim(strtolower($source));
        switch ($source) {
            case 'user':
                if ($useUser && ($lang = detect_user_language())) {
                    return $lang;
                }
                break;
            case 'ip':
                if ($useIP && ($lang = detect_ip_language())) {
                    return $lang;
                }
                break;
            case 'browser':
                if ($useBrowser && ($lang = detect_browser_language())) {
                    return $lang;
                }
                break;
            case 'default':
                return $default;
        }
    }

    return $default;
}