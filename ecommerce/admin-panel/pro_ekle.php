<?php
require '../baglan.php';

// Kategorileri almak için sorgu
$query = $db->prepare("SELECT cat_name FROM category");
$query->execute();
$categories = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürün Ekle</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container my-5">
        <h2>Ürün Kaydet</h2>
        <form action="pro_islem.php" method="post" enctype="multipart/form-data">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Ürün Adı</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="pro_name" placeholder="Ürün Adı" required>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Ürün Açıklaması</label>
                <div class="col-sm-6">
                    <textarea class="form-control" name="pro_des" placeholder="Ürün Açıklaması" required></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Kategori Seçin</label>
                <div class="col-sm-6">
                    <select name="pro_cat" class="form-control" required>
                        <option value="">Kategori Seçin</option>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category['cat_name']); ?>">
                                <?php echo htmlspecialchars($category['cat_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Ürün Fiyatı</label>
                <div class="col-sm-6">
                    <input type="number" class="form-control" name="pro_price" placeholder="Ürün Fiyatı" required>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Ürün Stok Bilgisi</label>
                <div class="col-sm-6">
                    <input type="number" class="form-control" name="pro_stock" placeholder="Ürün Stok" required>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Ürün Resmi</label>
                <div class="col-sm-6">
                    <input type="file" class="form-control" name="pro_picture" required>
                </div>
            </div>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" name="urunekle" class="btn btn-primary">Ürünü Kaydet</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="/admin-panel/products.php" role="button">İptal Et</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
