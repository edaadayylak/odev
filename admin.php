<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Girişi - Kuaför Randevu Sistemi</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center">
        <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
            <div class="text-center mb-6">
                <i class="fas fa-user-lock text-4xl text-blue-500 mb-3"></i>
                <h2 class="text-2xl font-bold">Admin Girişi</h2>
            </div>
            
            <form id="loginForm" action="admin_login_check.php" method="POST">
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">
                        <i class="fas fa-user mr-2"></i>Kullanıcı Adı:
                    </label>
                    <input type="text" name="username" id="username" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <div class="mb-6">
                    <label class="block text-gray-700 mb-2">
                        <i class="fas fa-lock mr-2"></i>Şifre:
                    </label>
                    <input type="password" name="password" id="password" class="w-full px-3 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500" required>
                </div>

                <button type="submit" class="w-full bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600 transition duration-300 flex items-center justify-center">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Giriş Yap
                </button>
            </form>
            
            <div class="mt-6">
                <div class="relative">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-gray-300"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span class="px-2 bg-white text-gray-500">Demo Bilgileri</span>
                    </div>
                </div>
                
                <div class="mt-4">
                    <table class="w-full text-sm">
                        <tr class="hover:bg-gray-50 cursor-pointer" onclick="fillCredentials('admin', 'admin123')">
                            <td class="py-2 px-3 border">Kullanıcı Adı</td>
                            <td class="py-2 px-3 border font-mono">admin</td>
                        </tr>
                        <tr class="hover:bg-gray-50 cursor-pointer" onclick="fillCredentials('admin', 'admin123')">
                            <td class="py-2 px-3 border">Şifre</td>
                            <td class="py-2 px-3 border font-mono">admin123</td>
                        </tr>
                    </table>
                </div>
            </div>
            
            <div class="mt-4 text-center">
                <a href="index.php" class="text-blue-500 hover:text-blue-700 flex items-center justify-center">
                    <i class="fas fa-home mr-2"></i>
                    Ana Sayfaya Dön
                </a>
            </div>
        </div>
    </div>

    <script>
    function fillCredentials(username, password) {
        document.getElementById('username').value = username;
        document.getElementById('password').value = password;
    }

    document.getElementById('loginForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        fetch('admin_login_check.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            console.log('Server response:', data); // Debug için
            if(data.trim() === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Başarılı!',
                    text: 'Giriş yapılıyor...',
                    showConfirmButton: false,
                    timer: 1500
                }).then(() => {
                    window.location.href = 'admin_panel.php';
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Hata!',
                    text: 'Hatalı kullanıcı adı veya şifre!'
                });
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Hata!',
                text: 'Bir hata oluştu. Lütfen tekrar deneyin.'
            });
        });
    });
    </script>
</body>
</html> 