<?php

ob_start();
session_start();

require '../baglan.php';


if(isset($_POST['kayit'])){
    $username = $_POST['username'];
    $password = $_POST['password'];
    $password_again = $_POST['password_again'];

    if(!$username){
        echo "Lütfen kullanıcı adınızı girin";
    } elseif(!$password || !$password_again){
        echo "Lütfen şifrenizi girin";
    } elseif($password != $password_again){
        echo "Girdiğiniz şifreler birbiri ile aynı değil";
    } else {
        // Şifreyi MD5 ile şifreleme
        $password_hashed = md5($password);

        // Veritabanı kayıt sistemi
        $sorgu = $db->prepare('INSERT INTO user SET username = ?, password = ?');
        $ekle = $sorgu->execute([
            $username, $password_hashed
        ]);

        if($ekle){
            echo "Kayıt başarıyla gerçekleşti, yönlendiriliyorsunuz";
            header('Refresh:2; login.php');
        } else {
            echo "Bir hata oluştu, tekrar kontrol ediniz.";
        }
    }
}


if(isset($_POST['giris'])){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if(!$username){
        echo "Kullanıcı adınızı giriniz";
    } elseif(!$password){
        echo "Şifrenizi giriniz";
    } else {
        // Girişte şifreyi MD5 ile şifreleme
        $password_hashed = md5($password);

        $kullanici_sor = $db->prepare('SELECT * FROM user WHERE username = ? AND password = ?');
        $kullanici_sor->execute([
            $username, $password_hashed
        ]);

        $say = $kullanici_sor->rowCount();
        if($say == 1){
            $_SESSION['username'] = $username;
            echo "Başarıyla giriş yaptınız, yönlendiriliyorsunuz";
            header('Refresh:2; index.php');
        } else {
            echo "Kullanıcı adını veya şifreyi kontrol ediniz.";
        }
    }
}




?>