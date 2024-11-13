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


$showQR = false;
$isLocked = false;
$qrcodeURL = '';
$amountToPay = '';
$expirationTime = '';
$totalWithTax = '';
date_default_timezone_set('Asia/Jakarta');
$payment_time = date("Y-m-d H:i:s", strtotime("+5 minutes"));

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_ids = $_POST['user_id'];
    $jumlah = 100000;

    $amount_str = strval($jumlah);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, 'https://api.paydisini.co.id/v1/');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);

    $unique_code = uniqid($username . '_');
    $api_key = '06a10122fa5db79f7c4d813954cc33ef';

    $signature_string = $api_key . $unique_code . '11' . $amount_str . '300' . 'NewTransaction';
    $signature = hash('md5', $signature_string);
    $signature2 = $api_key . $unique_code . 'StatusTransaction';
    $signatureStatus = hash('md5', $signature2);

    $post = array(
        'key' => $api_key,
        'request' => 'new',
        'unique_code' => $unique_code,
        'service' => '11',
        'amount' => $amount_str,
        'note' => 'Pembayaran dari ' . $username,
        'valid_time' => '300',
        'type_fee' => '1',
        'payment_guide' => FALSE,
        'signature' => $signature,
    );

    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error: ' . curl_error($ch);
    } else {
        $response = json_decode($result);

        if (isset($response->data)) {
            $data = $response->data;

            $showQR = true;
            $qrcodeURL = htmlspecialchars($data->qrcode_url);
            $totalWithTax = $jumlah + ($jumlah * 0.007);
            $expirationTime = time() + 300;

            $db = \Config\Database::getInstance();

            $stmt = $db->prepare("INSERT INTO bayarlangganan (user_id, username, unique_code, signature, amount, status) VALUES (?, ?, ?, ?, ?, ?)");
            $status = 'Pending';
            $stmt->bindParam(1, $user_id);
            $stmt->bindParam(2, $username);
            $stmt->bindParam(3, $unique_code);
            $stmt->bindParam(4, $signatureStatus);
            $stmt->bindParam(5, $amount_str);
            $stmt->bindParam(6, $status);
            $stmt->execute();

            $_SESSION['qrcodeURL'] = $qrcodeURL;
            $_SESSION['signatureStatus'] = $signatureStatus;
            $_SESSION['jumlah'] = $jumlah;

            header("Location: /berlangganan");
            exit;
        } else {
            echo 'Gagal mendapatkan URL pembayaran!';
        }
    }
}

if (isset($_SESSION['qrcodeURL'])) {
    $showQR = true;
    $isLocked = true;
    $qrcodeURL = $_SESSION['qrcodeURL'];
    $signatureStatus = $_SESSION['signatureStatus'];
    $jumlah = $_SESSION['jumlah'];
    $totalWithTax = $jumlah + ($jumlah * 0.007);
}

if (isset($_GET['action']) && $_GET['action'] === 'getStatus') {
    $db = \Config\Database::getInstance();

    $stmt = $db->prepare("SELECT status FROM bayarlangganan WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->bindParam(1, $user_id);
    $stmt->execute();
    $statusResult = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($statusResult) {
        echo json_encode(['status' => $statusResult['status']]);
    } else {
        echo json_encode(['status' => 'Unknown']);
    }

    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh</title>
    <link rel="stylesheet" href="Public/Css/Member/Berlangganan.css">
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
            <li><a href="/konsul"><i class='bx bxs-phone-call'></i></a></li>
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
        <div class="content-wrapper">
            <div class="left-content">
                <h2>Langganan Harmoniikah <span>/Bulan</span></h2>
                <p class="kotak">
                    <span class="total-text">Total Pembayaran</span> 
                    <span class="total-price">Rp. 100.000</span>
                </p>
                <div class="container">
                    <form method="POST" action="">
                        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id); ?>">
                        <button class="buy <?php echo $isLocked ? 'locked' : ''; ?>" type="submit" <?php echo $isLocked ? 'disabled' : ''; ?>>
                            <?php echo $isLocked ? "<i class='bx bx-lock'></i>" : "Beli"; ?>
                        </button>
                    </form>
                </div>
            </div>
            
            <div class="qris-border right-content">
                <?php if ($showQR): ?>
                <div class="qr-container">
                    <h2>Pembayaran Langganan</h2>
                    <p class="status-container">
                        <span class="status-text">Payment ID : <?php echo htmlspecialchars($signatureStatus)?></span>
                    </p>
                    <p class="status-container">
                        <span class="status-text">Status : <span id="status-text"><?php echo htmlspecialchars($statusResult['status'] ?? 'Pending'); ?></span></span>
                        <i id="emojistatus" class='bx bx-loader-alt bx-spin bx-rotate-90 status-icon'></i>
                    </p>
                    <div class="qr-image">
                        <img src="<?php echo $qrcodeURL; ?>" alt="QR Code">
                    </div>
                    <p class="qr-amount">Jumlah Yang Harus Dibayar : Rp.<?php echo number_format($totalWithTax, 0, ','); ?></p>
                    <p>Lakukan Pembayaran Sebelum : <?php echo $payment_time; ?></p>
                </div>
            <?php else: ?>
                <p>Silahkan Klik Beli Untuk Melanjutkan</p>
            <?php endif; ?>
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
<script>
    function updateStatus() {
    fetch('?action=getStatus', { method: 'GET' })
        .then(response => response.json())
        .then(data => {
            const emojistatus = document.getElementById('emojistatus');
            const statusText = document.getElementById('status-text');

            if (data.status === 'Completed') {
                // Hentikan interval pengecekan
                clearInterval(intervalId);
                clearInterval(CheckStatusintervalId); // Pastikan interval lain juga dihentikan

                statusText.textContent = data.status;

                // Pastikan elemen emoji dikosongkan dan diganti dengan elemen baru
                console.log('Updating emoji to success icon');
                emojistatus.innerHTML = "<i class='bx bx-check-circle bx-tada' style='color:#09bc1d'></i>";

                alert('Pembayaran Berhasil!');
                setTimeout(() => {
                    window.location.href = '/dashboard';
                }, 3000);
            } else {
                // Update status text tanpa mengubah emoji
                statusText.textContent = data.status;
            }
        })
        .catch(error => console.error('Error:', error));
}

function checkTransactionStatus() {
    fetch('/check2', { method: 'GET' })
        .then(response => response.json())
        .then(data => {
            document.getElementById('status').textContent = data.status;
            if (data.status === 'Success') {
                clearInterval(CheckStatusintervalId);
                clearInterval(intervalId);
            }
        })
        .catch(error => console.error('Error:', error));
}
    const intervalId = setInterval(updateStatus, 2000);
    const CheckStatusintervalId = setInterval(checkTransactionStatus, 2000);
</script>
</html>