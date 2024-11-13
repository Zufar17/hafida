const menuToggle = document.getElementById('menu-toggle');
const sidebar = document.getElementById('sidebar');

menuToggle.addEventListener('click', function() {
    sidebar.classList.toggle('open');
});
const closeBtn = document.getElementById('close-btn');

closeBtn.addEventListener('click', function() {
    sidebar.classList.toggle('open');
});

let slideIndex = 0; // Indeks slide saat ini
const slides = document.querySelectorAll('.slider-image img'); // Ambil semua gambar slider
const totalSlides = slides.length; // Total jumlah slide

// Daftar judul untuk setiap slide
const slideTitles = [
    "Rancangan Anggaran Finansial Pernikahan",
    "Judul Slide 2", // Judul untuk gambar kedua
];

// Daftar URL untuk setiap slide
const slideLinks = [
    "/artikel", // URL untuk gambar pertama
    "/artikel", // URL untuk gambar kedua
];

// Menampilkan slide aktif
function showSlide(index) {
    slides.forEach((slide, i) => {
        slide.classList.remove('active'); // Hapus kelas active dari semua gambar
        if (i === index) {
            slide.classList.add('active'); // Tambahkan kelas active pada gambar yang sesuai
        }
    });
    // Perbarui judul berdasarkan indeks slide
    document.getElementById('slider-title').innerText = slideTitles[index];
    // Perbarui URL tombol "Baca Sekarang"
    document.getElementById('read-more-button').href = slideLinks[index];
}

// Menggeser slide
function moveSlide(n) {
    slideIndex = (slideIndex + n + totalSlides) % totalSlides; // Hitung indeks slide selanjutnya
    showSlide(slideIndex); // Tampilkan slide yang sesuai
}

// Tampilkan gambar pertama saat halaman dimuat
showSlide(slideIndex);

// Otomatis geser setiap 10 detik
setInterval(() => {
    moveSlide(1); // Geser ke slide berikutnya
}, 5000); // 10 detik

function toggleDropdown() {
    const dropdownMenu = document.getElementById("dropdown-menu");
    dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
}

// Tutup dropdown jika mengklik di luar dropdown
window.onclick = function(event) {
    if (!event.target.matches('.user-icon') && !event.target.matches('.user-icon *')) {
        const dropdownMenu = document.getElementById("dropdown-menu");
        dropdownMenu.style.display = "none";
    }
}

function previewImage(event) {
    const reader = new FileReader();
    const imageField = document.getElementById("preview");

    reader.onload = function() {
        if (reader.readyState === 2) {
            imageField.src = reader.result;
        }
    }
    reader.readAsDataURL(event.target.files[0]);
}

function showDetail(psikolog) {
    document.getElementById('detail-nama').textContent = psikolog.nama;
    document.getElementById('detail-kategori').textContent = psikolog.kategori;
    document.getElementById('detail-periode').textContent = psikolog.periode_kerja + ' Tahun';
    document.getElementById('detail-harga').textContent = 'Rp. ' + psikolog.harga;
    
    document.getElementById('detail-psikolog').style.display = 'block';
}

function closeDetail() {
    document.getElementById('detail-psikolog').style.display = 'none';
}
