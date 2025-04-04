<?php 
include 'config.php';

// Recoger la acción
$accion = $_POST['accion'] ?? '';
if ($accion !== 'login') {
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

// Buscar el usuario y recuperar el hash de la contraseña
$query = "SELECT id, password FROM usuarios WHERE username = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);
mysqli_stmt_store_result($stmt);

if(mysqli_stmt_num_rows($stmt) == 0){
    echo json_encode(["error" => "Usuario no encontrado"]);
    mysqli_stmt_close($stmt);
    mysqli_close($con);
    exit();
}

mysqli_stmt_bind_result($stmt, $id, $hashedPassword);
mysqli_stmt_fetch($stmt);
mysqli_stmt_close($stmt);

// Verificar la contraseña
if(password_verify($password, $hashedPassword)){
    echo json_encode(["success" => "Inicio de sesión correcto", "userId" => $id]);
} else {
    echo json_encode(["error" => "Contraseña incorrecta"]);
}

mysqli_close($con);
?>
