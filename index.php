<?php
// index.php - Ana yönlendirme dosyası (router)

require_once 'core/config.php';
require_once 'core/session.php';

// URL'i parse et
$request_uri = $_SERVER['REQUEST_URI'];
$parsed_url = parse_url($request_uri);
$path = $parsed_url['path'];

// Base path'i çıkar (/taply/ kısmını temizle)
$script_name = dirname($_SERVER['SCRIPT_NAME']);
if ($script_name !== '/') {
    $path = str_replace($script_name, '', $path);
}

// Baştaki ve sondaki slash'leri temizle
$path = trim($path, '/');

// Debug için (geliştirme aşamasında - sonra kaldırabilirsiniz)
// echo "<!-- Debug: Path = '$path' -->"; 

// Eğer path boşsa, ana sayfaya yönlendir
if (empty($path)) {
    include 'pages/home.php';
    exit;
}

// Admin paneli kontrolü
if (strpos($path, 'admin') === 0) {
    $admin_path = str_replace('admin/', '', $path);
    $admin_path = str_replace('admin', '', $admin_path);
    
    switch ($admin_path) {
        case '':
        case 'index':
            include 'admin/index.php';
            break;
        case 'dashboard':
            include 'admin/dashboard.php';
            break;
        case 'manage_users':
            include 'admin/manage_users.php';
            break;
        case 'themes':
            include 'admin/themes.php';
            break;
        case 'settings':
            include 'admin/settings.php';
            break;
        default:
            http_response_code(404);
            include 'pages/404.php';
    }
    exit;
}

// Kullanıcı paneli kontrolü
if (strpos($path, 'users') === 0) {
    $user_path = str_replace('users/', '', $path);
    $user_path = str_replace('users', '', $user_path);
    
    switch ($user_path) {
        case '':
        case 'dashboard':
            include 'users/dashboard.php';
            break;
        case 'register':
            include 'users/register.php';
            break;
        case 'login':
            include 'users/login.php';
            break;
        case 'logout':
            include 'users/logout.php';
            break;
        case 'profile':
            include 'users/profile.php';
            break;
        default:
            http_response_code(404);
            include 'pages/404.php';
    }
    exit;
}

// API istekleri
if (strpos($path, 'api') === 0) {
    $api_path = str_replace('api/', '', $path);
    $api_file = 'api/' . $api_path . '.php';
    
    if (file_exists($api_file)) {
        include $api_file;
    } else {
        http_response_code(404);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'API endpoint not found']);
    }
    exit;
}

// Statik dosyalar (assets) - normal olarak web sunucu tarafından servis edilir
if (strpos($path, 'assets') === 0) {
    http_response_code(404);
    exit;
}

// Özel sayfalar
$special_pages = [
    'help' => 'pages/help.php',
    'contact' => 'pages/contact.php', 
    'privacy' => 'pages/privacy.php',
    'terms' => 'pages/terms.php'
];

if (isset($special_pages[$path])) {
    if (file_exists($special_pages[$path])) {
        include $special_pages[$path];
    } else {
        http_response_code(404);
        include 'pages/404.php';
    }
    exit;
}

// Kullanıcı profil sayfaları (taply.life/kullaniciadi)
$username = $path;

// Kullanıcı adı doğrulama
if (validate_username($username)) {
    // Kullanıcı var mı kontrol et
    $user = get_user_by_username($username);
    
    if ($user) {
        // Kullanıcı profil sayfasını göster
        include 'pages/view_profile.php';
    } else {
        // Kullanıcı bulunamadı
        http_response_code(404);
        include 'pages/404.php';
    }
} else {
    // Geçersiz URL
    http_response_code(404);
    include 'pages/404.php';
}
?>