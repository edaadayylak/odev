<?php
session_start();

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        // Debug için şifreleri kontrol edelim
        $hashed_password = md5($password);
        
        $stmt = $db->prepare("SELECT * FROM admin WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($user && $user['password'] === $hashed_password) {
            $_SESSION['admin_logged_in'] = true;
            echo "success";
        } else {
            // Debug bilgisi
            error_log("Login attempt failed - Username: $username, Hashed password: $hashed_password");
            echo "error";
        }
    } catch(PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo "error";
    }
}
?> 