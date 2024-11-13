<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$username = $_SESSION['username'] ?? '';
$role = $_SESSION['role'] ?? '';

if (!$username || $role !== 'psikolog') {
    http_response_code(403);
    echo 'Akses ditolak';
    exit;
}

$date = $_GET['date'] ?? '';

$db = \Config\Database::getInstance();

$sesi = [];
if ($date) {
    $query = $db->prepare("SELECT * FROM sesi WHERE tanggal = :date");
    $query->bindParam(':date', $date);
    $query->execute();
    $sesi = $query->fetchAll(PDO::FETCH_ASSOC);


}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh</title>
    <link rel="stylesheet" href="Public/Css/Psikolog/Jadwal.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
</head>
<body>

    <div class ="sidebar" id="sidebar">
        <div class="close-btn" id="close-btn">
            <i class='bx bx-x'></i>
        </div>
        <div class="logo">
            <img src="Resource\Image\Logo.jpg" alt="Harmonikah">
        </div>
        <ul>
            <li ><a href="/dashboard"><i class='bx bxs-home'></i></a></li>
            <li class="active"><a href="/jadwal"><i class='bx bxs-phone-call'></i></a></li>
            <li><a href="/forum"><i class='bx bxs-conversation'></i></a></li>
            <li><a href="/webbinar"><i class='bx bxs-video' ></i></a></li>
            <li><a href="/artikel"><i class='bx bx-book-open' ></i></a></li>
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
        <div class="calendar-container">
            <div class="month-title" id="month-title"></div>
            <div class="week" id="week-container">
                <!-- Daftar tanggal akan diisi oleh JavaScript -->
            </div>
        </div>
        <div class="jadwal-list">
            <p id="awalan">Silahkan Pilih Tanggal</p>
            <?php if ($date && $sesi): ?>
                <?php foreach ($sesi as $row): ?>
                    <?php
                    $ids = $row['member_id'];
                    $carimember = $db->prepare("SELECT * FROM users WHERE user_id = :ids");
                    $carimember->bindParam(':ids',$ids);
                    $carimember->execute();
                    $member = $carimember->fetch(PDO::FETCH_ASSOC);
                    ?>
                    <div class="sesi-item">
                        <p><?= htmlspecialchars($row['waktu_mulai'] . ' - ' . $row['waktu_akhir']) ?></p>
                        <p class="namamember"><?= htmlspecialchars($member['username']) ?></p>
                        <a href="/room?sesi_id=<?= htmlspecialchars($row['sesi_id']) ?>" class="mulai-button">Mulai</a>
                    </div>
                <?php endforeach; ?>
            <?php elseif ($date): ?>
                <p>Tidak ada sesi untuk tanggal ini.</p>
            <?php endif; ?>
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
<script>
    // Fungsi untuk mendapatkan hari pertama (Senin) dari minggu ini
    function getMonday(d) {
        d = new Date(d);
        const day = d.getDay(),
              diff = d.getDate() - day + (day === 0 ? -6 : 1); 
        return new Date(d.setDate(diff));
    }

    // Mendapatkan hari pertama minggu ini (Senin)
    const today = new Date();
    const monday = getMonday(today);

    // Buat list tanggal untuk seminggu (Senin - Minggu)
    const dates = [];
    for (let i = 0; i < 7; i++) {
        const date = new Date(monday);
        date.setDate(monday.getDate() + i);
        dates.push(date);
    }

    // Menampilkan nama bulan dan tahun sesuai dengan tanggal saat ini
    const monthTitle = document.getElementById('month-title');
    const currentMonth = today.toLocaleString('default', { month: 'long' });
    const currentYear = today.getFullYear();
    monthTitle.textContent = `${currentMonth} ${currentYear}`;

    // Menampilkan daftar tanggal dan hari sebagai tombol pada elemen dengan id "week-container"
    const weekContainer = document.getElementById('week-container');
    weekContainer.innerHTML = dates.map(date => {
        const dayName = date.toLocaleString('default', { weekday: 'short' }); // Mendapatkan nama hari
        return `<button class="date-button" onclick="filterByDate('${date.toISOString().slice(0, 10)}')">
                    ${dayName} ${date.getDate()}
                </button>`;
    }).join('');

    // Fungsi untuk memfilter berdasarkan tanggal yang dipilih
    function filterByDate(selectedDate) {
        const url = new URL(window.location.href);
        url.searchParams.set('date', selectedDate);
        window.location.href = url;
    }
    const awalan = document.getElementById('awalan');

// Cek apakah ada parameter "date" di URL
const urlParams = new URLSearchParams(window.location.search);
const dateParam = urlParams.get('date');

// Jika tidak ada parameter "date", tampilkan elemen "awalan"
if (!dateParam) {
    awalan.style.display = 'block';
} else {
    awalan.style.display = 'none';
}
</script>

</html>