<?php
// Configuration file
$envFile = __DIR__ . '/.env';
if (file_exists($envFile)) {
    $envVariables = parse_ini_file($envFile);
    foreach ($envVariables as $key => $value) {
        $_ENV[$key] = $value;
        putenv("$key=$value");
    }
}
define('GEMINI_API_KEY', $_ENV['GEMINI_API_KEY']);
define('SITE_NAME', 'AI Medical Chatboard');
define('SITE_URL', 'http://localhost/PHP/FYP/');
?>