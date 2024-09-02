<?php 
include 'baglan.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=ecommerce;charset=utf8", "root", "hilal123");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Veritabanı bağlantısı başarısız: " . $e->getMessage();
    die();
}

// pro_id'yi al
$pro_id = isset($_GET['pro_id']) ? (int)$_GET['pro_id'] : 0;

// Eğer geçerli bir ID gelmediyse anasayfaya yönlendir
if ($pro_id == 0) {
    header("Location: index.php");
    exit;
}

// Veritabanından ürün detaylarını çek
$query = $pdo->prepare("SELECT pro_name, pro_picture, pro_price, pro_cat, pro_des FROM product WHERE pro_id = :pro_id");
$query->bindParam(':pro_id', $pro_id, PDO::PARAM_INT);
$query->execute();
$product = $query->fetch(PDO::FETCH_ASSOC);

// Eğer ürün bulunamazsa anasayfaya yönlendir
if (!$product) {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title><?php echo htmlspecialchars($product['pro_name']); ?> - Heka Store</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="assets/favicon.ico" />
    <!-- Bootstrap icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>
<body>
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="index.php">Heka Store</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="kategori.php">Kategoriler</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Product section-->
    <section class="py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="row gx-4 gx-lg-5 align-items-center">
                <div class="col-md-6"><img class="card-img-top mb-5 mb-md-0" src="admin-panel/images/<?php echo htmlspecialchars($product['pro_picture']); ?>" alt="<?php echo htmlspecialchars($product['pro_name']); ?>" /></div>
                <div class="col-md-6">
                    <h1 class="display-5 fw-bolder"><?php echo htmlspecialchars($product['pro_name']); ?></h1>
                    <div class="fs-5 mb-5">
                        <span>Fiyat: <?php echo htmlspecialchars($product['pro_price']); ?> TL  /// </span>
                        <span>Kategori: <?php echo htmlspecialchars($product['pro_cat']); ?></span>
                    </div>
                    <p class="lead"><?php echo htmlspecialchars($product['pro_des']); ?></p>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer-->
    <footer class="py-5 bg-dark">
        <div class="container"><p class="m-0 text-center text-white">Copyright &copy; Your Website 2023</p></div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>
</html>
