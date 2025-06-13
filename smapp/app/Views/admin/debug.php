<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Debug - SMAPP Config</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; }
        .debug-box { background: #f8f9fa; border: 1px solid #dee2e6; padding: 15px; margin: 10px 0; border-radius: 5px; }
        .error { background: #f8d7da; border-color: #f5c6cb; color: #721c24; }
        .success { background: #d4edda; border-color: #c3e6cb; color: #155724; }
        pre { background: #e9ecef; padding: 10px; border-radius: 3px; overflow-x: auto; }
    </style>
</head>
<body>
    <div class="container">
        <h1>SMAPP Config Debug</h1>
        
        <div class="debug-box">
            <h3>Config Dosyası Durumu</h3>
            <?php
            $config_file = FCPATH . 'smapp_config.php';
            echo "<strong>Dosya Yolu:</strong> " . $config_file . "<br>";
            echo "<strong>Dosya Var mı:</strong> " . (file_exists($config_file) ? 'Evet ✓' : 'Hayır ✗') . "<br>";
            echo "<strong>Yazılabilir mi:</strong> " . (is_writable($config_file) ? 'Evet ✓' : 'Hayır ✗') . "<br>";
            echo "<strong>Okunabilir mi:</strong> " . (is_readable($config_file) ? 'Evet ✓' : 'Hayır ✗') . "<br>";
            if (file_exists($config_file)) {
                echo "<strong>Dosya Boyutu:</strong> " . filesize($config_file) . " bytes<br>";
                echo "<strong>Son Değişiklik:</strong> " . date('Y-m-d H:i:s', filemtime($config_file)) . "<br>";
            }
            ?>
        </div>

        <div class="debug-box">
            <h3>Config Fonksiyonları</h3>
            <?php
            echo "<strong>get_smapp_config fonksiyonu:</strong> " . (function_exists('get_smapp_config') ? 'Mevcut ✓' : 'Mevcut Değil ✗') . "<br>";
            echo "<strong>set_smapp_config fonksiyonu:</strong> " . (function_exists('set_smapp_config') ? 'Mevcut ✓' : 'Mevcut Değil ✗') . "<br>";
            echo "<strong>get_admin_url fonksiyonu:</strong> " . (function_exists('get_admin_url') ? 'Mevcut ✓' : 'Mevcut Değil ✗') . "<br>";
            echo "<strong>SMAPP_CONFIG_LOADED:</strong> " . (defined('SMAPP_CONFIG_LOADED') ? 'Tanımlı ✓' : 'Tanımlı Değil ✗') . "<br>";
            ?>
        </div>

        <div class="debug-box">
            <h3>Mevcut Config Değerleri</h3>
            <?php
            if (function_exists('get_all_smapp_config')) {
                $all_config = get_all_smapp_config();
                echo "<pre>" . print_r($all_config, true) . "</pre>";
            } else {
                echo "<span class='error'>Config fonksiyonları yüklü değil!</span>";
            }
            ?>
        </div>

        <div class="debug-box">
            <h3>Config Dosyası İçeriği</h3>
            <?php
            if (file_exists($config_file)) {
                $content = file_get_contents($config_file);
                echo "<pre>" . htmlspecialchars($content) . "</pre>";
            } else {
                echo "<span class='error'>Config dosyası bulunamadı!</span>";
            }
            ?>
        </div>

        <div class="debug-box">
            <h3>Test: Config Güncelleme</h3>
            <?php
            if (isset($_POST['test_update'])) {
                try {
                    $new_route = 'test-admin';
                    
                    // Dosya içeriğini oku
                    $content = file_get_contents($config_file);
                    echo "<strong>Orijinal İçerik Okundu:</strong> " . (strlen($content) > 0 ? 'Evet ✓' : 'Hayır ✗') . "<br>";
                    
                    // Regex ile değiştir
                    $pattern = "/(\\\$smapp_config\s*\[\s*['\"]admin_route['\"]\s*\]\s*=\s*['\"])[^'\"]*(['\"])/";
                    $replacement = '${1}' . $new_route . '${2}';
                    $new_content = preg_replace($pattern, $replacement, $content);
                    
                    echo "<strong>Regex Eşleşti:</strong> " . ($new_content !== $content ? 'Evet ✓' : 'Hayır ✗') . "<br>";
                    
                    if ($new_content !== $content) {
                        // Dosyayı yaz
                        $result = file_put_contents($config_file, $new_content);
                        echo "<strong>Dosya Yazıldı:</strong> " . ($result !== false ? 'Evet ✓ (' . $result . ' bytes)' : 'Hayır ✗') . "<br>";
                        
                        if ($result !== false) {
                            echo "<div class='debug-box success'><strong>Test Başarılı!</strong> Config dosyası güncellendi.</div>";
                        }
                    } else {
                        echo "<div class='debug-box error'><strong>Regex Hatası!</strong> Pattern eşleşmedi.</div>";
                        echo "<strong>Kullanılan Pattern:</strong> <code>" . htmlspecialchars($pattern) . "</code><br>";
                    }
                    
                } catch (Exception $e) {
                    echo "<div class='debug-box error'><strong>Hata:</strong> " . $e->getMessage() . "</div>";
                }
            }
            ?>
            
            <form method="post">
                <button type="submit" name="test_update" style="background: #007bff; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">
                    Config Güncelleme Testi Yap
                </button>
            </form>
        </div>

        <div style="margin-top: 20px;">
            <a href="<?= get_admin_url() ?>" style="color: #007bff;">← Admin Paneline Dön</a>
        </div>
    </div>
</body>
</html>