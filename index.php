<?php
include 'db.php';
include 'randevu_kontrol.php';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Beauty Queen - Kuaför Randevu Sistemi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .gradient-bg {
            background: linear-gradient(135deg, #ff69b4, #ff1493);
        }
        .glass-effect {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body class="min-h-screen bg-gradient-to-br from-pink-100 via-pink-200 to-pink-300">
    <div class="container mx-auto px-4 py-8">
        <!-- Header -->
        <div class="text-center mb-12">
            <h1 class="text-4xl font-bold text-pink-600 mb-2">
                <i class="fas fa-crown text-yellow-400 mr-2"></i>Beauty Queen
            </h1>
            <p class="text-pink-500">Güzelliğiniz Bizim İşimiz!</p>
        </div>

        <!-- Ana Bölüm -->
        <div class="max-w-4xl mx-auto">
            <div class="glass-effect rounded-lg shadow-xl p-8 mb-8">
                <h2 class="text-2xl font-bold text-pink-600 mb-6">
                    <i class="fas fa-calendar-alt mr-2"></i>Randevu Al
                </h2>

                <form id="randevuForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Kişisel Bilgiler -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 mb-2">Ad Soyad</label>
                                <div class="relative">
                                    <i class="fas fa-user absolute left-3 top-3 text-pink-400"></i>
                                    <input type="text" name="adsoyad" required
                                        class="w-full pl-10 pr-4 py-2 border border-pink-200 rounded-lg focus:ring-2 focus:ring-pink-400 focus:border-transparent">
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-700 mb-2">Telefon</label>
                                <div class="relative">
                                    <i class="fas fa-phone absolute left-3 top-3 text-pink-400"></i>
                                    <input type="tel" name="telefon" required pattern="[0-9]{10}"
                                        class="w-full pl-10 pr-4 py-2 border border-pink-200 rounded-lg focus:ring-2 focus:ring-pink-400 focus:border-transparent"
                                        placeholder="5XX XXX XXXX">
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-700 mb-2">E-posta</label>
                                <div class="relative">
                                    <i class="fas fa-envelope absolute left-3 top-3 text-pink-400"></i>
                                    <input type="email" name="email"
                                        class="w-full pl-10 pr-4 py-2 border border-pink-200 rounded-lg focus:ring-2 focus:ring-pink-400 focus:border-transparent">
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-700 mb-2">Cinsiyet</label>
                                <div class="relative">
                                    <i class="fas fa-venus-mars absolute left-3 top-3 text-pink-400"></i>
                                    <select name="cinsiyet" required
                                        class="w-full pl-10 pr-4 py-2 border border-pink-200 rounded-lg focus:ring-2 focus:ring-pink-400 focus:border-transparent">
                                        <option value="Kadın">Kadın</option>
                                        <option value="Erkek">Erkek</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Randevu Detayları -->
                        <div class="space-y-4">
                            <div>
                                <label class="block text-gray-700 mb-2">İşlem</label>
                                <div class="relative">
                                    <i class="fas fa-cut absolute left-3 top-3 text-pink-400"></i>
                                    <select name="islem_id" required id="islem_id"
                                        class="w-full pl-10 pr-4 py-2 border border-pink-200 rounded-lg focus:ring-2 focus:ring-pink-400 focus:border-transparent">
                                        <option value="">İşlem Seçin</option>
                                        <?php
                                        $islemler = $db->query("SELECT * FROM kategoriler WHERE aktif = 1 ORDER BY kategori_adi");
                                        while($islem = $islemler->fetch(PDO::FETCH_ASSOC)) {
                                            echo '<option value="'.$islem['id'].'" data-sure="'.$islem['sure'].'">'
                                                .$islem['kategori_adi'].' - '.$islem['fiyat'].' ₺ ('.$islem['sure'].' dk)</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-700 mb-2">Tarih</label>
                                <div class="relative">
                                    <i class="fas fa-calendar absolute left-3 top-3 text-pink-400"></i>
                                    <input type="date" name="tarih" required id="tarih" min="<?php echo date('Y-m-d'); ?>"
                                        class="w-full pl-10 pr-4 py-2 border border-pink-200 rounded-lg focus:ring-2 focus:ring-pink-400 focus:border-transparent">
                                </div>
                            </div>

                            <div>
                                <label class="block text-gray-700 mb-2">Saat</label>
                                <div class="relative">
                                    <i class="fas fa-clock absolute left-3 top-3 text-pink-400"></i>
                                    <select name="saat" required id="saat"
                                        class="w-full pl-10 pr-4 py-2 border border-pink-200 rounded-lg focus:ring-2 focus:ring-pink-400 focus:border-transparent">
                                        <option value="">Saat Seçin</option>
                                        <?php
                                        $baslangic = strtotime('09:00');
                                        $bitis = strtotime('20:00');
                                        $aralik = 30 * 60; // 30 dakika

                                        for($i = $baslangic; $i <= $bitis; $i += $aralik) {
                                            echo '<option value="'.date('H:i', $i).'">'.date('H:i', $i).'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="text-center">
                        <button type="submit" class="gradient-bg text-white px-8 py-3 rounded-full hover:opacity-90 transition-opacity">
                            <i class="fas fa-check-circle mr-2"></i>Randevu Al
                        </button>
                    </div>
                </form>
            </div>

            <!-- Hizmetler -->
            <div class="glass-effect rounded-lg shadow-xl p-8">
                <h2 class="text-2xl font-bold text-pink-600 mb-6">
                    <i class="fas fa-star mr-2"></i>Hizmetlerimiz
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php
                    $hizmetler = $db->query("SELECT * FROM kategoriler WHERE aktif = 1 ORDER BY kategori_adi");
                    while($hizmet = $hizmetler->fetch(PDO::FETCH_ASSOC)) {
                        echo '<div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow">';
                        echo '<h3 class="text-lg font-semibold text-pink-600 mb-2">'.$hizmet['kategori_adi'].'</h3>';
                      
                        echo '<p class="text-pink-500 font-bold"><i class="fas fa-tag mr-2"></i>'.$hizmet['fiyat'].' ₺</p>';
                        echo '</div>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.getElementById('randevuForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        // Form verilerini konsola yazdır
        console.log('Form verileri:');
        for (let pair of formData.entries()) {
            console.log(pair[0] + ': ' + pair[1]);
        }

        // Randevu kaydı
        fetch('randevu_kaydet.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            console.log('Randevu kaydet sonucu:', result);
            if(result.success) {
                Swal.fire({
                    title: 'Başarılı!',
                    text: result.message,
                    icon: 'success',
                    confirmButtonColor: '#ff1493'
                }).then(() => {
                    window.location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Hata!',
                    text: result.message,
                    icon: 'error',
                    confirmButtonColor: '#ff1493'
                });
            }
        })
        .catch(error => {
            console.error('Hata:', error);
            Swal.fire({
                title: 'Hata!',
                text: 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.',
                icon: 'error',
                confirmButtonColor: '#ff1493'
            });
        });
    });

    // Tarih kontrolü
    document.getElementById('tarih').addEventListener('change', function() {
        const today = new Date();
        const selected = new Date(this.value);
        
        if(selected < today) {
            this.value = today.toISOString().split('T')[0];
            Swal.fire({
                title: 'Uyarı!',
                text: 'Geçmiş bir tarih seçemezsiniz!',
                icon: 'warning',
                confirmButtonColor: '#ff1493'
            });
        }
    });
    </script>
</body>
</html> 