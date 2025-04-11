<?php
include 'config.php';

// Recoger la acción enviada por POST
$accion = $_POST['accion'] ?? '';
if ($accion !== 'crear') {
    echo json_encode(["error" => "Acción no válida"]);
    exit();
}

// Recoger y validar los parámetros
$titulo = trim($_POST['titulo'] ?? '');
$descripcion = trim($_POST['descripcion'] ?? '');
$fechaCreacion = $_POST['fechaCreacion'] ?? 0;
$fechaFinalizacion = $_POST['fechaFinalizacion'] ?? 0;
$completado = $_POST['completado'] ?? 0;
$prioridad = $_POST['prioridad'] ?? 0;
$usuarioId = $_POST['usuarioId'] ?? 0;
$coordenadas = trim($_POST['coordenadas'] ?? '');
$localId = $_POST['localId'] ?? 0;

if (empty($titulo) || $fechaCreacion == 0 || $fechaFinalizacion == 0 || $usuarioId == 0) {
    echo json_encode(["error" => "Faltan datos obligatorios"]);
    exit();
}

// Preparar la consulta para insertar la tarea
$query = "INSERT INTO tareas (titulo, descripcion, fechaCreacion, FechaFinalizacion, completado, prioridad, usuarioId, localizacion, localId) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, "ssiiiiisi", $titulo, $descripcion, $fechaCreacion, $fechaFinalizacion, $completado, $prioridad, $usuarioId, $coordenadas, $localId);

if (mysqli_stmt_execute($stmt)) {
    $last_id = mysqli_insert_id($con);
    echo json_encode(["success" => "Tarea creada correctamente", "id" => $last_id]);
} else {
    echo json_encode(["error" => "Error al crear la tarea"]);
}
mysqli_stmt_close($stmt);
mysqli_close($con);
?>
