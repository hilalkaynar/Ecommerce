<?php
$servername = "localhost";
$username = "root";
$password = "hilal123";
$database = "ecommerce";

try {
    $connection = new PDO("mysql:host=$servername;dbname=$database", $username, $password);
    $connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

$pro_id = "";
$pro_name = "";
$pro_des = "";
$pro_cat = "";
$pro_price = "";
$pro_stock = "";
$pro_picture = "";

$errorMessage = "";
$successMessage = "";

// Kategori isimlerini çekmek için sorgu
$cat_query = "SELECT cat_id, cat_name FROM category";
$cat_stmt = $connection->prepare($cat_query);
$cat_stmt->execute();
$categories = $cat_stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (!isset($_GET["pro_id"])) {
        header("location: /admin-panel/products.php");
        exit;
    }

    $pro_id = $_GET["pro_id"];
    $sql = "SELECT * FROM product WHERE pro_id = :pro_id";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':pro_id', $pro_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        header("location: /admin-panel/products.php");
        exit;
    }

    $pro_name = $row["pro_name"];
    $pro_des = $row["pro_des"];
    $pro_cat = $row["pro_cat"];
    $pro_price = $row["pro_price"];
    $pro_stock = $row["pro_stock"];
    $pro_picture = $row["pro_picture"];
} else {
    $pro_id = $_POST["pro_id"];
    $pro_name = $_POST["pro_name"];
    $pro_des = $_POST["pro_des"];
    $pro_cat = $_POST["pro_cat"]; // Kategori adı burada
    $pro_price = $_POST["pro_price"];
    $pro_stock = $_POST["pro_stock"];

    // Dosya yükleme işlemi
    if (isset($_FILES["pro_picture"]) && $_FILES["pro_picture"]["error"] == 0) {
        $targetDir = "images/"; // Yüklenen dosyaların kaydedileceği dizin
        $fileName = basename($_FILES["pro_picture"]["name"]);
        $targetFilePath = $targetDir . $fileName;
        $fileType = pathinfo($targetFilePath, PATHINFO_EXTENSION);

        // Yalnızca belirli dosya türlerine izin vermek için
        $allowTypes = array('jpg', 'jpeg', 'png', 'gif');
        if (in_array(strtolower($fileType), $allowTypes)) {
            // Dosyayı hedef dizine yükle
            if (move_uploaded_file($_FILES["pro_picture"]["tmp_name"], $targetFilePath)) {
                $pro_picture = $fileName;
            } else {
                $errorMessage = "Dosya yükleme hatası.";
            }
        } else {
            $errorMessage = "Yalnızca JPG, JPEG, PNG ve GIF dosya türlerine izin verilir.";
        }
    } else {
        $pro_picture = $_POST["existing_picture"]; // Mevcut resmi koru
    }

    do {
        if (empty($pro_name) || empty($pro_des) || empty($pro_cat) || empty($pro_price) || empty($pro_stock)) {
            $errorMessage = "Tüm alanları doldurun.";
            break;
        }

        $sql = "UPDATE product 
                SET pro_name = :pro_name, 
                    pro_des = :pro_des, 
                    pro_cat = :pro_cat, 
                    pro_price = :pro_price, 
                    pro_stock = :pro_stock, 
                    pro_picture = :pro_picture 
                WHERE pro_id = :pro_id";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':pro_name', $pro_name, PDO::PARAM_STR);
        $stmt->bindParam(':pro_des', $pro_des, PDO::PARAM_STR);
        $stmt->bindParam(':pro_cat', $pro_cat, PDO::PARAM_STR); // Kategori adı burada
        $stmt->bindParam(':pro_price', $pro_price, PDO::PARAM_STR);
        $stmt->bindParam(':pro_stock', $pro_stock, PDO::PARAM_INT);
        $stmt->bindParam(':pro_picture', $pro_picture, PDO::PARAM_STR);
        $stmt->bindParam(':pro_id', $pro_id, PDO::PARAM_INT);
        $result = $stmt->execute();

        if (!$result) {
            $errorMessage = "Geçersiz sorgu: " . $stmt->errorInfo()[2];
            break;
        }

        $successMessage = "Ürün başarıyla güncellendi!";
        header("location: /admin-panel/products.php");
        exit;

    } while (false);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürünü Güncelle</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container my-5">
        <h2>Ürünü Güncelle</h2>

        <?php
        if (!empty($errorMessage)) {
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>$errorMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
        }
        if (!empty($successMessage)) {
            echo "
            <div class='alert alert-success alert-dismissible fade show' role='alert'>
                <strong>$successMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
        }
        ?>

        <form method="post" enctype="multipart/form-data">
            <input type="hidden" name="pro_id" value="<?php echo htmlspecialchars($pro_id); ?>">
            <input type="hidden" name="existing_picture" value="<?php echo htmlspecialchars($pro_picture); ?>">

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Ürün İsmi</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="pro_name" value="<?php echo htmlspecialchars($pro_name); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Ürün Açıklaması</label>
                <div class="col-sm-6">
                    <textarea class="form-control" name="pro_des"><?php echo htmlspecialchars($pro_des); ?></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Ürün Kategorisi</label>
                <div class="col-sm-6">
                    <select class="form-control" name="pro_cat">
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo htmlspecialchars($category['cat_name']); ?>" <?php if ($category['cat_name'] == $pro_cat) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($category['cat_name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Ürün Fiyatı</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="pro_price" value="<?php echo htmlspecialchars($pro_price); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Ürün Stok Bilgisi</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="pro_stock" value="<?php echo htmlspecialchars($pro_stock); ?>">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Mevcut Ürün Resmi</label>
                <div class="col-sm-6">
                    <img src="images/<?php echo htmlspecialchars($pro_picture); ?>" alt="Ürün Resmi" class="img-thumbnail" style="max-width: 200px;">
                </div>
            </div>

            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Yeni Ürün Resmi</label>
                <div class="col-sm-6">
                    <input type="file" class="form-control" name="pro_picture">
                </div>
            </div>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="/admin-panel/products.php" role="button">İptal Et</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>
