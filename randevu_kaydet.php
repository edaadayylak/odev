<?php
include 'db.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'C:/wamp64/logs/php_error.log');

try {
    // Form verilerini al
    $adsoyad = trim($_POST['adsoyad'] ?? '');
    $telefon = trim($_POST['telefon'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $islem_id = trim($_POST['islem_id'] ?? '');
    $tarih = trim($_POST['tarih'] ?? '');
    $saat = trim($_POST['saat'] ?? '');
    $cinsiyet = trim($_POST['cinsiyet'] ?? '');

    // Boş alan kontrolü
    if(empty($adsoyad) || empty($telefon) || empty($islem_id) || empty($tarih) || empty($saat) || empty($cinsiyet)) {
        throw new Exception('Lütfen tüm alanları doldurun.');
    }

    // İşlem bilgilerini al
    $stmt = $db->prepare("SELECT kategori_adi FROM kategoriler WHERE id = ?");
    $stmt->execute([$islem_id]);
    $islem = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$islem) {
        throw new Exception('Geçersiz işlem seçimi');
    }

    // Randevuyu kaydet
    $sql = "INSERT INTO randevular (adsoyad, telefon, email, cinsiyet, islem_id, islem, tarih, saat, durum) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, 'Bekliyor')";
    
    $stmt = $db->prepare($sql);
    
    if($stmt->execute([$adsoyad, $telefon, $email, $cinsiyet, $islem_id, $islem['kategori_adi'], $tarih, $saat])) {
        $lastId = $db->lastInsertId();
        echo json_encode([
            'success' => true,
            'message' => 'Randevunuz başarıyla oluşturuldu.',
            'id' => $lastId
        ]);
    } else {
        throw new Exception('Randevu kaydedilemedi');
    }

} catch (Exception $e) {
    error_log("Hata: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 