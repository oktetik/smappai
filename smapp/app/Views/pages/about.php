<!DOCTYPE html>
<html lang="<?= $language ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?> - SMAPP</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .language-switcher {
            text-align: right;
            margin-bottom: 20px;
        }
        .language-switcher a {
            margin: 0 5px;
            padding: 5px 10px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 3px;
        }
        .language-switcher a.active {
            background: #28a745;
        }
        .nav {
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #dee2e6;
        }
        .nav a {
            margin-right: 15px;
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="language-switcher">
            <a href="<?= create_language_url('hakkimizda', 'tr') ?>" <?= $language === 'tr' ? 'class="active"' : '' ?>>TR</a>
            <a href="<?= create_language_url('about', 'en') ?>" <?= $language === 'en' ? 'class="active"' : '' ?>>EN</a>
        </div>

        <div class="nav">
            <a href="<?= create_language_url('', $language) ?>">Ana Sayfa / Home</a>
            <a href="<?= create_language_url($language === 'tr' ? 'hakkimizda' : 'about', $language) ?>"><?= $title ?></a>
            <a href="<?= create_language_url($language === 'tr' ? 'iletisim' : 'contact', $language) ?>"><?= $language === 'tr' ? 'İletişim' : 'Contact' ?></a>
            <a href="<?= get_admin_url_with_language('', $language) ?>">Admin Panel</a>
        </div>

        <h1><?= $title ?></h1>
        
        <?php if ($language === 'tr'): ?>
            <p>Bu SMAPP sisteminin hakkımızda sayfasıdır. Sistem çok dilli yapıya sahiptir ve URL tabanlı dil değiştirme özelliği bulunmaktadır.</p>
            
            <h2>Özellikler</h2>
            <ul>
                <li>Çok dilli destek (Türkçe/İngilizce)</li>
                <li>URL tabanlı dil değiştirme</li>
                <li>Admin panel dil desteği</li>
                <li>Dinamik rota sistemi</li>
                <li>CodeIgniter 4 tabanlı</li>
            </ul>
            
            <p><strong>Mevcut Dil:</strong> <?= $language ?> (Türkçe)</p>
            <p><strong>URL Formatı:</strong> domain.com/tr/hakkimizda</p>
            
        <?php else: ?>
            <p>This is the about page of the SMAPP system. The system has multilingual structure and URL-based language switching feature.</p>
            
            <h2>Features</h2>
            <ul>
                <li>Multilingual support (Turkish/English)</li>
                <li>URL-based language switching</li>
                <li>Admin panel language support</li>
                <li>Dynamic routing system</li>
                <li>CodeIgniter 4 based</li>
            </ul>
            
            <p><strong>Current Language:</strong> <?= $language ?> (English)</p>
            <p><strong>URL Format:</strong> domain.com/en/about</p>
        <?php endif; ?>

        <div style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #dee2e6; color: #6c757d; text-align: center;">
            <small>SMAPP Multilingual System - Current Language: <?= strtoupper($language) ?></small>
        </div>
    </div>
</body>
</html>