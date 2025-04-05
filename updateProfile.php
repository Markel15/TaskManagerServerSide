<?php
include 'config.php';

// Recoger y validar la acción
$accion = $_POST['accion'] ?? '';
if ($accion !== 'updateProfileImage') {
    echo json_encode(["error" => "Acción no válida"]);
    exit();
}

// Recoger parámetros
$userId = $_POST['userId'] ?? '';
$imagen = $_POST['imagen'] ?? '';

if (empty($userId) || empty($imagen)) {
    echo json_encode(["error" => "Faltan datos"]);
    exit();
}

// Actualizar solo la imagen de perfil
$query = "UPDATE usuarios SET imagenPerfil = ? WHERE id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "si", $imagen, $userId);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["success" => "Imagen de perfil actualizada"]);
} else {
    echo json_encode(["error" => "Error al actualizar la imagen de perfil"]);
}

mysqli_stmt_close($stmt);
mysqli_close($con);
?>
