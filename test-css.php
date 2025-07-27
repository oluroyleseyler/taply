<?php
require_once 'core/config.php';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CSS Test - Taply.life</title>
    
    <!-- CSS Test -->
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>assets/css/main.css">
    
    <style>
        .test-box {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 12px;
            margin: 2rem;
            text-align: center;
        }
        
        .status {
            display: inline-block;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            margin: 0.5rem;
            font-weight: bold;
        }
        
        .success { background: #28a745; color: white; }
        .error { background: #dc3545; color: white; }
        .warning { background: #ffc107; color: black; }
    </style>
</head>
<body>

<div class="test-box">
    <h1>🎨 CSS Test Sayfası</h1>
    <p>Bu sayfa CSS yüklenmesini test eder.</p>
</div>

<div style="margin: 2rem; font-family: Arial, sans-serif;">
    <h2>📊 CSS Yükleme Durumu:</h2>
    
    <div class="status-checks">
        <?php
        $css_files = [
            'assets/css/main.css',
            'assets/css/profile.css', 
            'assets/css/home.css'
        ];
        
        foreach ($css_files as $file) {
            if (file_exists($file)) {
                echo "<span class='status success'>✅ $file - Mevcut</span><br>";
            } else {
                echo "<span class='status error'>❌ $file - Bulunamadı</span><br>";
            }
        }
        ?>
    </div>
    
    <h3>🔗 CSS URL Testi:</h3>
    <ul>
        <li><strong>BASE_URL:</strong> <?php echo BASE_URL; ?></li>
        <li><strong>CSS URL:</strong> <?php echo BASE_URL; ?>assets/css/main.css</li>
        <li><strong>Ana dizin:</strong> <?php echo __DIR__; ?></li>
    </ul>
    
    <h3>📁 Dosya Yolu Kontrolleri:</h3>
    <ul>
        <?php
        $paths = [
            'assets/' => is_dir('assets/'),
            'assets/css/' => is_dir('assets/css/'),
            'assets/css/main.css' => file_exists('assets/css/main.css')
        ];
        
        foreach ($paths as $path => $exists) {
            $icon = $exists ? '✅' : '❌';
            $status = $exists ? 'Mevcut' : 'Bulunamadı';
            echo "<li>$icon <strong>$path</strong> - $status</li>";
        }
        ?>
    </ul>
    
    <h3>🌐 Direkt Linkler:</h3>
    <ul>
        <li><a href="<?php echo BASE_URL; ?>assets/css/main.css" target="_blank">Main CSS'i Aç</a></li>
        <li><a href="<?php echo BASE_URL; ?>assets/css/profile.css" target="_blank">Profile CSS'i Aç</a></li>
        <li><a href="<?php echo BASE_URL; ?>assets/css/home.css" target="_blank">Home CSS'i Aç</a></li>
    </ul>
    
    <div style="margin-top: 2rem; padding: 1rem; background: #f8f9fa; border-radius: 8px;">
        <h4>🔧 Eğer CSS çalışmıyorsa:</h4>
        <ol>
            <li><code>config.php</code> dosyasında <code>BASE_URL</code> kontrolü</li>
            <li>CSS dosyalarının <code>assets/css/</code> klasöründe olduğunu kontrol edin</li>
            <li>Dosya izinlerini kontrol edin (755 klasör, 644 dosya)</li>
            <li>Tarayıcı cache'ini temizleyin (Ctrl+F5)</li>
        </ol>
    </div>
    
    <p style="text-align: center; margin-top: 2rem;">
        <a href="<?php echo BASE_URL; ?>" style="background: #667eea; color: white; padding: 1rem 2rem; text-decoration: none; border-radius: 8px;">
            ← Ana Sayfaya Dön
        </a>
    </p>
</div>

</body>
</html>