<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$username = $_SESSION['username'] ?? '';
$role = $_SESSION['role'] ?? '';
$user_id = $_SESSION['user_id'];

if (!$username || $role !== 'psikolog') {
    header('Location: /login');
    exit;
}

$db = \Config\Database::getInstance();

// Cek keanggotaan premium
$checkPremium = $db->prepare("SELECT premium FROM users WHERE user_id = :user_id");
$checkPremium->bindParam(':user_id', $user_id);
$checkPremium->execute();
$isPremium = $checkPremium->fetch(PDO::FETCH_ASSOC);

// Ambil forum
$getForums = $db->prepare("SELECT chat_id, judul, pengirim, konten, tanggal FROM forum");
$getForums->execute();
$forums = $getForums->fetchAll(PDO::FETCH_ASSOC);

// Menangani penambahan balasan
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reply'])) {
    $chat_id = $_POST['chat_id'];
    $konten = $_POST['konten'];

    $addReply = $db->prepare("INSERT INTO reply (chat_id, pengirim, konten) VALUES (:chat_id, :pengirim, :konten)");
    $addReply->bindParam(':chat_id', $chat_id);
    $addReply->bindParam(':pengirim', $username); // Atau gunakan ID pengguna
    $addReply->bindParam(':konten', $konten);
    $addReply->execute();

    header('Location: /forum');
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh</title>
    <link rel="stylesheet" href="Public/Css/Member/Forum.css">
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
            <li><a href="/dashboard"><i class='bx bxs-home'></i></a></li>
            <li><a href="/konsul"><i class='bx bxs-phone-call'></i></a></li>
            <li class="active"><a href="/forum"><i class='bx bxs-conversation'></i></a></li>
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
                    <a href="/settings">Pengaturan</a>
                    <a href="/logout">Keluar</a>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <?php if ($isPremium && ($isPremium['premium'] === 'Y' || $isPremium['premium'] === 'N')): ?>
            <?php foreach ($forums as $forum): ?>
                <div class="forum-border">
                    <div class="fotoprofile">
                        <img src="Resource/Image/d395771085aab05244a4fb8fd91bf4ee.jpg" alt="">
                    </div>
                    <div class="forum-item">
                        <h2><?php echo htmlspecialchars($forum['judul']); ?></h2>
                        <div class="forum-content">
                            <p><?php echo htmlspecialchars($forum['konten']); ?></p>
                        </div>
                        <small>Posted on: <?php echo htmlspecialchars($forum['tanggal']); ?></small>
                        
                        <!-- Tombol Reply dengan ikon -->
                        <div class="reply">
                            <button onclick="toggleReply(this)">
                                <i class='bx bx-message-rounded-dots'></i>
                            </button>
                        </div>
                        
                        <!-- Area untuk reply dan melihat semua reply -->
                        <div class="reply-area" style="display: none;">
                            <form method="post">
                                <input type="hidden" name="chat_id" value="<?php echo $forum['chat_id']; ?>">
                                <div class="reply-input">
                                    <textarea name="konten" placeholder="Tulis balasan..." required></textarea>
                                    <button type="submit" name="reply">Kirim</button>
                                </div>
                            </form>
                            <div class="replies">
                                <?php
                                // Ambil balasan untuk forum ini
                                $getReplies = $db->prepare("SELECT pengirim, konten, tanggal FROM reply WHERE chat_id = :chat_id");
                                $getReplies->bindParam(':chat_id', $forum['chat_id']);
                                $getReplies->execute();
                                $replies = $getReplies->fetchAll(PDO::FETCH_ASSOC);

                                // Tampilkan balasan
                                foreach ($replies as $reply): ?>
                                    <div class="reply-item">
                                        <strong><?php echo htmlspecialchars($reply['pengirim']); ?>:</strong>
                                        <p><?php echo htmlspecialchars($reply['konten']); ?></p>
                                        <small>Posted on: <?php echo htmlspecialchars($reply['tanggal']); ?></small>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Anda Tidak Berlangganan</p>
        <?php endif; ?>
        <!-- Tombol untuk menambahkan forum -->
        <a href="/addforum" class="add-forum-button">
            <i class='bx bx-plus-medical'></i>
        </a>
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
    function toggleReply(button) {
    const replyArea = button.parentElement.nextElementSibling; // Dapatkan area balasan yang sesuai
    replyArea.style.display = replyArea.style.display === 'none' ? 'block' : 'none'; // Tampilkan atau sembunyikan area balasan
}

</script>
</html>
