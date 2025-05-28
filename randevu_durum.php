<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

$id = $_POST['id'] ?? 0;
$durum = $_POST['durum'] ?? '';

if($id && $durum) {
    $stmt = $db->prepare("UPDATE randevular SET durum = ? WHERE id = ?");
    if($stmt->execute([$durum, $id])) {
        echo 'success';
    } else {
        echo 'error';
    }
} else {
    http_response_code(400);
    echo 'Invalid parameters';
}
?> 