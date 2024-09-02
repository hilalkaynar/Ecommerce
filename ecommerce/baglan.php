<?php
try {
     $db = new PDO("mysql:host=localhost;dbname=ecommerce", "root", "hilal123");
} catch ( PDOException $e ){
     print $e->getMessage();
}

?>