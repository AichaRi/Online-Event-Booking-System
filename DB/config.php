<?php
// config.php
// هذا الملف يستخدم للاتصال بقاعدة البيانات عبر PDO

$host = 'localhost';
$dbname = 'event_booking_system';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // ضبط خصائص الاتصال
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
