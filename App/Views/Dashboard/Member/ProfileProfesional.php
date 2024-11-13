<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$username = $_SESSION['username'] ?? '';
$role = $_SESSION['role'] ?? '';

if (!$username || $role !== 'member') {
    header('Location: /login');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $psikolog_ids = $_POST['psikolog_id'] ?? null;

    if ($psikolog_ids) {
        $db = \Config\Database::getInstance();

        $query = $db->prepare("SELECT * FROM psikolog WHERE psikolog_id = :id");
        $query->bindParam(':id', $psikolog_ids, PDO::PARAM_INT);
        $query->execute();
        $psikolog = $query->fetch(PDO::FETCH_ASSOC);

        if (!$psikolog) {
            echo "Data psikolog tidak ditemukan.";
            exit;
        }
    } else {
        echo "ID psikolog tidak valid.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh</title>
    <link rel="stylesheet" href="Public/Css/Member/ProfileProfesional.css">
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
        <div class="profiles">
            <div class="profile-box">
                <div class="profile-img">
                    <?php 
                    $imageSrc = 'data:image/jpeg;base64,' . base64_encode($psikolog['gambar']) ?? '';
                    ?>
                    <img src="<?= $imageSrc ?>" alt="Profile Image" />
                </div>
                <div class="profile-info">
                    <h3><?php echo htmlspecialchars($psikolog['nama']); ?></h3>
                    <p><?php echo htmlspecialchars($psikolog['kategori']); ?></p>   
                    <p class="pengalaman"><i class='bx bxs-briefcase-alt-2'></i> <?php echo htmlspecialchars($psikolog['periode_kerja']); ?> Tahun</p>
                    <p class="value">Rp. <?php echo htmlspecialchars($psikolog['harga']); ?></p>
                    <div class="info-item">
                        <i class='bx bxs-graduation bold-icon'></i>
                        <div class="info-label-container">
                            <span class="info-label">Alumnus:</span>
                            <p class="value"><?php echo htmlspecialchars($psikolog['alumnus']); ?></p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class='bx bxs-building-house bold-icon'></i>
                        <div class="info-label-container">
                            <span class="info-label">Praktik:</span>
                            <p class="value"><?php echo htmlspecialchars($psikolog['praktik']); ?></p>
                        </div>
                    </div>
                    <div class="info-item">
                        <i class='bx bxs-id-card bold-icon'></i>
                        <div class="info-label-container">
                            <span class="info-label">No STR:</span>
                            <p class="value"><?php echo htmlspecialchars($psikolog['no_str']); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="jadwal-container">
                <form method="POST" action="/aturjadwal">
                    <input type="hidden" name="psikolog_id" value="<?php echo htmlspecialchars($psikolog['psikolog_id']); ?>" />
                    <button type="submit" class="btn-order">Atur Jadwal</button>
                </form>
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