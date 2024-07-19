<?php
$host = '34.42.1.3'; // Cambia esto si tu servidor MySQL no está en localhost
$dbname = 'sitios_database';
$username = 'adolfoc';
$password = 'ofloda01';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "conexion exitosa";
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
