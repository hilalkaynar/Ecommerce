<?php

ob_start();
session_start();

require '../baglan.php';


    $cat_name = $_POST['cat_name'];

    if(!$cat_name){
        echo "Lütfen kategori adını girin";
    } else{
        //veritabanı kayıt sistemi
        $sorgu = $db->prepare('INSERT INTO category SET cat_name = ?');
        $ekle = $sorgu->execute([
            $cat_name
        ]);
        if($ekle){
            echo "Kayıt başarıyla gerçekleşti, yönlendiriliyorsunuz";
            header('Refresh:2; categories.php');
        } else{
            echo "Bir hata oluştu, Tekrar Kontrol Ediniz.";
        }
    }





?>