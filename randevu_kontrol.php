<?php
include 'db.php';

function randevuKontrol($tarih, $saat, $islem_id) {
    global $db;
    
    // Seçilen işlemin süresini al
    $stmt = $db->prepare("SELECT sure FROM kategoriler WHERE id = ? AND aktif = 1");
    $stmt->execute([$islem_id]);
    $islem = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$islem) {
        return ['success' => false, 'message' => 'Geçersiz işlem seçimi.'];
    }
    
    $islem_suresi = $islem['sure'];
    
    // Seçilen saati dakikaya çevir
    $secilen_saat = strtotime($saat);
    $bitis_saati = strtotime("+{$islem_suresi} minutes", $secilen_saat);
    
    // Aynı gün için tüm randevuları kontrol et
    $stmt = $db->prepare("
        SELECT r.*, k.sure 
        FROM randevular r 
        JOIN kategoriler k ON r.islem_id = k.id 
        WHERE r.tarih = ? AND r.durum != 'İptal'
    ");
    $stmt->execute([$tarih]);
    $randevular = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $dolu_saatler = [];
    foreach($randevular as $randevu) {
        $randevu_baslangic = strtotime($randevu['saat']);
        $randevu_bitis = strtotime("+{$randevu['sure']} minutes", $randevu_baslangic);
        
        // Çakışma kontrolü
        if(($secilen_saat >= $randevu_baslangic && $secilen_saat < $randevu_bitis) ||
           ($bitis_saati > $randevu_baslangic && $bitis_saati <= $randevu_bitis) ||
           ($secilen_saat <= $randevu_baslangic && $bitis_saati >= $randevu_bitis)) {
            
            $dolu_saatler[] = [
                'baslangic' => date('H:i', $randevu_baslangic),
                'bitis' => date('H:i', $randevu_bitis)
            ];
        }
    }
    
    if(!empty($dolu_saatler)) {
        $mesaj = "Bu saatler dolu:<br>";
        foreach($dolu_saatler as $dolu) {
            $mesaj .= "• {$dolu['baslangic']} - {$dolu['bitis']}<br>";
        }
        return ['success' => false, 'message' => $mesaj];
    }
    
    return ['success' => true];
}

// AJAX isteği kontrolü
if(isset($_POST['tarih']) && isset($_POST['saat']) && isset($_POST['islem_id'])) {
    header('Content-Type: application/json');
    echo json_encode(randevuKontrol($_POST['tarih'], $_POST['saat'], $_POST['islem_id']));
    exit;
}
?> 