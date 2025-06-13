<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Api extends BaseController
{
    /**
     * Constructor - Admin API için yetkilendirme kontrolü
     */
    public function __construct()
    {
        // Admin oturumu kontrolü
        if (!session()->get('admin_logged_in')) {
            // JSON response döndür
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Yetkisiz erişim. Lütfen giriş yapın.',
                'redirect' => get_admin_url() . '-login'
            ]);
            exit;
        }
    }
    
    /**
     * Sistem istatistiklerini getir
     */
    public function stats()
    {
        try {
            // Örnek istatistik verileri
            $stats = [
                'total_users' => 150,
                'active_sessions' => 23,
                'total_pages' => 45,
                'server_load' => '2.1',
                'memory_usage' => '64%',
                'disk_usage' => '78%',
                'admin_route' => get_smapp_config('admin_route', 'smapp'),
                'system_status' => 'online'
            ];
            
            return $this->response->setJSON([
                'success' => true,
                'data' => $stats,
                'timestamp' => date('Y-m-d H:i:s')
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'İstatistikler alınırken hata oluştu: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Ayarları kaydet
     */
    public function save()
    {
        try {
            $input = $this->request->getJSON(true);
            
            if (empty($input)) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Geçersiz veri formatı'
                ]);
            }
            
            // Admin rotası güncelleme
            if (isset($input['admin_route'])) {
                $new_route = trim($input['admin_route']);
                
                if (empty($new_route)) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Admin rotası boş olamaz'
                    ]);
                }
                
                // Geçersiz karakterleri kontrol et
                if (!preg_match('/^[a-zA-Z0-9_-]+$/', $new_route)) {
                    return $this->response->setJSON([
                        'success' => false,
                        'message' => 'Admin rotası sadece harf, rakam, tire ve alt çizgi içerebilir'
                    ]);
                }
                
                // Config dosyasını güncelle
                $this->updateConfigFile('admin_route', $new_route);
                
                return $this->response->setJSON([
                    'success' => true,
                    'message' => 'Admin rotası başarıyla güncellendi',
                    'data' => [
                        'old_route' => get_smapp_config('admin_route'),
                        'new_route' => $new_route,
                        'new_admin_url' => get_admin_url()
                    ]
                ]);
            }
            
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Güncellenecek ayar bulunamadı'
            ]);
            
        } catch (\Exception $e) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Ayarlar kaydedilirken hata oluştu: ' . $e->getMessage()
            ]);
        }
    }
    
    /**
     * Config dosyasını güncelle
     */
    private function updateConfigFile($key, $value)
    {
        $config_file = FCPATH . 'smapp_config.php';
        
        if (!file_exists($config_file)) {
            throw new \Exception('Config dosyası bulunamadı');
        }
        
        $content = file_get_contents($config_file);
        
        if ($key === 'admin_route') {
            $pattern = "/(\\\$smapp_config\['admin_route'\]\s*=\s*')[^']*(')/";
            $replacement = '$1' . $value . '$2';
            $new_content = preg_replace($pattern, $replacement, $content);
            
            if ($new_content === null || $new_content === $content) {
                throw new \Exception('Config dosyası güncellenemedi');
            }
            
            if (!file_put_contents($config_file, $new_content)) {
                throw new \Exception('Config dosyası yazılamadı');
            }
            
            // Memory'deki config'i de güncelle
            set_smapp_config($key, $value);
        }
    }
    
    /**
     * Sistem durumunu kontrol et
     */
    public function health()
    {
        $health_data = [
            'status' => 'healthy',
            'timestamp' => date('Y-m-d H:i:s'),
            'config_loaded' => defined('SMAPP_CONFIG_LOADED'),
            'admin_route' => get_smapp_config('admin_route', 'smapp'),
            'php_version' => PHP_VERSION,
            'codeigniter_version' => \CodeIgniter\CodeIgniter::CI_VERSION ?? 'Unknown'
        ];
        
        return $this->response->setJSON([
            'success' => true,
            'data' => $health_data
        ]);
    }
}