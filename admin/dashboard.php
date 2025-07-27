<?php
// Router sistemi kullanıldığında core dosyalar zaten yüklü
// require_once '../core/config.php'; // Bu satır kaldırıldı
// require_once '../core/session.php'; // Bu satır kaldırıldı

// Admin yetkisi kontrolü
require_admin();

// Eğer get_logged_in_user fonksiyonu kullanılıyorsa burada da güncelleyin
// $current_admin = get_logged_in_user(); // Gerekirse

// İstatistikleri al
$stats = [];

// Toplam kullanıcı sayısı
$user_count_query = "SELECT COUNT(*) as count FROM users";
$user_count_result = mysqli_query($conn, $user_count_query);
$stats['total_users'] = mysqli_fetch_assoc($user_count_result)['count'];

// Toplam link sayısı
$link_count_query = "SELECT COUNT(*) as count FROM links";
$link_count_result = mysqli_query($conn, $link_count_query);
$stats['total_links'] = mysqli_fetch_assoc($link_count_result)['count'];

// Bu ay kayıt olan kullanıcılar
$this_month_query = "SELECT COUNT(*) as count FROM users WHERE MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE())";
$this_month_result = mysqli_query($conn, $this_month_query);
$stats['this_month_users'] = mysqli_fetch_assoc($this_month_result)['count'];

// Son kullanıcılar
$recent_users_query = "SELECT username, email, created_at FROM users ORDER BY created_at DESC LIMIT 5";
$recent_users_result = mysqli_query($conn, $recent_users_query);
$recent_users = [];
while ($row = mysqli_fetch_assoc($recent_users_result)) {
    $recent_users[] = $row;
}

$page_title = 'Admin Dashboard';
$additional_css = ['assets/css/admin.css'];
?>

<?php include 'includes/header.php'; ?>

<div class="admin-container">
    <div class="admin-sidebar">
        <div class="admin-brand">
            <h3>Admin Panel</h3>
            <p>Hoş geldiniz!</p>
        </div>
        
        <nav class="admin-nav">
            <a href="<?php echo BASE_URL; ?>admin/dashboard" class="nav-item active">
                <i class="icon-dashboard"></i> Dashboard
            </a>
            <a href="<?php echo BASE_URL; ?>admin/manage_users" class="nav-item">
                <i class="icon-users"></i> Kullanıcı Yönetimi
            </a>
            <a href="<?php echo BASE_URL; ?>admin/themes" class="nav-item">
                <i class="icon-palette"></i> Tema Yönetimi
            </a>
            <a href="<?php echo BASE_URL; ?>admin/settings" class="nav-item">
                <i class="icon-settings"></i> Sistem Ayarları
            </a>
            <a href="<?php echo BASE_URL; ?>users/logout" class="nav-item logout">
                <i class="icon-logout"></i> Çıkış
            </a>
        </nav>
    </div>
    
    <div class="admin-content">
        <div class="admin-header">
            <h1>Dashboard</h1>
            <p>Sistem genel durumu ve istatistikler</p>
        </div>
        
        <!-- İstatistik Kartları -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="icon-users"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['total_users']); ?></h3>
                    <p>Toplam Kullanıcı</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="icon-link"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['total_links']); ?></h3>
                    <p>Toplam Link</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="icon-calendar"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo number_format($stats['this_month_users']); ?></h3>
                    <p>Bu Ay Yeni Üye</p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="stat-icon">
                    <i class="icon-chart"></i>
                </div>
                <div class="stat-content">
                    <h3><?php echo $stats['total_links'] > 0 ? number_format($stats['total_links'] / $stats['total_users'], 1) : '0'; ?></h3>
                    <p>Ortalama Link/Kullanıcı</p>
                </div>
            </div>
        </div>
        
        <!-- Son Aktiviteler -->
        <div class="dashboard-widgets">
            <div class="widget">
                <div class="widget-header">
                    <h3>Son Kayıt Olan Kullanıcılar</h3>
                </div>
                <div class="widget-content">
                    <?php if (empty($recent_users)): ?>
                        <p class="empty-state">Henüz kullanıcı yok.</p>
                    <?php else: ?>
                        <div class="user-list">
                            <?php foreach ($recent_users as $user): ?>
                                <div class="user-item">
                                    <div class="user-avatar">
                                        <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                                    </div>
                                    <div class="user-info">
                                        <h4><?php echo safe_output($user['username']); ?></h4>
                                        <p><?php echo safe_output($user['email']); ?></p>
                                        <small><?php echo date('d.m.Y H:i', strtotime($user['created_at'])); ?></small>
                                    </div>
                                    <div class="user-actions">
                                        <a href="<?php echo BASE_URL . safe_output($user['username']); ?>" 
                                           target="_blank" class="btn-icon" title="Profili Görüntüle">
                                            <i class="icon-eye"></i>
                                        </a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <div class="widget">
                <div class="widget-header">
                    <h3>Sistem Bilgileri</h3>
                </div>
                <div class="widget-content">
                    <div class="system-info">
                        <div class="info-item">
                            <span class="info-label">PHP Versiyonu:</span>
                            <span class="info-value"><?php echo PHP_VERSION; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">MySQL Versiyonu:</span>
                            <span class="info-value"><?php echo mysqli_get_server_info($conn); ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Server:</span>
                            <span class="info-value"><?php echo $_SERVER['SERVER_SOFTWARE']; ?></span>
                        </div>
                        <div class="info-item">
                            <span class="info-label">Disk Kullanımı:</span>
                            <span class="info-value">
                                <?php 
                                $bytes = disk_free_space(".");
                                $gb = round($bytes / 1024 / 1024 / 1024, 2);
                                echo $gb . " GB Boş";
                                ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Hızlı Aksiyonlar -->
        <div class="quick-actions">
            <h3>Hızlı Aksiyonlar</h3>
            <div class="actions-grid">
                <a href="<?php echo BASE_URL; ?>admin/manage_users" class="action-card">
                    <i class="icon-users"></i>
                    <h4>Kullanıcıları Yönet</h4>
                    <p>Kullanıcıları listele, düzenle veya sil</p>
                </a>
                
                <a href="<?php echo BASE_URL; ?>admin/themes" class="action-card">
                    <i class="icon-palette"></i>
                    <h4>Tema Ekle</h4>
                    <p>Yeni tema ekle veya mevcut temaları düzenle</p>
                </a>
                
                <a href="<?php echo BASE_URL; ?>admin/settings" class="action-card">
                    <i class="icon-settings"></i>
                    <h4>Sistem Ayarları</h4>
                    <p>Site ayarlarını yapılandır</p>
                </a>
                
                <a href="<?php echo BASE_URL; ?>admin/backup" class="action-card">
                    <i class="icon-download"></i>
                    <h4>Yedek Al</h4>
                    <p>Veritabanı yedeği oluştur</p>
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
