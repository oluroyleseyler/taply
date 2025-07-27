<?php
// Router sistemi kullanıldığında core dosyalar zaten yüklü
// require_once '../core/config.php'; // Bu satır kaldırıldı
// require_once '../core/session.php'; // Bu satır kaldırıldı
// require_once '../core/auth.php'; // Bu satır kaldırıldı

// Eğer admin zaten giriş yapmışsa dashboard'a yönlendir
if (is_admin()) {
    redirect(BASE_URL . 'admin/dashboard');
}

$error_message = '';

// Form gönderildi mi?
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // CSRF token kontrolü
    if (!verify_csrf_token($_POST['csrf_token'])) {
        $error_message = 'Güvenlik hatası. Lütfen tekrar deneyin.';
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        // Admin giriş denemesi
        $result = authenticate_admin($email, $password);
        
        if ($result['success']) {
            // Başarılı giriş - admin dashboard'a yönlendir
            redirect(BASE_URL . 'admin/dashboard');
        } else {
            $error_message = $result['message'];
        }
    }
}

$page_title = 'Admin Giriş';
$page_description = 'Taply.life admin paneline giriş yapın.';
?>

<?php include 'includes/header.php'; ?>

<div class="auth-container">
    <div class="auth-card">
        <div class="auth-header">
            <h1>Admin Paneli</h1>
            <p>Yönetici girişi</p>
        </div>
        
        <?php if ($error_message): ?>
            <div class="alert alert-error">
                <?php echo safe_output($error_message); ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" class="auth-form">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
            
            <div class="form-group">
                <label for="email">Admin E-mail</label>
                <input type="email" id="email" name="email" required
                       value="<?php echo isset($_POST['email']) ? safe_output($_POST['email']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="password">Şifre</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="btn btn-primary btn-full">Admin Girişi</button>
        </form>
        
        <div class="auth-footer">
            <p><a href="<?php echo BASE_URL; ?>">← Ana sayfaya dön</a></p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>