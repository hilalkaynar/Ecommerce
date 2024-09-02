<?php
require '../baglan.php';

$limit = 10; // Sayfa başına gösterilecek ürün sayısı
$s = isset($_GET['s']) ? (int)$_GET['s'] : 1; // Geçerli sayfa numarası, varsayılan olarak 1
if ($s < 1) $s = 1;

$start = ($s - 1) * $limit;

// Toplam ürün sayısını bulma
$total_query = $db->prepare("SELECT COUNT(*) FROM product");
$total_query->execute();
$total_products = $total_query->fetchColumn();

// Toplam sayfa sayısı
$total_pages = ceil($total_products / $limit);

// Ürünleri sorgulama
$query = $db->prepare("SELECT * FROM product ORDER BY pro_id DESC LIMIT :start, :limit");
$query->bindValue(':start', $start, PDO::PARAM_INT);
$query->bindValue(':limit', $limit, PDO::PARAM_INT);
$query->execute();
?>

<?php require_once 'inc/header.php'; ?>
<?php require_once 'inc/sidebar.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ürünler</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0-beta1/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container my-5">
        <h2>.</h2>
        
        <a class="btn btn-primary" href="http://localhost/admin-panel/pro_ekle.php" role="button">Yeni Ürün</a>
        <br><br>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Ürün İsmi</th>
                    <th>Ürün Açıklaması</th>
                    <th>Ürün Kategorisi</th>
                    <th>Ürün Fiyatı</th>
                    <th>Stok Bilgisi</th>
                    <th>Ürün Resmi</th>
                    <th>İşlem</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($query as $row){
                ?>
                <tr>
                    <td><?php echo $row['pro_id'];?></td>
                    <td><?php echo $row['pro_name'];?></td>
                    <td><?php echo $row['pro_des'];?></td>
                    <td><?php echo $row['pro_cat'];?></td>
                    <td><?php echo $row['pro_price'];?></td>
                    <td><?php echo $row['pro_stock'];?></td>
                    <td><img src="images/<?php echo $row['pro_picture'];?>" alt="Ürün Resmi" style="width:50px;height:50px;"></td>
                    <td>
                        <a class='btn btn-primary btn-sm' href='/admin-panel/pro_edit.php?pro_id=<?php echo $row['pro_id'];?>'>Düzenle</a>
                        <a class='btn btn-danger btn-sm' href='/admin-panel/pro_delete.php?pro_id=<?php echo $row['pro_id'];?>'>Sil</a>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav aria-label="Page navigation">
            <ul class="pagination">
                <?php if($s > 1): ?>
                    <li class="page-item"><a class="page-link" href="?s=<?php echo $s - 1; ?>">&laquo; Önceki</a></li>
                <?php endif; ?>

                <?php for($i = 1; $i <= $total_pages; $i++): ?>
                    <li class="page-item <?php if($i == $s) echo 'active'; ?>">
                        <a class="page-link" href="?s=<?php echo $i; ?>"><?php echo $i; ?></a>
                    </li>
                <?php endfor; ?>

                <?php if($s < $total_pages): ?>
                    <li class="page-item"><a class="page-link" href="?s=<?php echo $s + 1; ?>">Sonraki &raquo;</a></li>
                <?php endif; ?>
            </ul>
        </nav>

    </div>
    
</body>
</html>
<?php require_once 'inc/footer.php'; ?>
