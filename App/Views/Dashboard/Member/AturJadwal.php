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
    $tanggalterpilih = $_POST['tanggalterpilih'] ?? '';
    $jamterpilih = $_POST['jamterpilih'] ?? '';

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
    
        if ($tanggalterpilih) {
            $_SESSION['psikolog_id'] = $psikolog_ids;
            $_SESSION['tanggalterpilih2'] = $tanggalterpilih;

            // Query jam konseling
            $jamQuery = $db->prepare("SELECT * FROM jam_konseling WHERE psikolog_id = :id");
            $jamQuery->bindParam(':id', $psikolog_ids, PDO::PARAM_INT);
            $jamQuery->execute();
            $jamKonseling = $jamQuery->fetchAll(PDO::FETCH_ASSOC);
    
            // Query sesi untuk tanggal yang dipilih
            $sesiList = $db->prepare("SELECT * FROM sesi WHERE tanggal = :tanggal AND psikolog_id = :id");
            $sesiList->bindParam(':tanggal', $tanggalterpilih, PDO::PARAM_STR);
            $sesiList->bindParam(':id', $psikolog_ids, PDO::PARAM_INT);
            $sesiList->execute();
            $sesiInfo = $sesiList->fetchAll(PDO::FETCH_ASSOC);
    
            $waktuTerbooking = array_column($sesiInfo, 'waktu_mulai');

            // Membuat response untuk jam konseling
            $options = '';
            $options .= "<option value='' disabled selected>Pilih Jam</option>";
            foreach ($jamKonseling as $jam) {
                $status = in_array($jam['waktu_mulai'], $waktuTerbooking) ? 'Terbooking' : 'Tersedia';
                $waktu = htmlspecialchars($jam['waktu_mulai'] . ' - ' . $jam['waktu_akhir']);
                $disabled = ($status === 'Terbooking') ? 'disabled' : '';
                $statusText = ($status === 'Terbooking') ? 'Terbooking' : 'Tersedia';

                $options .= "<option value='{$jam['id']}' $disabled>{$waktu} - {$statusText}</option>";
            }

            if ($jamterpilih) {
                $_SESSION['jamterpilih'] = $jamterpilih; // Simpan ke session jika ada
            }

            echo $options;
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
    <link rel="stylesheet" href="Public/Css/Member/AturJadwal.css">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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
        <div class="jadwal-content">
            <div class="profile">
                <?php $imageSrc = 'data:image/jpeg;base64,' . base64_encode($psikolog['gambar']) ?? ''; ?>
                <img src="<?= $imageSrc ?>" alt="Profile Image" />
                <div class="profile-info">
                    <h3><?php echo htmlspecialchars($psikolog['nama'])?></h3>
                    <span><?php echo htmlspecialchars($psikolog['kategori'])?></span>
                </div>
            </div>
            <p>Buat janji dengan profesional</p>
            <div class="pilih-tanggal">
                <button id="today-button">Hari Ini</button>
                <span id="current-month-year"><?php echo date('F Y');?></span>
                <i id="ikon" class='bx bxs-calendar' id="calendar-icon" style="cursor: pointer;"></i>
            </div>

            <div id="jamss" class="pilih-jam" style="display: none">
                <p id="tgl">Tanggal : </p>
                <p id="harisebut">Pilih Jam : </p>
                <select id="jam-konselings" name="jam_konseling">
                </select>
            </div>
            
            <form id="tanggal-form">
                <div class="pilihjam">
                    <input type="hidden" name="psikolog_id" value="<?php echo htmlspecialchars($psikolog_ids) ?>">
                    <input type="hidden" name="tanggalterpilih" value="">
                </div>
            </form>

            <form action="/konfirmasi" method="POST">
            <div class="confirm" id ="confirmbtn" style="display: none">
                <button href="/paykonsul" id="confirm-button">Konfirmasi</button>
                <input type="hidden" name="psikolog_id" value="<?php echo htmlspecialchars($psikolog_ids) ?>">
                <input type="hidden" name="tanggalterpilih2" value="">
                <input type="hidden" name="jamterpilih" value="">
            </div>
            </form>
            

        </div>
        <div id="calendar" class="calendar" style="display: none;">
            <?php
            $daysInMonth = date('t');
            $month = date('F');
            echo "<div class='calendar-header'><h4>$month</h4></div>";
            echo "<div class='calendar-days'>";
            for ($day = 1; $day <= $daysInMonth; $day++) {
                echo "<div class='day'>$day</div>";
            }
            echo "</div>";
            ?>
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
    function formatTanggal(year, month, day) {
    const monthFormatted = String(month).padStart(2, '0');
    const dayFormatted = String(day).padStart(2, '0');
    return `${year}-${monthFormatted}-${dayFormatted}`;
}
function ambilJamKonseling(tanggalterpilih, psikologId) {
            $.ajax({
                url: window.location.href,
                type: 'POST',
                data: {
                    psikolog_id: psikologId,
                    tanggalterpilih: tanggalterpilih
                },
                success: function(response) {
                    $('#jam-konselings').html(response);
                    document.getElementById('jamss').style.display = "block";
                    document.getElementById('confirmbtn').style.display = "block";
                    document.querySelector('input[name="tanggalterpilih2"]').value = tanggalterpilih;
                },
                error: function() {
                    alert('Terjadi kesalahan saat mengambil data jam konseling.');
                }
            });
        }

document.getElementById('ikon').addEventListener('click', function() {
    const calendar = document.getElementById('calendar');
    if (calendar.style.display === "none") {
        calendar.style.display = "block";
    } else {
        calendar.style.display = "none";
    }
});

const days = document.querySelectorAll('.day');
days.forEach(day => {
    day.addEventListener('click', function() {
        const selectedDay = parseInt(this.innerText, 10);
        const currentMonthYear = document.getElementById('current-month-year').innerText;
        const [month, year] = currentMonthYear.split(' ');
        const monthIndex = new Date(Date.parse(month +" 1, 2024")).getMonth() + 1;
        const formattedDate = formatTanggal(year, monthIndex, selectedDay);

        ambilJamKonseling(formattedDate, $('input[name="psikolog_id"]').val());

        document.querySelector('input[name="tanggalterpilih"]').value = formattedDate;
        document.getElementById('calendar').style.display = "none";
        
        // Lakukan AJAX request untuk mendapatkan jam konseling
        $.ajax({
            url: window.location.href,
            type: 'POST',
            data: { tanggalterpilih: formattedDate, psikolog_id: $('input[name="psikolog_id"]').val() },
            success: function(response) {
                $('#jam-konselings').html(response);
                alert('Tanggal terpilih: ' + formattedDate);
                document.getElementById('jamss').style.display = "block";
                document.getElementById('confirmbtn').style.display = "block";
                document.getElementById('tgl').innerHTML = "Tanggal : " + formattedDate;
                document.querySelector('input[name="tanggalterpilih2"]').value = formattedDate;
            }
        });
    });
});

document.getElementById('today-button').addEventListener('click', function() {
    const today = new Date();
    const day = today.getDate();
    const month = today.getMonth() + 1;
    const year = today.getFullYear();
    const formattedDate = formatTanggal(year, month, day);

    ambilJamKonseling(formattedDate, $('input[name="psikolog_id"]').val());

    document.querySelector('input[name="tanggalterpilih"]').value = formattedDate;
    document.getElementById('calendar').style.display = "none";

    // Lakukan AJAX request untuk mendapatkan jam konseling
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: { tanggalterpilih: formattedDate, psikolog_id: $('input[name="psikolog_id"]').val() },
        success: function(response) {
            $('#jam-konselings').html(response);
            alert('Tanggal terpilih: ' + formattedDate);
            document.getElementById('jamss').style.display = "block";
            document.getElementById('confirmbtn').style.display = "block";
            document.getElementById('tgl').innerHTML = "Tanggal : " + formattedDate;
            document.querySelector('input[name="tanggalterpilih2"]').value = formattedDate;
        }
    });
});

$('#jam-konselings').on('change', function() {
    const selectedJam = $(this).val();
    $('input[name="jamterpilih"]').val(selectedJam); // Set nilai input hidden

    // Anda juga bisa menyimpan nilai jamterpilih ke session melalui AJAX jika diperlukan
    $.ajax({
        url: window.location.href,
        type: 'POST',
        data: {
            psikolog_id: $('input[name="psikolog_id"]').val(),
            tanggalterpilih: $('input[name="tanggalterpilih"]').val(),
            jamterpilih: selectedJam
        },
        success: function(response) {
            // Handle response jika diperlukan
        }
    });
});



// Tambahkan event listener pada tombol konfirmasi
document.getElementById('confirm-button').addEventListener('click', function(event) {
    const jamKonselingSelect = document.getElementById('jam-konselings');
    const jamTerpilih = jamKonselingSelect.value;

    // Cek apakah jam terpilih adalah kosong
    if (!jamTerpilih) {
        event.preventDefault(); // Hentikan pengiriman form
        alert("Pilih Jam Terlebih Dahulu");
    } else {
        // Jika ada jam yang dipilih, simpan nilai jam terpilih
        document.querySelector('input[name="jamterpilih"]').value = jamTerpilih;
    }
});

</script>
</html>