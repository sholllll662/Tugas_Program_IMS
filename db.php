
<?php
// db.php
// Konfigurasi koneksi DB - ubah sesuai environmentmu
$host = 'localhost';
$db   = 'db_ims';
$user = 'root';
$pass = ''; // biasanya kosong di XAMPP
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Untuk development tampilkan pesan error, di production jangan tampilkan detail
    exit('Koneksi DB gagal: ' . $e->getMessage());
}
