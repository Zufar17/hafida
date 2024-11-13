<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh</title>
    <link rel="stylesheet" href="Public\Css\Nilai.css">
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
            <li><a href="/artikel"><i class='bx bx-book-open' ></i></a></li>
            <li class="active"><a href="/nilai"><i class='bx bx-task' ></i></a></li>
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
        <div class="formawal">
            <h2>Siap Untuk Menikah? Cek Nilaimu Disini!</h2>
            <form id="inputForm" onsubmit="return startSurvey()">
                <input type="text" id="nama" name="nama" placeholder="Nama Lengkap" required>
                <input type="email" id="email" name="email" placeholder="Email" required>

                <div class="row">
                    <div class="col">
                        <input type="number" id="usia" name="usia" placeholder="Usia" required>
                    </div>
                    <div class="col">
                        <select id="gender" name="gender" required>
                            <option value="pria">Pria</option>
                            <option value="wanita">Wanita</option>
                        </select>
                    </div>
                </div>

                <button type="submit" class="btns">Start</button>
            </form>
        </div>

        <div id="pertanyaan" style="display: none">
            <h3>Aspek Emosi</h3>
            <div class="pertanyaan">
                <p>1. Seberapa baik Anda dapat mengelola perasaan marah atau frustrasi ketika berhadapan dengan pasangan?</p>
                <div>
                    <i class='bx bx-heart' onclick="pilihJawaban(0, 100, this)">
                        <p>Sangat Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(0, 80, this)">
                        <p>Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(0, 60, this)">
                        <p>Cukup Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(0, 40, this)">
                        <p>Tidak Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(0, 20, this)">
                        <p>Sangat Tidak Baik</p>
                    </i>
                </div>
            </div>
            <div class="pertanyaan">
                <p>2. Ketika menghadapi konflik dengan pasangan, seberapa sering Anda merasa dapat menenangkan diri sebelum mengambil tindakan?</p>
                <div>
                    <i class='bx bx-heart' onclick="pilihJawaban(1, 100, this)">
                        <p>Sangat Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(1, 80, this)">
                        <p>Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(1, 60, this)">
                        <p>Cukup Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(1, 40, this)">
                        <p>Tidak Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(1, 20, this)">
                        <p>Sangat Tidak Baik</p>
                    </i>
                </div>
            </div>
            <div class="pertanyaan">
                <p>3. Seberapa yakin Anda dapat menangani situasi stres dalam kehidupan pernikahan tanpa mempengaruhi hubungan dengan pasangan?</p>
                <div>
                    <i class='bx bx-heart' onclick="pilihJawaban(2, 100, this)">
                        <p>Sangat Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(2, 80, this)">
                        <p>Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(2, 60, this)">
                        <p>Cukup Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(2, 40, this)">
                        <p>Tidak Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(2, 20, this)">
                        <p>Sangat Tidak Baik</p>
                    </i>
                </div>
            </div>

            <h3>Aspek Keuangan</h3>
            <div class="pertanyaan">
                <p>1. Seberapa yakin Anda dengan kemampuan finansial Anda untuk memenuhi kebutuhan sehari-hari dalam pernikahan?</p>
                <div>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 100, this)">
                        <p>Sangat Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 80, this)">
                        <p>Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 60, this)">
                        <p>Cukup Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 40, this)">
                        <p>Tidak Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 20, this)">
                        <p>Sangat Tidak Baik</p>
                    </i>
                </div>
            </div>
            <div class="pertanyaan">
                <p>2. Apakah Anda dan pasangan sudah berdiskusi mengenai pembagian tanggung jawab keuangan dalam pernikahan?</p>
                <div>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 100, this)">
                        <p>Sangat Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 80, this)">
                        <p>Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 60, this)">
                        <p>Cukup Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 40, this)">
                        <p>Tidak Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 20, this)">
                        <p>Sangat Tidak Baik</p>
                    </i>
                </div>
            </div>
            <div class="pertanyaan">
                <p>3. Apakah Anda memiliki rencana keuangan yang jelas untuk masa depan keluarga Anda, seperti tabungan, investasi, atau dana darurat?</p>
                <div>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 100, this)">
                        <p>Sangat Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 80, this)">
                        <p>Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 60, this)">
                        <p>Cukup Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 40, this)">
                        <p>Tidak Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 20, this)">
                        <p>Sangat Tidak Baik</p>
                    </i>
                </div>
            </div>

            <h3>Aspek Komitmen</h3>
            <div class="pertanyaan">
                <p>1. Seberapa besar komitmen Anda untuk terus bersama pasangan, meskipun
                menghadapi kesulitan dalam hubungan?</p>
                <div>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 100, this)">
                        <p>Sangat Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 80, this)">
                        <p>Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 60, this)">
                        <p>Cukup Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 40, this)">
                        <p>Tidak Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 20, this)">
                        <p>Sangat Tidak Baik</p>
                    </i>
                </div>
            </div>
            <div class="pertanyaan">
                <p>2. ApakahAndamerasasiap untuk berkomitmen terhadap tanggung jawab dan
                kewajiban yang datang dengan pernikahan?</p>
                <div>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 100, this)">
                        <p>Sangat Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 80, this)">
                        <p>Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 60, this)">
                        <p>Cukup Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 40, this)">
                        <p>Tidak Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 20, this)">
                        <p>Sangat Tidak Baik</p>
                    </i>
                </div>
            </div>
            <div class="pertanyaan">
                <p>3. Seberapa besar keyakinan Anda bahwa hubungan Anda dengan pasangan
                adalah untuk jangka panjang?</p>
                <div>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 100, this)">
                        <p>Sangat Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 80, this)">
                        <p>Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 60, this)">
                        <p>Cukup Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 40, this)">
                        <p>Tidak Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 20, this)">
                        <p>Sangat Tidak Baik</p>
                    </i>
                </div>
            </div>
            <h3>Aspek Komunikasi</h3>
            <div class="pertanyaan">
                <p>1. Seberapa sering Anda merasa dapat berbicara secara terbuka dengan
                pasangan tentang perasaan atau masalah pribadi?</p>
                <div>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 100, this)">
                        <p>Sangat Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 80, this)">
                        <p>Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 60, this)">
                        <p>Cukup Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 40, this)">
                        <p>Tidak Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 20, this)">
                        <p>Sangat Tidak Baik</p>
                    </i>
                </div>
            </div>
            <div class="pertanyaan">
                <p>2. Seberapa efektif Anda merasa dapat mendengarkan pasangan tanpa
                menghakimi atau memberikan respon negatif?</p>
                <div>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 100, this)">
                        <p>Sangat Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 80, this)">
                        <p>Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 60, this)">
                        <p>Cukup Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 40, this)">
                        <p>Tidak Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 20, this)">
                        <p>Sangat Tidak Baik</p>
                    </i>
                </div>
            </div>
            <div class="pertanyaan">
                <p>3. ApakahAndamerasanyaman berdiskusi dengan pasangan mengenai masalah
                sensitif seperti keuangan, anak, atau hubungan dengan keluarga besar?</p>
                <div>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 100, this)">
                        <p>Sangat Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 80, this)">
                        <p>Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 60, this)">
                        <p>Cukup Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 40, this)">
                        <p>Tidak Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 20, this)">
                        <p>Sangat Tidak Baik</p>
                    </i>
                </div>
            </div>
            <h3>Aspek Nilai dan Harapan</h3>
            <div class="pertanyaan">
                <p>1. ApakahAndadanpasangan memiliki kesamaan pandangan mengenai
 nilai-nilai penting dalam pernikahan, seperti kepercayaan, kejujuran, atau
 komitmen?</p>
                <div>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 100, this)">
                        <p>Sangat Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 80, this)">
                        <p>Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 60, this)">
                        <p>Cukup Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 40, this)">
                        <p>Tidak Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 20, this)">
                        <p>Sangat Tidak Baik</p>
                    </i>
                </div>
            </div>
            <div class="pertanyaan">
                <p>2. Seberapa selaras harapan Anda tentang peran dalam rumah tangga, seperti
 pengasuhan anak atau pembagian tugas rumah tangga, dengan harapan
 pasangan?</p>
                <div>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 100, this)">
                        <p>Sangat Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 80, this)">
                        <p>Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 60, this)">
                        <p>Cukup Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 40, this)">
                        <p>Tidak Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 20, this)">
                        <p>Sangat Tidak Baik</p>
                    </i>
                </div>
            </div>
            <div class="pertanyaan">
                <p>3. ApakahAndadanpasangan telah mendiskusikan tujuan jangka panjang,
                seperti di mana akan tinggal, karier, dan rencana memiliki anak?</p>
                <div>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 100, this)">
                        <p>Sangat Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 80, this)">
                        <p>Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 60, this)">
                        <p>Cukup Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 40, this)">
                        <p>Tidak Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 20, this)">
                        <p>Sangat Tidak Baik</p>
                    </i>
                </div>
            </div>
            <h3>Aspek Keluarga</h3>
            <div class="pertanyaan">
                <p>1. Seberapa siap Anda untuk mengelola hubungan dengan keluarga besar
                pasangan, termasuk menghadapi potensi konflik?</p>
                <div>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 100, this)">
                        <p>Sangat Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 80, this)">
                        <p>Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 60, this)">
                        <p>Cukup Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 40, this)">
                        <p>Tidak Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(3, 20, this)">
                        <p>Sangat Tidak Baik</p>
                    </i>
                </div>
            </div>
            <div class="pertanyaan">
                <p>2. ApakahAndadanpasangan memiliki kesepakatan mengenai bagaimana
                berhubungan dengan keluarga besar masing-masing setelah menikah?</p>
                <div>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 100, this)">
                        <p>Sangat Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 80, this)">
                        <p>Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 60, this)">
                        <p>Cukup Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 40, this)">
                        <p>Tidak Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(4, 20, this)">
                        <p>Sangat Tidak Baik</p>
                    </i>
                </div>
            </div>
            <div class="pertanyaan">
                <p>3. Seberapa sering Anda merasa mampu menjaga keseimbangan antara
                kebutuhan pasangan dan keluarga besar dalam kehidupan sehari-hari?</p>
                <div>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 100, this)">
                        <p>Sangat Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 80, this)">
                        <p>Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 60, this)">
                        <p>Cukup Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 40, this)">
                        <p>Tidak Baik</p>
                    </i>
                    <i class='bx bx-heart' onclick="pilihJawaban(5, 20, this)">
                        <p>Sangat Tidak Baik</p>
                    </i>
                </div>
            </div>

            <!-- Tambahkan div pertanyaan lainnya untuk aspek komitmen, komunikasi, nilai dan harapan, serta keluarga -->
        </div>
        <button class="btns" onclick="hitungRataRata()" id="btns" style="display: none">Submit</button>
        <p id="hasilRataRata"></p>
    </div>

    <div id="popup" class="popup">
        <div class="popup-content">
            <h2>Hasil Test Anda</h2>
            <p id="emoji"></p>
            <p id="nama"></p>
            <p id="umur"></p>
            <p id="gender"></p>
            <p id="nilai"></p>
            <button id="btn-tutup" class="tutup-btn">Kembali</button>
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
function startSurvey() {
    const nama = document.getElementById('nama').value;
    const email = document.getElementById('email').value;
    const usia = document.getElementById('usia').value;
    const gender = document.getElementById('gender').value;

    if (!nama || !email || !usia || !gender) {
        alert("Harap isi semua field!");
        return false; // Mencegah fungsi dijalankan lebih lanjut
    }

    // Sembunyikan form awal
    document.querySelector('.formawal').style.display = 'none';

    // Tampilkan pertanyaan
    document.getElementById('pertanyaan').style.display = 'block';
    document.getElementById('btns').style.display = 'block';

    return false; // Mencegah form disubmit
}


