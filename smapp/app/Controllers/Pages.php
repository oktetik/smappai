<?php

namespace App\Controllers;

class Pages extends BaseController
{
    /**
     * Hakkımızda sayfası
     */
    public function about()
    {
        $data = [
            'title' => $this->adminLang('about') ?: 'Hakkımızda',
            'language' => $this->language,
            'lang' => $this->lang
        ];
        
        return view('pages/about', $data);
    }
    
    /**
     * İletişim sayfası
     */
    public function contact()
    {
        $data = [
            'title' => $this->adminLang('contact') ?: 'İletişim',
            'language' => $this->language,
            'lang' => $this->lang
        ];
        
        return view('pages/contact', $data);
    }
}