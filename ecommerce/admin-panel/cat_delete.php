
<?php
if (isset($_GET["cat_id"])) {
    $cat_id = $_GET["cat_id"];

    try {
        $db = new PDO("mysql:host=localhost;dbname=ecommerce", "root", "hilal123");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Bağlantı hatası: " . $e->getMessage());
    }

    // İşlem için bir transaction başlat
    $db->beginTransaction();

    try {
        // Kategori ismini almak için sorgu
        $getCatNameSql = "SELECT cat_name FROM category WHERE cat_id = :cat_id";
        $getCatNameStmt = $db->prepare($getCatNameSql);
        $getCatNameStmt->bindParam(':cat_id', $cat_id, PDO::PARAM_INT);
        $getCatNameStmt->execute();
        $category = $getCatNameStmt->fetch(PDO::FETCH_ASSOC);

        if (!$category) {
            throw new Exception("Kategori bulunamadı.");
        }

        $cat_name = $category['cat_name'];

        // Önce kategoriye ait ürünleri sil
        $deleteProductsSql = "DELETE FROM product WHERE pro_cat = :cat_name";
        $deleteProductsStmt = $db->prepare($deleteProductsSql);
        $deleteProductsStmt->bindParam(':cat_name', $cat_name, PDO::PARAM_STR);
        $deleteProductsStmt->execute();

        // Ardından kategoriyi sil
        $deleteCategorySql = "DELETE FROM category WHERE cat_id = :cat_id";
        $deleteCategoryStmt = $db->prepare($deleteCategorySql);
        $deleteCategoryStmt->bindParam(':cat_id', $cat_id, PDO::PARAM_INT);
        $deleteCategoryStmt->execute();

        // Transaction'ı başarılı bir şekilde tamamla
        $db->commit();

        // İşlem başarılı olduktan sonra yönlendirme yap
        header("Location: /admin-panel/categories.php");
        exit;
    } catch (Exception $e) {
        // Hata durumunda transaction'ı geri al
        $db->rollBack();
        die("Hata: " . $e->getMessage());
    }
} else {
    // cat_id GET parametresi sağlanmadıysa, kullanıcıyı geri yönlendir
    header("Location: /admin-panel/categories.php");
    exit;
}
?>
