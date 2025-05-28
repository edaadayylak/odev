<?php
session_start();
if(!isset($_SESSION['admin_logged_in'])) {
    header("Location: admin_login.php");
    exit();
}
include 'db.php';
?>
<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Kuaför Randevu Sistemi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-pink-50">
    <div class="container mx-auto px-4 py-8">
        <!-- Üst Menü -->
        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-pink-600">Admin Panel</h1>
            <div class="space-x-4">
                <button onclick="openKategoriModal()" class="bg-pink-500 text-white px-4 py-2 rounded hover:bg-pink-600">
                    <i class="fas fa-plus mr-2"></i>Yeni Kategori
                </button>
                <a href="admin_logout.php" class="bg-gray-500 text-white px-4 py-2 rounded hover:bg-gray-600">
                    <i class="fas fa-sign-out-alt mr-2"></i>Çıkış
                </a>
            </div>
        </div>

        <!-- Kategoriler -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <h2 class="text-xl font-bold mb-4">Kategoriler</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-pink-50">
                        <tr>
                            <th class="px-6 py-3 text-left">Kategori</th>
                            <th class="px-6 py-3 text-left">Fiyat</th>
                            <th class="px-6 py-3 text-left">Süre</th>
                            <th class="px-6 py-3 text-left">Durum</th>
                            <th class="px-6 py-3 text-left">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $kategoriler = $db->query("SELECT * FROM kategoriler ORDER BY kategori_adi");
                        while($row = $kategoriler->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr class="border-b">';
                            echo '<td class="px-6 py-4">'.$row['kategori_adi'].'</td>';
                            echo '<td class="px-6 py-4">'.number_format($row['fiyat'], 2).' ₺</td>';
                            echo '<td class="px-6 py-4">'.$row['sure'].' dk</td>';
                            echo '<td class="px-6 py-4">';
                            echo $row['aktif'] ? 
                                '<span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">Aktif</span>' : 
                                '<span class="px-2 py-1 bg-red-100 text-red-800 rounded-full text-sm">Pasif</span>';
                            echo '</td>';
                            echo '<td class="px-6 py-4">';
                            echo '<button onclick="editKategori('.$row['id'].')" class="text-blue-600 hover:text-blue-900 mr-3"><i class="fas fa-edit"></i></button>';
                            echo '<button onclick="toggleKategori('.$row['id'].', '.$row['aktif'].')" class="text-'.($row['aktif'] ? 'red' : 'green').'-600"><i class="fas fa-'.($row['aktif'] ? 'times' : 'check').'"></i></button>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Randevular -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-xl font-bold mb-4">Randevular</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-pink-50">
                        <tr>
                            <th class="px-6 py-3 text-left">Müşteri</th>
                            <th class="px-6 py-3 text-left">Tarih/Saat</th>
                            <th class="px-6 py-3 text-left">İşlem</th>
                            <th class="px-6 py-3 text-left">Durum</th>
                            <th class="px-6 py-3 text-left">İşlemler</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $randevular = $db->query("SELECT * FROM randevular ORDER BY tarih DESC, saat DESC");
                        while($row = $randevular->fetch(PDO::FETCH_ASSOC)) {
                            echo '<tr class="border-b">';
                            echo '<td class="px-6 py-4">'.$row['adsoyad'].'<br><small class="text-gray-500">'.$row['telefon'].'</small></td>';
                            echo '<td class="px-6 py-4">'.$row['tarih'].'<br>'.$row['saat'].'</td>';
                            echo '<td class="px-6 py-4">'.$row['islem'].'</td>';
                            echo '<td class="px-6 py-4">';
                            echo '<select onchange="updateDurum('.$row['id'].', this.value)" class="border rounded px-2 py-1">';
                            $durumlar = ['Bekliyor', 'Onaylandı', 'Tamamlandı', 'İptal', 'Gelmedi'];
                            foreach($durumlar as $durum) {
                                $selected = ($row['durum'] == $durum) ? ' selected' : '';
                                echo '<option value="'.$durum.'"'.$selected.'>'.$durum.'</option>';
                            }
                            echo '</select>';
                            echo '</td>';
                            echo '<td class="px-6 py-4">';
                            echo '<button onclick="deleteRandevu('.$row['id'].')" class="text-red-600 hover:text-red-900"><i class="fas fa-trash"></i></button>';
                            echo '</td>';
                            echo '</tr>';
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Kategori Modal -->
    <div id="kategoriModal" class="hidden fixed inset-0 bg-black bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="modalTitle">Yeni Kategori</h3>
            <form id="kategoriForm">
                <input type="hidden" id="kategori_id" name="kategori_id">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Kategori Adı:</label>
                    <input type="text" id="kategori_adi" name="kategori_adi" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Fiyat (₺):</label>
                    <input type="number" id="fiyat" name="fiyat" step="0.01" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Süre (Dakika):</label>
                    <input type="number" id="sure" name="sure" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeKategoriModal()" class="px-4 py-2 bg-gray-200 text-gray-800 rounded hover:bg-gray-300">İptal</button>
                    <button type="submit" class="px-4 py-2 bg-pink-500 text-white rounded hover:bg-pink-600">Kaydet</button>
                </div>
            </form>
        </div>
    </div>

    <script>
    function openKategoriModal() {
        document.getElementById('modalTitle').textContent = 'Yeni Kategori';
        document.getElementById('kategoriForm').reset();
        document.getElementById('kategori_id').value = '';
        document.getElementById('kategoriModal').classList.remove('hidden');
    }

    function closeKategoriModal() {
        document.getElementById('kategoriModal').classList.add('hidden');
    }

    function editKategori(id) {
        fetch('kategori_islem.php?action=get&id=' + id)
            .then(response => response.json())
            .then(data => {
                document.getElementById('modalTitle').textContent = 'Kategori Düzenle';
                document.getElementById('kategori_id').value = data.id;
                document.getElementById('kategori_adi').value = data.kategori_adi;
                document.getElementById('fiyat').value = data.fiyat;
                document.getElementById('sure').value = data.sure;
                document.getElementById('kategoriModal').classList.remove('hidden');
            });
    }

    function toggleKategori(id, currentStatus) {
        Swal.fire({
            title: 'Emin misiniz?',
            text: currentStatus ? 'Bu kategori pasif duruma geçecek.' : 'Bu kategori aktif duruma geçecek.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ec4899',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Evet',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('kategori_islem.php?action=toggle&id=' + id)
                    .then(response => response.text())
                    .then(data => {
                        if(data === 'success') {
                            window.location.reload();
                        }
                    });
            }
        });
    }

    function updateDurum(id, durum) {
        fetch('randevu_durum.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'id=' + id + '&durum=' + durum
        })
        .then(response => response.text())
        .then(data => {
            if(data === 'success') {
                Swal.fire({
                    title: 'Başarılı!',
                    text: 'Durum güncellendi',
                    icon: 'success',
                    timer: 1500,
                    showConfirmButton: false
                });
            }
        });
    }

    function deleteRandevu(id) {
        Swal.fire({
            title: 'Emin misiniz?',
            text: "Bu randevu silinecek!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ec4899',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Evet, Sil',
            cancelButtonText: 'İptal'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = 'randevu_sil.php?id=' + id;
            }
        });
    }

    document.getElementById('kategoriForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const id = document.getElementById('kategori_id').value;
        const action = id ? 'update' : 'add';
        
        fetch('kategori_islem.php?action=' + action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            if(data === 'success') {
                window.location.reload();
            }
        });
    });
    </script>
</body>
</html> 