<?php
$host     = "localhost";
$port     = "3306"; // Port default MySQL
$username = "kevinnazar";
$password = "kevinnazar";
$database = "vgem_db";

try {
    // Format DSN untuk MySQL
    $dsn = "mysql:host=$host;port=$port;dbname=$database;charset=utf8mb4";

    // Membuat koneksi PDO
    $conn = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    $conn->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $conn->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);

    // echo "Koneksi ke MySQL Berhasil!"; 

} catch (PDOException $e) {
    // Tampilkan error aslinya dulu buat debugging di lokal
    die("Database Error: " . $e->getMessage());
}
