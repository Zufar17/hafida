<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$username = $_SESSION['username'] ?? '';
$role = $_SESSION['role'] ?? '';
$user_id = $_SESSION['user_id'];

if (!$username || $role !== 'member') {
    header('Location: /login');
    exit;
}

$db = \Config\Database::getInstance();

if (isset($_POST['add_forum'])) {
    $id_adders = $user_id;
    $judul = $_POST['judul'];
    $kontent = $_POST['kontent'];

    $carinama = $db->prepare("SELECT username FROM users WHERE user_id = :user_id");
    $carinama->bindParam(':user_id', $user_id);
    $carinama->execute();
    $nama = $carinama->fetch(PDO::FETCH_ASSOC);

    if ($nama) {
        $username = $nama['username'];

        try {
            $stmt = $db->prepare("INSERT INTO forum (pengirim, judul, konten) VALUES(?, ?, ?)");
            $stmt->bindParam(1, $username); // Menggunakan $username, bukan $nama
            $stmt->bindParam(2, $judul);
            $stmt->bindParam(3, $kontent);
            $stmt->execute();

            header('Location: /addforum');
            exit;
        } catch (PDOException $e) {
            echo "Error : " . $e->getMessage();
        }
    } else {
        echo "User tidak ditemukan.";
    }
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh</title>
    <link rel="stylesheet" href="Public/Css/Member/AddForum.css">
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
            <li><a href="/dashboard"><i class='bx bxs-home'></i></a></li>
            <li><a href="/konsul"><i class='bx bxs-phone-call'></i></a></li>
            <li class="active"><a href="/forum"><i class='bx bxs-conversation'></i></a></li>
            <li><a href="/vidio"><i class='bx bxs-video' ></i></a></li>
            <li><a href="/artikel"><i class='bx bx-book-open' ></i></a></li>
            <li><a href="/nilai"><i class='bx bx-task' ></i></a></li>
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
                    <a href="/logout">Keluar</a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="addforum-border">
            <form action="/addforum" method="post">
                <input type="text" name="judul" placeholder="Judul" required>
                <input type="text" name="kontent" placeholder="isi" required>
                <button type="submit" name="add_forum">Tambah</button>
            </form>
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