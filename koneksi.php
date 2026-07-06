<?php
$host     = "localhost";
$port     = "5432"; // Port default PostgreSQL
$username = "kevinnazar";
$password = "kevinnazar";
$database = "vgem_db";

try {
    // Format DSN untuk PostgreSQL
    $dsn = "pgsql:host=$host;port=$port;dbname=$database";

    // Membuat koneksi PDO
    $conn = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    // HAPUS ATAU KOMENTAR BARIS ECHO DI BAWAH INI:
    // echo "Koneksi ke PostgreSQL Berhasil!"; 

} catch (PDOException $e) {
    // Tampilkan error aslinya dulu buat debugging di lokal
    die("Database Error: " . $e->getMessage());
}
