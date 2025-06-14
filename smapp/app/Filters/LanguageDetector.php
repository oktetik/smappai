<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * LanguageDetector
 *
 * Detects the best language for the visitor (user-setting → IP → browser → default)
 * and either:
 *   • redirects to /{lang}/… when force_language_route is enabled and URL lacks lang segment
 *   • registers flashdata “suggest_lang” when URL lang ≠ best lang
 *
 * Config flags (in smapp_config.php):
 *   language_detect_use_ip        bool
 *   language_detect_use_browser   bool
 *   language_detect_use_user      bool
 *   language_detect_priority      array  ['user','ip','browser','default']
 */
class LanguageDetector implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        helper(['smapp', 'url']);                     // ensure helper functions
        $uriPath     = $request->getUri()->getPath();
        $uriSegments = explode('/', trim($uriPath, '/'));

        // If path starts with index.php, skip it for language detection/redirect
        $hadIndex = false;
        if (!empty($uriSegments) && $uriSegments[0] === 'index.php') {
            $hadIndex = true;
            array_shift($uriSegments);                // drop index.php
        }

        $urlLang = $uriSegments[0] ?? '';

        // Determine best language according to helper
        $bestLang     = get_best_language();          // helper we’ll add
        $defaultLang  = get_default_language();
        $forceRoute   = get_smapp_config('force_language_route', true);

        // ===== scenario 1: URL already contains a language segment
        if (is_valid_language($urlLang)) {
            // Store current language into session for later requests
            session()->set('current_language', $urlLang);

            // If URL lang differs from bestLang, suggest a switch
            if ($urlLang !== $bestLang) {
                session()->setFlashdata('suggest_lang', $bestLang);
            }
            return $request;
        }

        // ===== scenario 2: URL has no language segment
        if ($forceRoute) {
            // Rebuild path parts keeping optional index.php
            $remainingPath = implode('/', $uriSegments);  // path after possible index.php removal
            $parts = [];
            if ($hadIndex) {
                $parts[] = 'index.php';
            }
            $parts[] = $bestLang;
            if ($remainingPath !== '') {
                $parts[] = $remainingPath;
            }
            $redirectPath = implode('/', $parts);

            // Use base_url to avoid double 'index.php' insertion by site_url
            return redirect()->to(base_url($redirectPath))->setStatusCode(302);
        }

        // If not forcing route, simply remember bestLang
        session()->set('current_language', $bestLang);
        return $request;
    }

    // No action after
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        return $response;
    }
}