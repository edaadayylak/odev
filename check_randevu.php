<?php
include 'db.php';

header('Content-Type: application/json');
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'C:/wamp64/logs/php_error.log');

try {
    // JSON verisini al
    $data = json_decode(file_get_contents('php://input'), true);
    
    error_log("Gelen kontrol verisi: " . print_r($data, true));

    if (!$data) {
        throw new Exception('Geçersiz veri formatı');
    }

    $adsoyad = trim($data['adsoyad'] ?? '');
    $tarih = trim($data['tarih'] ?? '');
    $saat = trim($data['saat'] ?? '');

    if (empty($adsoyad) || empty($tarih) || empty($saat)) {
        throw new Exception('Eksik parametreler');
    }

    // Veritabanında randevuyu kontrol et
    $stmt = $db->prepare("SELECT * FROM randevular WHERE adsoyad = ? AND tarih = ? AND saat = ? ORDER BY id DESC LIMIT 1");
    $stmt->execute([$adsoyad, $tarih, $saat]);
    $randevu = $stmt->fetch(PDO::FETCH_ASSOC);

    error_log("Bulunan randevu: " . print_r($randevu, true));

    echo json_encode([
        'success' => true,
        'exists' => ($randevu !== false),
        'data' => $randevu
    ]);

} catch (Exception $e) {
    error_log("Randevu kontrol hatası: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?> 