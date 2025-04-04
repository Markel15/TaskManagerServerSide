<?php
include 'config.php';

// Recoger y validar la acción
$accion = $_POST['accion'] ?? '';
if ($accion !== 'updateProfile') {
    echo json_encode(["error" => "Acción no válida"]);
    exit();
}

// Recoger parámetros
$userId = $_POST['userId'] ?? '';
$username = trim($_POST['username'] ?? '');
$imagen = $_POST['imagen'] ?? ''; // Cadena Base64; puede ser vacía si no se actualiza

if(empty($userId) || empty($username)){
    echo json_encode(["error" => "Faltan datos"]);
    exit();
}

// Preparamos la sentencia SQL según si se envía imagen o no
if(!empty($imagen)) {
    $query = "UPDATE usuarios SET username = ?, fotoPerfil = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ssi", $username, $imagen, $userId);
} else {
    $query = "UPDATE usuarios SET username = ? WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "si", $username, $userId);
}

if(mysqli_stmt_execute($stmt)){
    echo json_encode(["success" => "Perfil actualizado"]);
} else {
    echo json_encode(["error" => "Error al actualizar el perfil"]);
}

mysqli_stmt_close($stmt);
mysqli_close($con);
?>
