<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$username = $_SESSION['username'] ?? '';
$role = $_SESSION['role'] ?? '';

if (!$username || $role !== 'owner') {
    header('Location: /login');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh</title>
    <link rel="stylesheet" href="Public/Css/Owner/Owner.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>

    <div class ="sidebar" id="sidebar">
        <div class="close-btn" id="close-btn">
            <i class='bx bx-x'></i>
        </div>
        <div class="logo">
            <img src="Resource//Image//Logo.jpg" alt="Harmonikah">
        </div>
        <ul>
            <li class="active"><a href="/dashboard"><i class='bx bxs-home'></i></a></li>
            <li><a href="/forum"><i class='bx bxs-conversation'></i></a></li>
            <li><a href="/webbinar"><i class='bx bxs-video' ></i></a></li>
            <li><a href="/artikel"><i class='bx bx-book-open' ></i></a></li>
            <li><a href="/addartikel"><i class='bx bx-folder-plus'></i></a></li>
        </ul>
    </div>

    <div class="header">
        <div class="menu-toggle" id="menu-toggle">
            <i class='bx bx-menu'></i>
        </div>
        <div class="search-bar">
            <input type="text" placeholder="Cari artikel atau kategori">
            <i class='bx bx-search'></i>
        </div>
        <div class="header-links">
            <a href="#" class="link-text">Langganan</a>
            <a href="#" class="link-text">Support</a>
            <i id="profilelogo" class='bx bxs-user-circle'></i>
            <div class="user-icon" onclick="toggleDropdown()">
                <i class='bx bx-chevron-down' ></i>
                <div class="dropdown-menu" id="dropdown-menu">
                    <a href="/profile">Profil</a>
                    <a href="/settings">Pengaturan</a>
                    <a href="/logout">Keluar</a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="slider">
            <div class="slider-text">
                <h4>ARTIKEL</h4>    
                <h2 id="slider-title">Rancangan Anggaran Finansial Pernikahan</h2>
                <a id="read-more-button" href="#">Baca Sekarang</a>
            </div>
            <div class="slider-image">
                <img src="Resource/Image/images.jpg" alt="Ring" class="active">
                <img src="Resource/Image/images (1).jpg" alt="Ring">
            </div>
            <div class="slider-controls">
                <button class="prev" onclick="moveSlide(-1)">&#10094;</button>
                <button class="next" onclick="moveSlide(1)">&#10095;</button>
            </div>
        </div>


        <div class="main-features">
            <h1>Berbagai Fitur Harmonikah</h1>
            <div class="feature">
                <img src="Resource\Image\Screenshot 2024-10-09 040526.png" alt="">
                <div>
                    <h2>Konseling</h2>
                    <p>Lakukan Konseling Kesiapan Menikah dengan Konselor Berpengalaman</p>
                    <ul>
                        <li><i class='bx bxs-check-circle' id="icon-check"></i>Lakukan dimana saja dan kapan saja</li>
                        <li><i class='bx bxs-check-circle' id="icon-check"></i>Konselor yang berpengalaman</li>
                        <li><i class='bx bxs-check-circle' id="icon-check"></i>Harga yang terjangkau</li>
                    </ul>
                    <button>Coba Sekarang</button>
                </div>
            </div>
            
            <div class="feature">
                <div>
                    <h2>Forum Diskusi</h2>
                    <p>Lakukan Diskusi Kesiapan Menikah dengan Teman dan Konselor Berpengalaman</p>
                    <ul>
                        <li><i class='bx bxs-check-circle' id="icon-check"></i>Lakukan dimana saja dan kapan saja</li>
                        <li><i class='bx bxs-check-circle' id="icon-check"></i>Konselor yang berpengalaman</li>
                        <li><i class='bx bxs-check-circle' id="icon-check"></i>Harga yang terjangkau</li>
                    </ul>
                    <button>Coba Sekarang</button>
                </div>
                <img src="Resource\Image\Screenshot 2024-10-09 042016.png" alt="">
            </div>
        </div>
    </div>

    <div class="footer">
        <div class="footer-content">
            <div class="sm">
                <span>Social Media</span>
                <div class="social-icons">
                    <a href="#"><img src="Resource/Image/instagram.svg" alt="Instagram"></a>
                    <a href="#"><img src="Resource/Image/x.png" alt=""></a>
                    <a href="#"><img src="Resource/Image/tiktok.jpg" alt=""></a>
                </div>
            </div>

            <div class="about-me">
                <span>About Us</span>
                <p>Harmonikah merupakan website yang menyediakan layanan konseling terkait kesiapan pernikahan.</p>
            </div>
            <div class="company">
                <span>Company</span>
                <a href="#">Kontak Kami</a>
                <a href="#">Tentang Kami</a>
            </div>
            <div class="tos">
                <span>Terms & Policies</span>
                <a href="#">Kebijakan Privasi</a>
                <a href="#">Ketentuan Penggunaan</a>
            </div>
        </div>
    </div>

</body>
<script src="Public/Js/Script.js" type="text/javascript"></script>
</html>