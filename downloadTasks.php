<?php
include 'config.php';

// Recoger el usuario (ID) desde POST
$usuarioId = $_POST['usuarioId'] ?? 0;
if ($usuarioId == 0) {
    echo json_encode(["error" => "usuarioId requerido"]);
    exit();
}

// Consulta para obtener las tareas no completadas para el usuario
$query = "SELECT localId, titulo, descripcion, fechaCreacion, FechaFinalizacion, completado, prioridad, usuarioId, localizacion FROM tareas WHERE usuarioId = ? AND completado = 0";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "i", $usuarioId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$tareas = array();
while ($row = mysqli_fetch_assoc($result)) {
    $tareas[] = $row;
}

mysqli_stmt_close($stmt);
mysqli_close($con);

// Devuelve el resultado en formato JSON
echo json_encode(["success" => "Tareas descargadas", "tareas" => $tareas]);
?>
