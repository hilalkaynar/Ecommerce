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

$cat_id = "";
$cat_name = "";

$errorMessage = "";
$successMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'GET') {

    if (!isset($_GET["cat_id"])) {
        header("location: /admin-panel/categories.php");
        exit;
    }

    $cat_id = $_GET["cat_id"];
    $sql = "SELECT * FROM category WHERE cat_id = :cat_id";
    $stmt = $connection->prepare($sql);
    $stmt->bindParam(':cat_id', $cat_id, PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$row) {
        header("location: /admin-panel/categories.php");
        exit;
    }

    $cat_name = $row["cat_name"];
} else {
    $cat_id = $_POST["cat_id"];
    $cat_name = $_POST["cat_name"];

    do {
        if (empty($cat_name) || empty($cat_id)) {
            $errorMessage = "Tüm alanları doldurun.";
            break;
        }
        $sql = "UPDATE category SET cat_name = :cat_name WHERE cat_id = :cat_id";
        $stmt = $connection->prepare($sql);
        $stmt->bindParam(':cat_name', $cat_name, PDO::PARAM_STR);
        $stmt->bindParam(':cat_id', $cat_id, PDO::PARAM_INT);
        $result = $stmt->execute();

        if (!$result) {
            $errorMessage = "Invalid query: " . $stmt->errorInfo()[2];
            break;
        }

        $successMessage = "Kategori başarıyla güncellendi!";
        header("location: /admin-panel/categories.php");
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
    <title>Kategoriyi Güncelle</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <div class="container my-5">
        <h2>Kategori Güncelle</h2>

        <?php
        if(!empty($errorMessage)){
            echo "
            <div class='alert alert-warning alert-dismissible fade show' role='alert'>
                <strong>$errorMessage</strong>
                <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
            </div>
            ";
        }
        ?>
        <form method="post">
            <input type="hidden" name="cat_id" value="<?php echo $cat_id; ?>">
            <div class="row mb-3">
                <label class="col-sm-3 col-form-label">Kategori İsmi</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" name="cat_name" value="<?php echo $cat_name; ?>">
                </div>
            </div>

            <div class="row mb-3">
                <div class="offset-sm-3 col-sm-3 d-grid">
                    <button type="submit" class="btn btn-primary">Güncelle</button>
                </div>
                <div class="col-sm-3 d-grid">
                    <a class="btn btn-outline-primary" href="/admin-panel/categories.php" role="button">İptal Et</a>
                </div>
            </div>
        </form>
    </div>
</body>
</html>