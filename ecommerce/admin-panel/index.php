<?php 
// Veritabanı bağlantısını ve oturum yönetimini sağla
include '../baglan.php'; 
session_start(); // Oturum başlat

// Oturum kontrolü yap
if (!isset($_SESSION['username'])) {
    // Oturum açılmamışsa login.php sayfasına yönlendir
    header('Location: login.php');
    exit; // Yönlendirme sonrası diğer kodların çalışmasını durdur
}
?>

<?php require_once 'inc/header.php'; ?>

<!-- Sidebar menu -->
<?php require_once 'inc/sidebar.php'; ?>

<main class="app-content">
  <div class="app-title">
    <div>
      <h1><i class="bi bi-speedometer"></i> Ana Sayfa</h1>
    </div>
  </div>
  <div class="row">
    <div class="row">
      <div class="col-md-6">
        <div class="tile">
          <h3 class="tile-title">Admin Paneline Hoş Geldiniz!</h3>
        </div>
      </div>
    </div>
  </div>
</main>

<?php require_once 'inc/footer.php'; ?>