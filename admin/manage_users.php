<?php
// Router sistemi kullanıldığında core dosyalar zaten yüklü
// require_once '../core/config.php'; // Bu satır kaldırıldı
// require_once '../core/session.php'; // Bu satır kaldırıldı

// Admin yetkisi kontrolü
require_admin();

// Kullanıcı işlemleri
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    if (!verify_csrf_token($_POST['csrf_token'])) {
        set_error_message('Güvenlik hatası. Lütfen tekrar deneyin.');
    } else {
        switch ($_POST['action']) {
            case 'delete_user':
                $user_id = intval($_POST['user_id']);
                
                // Önce kullanıcının linklerini sil
                $delete_links_query = "DELETE FROM links WHERE user_id = $user_id";
                mysqli_query($conn, $delete_links_query);
                
                // Sonra kullanıcıyı sil
                $delete_user_query = "DELETE FROM users WHERE id = $user_id";
                
                if (mysqli_query($conn, $delete_user_query)) {
                    set_success_message('Kullanıcı başarıyla silindi.');
                } else {
                    set_error_message('Kullanıcı silinirken bir hata oluştu.');
                }
                break;
                
            case 'toggle_status':
                $user_id = intval($_POST['user_id']);
                $status = $_POST['status'] == '1' ? '0' : '1';
                
                $update_query = "UPDATE users SET status = '$status' WHERE id = $user_id";
                
                if (mysqli_query($conn, $update_query)) {
                    set_success_message('Kullanıcı durumu güncellendi.');
                } else {
                    set_error_message('Durum güncellenirken bir hata oluştu.');
                }
                break;
        }
        
        redirect(BASE_URL . 'admin/manage_users');
    }
}

// Arama ve filtreleme
$search = isset($_GET['search']) ? safe_input($_GET['search']) : '';
$page = isset($_GET['page']) ? max(1, intval($_GET['page'])) : 1;
$per_page = 20;
$offset = ($page - 1) * $per_page;

// Toplam kullanıcı sayısı
$count_query = "SELECT COUNT(*) as count FROM users";
if ($search) {
    $count_query .= " WHERE username LIKE '%$search%' OR email LIKE '%$search%'";
}
$count_result = mysqli_query($conn, $count_query);
$total_users = mysqli_fetch_assoc($count_result)['count'];
$total_pages = ceil($total_users / $per_page);

// Kullanıcıları getir
$users_query = "SELECT u.*, 
                       (SELECT COUNT(*) FROM links WHERE user_id = u.id) as link_count
                FROM users u";

if ($search) {
    $users_query .= " WHERE u.username LIKE '%$search%' OR u.email LIKE '%$search%'";
}

$users_query .= " ORDER BY u.created_at DESC LIMIT $per_page OFFSET $offset";
$users_result = mysqli_query($conn, $users_query);

$page_title = 'Kullanıcı Yönetimi';
$additional_css = ['assets/css/admin.css'];
?>

<?php include 'includes/header.php'; ?>

