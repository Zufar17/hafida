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

function tambahArtikel($db, $topik, $judul, $penulis, $isi, $gambar) {
    try {
        // Query untuk menambahkan artikel ke database
        $query = $db->prepare("INSERT INTO artikel (topik, judul, penulis, isi, gambar) VALUES (:topik, :judul, :penulis, :isi, :gambar)");
        
        // Bind parameter untuk menghindari SQL injection
        $query->bindParam(':topik', $topik);
        $query->bindParam(':judul', $judul);
        $query->bindParam(':penulis', $penulis);
        $query->bindParam(':isi', $isi);
        $query->bindParam(':gambar', $gambar, PDO::PARAM_LOB);
        
        // Eksekusi query
        $query->execute();
        
        return "Artikel berhasil ditambahkan.";
    } catch (PDOException $e) {
        return "Gagal menambahkan artikel: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $db = \Config\Database::getInstance();
    
    $topik = $_POST['topik'];
    $judul = $_POST['judul'];
    $penulis = $_POST['penulis'];
    $isi = $_POST['isi'];
    $gambar = null;

    // Jika ada gambar yang diunggah, baca isi file-nya
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $gambar = file_get_contents($_FILES['gambar']['tmp_name']);
    }

    // Panggil fungsi untuk menambahkan artikel
    $hasil = tambahArtikel($db, $topik, $judul, $penulis, $isi, $gambar);
    
    echo $hasil;
}

?>


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh</title>
    <link rel="stylesheet" href="Public/Css/Owner/AddArtikel.css">
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
            <li><a href="/forum"><i class='bx bxs-conversation'></i></a></li>
            <li><a href="/webbinar"><i class='bx bxs-video' ></i></a></li>
            <li><a href="/artikel"><i class='bx bx-book-open' ></i></a></li>
            <li class="active"><a href="/addartikel"><i class='bx bx-folder-plus'></i></a></li>
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
        <h2>Tambah Artikel Baru</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <label for="topik">Topik:</label>
            <select name="topik" id="topik" required>
                <option value="Financial">Financial</option>
                <option value="Parenting">Parenting</option>
                <option value="Komunikasi">Komunikasi</option>
            </select><br><br>
            
            <label for="judul">Judul:</label>
            <input type="text" name="judul" id="judul" required><br><br>
            
            <label for="penulis">Penulis:</label>
            <input type="text" name="penulis" id="penulis" required><br><br>
            
            <label for="isi">Isi:</label>
            <textarea name="isi" id="isi" required></textarea><br><br>
            
            <label for="gambar">Gambar:</label>
            <input type="file" name="gambar" id="gambar" accept="image/*"><br><br>
            
            <button type="submit">Tambah Artikel</button>
        </form>
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