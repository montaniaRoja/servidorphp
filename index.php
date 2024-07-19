<?php
require 'config.php';
require 'sitios.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header("Content-Type: application/json; charset=UTF-8");
$requestMethod = $_SERVER['REQUEST_METHOD'];
$path = explode('/', trim($_SERVER['PATH_INFO'], '/'));

switch ($requestMethod) {
    case 'GET':
        if (isset($path[1])) {
            $response = getSitioById($pdo, $path[1]);
        } else {
            $response = getSitios($pdo);
        }
        break;

    case 'POST':
        $data = $_POST;
        $uploadDirectory = 'uploads/';

        // Verifica si el directorio existe, de lo contrario intenta crearlo
        if (!file_exists($uploadDirectory)) {
            if (!mkdir($uploadDirectory, 0777, true)) {
                error_log("Error creando el directorio de subidas.");
                echo json_encode(['error' => 'Error creando el directorio de subidas.']);
                exit;
            }
        }

        // Inicializa las rutas de archivos como nulas
        $data['fotografia'] = null;
        $data['audiofile'] = null;

        // Manejo del archivo de fotografía
        if (isset($_FILES['fotografia']) && $_FILES['fotografia']['error'] == UPLOAD_ERR_OK) {
            $fotoPath = $uploadDirectory . basename($_FILES['fotografia']['name']);
            if (move_uploaded_file($_FILES['fotografia']['tmp_name'], $fotoPath)) {
                $data['fotografia'] = $fotoPath;
            } else {
                error_log("Error moviendo el archivo de fotografía.");
            }
        } else {
            error_log("Error en la subida de fotografía: " . $_FILES['fotografia']['error']);
        }

        // Manejo del archivo de audio
        if (isset($_FILES['audiofile']) && $_FILES['audiofile']['error'] == UPLOAD_ERR_OK) {
            $audioPath = $uploadDirectory . basename($_FILES['audiofile']['name']);
            if (move_uploaded_file($_FILES['audiofile']['tmp_name'], $audioPath)) {
                $data['audiofile'] = $audioPath;
            } else {
                error_log("Error moviendo el archivo de audio.");
            }
        } else {
            error_log("Error en la subida de audio: " . $_FILES['audiofile']['error']);
        }

        $response = createSitio($pdo, $data);
        break;

    case 'PUT':
        parse_str(file_get_contents('php://input'), $data);
        $response = updateSitio($pdo, $path[1], $data);
        break;

    case 'DELETE':
        $response = deleteSitio($pdo, $path[1]);
        break;

    default:
        $response = ['error' => 'Método no soportado'];
        break;
}

echo json_encode($response);
?>
