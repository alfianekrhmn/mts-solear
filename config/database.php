<?php
$host = "sql301.infinityfree.com"; // GANTI sesuai panel
$db   = "if0_40917760_sekolah_db";
$user = "if0_40917760"; 
$pass = "OgbcFoQU4W0h";

$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ]);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}