document.getElementById('btn-tutup').addEventListener('click', function() {
        window.location.href = '/nilai'; // Arahkan ke halaman dashboard
});

const nilaiJawaban = new Array(18).fill(0); // Array untuk menyimpan nilai jawaban, ada 18 pertanyaan

function pilihJawaban(indexPertanyaan, nilai, element) {
    nilaiJawaban[indexPertanyaan] = nilai;

    // Hapus kelas 'selected-icon' dari semua ikon dalam pertanyaan yang sama
    const parent = element.parentElement;
    Array.from(parent.children).forEach(icon => {
        icon.classList.remove('selected-icon');
        icon.classList.remove('bxs-heart');
        icon.classList.add('bx-heart');
    });

    // Tambahkan kelas 'selected-icon' dan ubah ke ikon solid pada ikon yang dipilih
    element.classList.add('selected-icon');
    element.classList.remove('bx-heart');
    element.classList.add('bxs-heart');
}

function hitungRataRata() {
    // Menghitung jumlah jawaban yang valid
    const jumlahJawaban = nilaiJawaban.filter(nilai => nilai !== 0).length;
    if (jumlahJawaban === 0) {
        document.getElementById("hasilRataRata").innerText = "Belum ada jawaban yang dipilih.";
        return;
    }

    const totalNilai = nilaiJawaban.reduce((total, nilai) => total + nilai, 0);
    const rataRata = totalNilai / jumlahJawaban;

    const nama = document.getElementById('nama').value;
    const usia = document.getElementById('usia').value;
    const gender = document.getElementById('gender').value;

    let warna;
    let kategori;
    let emoji;
    if (rataRata >= 81 && rataRata <= 100) {
        warna = 'green';
        kategori = 'Sangat Siap';
        emoji = '❤️';
    } else if (rataRata >= 61 && rataRata < 81) {
        warna = 'yellow';
        kategori = 'Cukup Siap';
        emoji = '👌';
    } else if (rataRata >= 41 && rataRata < 61) {
        warna = 'orange';
        kategori = 'Kurang Siap';
        emoji = '👎';
    } else {
        warna = 'red';
        kategori = 'Tidak Siap';
        emoji = '😣';
    }

    hasilRataRata.style.color = warna;
    document.getElementById('popup').style.display = 'flex';
    document.getElementById('popup').querySelector('#emoji').innerText = emoji;
    document.getElementById('popup').querySelector('#nama').innerText = "Nama : " + nama;
    document.getElementById('popup').querySelector('#umur').innerText = "Usia : " + usia;
    document.getElementById('popup').querySelector('#gender').innerText = "Gender : " + gender;
    document.getElementById('popup').querySelector('#nilai').innerText = "Hasil : " + Math.round(rataRata) + ` ( ${kategori} )`;
    hasilRataRata.innerText = `Hasil Rata-Rata: ${Math.round(rataRata)} ${kategori}`;
}


</script>
</html>