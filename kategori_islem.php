<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

$action = $_GET['action'] ?? '';

switch($action) {
    case 'get':
        $id = $_GET['id'] ?? 0;
        $stmt = $db->prepare("SELECT * FROM kategoriler WHERE id = ?");
        $stmt->execute([$id]);
        $kategori = $stmt->fetch(PDO::FETCH_ASSOC);
        echo json_encode($kategori);
        break;

    case 'add':
        $kategori_adi = $_POST['kategori_adi'] ?? '';
        $fiyat = $_POST['fiyat'] ?? 0;
        $sure = $_POST['sure'] ?? 30;

        $stmt = $db->prepare("INSERT INTO kategoriler (kategori_adi, fiyat, sure) VALUES (?, ?, ?)");
        if($stmt->execute([$kategori_adi, $fiyat, $sure])) {
            echo 'success';
        } else {
            echo 'error';
        }
        break;

    case 'update':
        $id = $_POST['kategori_id'] ?? 0;
        $kategori_adi = $_POST['kategori_adi'] ?? '';
        $fiyat = $_POST['fiyat'] ?? 0;
        $sure = $_POST['sure'] ?? 30;

        $stmt = $db->prepare("UPDATE kategoriler SET kategori_adi = ?, fiyat = ?, sure = ? WHERE id = ?");
        if($stmt->execute([$kategori_adi, $fiyat, $sure, $id])) {
            echo 'success';
        } else {
            echo 'error';
        }
        break;

    case 'toggle':
        $id = $_GET['id'] ?? 0;
        $stmt = $db->prepare("UPDATE kategoriler SET aktif = NOT aktif WHERE id = ?");
        if($stmt->execute([$id])) {
            echo 'success';
        } else {
            echo 'error';
        }
        break;

    default:
        http_response_code(400);
        echo 'Invalid action';
        break;
}
?> 