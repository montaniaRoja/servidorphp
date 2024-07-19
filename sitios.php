<?php
require 'config.php';

// Obtener todos los sitios
function getSitios($pdo) {
    $stmt = $pdo->query('SELECT * FROM tbl_sitios');
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Obtener un sitio por ID
function getSitioById($pdo, $id) {
    $stmt = $pdo->prepare('SELECT * FROM tbl_sitios WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Crear un nuevo sitio
function createSitio($pdo, $data) {
    $stmt = $pdo->prepare('INSERT INTO tbl_sitios (descripcion, latitud, longitud, fotografia, audiofile) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$data['descripcion'], $data['latitud'], $data['longitud'], $data['fotografia'], $data['audiofile']]);
    return $pdo->lastInsertId();
}

// Actualizar un sitio
function updateSitio($pdo, $id, $data) {
    $stmt = $pdo->prepare('UPDATE tbl_sitios SET descripcion = ?, latitud = ?, longitud = ?, fotografia = ?, audiofile = ? WHERE id = ?');
    $stmt->execute([$data['descripcion'], $data['latitud'], $data['longitud'], $data['fotografia'], $data['audiofile'], $id]);
    return $stmt->rowCount();
}

// Eliminar un sitio
function deleteSitio($pdo, $id) {
    $stmt = $pdo->prepare('DELETE FROM tbl_sitios WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->rowCount();
}
?>
