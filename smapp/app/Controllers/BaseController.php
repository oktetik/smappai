<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Current language
     *
     * @var string
     */
    protected $language = 'tr';

    /**
     * Language service
     *
     * @var \CodeIgniter\Language\Language
     */
    protected $lang;

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = service('session');
        
        // Dil sistemini başlat
        $this->initLanguage();
    }

    /**
     * Dil sistemini başlat
     */
    protected function initLanguage()
    {
        try {
            // Önce URL'den dil bilgisini almaya çalış
            $uri = $this->request->getUri()->getPath();
            if (function_exists('extract_language_from_uri')) {
                $language_info = extract_language_from_uri($uri);
                $this->language = $language_info['language'];
            } else {
                // Config'den varsayılan dili al (eğer config yüklüyse)
                if (function_exists('get_smapp_config')) {
                    $this->language = get_smapp_config('default_language', 'tr');
                } else {
                    $this->language = 'tr'; // Varsayılan dil
                }
            }
            
            // Session'dan dil bilgisini kontrol et (filter tarafından set edilmiş olabilir)
            if (session()->has('current_language')) {
                $this->language = session()->get('current_language');
            }
            
            // Dil servisini başlat
            $this->lang = \Config\Services::language($this->language);
            
            // Locale'i ayarla
            $this->lang->setLocale($this->language);
            
        } catch (\Exception $e) {
            // Hata durumunda varsayılan değerler kullan
            $this->language = 'tr';
            $this->lang = \Config\Services::language();
            $this->lang->setLocale($this->language);
            
            // Hata logla
            log_message('error', 'Language initialization error: ' . $e->getMessage());
        }
    }

    /**
     * Dil anahtarını çevir
     *
     * @param string $key Dil anahtarı (örn: 'Admin.title')
     * @param array $data Değişken verileri
     * @return string
     */
    protected function lang(string $key, array $data = []): string
    {
        try {
            if ($this->lang) {
                // Önce CodeIgniter'ın lang() fonksiyonunu deneriz
                $result = lang($key, $data, $this->language);
                // Eğer çeviri bulunamazsa, lang() anahtarı döndürür
                if ($result !== $key) {
                    return $result;
                }
                // Alternatif olarak Language service üzerinden de deneyelim
                $result2 = $this->lang->getLine($key, $data);
                if ($result2 !== false) {
                    return $result2;
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Lang error for key: ' . $key . ' - ' . $e->getMessage());
        }
        
        return $key;
    }

    /**
     * Admin dil anahtarını çevir (kısayol)
     *
     * @param string $key Admin dil anahtarı (örn: 'title')
     * @param array $data Değişken verileri
     * @return string
     */
    protected function adminLang(string $key, array $data = []): string
    {
        try {
            if ($this->lang) {
                return admin_lang($key, $data, $this->language);
            }
        } catch (\Exception $e) {
            log_message('error', 'AdminLang error for key: ' . $key . ' - ' . $e->getMessage());
        }
        
        // Fallback: anahtar adını döndür
        return $key;
    }

    /**
     * Mevcut dili al
     *
     * @return string
     */
    protected function getLanguage(): string
    {
        return $this->language;
    }

    /**
     * Dili değiştir
     *
     * @param string $language
     */
    protected function setLanguage(string $language)
    {
        $this->language = $language;
        session()->set('admin_language', $language);
        $this->initLanguage();
    }
}
