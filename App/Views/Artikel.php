<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$db = \Config\Database::getInstance();

// Ambil semua topik yang unik
$queryTopik = $db->prepare("SELECT DISTINCT topik FROM artikel");
$queryTopik->execute();
$daftarTopik = $queryTopik->fetchAll(PDO::FETCH_ASSOC);

// Cek apakah ada topik yang dipilih
$topikDipilih = isset($_GET['topik']) ? $_GET['topik'] : '';

// Query untuk mengambil artikel berdasarkan topik yang dipilih
if ($topikDipilih) {
    $queryArtikel = $db->prepare("SELECT * FROM artikel WHERE topik = :topik");
    $queryArtikel->bindParam(':topik', $topikDipilih);
    $queryArtikel->execute();
} else {
    $queryArtikel = $db->prepare("SELECT * FROM artikel");
    $queryArtikel->execute();
}
$daftarArtikel = $queryArtikel->fetchAll(PDO::FETCH_ASSOC);

$queryArtikelPopuler = $db->prepare("SELECT * FROM artikel ORDER BY artikel_id DESC LIMIT 1");
$queryArtikelPopuler->execute();
$artikelPopuler = $queryArtikelPopuler->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['artikel_id'])) {
    $artikelId = $_POST['artikel_id'];
    
    $queryDetail = $db->prepare("SELECT judul, isi, penulis FROM artikel WHERE artikel_id = :artikel_id");
    $queryDetail->bindParam(':artikel_id', $artikelId);
    $queryDetail->execute();
    
    $detailArtikel = $queryDetail->fetch(PDO::FETCH_ASSOC);
    
    header('Content-Type: application/json');
    echo json_encode($detailArtikel);
    exit;
}


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh</title>
    <link rel="stylesheet" href="Public\Css\Artikel.css">
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
            <li><a href="/"><i class='bx bxs-home'></i></a></li>
            <li><a href="/konsul"><i class='bx bxs-phone-call'></i></a></li>
            <li><a href="/forum"><i class='bx bxs-conversation'></i></a></li>
            <li><a href="/webbinar"><i class='bx bxs-video'></i></a></li>
            <li class="active"><a href="/artikel"><i class='bx bx-book-open' ></i></a></li>
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
            <a href="/login"><button>Login</button></a>
        </div>
    </div>

    <div class="main-content">
        <div class="judul1">Topik Terkini</div>
        
        <div class="topik-list">
            <?php if (!empty($daftarTopik)): ?>
                <a href="/artikel" class="topik-item">Semua</a>
                <?php foreach ($daftarTopik as $topik): ?>
                    <a href="?topik=<?php echo urlencode($topik['topik']); ?>" class="topik-item">
                        <?php echo htmlspecialchars($topik['topik']); ?>
                    </a>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Belum Ada Topik</p>
            <?php endif; ?>
        </div>

        <div class="populer">
            Artikel Terpopuler
        </div>
        <div class="artikel-populer">
            <?php if (!empty($artikelPopuler)): ?>
                <div class="artikel-item">
                    <?php 
                    $imageSrc = 'data:image/jpeg;base64,' . base64_encode($artikelPopuler['gambar']) ?? '';
                    ?>
                    <img src="<?= $imageSrc ?>" alt="" class="artikel-image" data-id="<?= $artikelPopuler['artikel_id'] ?>">
                    <h2><?php echo htmlspecialchars($artikelPopuler['judul']); ?></h2>
                </div>
            <?php else: ?>
                <p>Tidak ada artikel terpopuler.</p>
            <?php endif; ?>
        </div>
        
        <div class="populer">
            Artikel Terbaru
        </div>

        <div class="artikel-list">
            <?php if (!empty($daftarArtikel)): ?>
                <?php foreach ($daftarArtikel as $artikel): ?>
                    <div class="artikel-item">
                        <?php 
                        $imageSrc = 'data:image/jpeg;base64,' . base64_encode($artikel['gambar']) ?? '';
                        ?>
                        <img src="<?= $imageSrc ?>" alt="" class="artikel-image" data-id="<?= $artikel['artikel_id'] ?>">
                        <h2><?php echo htmlspecialchars($artikel['judul']); ?></h2>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Tidak ada artikel untuk topik ini.</p>
            <?php endif; ?>
        </div>

    </div>

    <div id="popup" class="popup">
        <div class="popup-content">
            <h2 class="popup-judul" id="popup-judul"></h2>
            <p class="popup-isi" id="popup-isi"></p>
            <p><strong class="popup-penulis">Penulis:</strong> <span id="popup-penulis"></span></p>
            <button id="btn-tutup" class="tutup-btn">Tutup</button>
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
            <div class="profesional">
                <span>Kamu Profesional?</span>
                <a href="/daftarprofesional"><button>Daftar</button></a>
            </div>
        </div>
    </div>

</body>
<script src="Public/Js/Script.js" type="text/javascript"></script>
<script>
document.querySelectorAll('.artikel-image').forEach(item => {
    item.addEventListener('click', event => {
        const artikelId = event.target.dataset.id;

        const formData = new FormData();
        formData.append('artikel_id', artikelId);

        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('popup').style.display = 'flex';
            document.getElementById('popup-judul').innerText = data.judul; // Mengisi dengan data judul
            document.getElementById('popup-isi').innerText = data.isi; // Mengisi dengan data isi
            document.getElementById('popup-penulis').innerText = data.penulis; // Mengisi dengan data penulis
        });
    });
});

// Menutup pop-up saat tombol Tutup diklik
document.getElementById('btn-tutup').addEventListener('click', () => {
    document.getElementById('popup').style.display = 'none';
});


// Menutup pop-up saat area luar diklik
window.addEventListener('click', (event) => {
    const popup = document.getElementById('popup');
    if (event.target === popup) {
        popup.style.display = 'none';
    }
});

// Menutup sidebar
document.getElementById('close-btn').addEventListener('click', () => {
    const sidebar = document.getElementById('sidebar');
    sidebar.style.display = 'none';
});

// Menampilkan sidebar
document.getElementById('menu-toggle').addEventListener('click', () => {
    const sidebar = document.getElementById('sidebar');
    sidebar.style.display = 'block';
});
</script>

</html>