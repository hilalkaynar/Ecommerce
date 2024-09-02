<?php
ob_start();
session_start();

require '../baglan.php';

// Dosya yükleme ayarları
$target_dir = "images/"; // Resimlerin kaydedeceği klasör
$target_file = $target_dir . basename($_FILES["pro_picture"]["name"]);
$imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Form verilerini al
$pro_name = $_POST['pro_name'];
$pro_des = $_POST['pro_des'];
$pro_cat = $_POST['pro_cat'];
$pro_price = $_POST['pro_price'];
$pro_stock = $_POST['pro_stock'];

// Dosya yüklenmesini kontrol et
$uploadOk = 1;
if ($_FILES["pro_picture"]["error"] != UPLOAD_ERR_OK) {
    echo "Dosya yükleme hatası: " . $_FILES["pro_picture"]["error"];
    $uploadOk = 0;
} else {
    // Dosya boyutu kontrol et
    if ($_FILES["pro_picture"]["size"] > 500000) { // 500 KB limit
        echo "Üzgünüz, dosya çok büyük.";
        $uploadOk = 0;
    }

    // Dosya türü kontrol et
    if (!in_array($imageFileType, ["jpg", "jpeg", "png", "gif"])) {
        echo "Üzgünüz, sadece JPG, JPEG, PNG ve GIF dosyalarına izin verilmektedir.";
        $uploadOk = 0;
    }

    // Dosya yüklenmesini kontrol et
    if ($uploadOk) {
        if (move_uploaded_file($_FILES["pro_picture"]["tmp_name"], $target_file)) {
            echo "Dosya ". htmlspecialchars(basename($_FILES["pro_picture"]["name"])). " başarıyla yüklendi.";
        } else {
            echo "Üzgünüz, dosyanızı yüklerken bir hata oluştu.";
            $uploadOk = 0;
        }
    }
}

if (!$pro_name) {
    echo "Lütfen ürün ismini girin.";
} elseif (!$pro_des) {
    echo "Lütfen ürün açıklamasını girin.";
} elseif (!$pro_cat) {
    echo "Lütfen ürün kategorisini girin.";
} elseif (!$pro_price) {
    echo "Lütfen ürün fiyatını girin.";
} elseif (!$pro_stock) {
    echo "Lütfen ürün stok adedini girin.";
} elseif ($uploadOk == 0) {
    echo "Lütfen geçerli bir ürün resmi girin.";
} else {
    $pro_picture = basename($_FILES["pro_picture"]["name"]);

    // Veritabanı kayıt işlemi
    $sorgu = $db->prepare('INSERT INTO product (pro_name, pro_des, pro_cat, pro_price, pro_stock, pro_picture) VALUES (?, ?, ?, ?, ?, ?)');
    $ekle = $sorgu->execute([$pro_name, $pro_des, $pro_cat, $pro_price, $pro_stock, $pro_picture]);

    if ($ekle) {
        echo "Kayıt başarıyla gerçekleşti, yönlendiriliyorsunuz";
        header('Refresh:2; url=products.php');
    } else {
        echo "Bir hata oluştu, lütfen tekrar deneyin.";
    }
}
?>
