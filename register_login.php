<?php
include 'config.php';

$accion = $_POST['accion'] ?? '';
if (!in_array($accion, ['register', 'login'])) {
    echo json_encode(["error" => "Acción no válida"]);
    exit();
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if (empty($username) || empty($password)) {
    echo json_encode(["error" => "Faltan datos"]);
    exit();
}

if ($accion === 'register') {
    // Comprobar si el usuario ya existe
    $query = "SELECT id FROM usuarios WHERE username = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) > 0) {
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

    if (mysqli_stmt_execute($stmt)) {
        $last_id = mysqli_insert_id($con);
        echo json_encode(["success" => "Usuario registrado correctamente", "id" => $last_id]);
    } else {
        echo json_encode(["error" => "Error al registrar el usuario"]);
    }
    mysqli_stmt_close($stmt);

} elseif ($accion === 'login') {
    // Buscar el usuario y recuperar el hash de la contraseña
    $query = "SELECT id, password FROM usuarios WHERE username = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "s", $username);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_store_result($stmt);

    if (mysqli_stmt_num_rows($stmt) == 0) {
        echo json_encode(["error" => "Usuario no encontrado"]);
        mysqli_stmt_close($stmt);
        mysqli_close($con);
        exit();
    }

    mysqli_stmt_bind_result($stmt, $id, $hashedPassword);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    // Verificar la contraseña
    if (password_verify($password, $hashedPassword)) {
        echo json_encode(["success" => "Inicio de sesión correcto", "userId" => $id]);
    } else {
        echo json_encode(["error" => "Contraseña incorrecta"]);
    }
}

mysqli_close($con);
?>
