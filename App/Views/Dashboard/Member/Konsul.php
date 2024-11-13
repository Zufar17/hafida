<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$username = $_SESSION['username'] ?? '';
$role = $_SESSION['role'] ?? '';
$user_id = $_SESSION['user_id'] ?? '';

if (!$username || $role !== 'member') {
    header('Location: /login');
    exit;
}

$db = \Config\Database::getInstance();

$daftar_psikolog = $db->prepare("SELECT * FROM psikolog");
$daftar_psikolog->execute();
$daftar_psikolog_result = $daftar_psikolog->fetchAll(PDO::FETCH_ASSOC);

$search_query = $_GET['search'] ?? '';

$filtered_psikolog = array_filter($daftar_psikolog_result, function($psikolog) use ($search_query) {
    return stripos($psikolog['nama'], $search_query) !== false || stripos($psikolog['kategori'], $search_query) !== false;
});

$sesiquery = $db->prepare("SELECT * FROM sesi WHERE member_id = :user_id");
$sesiquery->bindParam(':user_id', $user_id);
$sesiquery->execute();
$sesi = $sesiquery->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh</title>
    <link rel="stylesheet" href="Public/Css/Member/Konsul.css">
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
        <h3>Jadwal Konsultasi Anda</h3>
        <div class="jadwal-tersedia">
            <?php if (!empty($sesi)): ?>
                <?php foreach ($sesi as $row): ?>
                    <div class="sesi-item">
                        <p><?= htmlspecialchars($row['waktu_mulai'] . ' - ' . $row['waktu_akhir']) ?></p>
                        <a href="/room?sesi_id=<?= htmlspecialchars($row['sesi_id']) ?>" class="mulai-button">Mulai</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Tidak ada sesi untuk tanggal ini.</p>
            <?php endif; ?>
        </div>

        <form method="GET" action="">
            <input type="text" name="search" placeholder="Cari nama psikolog atau kategori..." value="<?php echo htmlspecialchars($search_query); ?>" />
            <button type="submit">Cari</button>
        </form>

        <div class="list">
            <?php if (!empty($filtered_psikolog)): ?>
                <?php foreach ($filtered_psikolog as $psikolog): ?>
                    <div class="list-psikolog">
                        <?php 
                        $imageSrc = 'data:image/jpeg;base64,' . base64_encode($psikolog['gambar']) ?? '';
                        ?>
                        <img id="preview" src="<?= $imageSrc ?>" alt="Profile Image" />
                        <div class="info-psikolog">
                            <h3><?php echo htmlspecialchars($psikolog['nama']); ?></h3>
                            <p><?php echo htmlspecialchars($psikolog['kategori']); ?></p>
                            <p class="pengalaman"><i class='bx bxs-briefcase-alt-2'></i> <?php echo htmlspecialchars($psikolog['periode_kerja']); ?> Tahun</p>
                            <p>Rp. <?php echo htmlspecialchars($psikolog['harga']); ?></p>
                            <div class="order-container">
                                <form method="POST" action="/profilprofesional">
                                    <input type="hidden" name="psikolog_id" value="<?php echo htmlspecialchars($psikolog['psikolog_id']); ?>" />
                                    <button type="submit" class="btn-order"><i class='bx bx-money'></i> Informasi & Order</button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Tidak ada psikolog yang sesuai dengan pencarian.</p>
            <?php endif; ?>
        </div>
    </div>

    <div id="detail-psikolog" class="detail-psikolog" style="display: none;">
        <h3 id="detail-nama"></h3>
        <p id="detail-kategori"></p>
        <p id="detail-periode"></p>
        <p id="detail-harga"></p>
        <button onclick="closeDetail()">Tutup</button>
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