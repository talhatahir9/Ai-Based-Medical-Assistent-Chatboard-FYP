<?php
// Load environment variables from .env file
if (file_exists(__DIR__ . '/.env')) {
    $envVariables = parse_ini_file(__DIR__ . '/.env');
    if ($envVariables) {
        foreach ($envVariables as $key => $value) {
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}

$host = 'localhost';
$dbname = 'ai_medical_ch';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

session_start();
?>