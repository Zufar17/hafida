<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

spl_autoload_register(function ($class_name) {
    $path = str_replace('\\', DIRECTORY_SEPARATOR, $class_name) . '.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        echo "Autoloader failed to find the class: $class_name at $path";
    }
});


// Include the Routes class
require_once 'Config/Routes.php';

// Set base URL
$base_url = 'http://' . $_SERVER['HTTP_HOST'] . str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

// Public Routes
Routes::add('/', 'HomeController', 'index');
Routes::add('/login','LoginController','index');
Routes::add('/register','RegisterController','index');
Routes::add('/dashboard','DashboardController','index');
Routes::add('/logout','LogoutController','index');
Routes::add('/room','RoomController','index');
Routes::add('/forum','ForumController','index');
Routes::add('/addforum','AddForumController','index');
Routes::add('/daftarprofesional','DaftarProfesionalController','index');
Routes::add('/artikel','ArtikelController','index');
Routes::add('/nilai','NilaiController','index');

// Member Routes
Routes::add('/konsul','Member\KonsulController','index');
Routes::add('/profilprofesional','Member\ProfileProfesionalController','index');
Routes::add('/aturjadwal','Member\AturJadwalController','index');
Routes::add('/konfirmasi','Member\KonfirmasiController','index');
Routes::add('/bayar','Member\BayarController','index');
Routes::add('/webbinar','Member\WebbinarController','index');
Routes::add('/langganan','Member\LanggananController','index');
Routes::add('/berlangganan','Member\BerlanggananController','index');

// Psikolog Routes
Routes::add('/profile','ProfileController','index');
Routes::add('/jadwal','Psikolog\JadwalController','index');

// Owner Routes
Routes::add('/addartikel','Owner\AddArtikelController','index');

// Run the routing
Routes::add('/check','CheckController','index');
Routes::add('/check2','CheckController2','index');

Routes::run();
