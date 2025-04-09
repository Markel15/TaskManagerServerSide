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

// Decodificar base64
$imagenBinaria = base64_decode($imagen);
if ($imagenBinaria === false) {
    echo json_encode(["error" => "Error al decodificar imagen"]);
    exit();
}

// Actualizar solo la imagen de perfil
$query = "UPDATE usuarios SET imagenPerfil = ? WHERE id = ?";
$stmt = mysqli_prepare($con, $query);

if (!$stmt) {
    echo json_encode(["error" => "Error al preparar la consulta: " . mysqli_error($con)]);
    exit();
}

mysqli_stmt_bind_param($stmt, "bi", $null, $userId); // "b" para BLOB

// Enviar BLOB manualmente
mysqli_stmt_send_long_data($stmt, 0, $imagenBinaria);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(["success" => "Imagen de perfil actualizada"]);
} else {
    echo json_encode(["error" => "Error al ejecutar: " . mysqli_stmt_error($stmt)]);
}

mysqli_stmt_close($stmt);
mysqli_close($con);
?>
