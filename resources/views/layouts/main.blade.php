<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title')</title>
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Font Awesome CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Bootstrap JS ve Popper JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<style>
    .search-results {
        position: absolute;
        width: 100%;
        max-height: 300px;
        overflow-y: auto;
        z-index: 1000;
    }

    /* Sadece butonlar ve textbox'lar için geçiş efekti */
    input[type="text"],
    input[type="password"],
    .btn {
        transition: all 0.3s ease;
    }

    /* Butonlar ve textbox'ların üzerine gelindiğinde belirginleşme */
    input[type="text"]:hover,
    input[type="password"]:hover,
    .btn:hover {
        transform: scale(1.1);
    }

    /* Butonlar ve textbox'lardan ayrılınca normale dönme */
    input[type="text"]:not(:hover),
    input[type="password"]:not(:hover),
    .btn:not(:hover) {
        transform: scale(1);
    }

    /* Bildirim renklerini ayarla */
    #notificationMessage {
        padding: 10px;
        border-radius: 20px;
    }

    .nav-item {
        margin-right: 20px;
    }

    .nav-item .dropdown-menu {
        min-width: 100px;
    }

    .nav-item .dropdown-item {
        padding: 10px 20px;
        color: #333;
        text-decoration: none;
    }

    .nav-item .dropdown-item:hover {
        background-color: #f8f9fa;
        color: #007bff;
    }

    .nav-item .fa-user, .nav-item .fa-wallet {
        margin-right: 8px;
    }
    /* Genel kaydırma çubuğu özellikleri */
    ::-webkit-scrollbar {
    width: 3px; /* Kaydırma çubuğu genişliği */
    }

    /* Yatay kaydırma çubuğu */
    ::-webkit-scrollbar-track {
    background-color: #f1f1f1; /* Kaydırma çubuğu arka plan rengi */
    }

    /* Kaydırma kolu (thumb) */
    ::-webkit-scrollbar-thumb {
    background-color: rgb(0, 68, 255); /* Kaydırma çubuğu rengi */
    border-radius: 4px; /* Kenar yuvarlaklığı */
    }

    /* Zamanla kaybolma efekti */
    ::-webkit-scrollbar-thumb:hover {
    background-color: #aaa; /* Kaydırma çubuğu rengi (hover durumunda) */
    }

</style>
</head>
<body>
    <div id="notification" class="position-fixed bottom-0 end-0 p-3" style="display: none; z-index: 1000;">
        <div id="notificationMessage" class="alert" role="alert">
            <!-- Bildirim içeriği buraya gelecek -->
        </div>
    </div>

    <div class="container">
        @yield('content')
    </div>

    <script>
        // Sayfa yüklendiğinde çalışacak kod
        document.addEventListener('DOMContentLoaded', function() {
            // Bildirimi fare ile tıklanarak gizle
            document.getElementById('notification').addEventListener('click', function() {
                hideNotification();
            });

            // Controller'dan gelen hata durumunda bildirimi göster
            @if(session('error'))
                showNotification("{{ session('error') }}", "danger");
            @elseif(session('success'))
                showNotification("{{ session('success') }}", "success");
            @elseif(session('warning'))
                showNotification("{{ session('warning') }}", "warning");
            @endif
        });

        // Bildirimi göster
        function showNotification(message, type) {
            var notification = document.getElementById('notificationMessage');
            notification.innerHTML = message;
            notification.classList.add('alert-' + type);
            document.getElementById('notification').style.display = 'block';
            // Otomatik gizleme zamanlayıcısı
            setTimeout(function() {
                hideNotification();
            }, 3000);
        }

        // Bildirimi gizle
        function hideNotification() {
            var notification = document.getElementById('notification');
            notification.style.display = 'none';
        }

    </script>
</body>
</html>