<div class="admin-container">
    <div class="admin-sidebar">
        <div class="admin-brand">
            <h3>Admin Panel</h3>
        </div>
        
        <nav class="admin-nav">
            <a href="<?php echo BASE_URL; ?>admin/dashboard" class="nav-item">
                <i class="icon-dashboard"></i> Dashboard
            </a>
            <a href="<?php echo BASE_URL; ?>admin/manage_users" class="nav-item active">
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
            <h1>Kullanıcı Yönetimi</h1>
            <p>Toplam <?php echo number_format($total_users); ?> kullanıcı</p>
        </div>
        
        <!-- Arama ve Filtreler -->
        <div class="admin-filters">
            <form method="GET" class="search-form">
                <div class="search-group">
                    <input type="text" name="search" placeholder="Kullanıcı adı veya e-mail ara..." 
                           value="<?php echo safe_output($search); ?>" class="search-input">
                    <button type="submit" class="btn btn-primary">
                        <i class="icon-search"></i> Ara
                    </button>
                    <?php if ($search): ?>
                        <a href="<?php echo BASE_URL; ?>admin/manage_users" class="btn btn-secondary">
                            <i class="icon-close"></i> Temizle
                        </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>
        
        <!-- Kullanıcı Listesi -->
        <div class="admin-table-container">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Kullanıcı</th>
                        <th>E-mail</th>
                        <th>Link Sayısı</th>
                        <th>Kayıt Tarihi</th>
                        <th>İşlemler</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($users_result) == 0): ?>
                        <tr>
                            <td colspan="5" class="empty-state">
                                <?php if ($search): ?>
                                    Arama kriterinize uygun kullanıcı bulunamadı.
                                <?php else: ?>
                                    Henüz kayıtlı kullanıcı yok.
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php while ($user = mysqli_fetch_assoc($users_result)): ?>
                            <tr>
                                <td>
                                    <div class="user-cell">
                                        <div class="user-avatar">
                                            <?php if ($user['profile_picture']): ?>
                                                <img src="<?php echo BASE_URL . UPLOAD_PATH . safe_output($user['profile_picture']); ?>" 
                                                     alt="<?php echo safe_output($user['username']); ?>">
                                            <?php else: ?>
                                                <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
                                            <?php endif; ?>
                                        </div>
                                        <div class="user-info">
                                            <strong><?php echo safe_output($user['username']); ?></strong>
                                            <?php if ($user['bio']): ?>
                                                <small><?php echo safe_output(substr($user['bio'], 0, 50)); ?>...</small>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </td>
                                <td><?php echo safe_output($user['email']); ?></td>
                                <td>
                                    <span class="badge"><?php echo $user['link_count']; ?></span>
                                </td>
                                <td>
                                    <time datetime="<?php echo $user['created_at']; ?>">
                                        <?php echo date('d.m.Y', strtotime($user['created_at'])); ?>
                                    </time>
                                    <br>
                                    <small><?php echo date('H:i', strtotime($user['created_at'])); ?></small>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="<?php echo BASE_URL . safe_output($user['username']); ?>" 
                                           target="_blank" class="btn-icon" title="Profili Görüntüle">
                                            <i class="icon-eye"></i>
                                        </a>
                                        
                                        <button class="btn-icon edit-user" 
                                                data-user-id="<?php echo $user['id']; ?>"
                                                data-username="<?php echo safe_output($user['username']); ?>"
                                                data-email="<?php echo safe_output($user['email']); ?>"
                                                title="Düzenle">
                                            <i class="icon-edit"></i>
                                        </button>
                                        
                                        <form method="POST" style="display: inline;" 
                                              onsubmit="return confirm('Bu kullanıcıyı silmek istediğinizden emin misiniz? Bu işlem geri alınamaz!')">
                                            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                                            <input type="hidden" name="action" value="delete_user">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" class="btn-icon btn-danger" title="Sil">
                                                <i class="icon-delete"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        
        <!-- Sayfalama -->
        <?php if ($total_pages > 1): ?>
            <div class="pagination">
                <?php if ($page > 1): ?>
                    <a href="?page=<?php echo $page - 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                       class="btn btn-secondary">
                        <i class="icon-arrow-left"></i> Önceki
                    </a>
                <?php endif; ?>
                
                <span class="page-info">
                    Sayfa <?php echo $page; ?> / <?php echo $total_pages; ?>
                </span>
                
                <?php if ($page < $total_pages): ?>
                    <a href="?page=<?php echo $page + 1; ?><?php echo $search ? '&search=' . urlencode($search) : ''; ?>" 
                       class="btn btn-secondary">
                        Sonraki <i class="icon-arrow-right"></i>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Kullanıcı Düzenleme Modal -->
<div id="editUserModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Kullanıcı Düzenle</h3>
            <button class="modal-close" id="closeEditModal">
                <i class="icon-close"></i>
            </button>
        </div>
        <div class="modal-body">
            <form id="editUserForm" method="POST">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                <input type="hidden" name="action" value="edit_user">
                <input type="hidden" name="user_id" id="edit_user_id">
                
                <div class="form-group">
                    <label for="edit_username">Kullanıcı Adı</label>
                    <input type="text" id="edit_username" name="username" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_email">E-mail</label>
                    <input type="email" id="edit_email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_password">Yeni Şifre (Boş bırakırsanız değişmez)</label>
                    <input type="password" id="edit_password" name="password">
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                    <button type="button" class="btn btn-secondary" id="cancelEdit">İptal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal işlemleri
    const editModal = document.getElementById('editUserModal');
    const editButtons = document.querySelectorAll('.edit-user');
    const closeEditModal = document.getElementById('closeEditModal');
    const cancelEdit = document.getElementById('cancelEdit');
    
    editButtons.forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-user-id');
            const username = this.getAttribute('data-username');
            const email = this.getAttribute('data-email');
            
            document.getElementById('edit_user_id').value = userId;
            document.getElementById('edit_username').value = username;
            document.getElementById('edit_email').value = email;
            document.getElementById('edit_password').value = '';
            
            editModal.classList.add('active');
        });
    });
    
    closeEditModal.addEventListener('click', function() {
        editModal.classList.remove('active');
    });
    
    cancelEdit.addEventListener('click', function() {
        editModal.classList.remove('active');
    });
    
    editModal.addEventListener('click', function(e) {
        if (e.target === editModal) {
            editModal.classList.remove('active');
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>