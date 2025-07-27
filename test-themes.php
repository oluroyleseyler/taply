<?php
require_once 'core/config.php';
require_once 'core/session.php';

// Kullanıcı girişi gerekli
require_login();

$current_user = get_logged_in_user();
$themes = get_themes();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Tema Test</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 2rem; }
        .theme-card { 
            border: 2px solid #ccc; 
            padding: 1rem; 
            margin: 1rem; 
            border-radius: 8px; 
            display: inline-block; 
            width: 200px;
            text-align: center;
            cursor: pointer;
        }
        .theme-card:hover { border-color: blue; }
        .theme-card.active { border-color: green; background: #f0f8f0; }
        .theme-preview { 
            height: 60px; 
            border-radius: 4px; 
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        .theme-default { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
        .theme-sunset { background: linear-gradient(135deg, #ff6b6b 0%, #ffa500 100%); }
        .theme-ocean { background: linear-gradient(135deg, #00c6ff 0%, #0072ff 100%); }
        .theme-forest { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); }
        .theme-night { background: linear-gradient(135deg, #2c3e50 0%, #000000 100%); }
        .theme-rose { background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); }
        .theme-cosmic { background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%); }
        .theme-minimal { background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); color: #333 !important; }
    </style>
</head>
<body>

<h1>🎨 Tema Test Sayfası</h1>

<p><strong>Kullanıcı:</strong> <?php echo $current_user['username']; ?></p>
<p><strong>Aktif Tema:</strong> <?php echo $current_user['selected_theme']; ?></p>
<p><strong>Tema Sayısı:</strong> <?php echo count($themes); ?></p>

<hr>

<?php if (empty($themes)): ?>
    <div style="background: #ffebee; padding: 1rem; border-radius: 8px; color: #c62828;">
        <h3>❌ Tema Bulunamadı!</h3>
        <p>get_themes() fonksiyonu boş dizi döndürüyor.</p>
        <p><a href="test-themes.php">→ Detaylı test sayfasına git</a></p>
    </div>
<?php else: ?>
    <h2>Mevcut Temalar:</h2>
    
    <?php foreach ($themes as $theme): ?>
        <div class="theme-card <?php echo $current_user['selected_theme'] == $theme['name'] ? 'active' : ''; ?>"
             onclick="selectTheme('<?php echo $theme['name']; ?>')">
            
            <div class="theme-preview theme-<?php echo $theme['name']; ?>">
                <?php echo strtoupper(substr($current_user['username'], 0, 1)); ?>
            </div>
            
            <h4><?php echo $theme['display_name']; ?></h4>
            <small><?php echo $theme['name']; ?></small>
            
            <?php if ($current_user['selected_theme'] == $theme['name']): ?>
                <br><span style="background: green; color: white; padding: 2px 8px; border-radius: 12px; font-size: 11px;">AKTİF</span>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
    
    <form id="themeForm" method="POST" action="users/dashboard" style="display: none;">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <input type="hidden" name="action" value="change_theme">
        <input type="hidden" name="theme" id="selectedTheme">
    </form>
<?php endif; ?>

<hr>
<p><a href="users/dashboard">← Dashboard'a dön</a></p>

<script>
function selectTheme(themeName) {
    if (confirm('Tema "' + themeName + '" olarak değiştirilsin mi?')) {
        document.getElementById('selectedTheme').value = themeName;
        document.getElementById('themeForm').submit();
    }
}
</script>

</body>
</html>