<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$username = $_SESSION['username'] ?? '';
$role = $_SESSION['role'] ?? '';
$admin = 100;

if (!$username || $role !== 'member') {
    header('Location: /login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $psikolog_ids = $_POST['psikolog_id'];
    $tanggalterpilih = $_POST['tanggalterpilih2'];
    $jamterpilih = $_POST['jamterpilih'];

    if ($psikolog_ids) {
        $db = \Config\Database::getInstance();

        $dataPiskolog = $db->prepare("SELECT * FROM psikolog WHERE psikolog_id = :id");
        $dataPiskolog->bindParam(':id', $psikolog_ids, PDO::PARAM_INT);
        $dataPiskolog->execute();
        $psikolog = $dataPiskolog->fetch(PDO::FETCH_ASSOC);
    }

    $hargatotal = $psikolog['harga'] + $admin;


}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh</title>
    <link rel="stylesheet" href="Public/Css/Member/Konfirmasi.css">
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
            <li class="active"><a href="/konsul"><i class='bx bxs-phone-call'></i></a></li>
            <li><a href="/forum"><i class='bx bxs-conversation'></i></a></li>
            <li><a href="/webbinar"><i class='bx bxs-video' ></i></a></li>
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
        <div class="mt1">
            <div class="profile">
                <?php $imageSrc = 'data:image/jpeg;base64,' . base64_encode($psikolog['gambar']) ?? ''; ?>
                <img src="<?= $imageSrc ?>" alt="Profile Image" />
                <div class="profile-info">
                    <h3><?php echo htmlspecialchars($psikolog['nama'])?></h3>
                    <span><?php echo htmlspecialchars($psikolog['kategori'])?></span>
                </div>
            </div>
            <div class="hargas">
                <p class="harga-label">Biaya Sesi 1 Jam</p>
                <p class="harga-value"><?php echo 'Rp' . number_format($psikolog['harga'], 0, ',', '.'); ?></p>

                <p class="harga-label">Biaya Admin</p>
                <p class="harga-value"><?php echo 'Rp' . number_format($admin, 0, ',', '.'); ?></p>

                <p class="harga-label">Total</p>
                <p class="harga-value"><?php echo 'Rp' . number_format($hargatotal, 0, ',', '.'); ?></p>
            </div>
            
            <form action="/bayar" method="POST">
                <div class="confirmpay">
                    <button>Konfirmasi</button>
                    <input type="hidden" name="konfirmasi" value="true">
                    <input type="hidden" name="psikolog_id" value="<?php echo htmlspecialchars($psikolog_ids) ?>">
                    <input type="hidden" name="tanggalterpilih2" value="">
                    <input type="hidden" name="jamterpilih" value="">
                    <input type="hidden" name="totalharga" value="<?php echo htmlspecialchars($hargatotal)?>">
                </div>
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