<?php
include 'config.php';

// Recoger la acción
$accion = $_POST['accion'] ?? '';
if ($accion !== 'register') {
    echo json_encode(["error" => "Acción no válida"]);
    exit();
}

// Recoger y validar parámetros
$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');
if(empty($username) || empty($password)){
    echo json_encode(["error" => "Faltan datos"]);
    exit();
}

// Comprobar si el usuario ya existe
$query = "SELECT id FROM usuarios WHERE username = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);
if(mysqli_stmt_num_rows($stmt) > 0){
    echo json_encode(["error" => "El usuario ya existe"]);
    mysqli_stmt_close($stmt);
    mysqli_close($con);
    exit();
}
mysqli_stmt_close($stmt);

// Cifrar la contraseña e insertar el usuario
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
$query = "INSERT INTO usuarios (username, password) VALUES (?, ?)";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "ss", $username, $hashedPassword);
if(mysqli_stmt_execute($stmt)){
    echo json_encode(["success" => "Usuario registrado correctamente"]);
} else {
    echo json_encode(["error" => "Error al registrar el usuario"]);
}
mysqli_stmt_close($stmt);
mysqli_close($con);
?>
