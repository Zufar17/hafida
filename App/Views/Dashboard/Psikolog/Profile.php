<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$username = $_SESSION['username'];
$role = $_SESSION['role'];

if (!$username || $role !== 'psikolog') {
    header('Location: /login');
    exit;
}

$db = \Config\Database::getInstance();

$get_data = $db->prepare("SELECT * FROM psikolog WHERE nama = :username");
$get_data->bindParam(':username', $username);
$get_data->execute();
$success = $get_data->fetch(PDO::FETCH_ASSOC);
$imageSrc = 'data:image/jpeg;base64,' . base64_encode($success['gambar']) ?? '';

$get_jam_konseling = $db->prepare("SELECT * FROM jam_konseling WHERE psikolog_id = :psikolog_id");
$get_jam_konseling->bindParam(':psikolog_id', $success['psikolog_id']);
$get_jam_konseling->execute();
$jam_konseling_list = $get_jam_konseling->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tempat_lahir = $_POST['tempat_lahir'] ?? $success['tempat_lahir'];
    $hari = $_POST['hari'] ?? $success['hari'];
    $bulan = $_POST['bulan'] ?? $success['bulan'];
    $tahun = $_POST['tahun'] ?? $success['tahun'];
    $harga = $_POST['harga'] ?? $success['harga'];
    $alumnus = $_POST['alumnus'] ?? $success['alumnus'];
    $kategori = $_POST['kategori'] ?? $success['kategori'];
    $no_str = $_POST['no_str'] ?? $success['no_str'];
    $praktik = $_POST['praktik'] ?? $success['praktik'];
    $periode_kerja = $_POST['periode_kerja'] ?? $success['periode_kerja'];
    $deskripsi = $_POST['deskripsi'] ?? $success['deskripsi'];
    
    // Menyimpan gambar
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === 0) {
        $gambar = $_FILES['gambar'];
        $image_data = file_get_contents($gambar['tmp_name']);
    } else {
        $image_data = $success['gambar'];
    }

    try {
        // Update data psikolog
        if ($success) {
            $stmt = $db->prepare("UPDATE psikolog 
                                   SET tempat_lahir = :tempat_lahir,
                                       hari = :hari,
                                       bulan = :bulan,
                                       tahun = :tahun,
                                       harga = :harga,
                                       alumnus = :alumnus,
                                       kategori = :kategori,
                                       no_str = :no_str,
                                       praktik = :praktik,
                                       periode_kerja = :periode_kerja,
                                       deskripsi = :deskripsi,
                                       gambar = :gambar
                                   WHERE nama = :username");

            $stmt->bindParam(':username', $username);
        } else {
            // Insert data
            $stmt = $db->prepare("INSERT INTO psikolog (tempat_lahir, hari, bulan, tahun, harga, alumnus, kategori, no_str, praktik, periode_kerja, deskripsi, gambar)
                                  VALUES (:tempat_lahir, :hari, :bulan, :tahun, :harga, :alumnus, :kategori, :no_str, :praktik, :periode_kerja, :deskripsi, :gambar)");
        }

        // Bind parameters
        $stmt->bindParam(':tempat_lahir', $tempat_lahir);
        $stmt->bindParam(':hari', $hari);
        $stmt->bindParam(':bulan', $bulan);
        $stmt->bindParam(':tahun', $tahun);
        $stmt->bindParam(':harga', $harga);
        $stmt->bindParam(':alumnus', $alumnus);
        $stmt->bindParam(':kategori', $kategori);
        $stmt->bindParam(':no_str', $no_str);
        $stmt->bindParam(':praktik', $praktik);
        $stmt->bindParam(':periode_kerja', $periode_kerja);
        $stmt->bindParam(':deskripsi', $deskripsi);
        $stmt->bindParam(':gambar', $image_data, PDO::PARAM_LOB);

        $stmt->execute();

        
        

        echo "Data berhasil disimpan.";
        header("Location: /profile");
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

if (isset($_POST['delete_time'])) {
    $id_to_delete = $_POST['delete_time'];

    try {
        $stmt = $db->prepare("DELETE FROM jam_konseling WHERE id = :id");
        $stmt->bindParam(':id', $id_to_delete);
        $stmt->execute();
        echo "Jam konsultasi berhasil dihapus.";
    } catch (PDOException $e) {
        echo "Error deleting consultation time: " . $e->getMessage();
    }
}

// Menambahkan jam konsultasi jika tombol add diklik
if (isset($_POST['add_time'])) {
    if (isset($_POST['waktu_mulai']) && isset($_POST['waktu_akhir'])) {
        $waktu_mulai_list = $_POST['waktu_mulai'];
        $waktu_akhir_list = $_POST['waktu_akhir'];

        foreach ($waktu_mulai_list as $key => $waktu_mulai) {
            $waktu_akhir = $waktu_akhir_list[$key];

            try {
                // Insert new consultation time
                $stmt = $db->prepare("INSERT INTO jam_konseling (psikolog_id, waktu_mulai, waktu_akhir) VALUES (:psikolog_id, :waktu_mulai, :waktu_akhir)");
                $stmt->bindParam(':psikolog_id', $success['psikolog_id']);
                $stmt->bindParam(':waktu_mulai', $waktu_mulai);
                $stmt->bindParam(':waktu_akhir', $waktu_akhir);
                $stmt->execute();
            } catch (PDOException $e) {
                echo "Error saving consultation time: " . $e->getMessage();
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh</title>
    <link rel="stylesheet" href="Public/Css/Psikolog/Profile.css">
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
            <li class="active"><a href="/dashboard"><i class='bx bxs-home'></i></a></li>
            <li><a href="/jadwal"><i class='bx bxs-phone-call'></i></a></li>
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
            <p><?php echo htmlspecialchars($username)?></p>
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
    <div class="addjasa">
        <h2>Edit Profile</h2>

        <div class="editprofile">
            <form action="/profile" method="post" enctype="multipart/form-data">
                <p>Foto Formal</p>
                <div class="upload-section">
                    <img id="preview" src="<?= $imageSrc ?>" alt="Profile Image" />
                    <label class="upload-btn" for="gambar">Upload Image</label>
                    <input type="file" id="gambar" name="gambar" accept="image/jpeg, image/png" onchange="previewImage(event)">
                </div>

                <p>Nama</p>
                <br>
                <input type="text" id="nama" name="nama" placeholder="Nama" value="<?php echo htmlspecialchars($success['nama']) ?>" required readonly>
                <br>
                <br>
                <p>Tempat, Tanggal Lahir</p>
                <br>
                <div class="row">
                    <input type="text" id="tempat_lahir" name="tempat_lahir" placeholder="Tempat Lahir" value="<?php echo htmlspecialchars($success['tempat_lahir']) ?>" required>
                    <input type="text" id="hari" name="hari" placeholder="Hari" value="<?php echo htmlspecialchars($success['hari']) ?? '' ?>" required>
                    <input type="text" id="bulan" name="bulan" placeholder="Bulan" value="<?php echo htmlspecialchars($success['bulan']) ?? '' ?>" required>
                    <input type="text" id="tahun" name="tahun" placeholder="Tahun" value="<?php echo htmlspecialchars($success['tahun']) ?? '' ?>" required>
                </div>
                <br>

                <p>Harga (IDR)</p>
                <br>
                <input type="number" id="harga" name="harga" placeholder="Harga" value="<?php echo htmlspecialchars($success['harga']) ?>" required>
                <br>
                <br>
                <p>Alumnus</p>
                <br>
                <input type="text" id="alumnus" name="alumnus" placeholder="Alumnus" value="<?php echo htmlspecialchars($success['alumnus']) ?>" required>
                <br>
                <br>
                <p>Kategori</p>
                <br>
                <input type="text" id="kategori" name="kategori" placeholder="Kategori" value="<?php echo htmlspecialchars($success['kategori']) ?>" required>
                <br>
                <br>
                <p>No STR</p>
                <br>
                <input type="text" id="no_str" name="no_str" placeholder="No STR" value="<?php echo htmlspecialchars($success['no_str']) ?>" required>
                <br>
                <br>
                <p>Praktik</p>
                <br>
                <input type="text" id="praktik" name="praktik" placeholder="Praktik" value="<?php echo htmlspecialchars($success['praktik']) ?>" required>
                <br>
                <br>
                <p>Periode Kerja</p>
                <br>
                <input type="text" id="periode_kerja" name="periode_kerja" placeholder="Periode Kerja" value="<?php echo htmlspecialchars($success['periode_kerja']) ?>" required>
                <br>
                <br>
                <p>Deskripsi</p>
                <br>
                <textarea id="deskripsi" name="deskripsi" placeholder="Deskripsi" required><?php echo htmlspecialchars($success['deskripsi']) ?></textarea>
                <br>
                <br>
                <p>Jam Konsultasi</p>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Jam Mulai</th>
                            <th>Jam Akhir</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($jam_konseling_list as $index => $jam): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($jam['waktu_mulai']); ?></td>
                            <td><?php echo htmlspecialchars($jam['waktu_akhir']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <h3>Tambah Jam Konsultasi</h3>
                <div id="consultation-times">
                    
                </div>
                <button type="button" id="add-time" onclick="addTime()">Tambah Kolom Jam</button>
                <button type="submit" class="submit-btn">Simpan</button>
            </form>
        </div>
    </div>
</div>

<script>
    function addTime() {
        const consultationTimes = document.getElementById('consultation-times');
        const newTime = document.createElement('div');
        newTime.className = 'consultation-time';
        newTime.innerHTML = `
            <input type="time" name="waktu_mulai[]" required>
            <input type="time" name="waktu_akhir[]" required>
            <button type="submit" name="add_time" class="submit-btn">Simpan Jam</button>
        `;
        consultationTimes.appendChild(newTime);
    }

    function previewImage(event) {
        const preview = document.getElementById('preview');
        const file = event.target.files[0];
        const reader = new FileReader();

        reader.onload = function(e) {
            preview.src = e.target.result;
        }

        reader.readAsDataURL(file);
    }
</script>

</body>
</html>
