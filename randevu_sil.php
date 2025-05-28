<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}

include 'db.php';

if(isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $stmt = $db->prepare("DELETE FROM randevular WHERE id = ?");
        $stmt->execute([$id]);
        
        echo '<script>alert("Randevu başarıyla silindi!");
        window.location.href = "admin_panel.php";</script>';
    } catch(PDOException $e) {
        echo "Hata: " . $e->getMessage();
    }
} else {
    header("Location: admin_panel.php");
    exit();
}
?> 