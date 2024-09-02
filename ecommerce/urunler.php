<?php 
include 'baglan.php';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=ecommerce;charset=utf8", "root", "hilal123");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Veritabanı bağlantısı başarısız: " . $e->getMessage();
    die();
}

if (isset($_GET['cat_id'])) {
    $cat_id = (int)$_GET['cat_id'];

    // cat_id'ye göre cat_name'i bul
    $query = $pdo->prepare("SELECT cat_name FROM category WHERE cat_id = :cat_id");
    $query->bindParam(':cat_id', $cat_id, PDO::PARAM_INT);
    $query->execute();
    $category = $query->fetch(PDO::FETCH_ASSOC);

    if ($category) {
        $cat_name = $category['cat_name'];

        // Sayfa numarasını al
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 4;  // Her sayfada gösterilecek ürün sayısı
        $offset = ($page - 1) * $limit;

        // Toplam ürün sayısını al
        $totalQuery = $pdo->prepare("SELECT COUNT(*) FROM product WHERE pro_cat = :cat_name");
        $totalQuery->bindParam(':cat_name', $cat_name, PDO::PARAM_STR);
        $totalQuery->execute();
        $totalProducts = $totalQuery->fetchColumn();
        $totalPages = ceil($totalProducts / $limit);

        // pro_cat (cat_name) ile eşleşen ürünleri al (limit ve offset kullanarak)
        $query = $pdo->prepare("SELECT pro_id, pro_name, pro_picture, pro_price 
                                FROM product 
                                WHERE pro_cat = :cat_name 
                                LIMIT :limit OFFSET :offset");
        $query->bindParam(':cat_name', $cat_name, PDO::PARAM_STR);
        $query->bindParam(':limit', $limit, PDO::PARAM_INT);
        $query->bindParam(':offset', $offset, PDO::PARAM_INT);
        $query->execute();
        $products = $query->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "Geçersiz kategori ID'si.";
        exit;
    }
} else {
    echo "Kategori seçilmedi.";
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
    <title>Heka Store - Ürünler</title>
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
            <a class="navbar-brand" href="http://localhost/index.php">HEKA STORE</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0 ms-lg-4">
                    <li class="nav-item"><a class="nav-link active" aria-current="page" href="http://localhost/index.php">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="http://localhost/kategori.php">Kategoriler</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Header-->
    <header class="bg-dark py-5">
        <div class="container px-4 px-lg-5 my-5">
            <div class="text-center text-white">
                <h1 class="display-4 fw-bolder">Shop in style</h1>
                <p class="lead fw-normal text-white-50 mb-0">With The Difference of Heka Store</p>
            </div>
        </div>
    </header>
    <!-- Section-->
    <section class="py-5">
        <div class="container px-4 px-lg-5 mt-5">
            <h1 class="text-center mb-4"><?php echo htmlspecialchars($cat_name); ?> Kategorisindeki Ürünler</h1>
            <div class="row gx-4 gx-lg-5 row-cols-2 row-cols-md-3 row-cols-xl-4 justify-content-center">
                <?php if ($products): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="col mb-5">
                            <div class="card h-100">
                                <!-- Product image-->
                                <img class="card-img-top" src="admin-panel/images/<?php echo htmlspecialchars($product['pro_picture']); ?>" alt="<?php echo htmlspecialchars($product['pro_name']); ?>" />
                                <!-- Product details-->
                                <div class="card-body p-4">
                                    <div class="text-center">
                                        <!-- Product name-->
                                        <h5 class="fw-bolder"><?php echo htmlspecialchars($product['pro_name']); ?></h5>
                                        <!-- Product price-->
                                        <?php echo htmlspecialchars($product['pro_price']); ?> TL
                                    </div>
                                </div>
                                <!-- Product actions-->
                                <div class="card-footer p-4 pt-0 border-top-0 bg-transparent">
                                    <div class="text-center"><a class="btn btn-outline-dark mt-auto" href="detail.php?pro_id=<?php echo $product['pro_id']; ?>">Detaylara Git</a></div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-center">Bu kategoride ürün bulunmamaktadır.</p>
                <?php endif; ?>
            </div>
            <!-- Pagination -->
            <nav aria-label="Page navigation example">
                <ul class="pagination justify-content-center">
                    <?php if($page > 1): ?>
                    <li class="page-item">
                        <a class="page-link" href="?cat_id=<?php echo $cat_id; ?>&page=<?php echo $page - 1; ?>" aria-label="Previous">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <?php endif; ?>
                    
                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <li class="page-item <?php echo ($page == $i) ? 'active' : ''; ?>">
                        <a class="page-link" href="?cat_id=<?php echo $cat_id; ?>&page=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                    <?php endfor; ?>
                    
                    <?php if($page < $totalPages): ?>
                    <li class="page-item">
                        <a class="page-link" href="?cat_id=<?php echo $cat_id; ?>&page=<?php echo $page + 1; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                    <?php endif; ?>
                </ul>
            </nav>
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
