<?php
$host = '127.0.0.1';
$dbname = 'gym_booking';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
} catch (PDOException $error) {
    error_log($error->getMessage());
    die('Database connection unavailable. Start MySQL and import database/gym_booking.sql.');
}
