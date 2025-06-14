<?php

/**
 * Admin Panel Dil Dosyası - Türkçe
 * 
 * Bu dosya admin panelinin tüm metinlerini içerir.
 */

return [
    // Genel
    'title' => 'Admin Panel',
    'welcome' => 'Hoş Geldiniz',
    'dashboard' => 'Dashboard',
    'settings' => 'Ayarlar',
    'users' => 'Kullanıcılar',
    'logout' => 'Çıkış',
    'login' => 'Giriş',
    'save' => 'Kaydet',
    'cancel' => 'İptal',
    'delete' => 'Sil',
    'edit' => 'Düzenle',
    'add' => 'Ekle',
    'back' => 'Geri',
    'yes' => 'Evet',
    'no' => 'Hayır',
    'enabled' => 'Etkin',
    'disabled' => 'Devre Dışı',
    'active' => 'Aktif',
    'inactive' => 'Pasif',
    'loading' => 'Yükleniyor...',
    'processing' => 'İşleniyor...',
    
    // Başlıklar
    'admin_panel' => 'SMAPP Yönetim Paneli',
    'system_settings' => 'Sistem Ayarları',
    'user_management' => 'Kullanıcı Yönetimi',
    'debug_page' => 'Debug - Config Sistemi',
    
    // Menü
    'menu' => [
        'dashboard' => 'Dashboard',
        'users' => 'Kullanıcılar',
        'settings' => 'Ayarlar',
        'debug' => 'Debug',
        'api_test' => 'API Test',
        'logout' => 'Çıkış'
    ],
    
    // Ana Sayfa
    'home' => [
        'welcome_message' => 'SMAPP config sistemi başarıyla çalışıyor.',
        'how_to_use' => 'Nasıl Kullanılır?',
        'admin_route_change' => 'Admin Rotası Değiştirme: Ayarlar sayfasından admin panelinin URL\'ini değiştirebilirsiniz.',
        'config_file' => 'Config Dosyası: {file} dosyasından manuel olarak da ayarları değiştirebilirsiniz.',
        'security' => 'Güvenlik: Admin rotasını değiştirerek güvenliği artırabilirsiniz.',
        'api_access' => 'API: {url} altından sistem API\'lerine erişebilirsiniz.',
        'current_config' => 'Mevcut Konfigürasyon',
        'admin_route' => 'Admin Rotası',
        'admin_url' => 'Admin URL',
        'config_loaded' => 'Config Dosyası',
        'system_time' => 'Sistem Zamanı',
        'version_info' => 'SMAPP Config System v1.0 - CodeIgniter {version}'
    ],
    
    // Ayarlar
    'settings' => [
        'title' => 'Sistem Ayarları',
        'description' => 'Sistem ayarlarını buradan yönetebilirsiniz',
        'back_to_admin' => 'Admin Paneline Dön',
        'current_config' => 'Mevcut Konfigürasyon',
        'admin_route' => 'Admin Panel Rotası',
        'admin_route_help' => 'Sadece harf, rakam, tire (-) ve alt çizgi (_) karakterleri kullanabilirsiniz.',
        'admin_route_examples' => 'Örnek: "admin", "panel", "yonetim", "my-admin" vb.',
        'current_value' => 'Mevcut değer',
        'force_https' => 'Force HTTPS',
        'force_https_help' => 'Etkinleştirildiğinde tüm HTTP istekleri otomatik olarak HTTPS\'e yönlendirilir.',
        'force_https_warning' => 'Uyarı: SSL sertifikanızın aktif olduğundan emin olun.',
        'force_www' => 'Force WWW',
        'force_www_help' => 'Etkinleştirildiğinde tüm istekler www subdomain\'i ile yönlendirilir.',
        'force_www_example' => 'Örnek: domain.com → www.domain.com',
        'default_language' => 'Varsayılan Dil',
        'default_language_help' => 'Sistemin varsayılan dili. Yeni ziyaretçiler bu dilde karşılanır.',
        'force_language_route' => 'Dil Rotası Zorunlu',
        'force_language_route_help' => 'Etkinleştirildiğinde varsayılan dil için bile URL\'de dil kodu zorunlu olur.',
        'force_language_route_example' => 'Etkin: domain.com/tr/sayfa | Devre Dışı: domain.com/sayfa (varsayılan dil için)',
        'language_settings' => 'Dil Ayarları',
        'environment_settings' => 'Ortam Ayarları',
        'save_settings' => 'Ayarları Kaydet',
        'warning_title' => 'Uyarı',
        'warning_message' => 'Admin rotasını değiştirdikten sonra yeni URL üzerinden erişim yapmanız gerekecektir. Mevcut oturumunuz sonlanacak ve yeni adresten tekrar giriş yapmanız gerekebilir.',
        'future_features' => 'Gelecek Özellikler',
        'future_features_desc' => 'Bu config sistemine eklenecek diğer ayarlar:',
        'future_list' => [
            'Site başlığı ve açıklaması',
            'E-posta ayarları',
            'Veritabanı ayarları',
            'Cache ayarları',
            'Güvenlik ayarları',
            'API ayarları'
        ]
    ],
    
    // Etiketler
    'label' => [
        'admin_route'          => 'Admin Rotası',
        'force_https'          => 'HTTPS Zorunlu',
        'force_www'            => 'WWW Zorunlu',
        'default_language'     => 'Varsayılan Dil',
        'force_language_route' => 'Dil Rotası Zorunlu',
        'ci_environment'      => 'CI Ortamı',
        'app_baseURL'         => 'Temel URL',
        'logger_threshold'    => 'Günlük Seviyesi'
    ],
    
    // Butonlar
    'button' => [
        'save' => 'Ayarları Kaydet'
    ],
    
    // Mesajlar
    'messages' => [
        'success' => 'İşlem başarıyla tamamlandı!',
        'error' => 'Bir hata oluştu!',
        'warning' => 'Uyarı!',
        'info' => 'Bilgi',
        'admin_route_updated' => 'Admin rotası başarıyla güncellendi!',
        'settings_updated' => 'Ayarlar başarıyla güncellendi!',
        'no_changes' => 'Herhangi bir değişiklik yapılmadı.',
        'config_update_error' => 'Config güncellenirken hata oluştu',
        'admin_route_empty' => 'Admin rotası boş olamaz!',
        'admin_route_invalid' => 'Admin rotası sadece harf, rakam, tire ve alt çizgi içerebilir!',
        'new_admin_url' => 'Yeni Admin URL',
        'post_request_received' => 'POST isteği alındı!'
    ],
    
    // Debug
    'debug' => [
        'title' => 'SMAPP Config Debug',
        'config_file_status' => 'Config Dosyası Durumu',
        'file_path' => 'Dosya Yolu',
        'file_exists' => 'Dosya Var mı',
        'file_writable' => 'Yazılabilir mi',
        'file_readable' => 'Okunabilir mi',
        'file_size' => 'Dosya Boyutu',
        'last_modified' => 'Son Değişiklik',
        'config_functions' => 'Config Fonksiyonları',
        'function_exists' => 'fonksiyonu',
        'available' => 'Mevcut',
        'not_available' => 'Mevcut Değil',
        'defined' => 'Tanımlı',
        'not_defined' => 'Tanımlı Değil',
        'current_values' => 'Mevcut Config Değerleri',
        'functions_not_loaded' => 'Config fonksiyonları yüklü değil!',
        'config_file_content' => 'Config Dosyası İçeriği',
        'file_not_found' => 'Config dosyası bulunamadı!',
        'test_config_update' => 'Test: Config Güncelleme',
        'test_button' => 'Config Güncelleme Testi Yap',
        'test_successful' => 'Test Başarılı! Config dosyası güncellendi.',
        'regex_error' => 'Regex Hatası! Pattern eşleşmedi.',
        'pattern_used' => 'Kullanılan Pattern',
        'original_content_read' => 'Orijinal İçerik Okundu',
        'regex_matched' => 'Regex Eşleşti',
        'file_written' => 'Dosya Yazıldı',
        'bytes' => 'bytes'
    ],
    
    // Form
    'form' => [
        'required' => 'Bu alan zorunludur',
        'invalid_format' => 'Geçersiz format',
        'confirm_change' => 'Bu değişikliği yapmak istediğinizden emin misiniz?',
        'confirm_admin_route_change' => 'Admin rotasını "{new_route}" olarak değiştirmek istediğinizden emin misiniz?\n\nDeğişiklik sonrası yeni adres: {new_url}\n\nBu işlem sonrası mevcut oturumunuz sonlanabilir.'
    ],
    
    // API
    'api' => [
        'unauthorized' => 'Yetkisiz erişim. Lütfen giriş yapın.',
        'invalid_data' => 'Geçersiz veri formatı',
        'stats_error' => 'İstatistikler alınırken hata oluştu',
        'save_error' => 'Ayarlar kaydedilirken hata oluştu',
        'admin_route_empty' => 'Admin rotası boş olamaz',
        'admin_route_invalid' => 'Admin rotası sadece harf, rakam, tire ve alt çizgi içerebilir',
        'config_not_found' => 'Güncellenecek ayar bulunamadı',
        'config_file_not_found' => 'Config dosyası bulunamadı',
        'config_update_failed' => 'Config dosyası güncellenemedi',
        'config_write_failed' => 'Config dosyası yazılamadı'
    ],
    
    // Giriş
    'login' => [
        'title' => 'Admin Girişi',
        'username' => 'Kullanıcı Adı',
        'password' => 'Şifre',
        'login_button' => 'Giriş Yap',
        'invalid_credentials' => 'Kullanıcı adı veya şifre hatalı!',
        'logout_success' => 'Başarıyla çıkış yaptınız.'
    ]
];