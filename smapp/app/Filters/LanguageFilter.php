<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class LanguageFilter implements FilterInterface
{
    /**
     * Dil filtresini uygula
     *
     * @param RequestInterface $request
     * @param array|null $arguments
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        // URL'den dil kodunu çıkar
        $uri = $request->getUri()->getPath();
        $language_info = extract_language_from_uri($uri);
        
        // Eğer arguments ile dil belirtilmişse onu kullan
        if (!empty($arguments) && is_array($arguments)) {
            $specified_language = $arguments[0] ?? null;
            if ($specified_language && is_valid_language($specified_language)) {
                $language_info['language'] = $specified_language;
            }
        }
        
        // Session'a dil bilgisini kaydet
        session()->set('current_language', $language_info['language']);
        session()->set('admin_language', $language_info['language']);
        
        // Request'e dil bilgisini ekle
        $request->setGlobal('current_language', $language_info['language']);
        
        return $request;
    }

    /**
     * After filter
     *
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @param array|null $arguments
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}