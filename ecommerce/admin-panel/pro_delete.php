<?php
if (isset($_GET["pro_id"])) {
    $pro_id = $_GET["pro_id"];

    try {
        $db = new PDO("mysql:host=localhost;dbname=ecommerce", "root", "hilal123");
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Bağlantı hatası: " . $e->getMessage());
    }

    // Silme işlemi için prepare ve execute 
    $sql = "DELETE FROM product WHERE pro_id = :pro_id";
    $stmt = $db->prepare($sql);
    $stmt->bindParam(':pro_id', $pro_id, PDO::PARAM_INT);
    $stmt->execute();

    // İşlem başarılı olduktan sonra yönlendirme 
    header("Location: /admin-panel/products.php");
    exit;
} else {
    // pro_id GET parametresi sağlanmadıysa, kullanıcıyı geri yönlendirme
    header("Location: /admin-panel/products.php");
    exit;
}
?>