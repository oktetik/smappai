<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class Admin extends BaseController
{
    /**
     * Admin ana sayfası
     */
    public function index()
    {
        // Admin paneli ana sayfası
        $data = [
            'title' => $this->adminLang('title'),
            'admin_route' => get_smapp_config('admin_route', 'smapp'),
            'admin_url' => get_admin_url_with_language('', $this->language),
            'lang' => $this->lang,
            'language' => $this->language,
            'supported_languages' => get_supported_languages()
        ];
        
        return view('admin/index', $data);
    }
    
    /**
     * Admin dashboard
     */
    public function dashboard()
    {
        $data = [
            'title' => 'Dashboard',
            'admin_route' => get_smapp_config('admin_route', 'smapp')
        ];
        
        return view('admin/dashboard', $data);
    }
    
    /**
     * Kullanıcı yönetimi
     */
    public function users()
    {
        $data = [
            'title' => 'Kullanıcı Yönetimi',
            'admin_route' => get_smapp_config('admin_route', 'smapp')
        ];
        
        return view('admin/users', $data);
    }
    
    /**
     * Sistem ayarları
     */
    public function settings()
    {
        $data = [
            'title' => $this->adminLang('system_settings'),
            'admin_route' => get_smapp_config('admin_route', 'smapp'),
            'current_admin_route' => get_smapp_config('admin_route', 'smapp'),
            'lang' => $this->lang,
            'language' => $this->language,
            'supported_languages' => get_supported_languages()
        ];
        
        // Basit test - POST isteği geldi mi?
        $data['debug_method'] = $this->request->getMethod();
        $data['debug_post_data'] = $this->request->getPost();
        
        // POST isteği ise ayarları kaydet
        if (strtolower($this->request->getMethod()) === 'post') {
            $data['debug_message'] = 'POST isteği alındı!';
            $new_admin_route = trim($this->request->getPost('admin_route'));
            $current_route = get_smapp_config('admin_route');
            
            // Debug için log
            log_message('info', 'Settings POST - New route: ' . $new_admin_route . ', Current route: ' . $current_route);
            
            // Validasyon
            if (empty($new_admin_route)) {
                $data['error_message'] = $this->adminLang('messages.admin_route_empty');
                log_message('error', 'Settings error: Empty admin route');
            } elseif (!preg_match('/^[a-zA-Z0-9_-]+$/', $new_admin_route)) {
                $data['error_message'] = $this->adminLang('messages.admin_route_invalid');
                log_message('error', 'Settings error: Invalid characters in admin route: ' . $new_admin_route);
            } else {
                // Tüm ayarları al
                $force_https = $this->request->getPost('force_https') ? true : false;
                $force_www   = $this->request->getPost('force_www') ? true : false;
                $default_language     = $this->request->getPost('default_language') ?: 'tr';
                $force_language_route = $this->request->getPost('force_language_route') ? true : false;
 
                // Smart language-detection flags
                $language_detect_use_user    = $this->request->getPost('language_detect_use_user') ? true : false;
                $language_detect_use_ip      = $this->request->getPost('language_detect_use_ip') ? true : false;
                $language_detect_use_browser = $this->request->getPost('language_detect_use_browser') ? true : false;
 
                // Priority list  (comma-separated → array)
                $language_detect_priority_raw = $this->request->getPost('language_detect_priority') ?? '';
                $language_detect_priority = array_filter(array_map('trim', explode(',', $language_detect_priority_raw)));
                if (empty($language_detect_priority)) {
                    $language_detect_priority = ['user', 'ip', 'browser', 'default'];
                }
 
                // Environment settings
                $ci_environment   = trim($this->request->getPost('ci_environment') ?? 'development');
                $app_baseURL      = trim($this->request->getPost('app_baseURL') ?? 'http://localhost/');
                $logger_threshold = intval($this->request->getPost('logger_threshold') ?? 4);
                
                log_message('info', 'Settings: Force HTTPS: ' . ($force_https ? 'true' : 'false'));
                log_message('info', 'Settings: Force WWW: ' . ($force_www ? 'true' : 'false'));
                log_message('info', 'Settings: Default Language: ' . $default_language);
                log_message('info', 'Settings: Force Language Route: ' . ($force_language_route ? 'true' : 'false'));
                
                // Varsayılan dilin geçerli olup olmadığını kontrol et
                if (!is_valid_language($default_language)) {
                    $data['error_message'] = $this->adminLang('messages.admin_route_invalid') . ' (Language)';
                    log_message('error', 'Settings error: Invalid default language: ' . $default_language);
                    return view('admin/settings', $data);
                }
                // Geçici olarak her zaman güncelleme yapmayı dene
                try {
                    log_message('info', 'Settings: Attempting to update config file');
                    log_message('info', 'Settings: Current route: ' . $current_route . ', New route: ' . $new_admin_route);
                    
                    // Config dosyasını güncelle
                    $this->updateConfigFile('admin_route', $new_admin_route);
                    $this->updateConfigFile('force_https', $force_https);
                    $this->updateConfigFile('force_www', $force_www);
                    $this->updateConfigFile('default_language', $default_language);
                    $this->updateConfigFile('force_language_route', $force_language_route);
 
                    // Smart language-detection settings
                    $this->updateConfigFile('language_detect_use_user', $language_detect_use_user);
                    $this->updateConfigFile('language_detect_use_ip', $language_detect_use_ip);
                    $this->updateConfigFile('language_detect_use_browser', $language_detect_use_browser);
                    $this->updateConfigFile('language_detect_priority', $language_detect_priority);
 
                    // Environment settings
                    $this->updateConfigFile('ci_environment', $ci_environment);
                    $this->updateConfigFile('app_baseURL', $app_baseURL);
                    $this->updateConfigFile('logger_threshold', $logger_threshold);
                    
                    // Güncelleme sonrası yeni değeri al
                    $updated_route = get_smapp_config('admin_route');
                    
                    $changes_made = ($new_admin_route !== $current_route) ||
                                   ($force_https !== get_smapp_config('force_https', false)) ||
                                   ($force_www  !== get_smapp_config('force_www', false)) ||
                                   ($default_language !== get_smapp_config('default_language', 'tr')) ||
                                   ($force_language_route !== get_smapp_config('force_language_route', true)) ||
                                   ($language_detect_use_user !== get_smapp_config('language_detect_use_user', true)) ||
                                   ($language_detect_use_ip   !== get_smapp_config('language_detect_use_ip', true)) ||
                                   ($language_detect_use_browser !== get_smapp_config('language_detect_use_browser', true)) ||
                                   (implode(',', $language_detect_priority) !== implode(',', get_smapp_config('language_detect_priority', ['user', 'ip', 'browser', 'default']))) ||
                                   ($ci_environment !== get_smapp_config('ci_environment', 'development')) ||
                                   ($app_baseURL   !== get_smapp_config('app_baseURL', 'http://localhost/')) ||
                                   ((int)$logger_threshold !== (int)get_smapp_config('logger_threshold', 4));
                    
                    if ($changes_made) {
                        log_message('info', 'Settings: Config updated successfully');
                        
                        // Başarılı güncelleme sonrası yeni URL'ye yönlendir
                        session()->setFlashdata('success_message', $this->adminLang('messages.settings_updated'));
                        return redirect()->to(get_admin_url_with_language('settings', $this->language));
                        
                    } else {
                        $data['info_message'] = $this->adminLang('messages.no_changes');
                        log_message('info', 'Settings: No changes made');
                    }
                    
                } catch (\Exception $e) {
                    $data['error_message'] = $this->adminLang('messages.config_update_error') . ': ' . $e->getMessage();
                    log_message('error', 'Settings config update error: ' . $e->getMessage());
                    log_message('error', 'Settings config update trace: ' . $e->getTraceAsString());
                }
            }
        }
        
        return view('admin/settings', $data);
    }
    
    /**
     * Admin giriş sayfası
     */
    public function login()
    {
        $data = [
            'title' => 'Admin Girişi',
            'admin_route' => get_smapp_config('admin_route', 'smapp')
        ];
        
        return view('admin/login', $data);
    }
    
    /**
     * Admin giriş işlemi
     */
    public function doLogin()
    {
        // Giriş işlemi burada yapılacak
        // Şimdilik basit bir örnek
        
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        
        // Basit doğrulama (gerçek projede veritabanı kullanılmalı)
        if ($username === 'admin' && $password === 'admin123') {
            session()->set('admin_logged_in', true);
            session()->set('admin_username', $username);
            
            return redirect()->to(get_admin_url());
        } else {
            return redirect()->back()->with('error', 'Kullanıcı adı veya şifre hatalı!');
        }
    }
    
    /**
     * Admin çıkış işlemi
     */
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/');
    }
    
    /**
     * Debug sayfası
     */
    public function debug()
    {
        $data = [
            'title' => $this->adminLang('debug_page'),
            'lang' => $this->lang,
            'language' => $this->language,
            'supported_languages' => get_supported_languages()
        ];
        
        return view('admin/debug', $data);
    }
    
    /**
     * Config dosyasını güncelle
     */
    private function updateConfigFile($key, $value)
    {
        // Proje kök dizinine (3 seviye yukarı) çıkıp public_html altındaki config dosyasını hedefle
        $config_file = dirname(dirname(dirname(__DIR__))) . '/public_html/smapp_config.php';
        
        log_message('info', 'updateConfigFile: Config file path = ' . $config_file);
        log_message('info', 'updateConfigFile: File exists = ' . (file_exists($config_file) ? 'YES' : 'NO'));
// Shortcut for modern array-style config
        if (function_exists('set_smapp_config')) {
            set_smapp_config($key, $value);
            log_message('info', 'updateConfigFile: Config updated via set_smapp_config()');
            return;
        }
        
        // Dosya varlığını kontrol et
        if (!file_exists($config_file)) {
            throw new \Exception('Config dosyası bulunamadı: ' . $config_file);
        }
        
        // Dosya yazılabilir mi kontrol et
        if (!is_writable($config_file)) {
            throw new \Exception('Config dosyası yazılabilir değil: ' . $config_file);
        }
        
        $content = file_get_contents($config_file);
        
        if ($content === false) {
            throw new \Exception('Config dosyası okunamadı');
        }
        
        // Config değerini güncelle
        if ($key === 'admin_route') {
            log_message('error', 'updateConfigFile: Starting update for admin_route to: ' . $value);
            log_message('error', 'updateConfigFile: Content preview: ' . substr($content, 0, 500));
            
            // admin_route satırını yakalamak için daha güvenli regex:
            // $smapp_config['admin_route'] = 'smapp';
            $pattern = "/(\\\$smapp_config\\['admin_route'\\]\\s*=\\s*)['\\\"][^'\\\"]*['\\\"]/";

            if (!preg_match($pattern, $content)) {
                log_message('error', 'Admin route pattern did not match');
                throw new \Exception('Config dosyasında admin_route ayarı bulunamadı. Pattern eşleşmedi.');
            }

            // Birinci grubu (=$ kısmı) koruyarak yalnızca değeri değiştir
            $replacement = "\$1'" . $value . "'";
            log_message('error', 'Using pattern: ' . $pattern);
            
        } elseif ($key === 'force_https') {
            log_message('info', 'updateConfigFile: Starting update for force_https to: ' . ($value ? 'true' : 'false'));
            
            $pattern = "/\\\$smapp_config\['force_https'\]\s*=\s*(true|false)\s*;\s*/";
            $replacement = "\$smapp_config['force_https'] = " . ($value ? 'true' : 'false') . ";\n";
            
        } elseif ($key === 'force_www') {
            log_message('info', 'updateConfigFile: Starting update for force_www to: ' . ($value ? 'true' : 'false'));
            
            $pattern = "/\\\$smapp_config\['force_www'\]\s*=\s*(true|false)\s*;\s*/";
            $replacement = "\$smapp_config['force_www'] = " . ($value ? 'true' : 'false') . ";\n";
            
        } elseif ($key === 'default_language') {
            log_message('info', 'updateConfigFile: Starting update for default_language to: ' . $value);
            
            $pattern = "/\\\$smapp_config\['default_language'\]\s*=\s*'[^']*';\s*/";
            $replacement = "\$smapp_config['default_language'] = '" . $value . "';\n";
            
        } elseif ($key === 'force_language_route') {
            log_message('info', 'updateConfigFile: Starting update for force_language_route to: ' . ($value ? 'true' : 'false'));
            
            $pattern = "/\\\$smapp_config\['force_language_route'\]\s*=\s*(true|false)\s*;\s*/";
            $replacement = "\$smapp_config['force_language_route'] = " . ($value ? 'true' : 'false') . ";\n";
            
        } elseif (in_array($key, ['language_detect_use_user', 'language_detect_use_ip', 'language_detect_use_browser'])) {
            log_message('info', "updateConfigFile: Starting update for {$key} to: " . ($value ? 'true' : 'false'));
            
            $pattern = "/\\\$smapp_config\['{$key}'\]\s*=\s*(true|false)\s*;/";
            $replacement = "\$smapp_config['{$key}'] = " . ($value ? 'true' : 'false') . ";\n";

        } elseif ($key === 'ci_environment') {
            log_message('info', 'updateConfigFile: Starting update for ci_environment to: ' . $value);

            $pattern = "/\\\$smapp_config\['ci_environment'\]\s*=\s*'[^']*';/";
            $replacement = "\$smapp_config['ci_environment'] = '" . $value . "';\n";

        } elseif ($key === 'app_baseURL') {
            log_message('info', 'updateConfigFile: Starting update for app_baseURL to: ' . $value);

            $pattern = "/\\\$smapp_config\['app_baseURL'\]\s*=\s*'[^']*';/";
            $replacement = "\$smapp_config['app_baseURL'] = '" . $value . "';\n";

        } elseif ($key === 'logger_threshold') {
            log_message('info', 'updateConfigFile: Starting update for logger_threshold to: ' . $value);

            $pattern = "/\\\$smapp_config\['logger_threshold'\]\s*=\s*[0-9]+\s*;/";
            $replacement = "\$smapp_config['logger_threshold'] = " . (int)$value . ";\n";

        } elseif ($key === 'language_detect_priority') {
            log_message('info', 'updateConfigFile: Starting update for language_detect_priority');
            
            $joined = "'" . implode("','", $value) . "'";
            $pattern = "/\\\$smapp_config\['language_detect_priority'\]\s*=\s*\[[^;]*\];/";
            $replacement = "\$smapp_config['language_detect_priority'] = [{$joined}];\n";
            
        } else {
            throw new \Exception('Bilinmeyen config anahtarı: ' . $key);
        }
        
        log_message('info', 'updateConfigFile: Using pattern: ' . $pattern);
        log_message('info', 'updateConfigFile: Content length: ' . strlen($content));
        
        // Debug: Config dosyasının ilgili satırını bul
        $lines = explode("\n", $content);
        foreach ($lines as $line_num => $line) {
            if (strpos($line, $key) !== false && strpos($line, '$smapp_config') !== false) {
                log_message('info', 'updateConfigFile: Found line ' . ($line_num + 1) . ': ' . $line);
                log_message('info', 'updateConfigFile: Line bytes: ' . bin2hex($line));
            }
        }
        
        // Test pattern match
        if (preg_match($pattern, $content)) {
            log_message('info', 'updateConfigFile: Pattern MATCHES content');
        } else {
            log_message('error', 'updateConfigFile: Pattern DOES NOT MATCH content');
        }
        
        $new_content = preg_replace($pattern, $replacement, $content);
        
        if ($new_content === null) {
            $error = preg_last_error();
            throw new \Exception('Regex hatası oluştu. Error code: ' . $error);
        }
        
        // Eğer yeni içerik eski içerikle aynıysa, bu anahtar zaten güncel demektir;
        // hata fırlatmak yerine sessizce başarılı say.
        if ($new_content === $content) {
            log_message('info', "updateConfigFile: {$key} already up-to-date, no changes written");
            return;
        }
        
        log_message('info', 'updateConfigFile: Content changed, writing to file');
        
        // Dosyayı yaz
        $result = file_put_contents($config_file, $new_content);
        
        if ($result === false) {
            throw new \Exception('Config dosyası yazılamadı');
        }
        
        log_message('info', 'updateConfigFile: File written successfully. Bytes: ' . $result);
        
        // Memory'deki config'i de güncelle
        set_smapp_config($key, $value);
        
        log_message('info', 'updateConfigFile: Memory config updated for ' . $key);
    }
}
