<?php
include 'config.php';

$userId = $_GET['userId'] ?? '';
if (empty($userId)) {
    echo json_encode(["error" => "Falta el userId"]);
    exit();
}

$query = "SELECT username, imagenPerfil FROM usuarios WHERE id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt, $username, $imagenPerfil);
if (mysqli_stmt_fetch($stmt)) {
    // La imagen está almacenada como BLOB, la convertimos a Base64
    $imagenBase64 = $imagenPerfil !== null ? base64_encode($imagenPerfil) : "";
    echo json_encode(["username" => $username, "imagen" => $imagenBase64]);
} else {
    echo json_encode(["error" => "Usuario no encontrado"]);
}
mysqli_stmt_close($stmt);
mysqli_close($con);
?>
