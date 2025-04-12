<?php
include 'config.php';

// Recoger la acción enviada por POST
$accion = $_POST['accion'] ?? '';
if ($accion == 'crear') {
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
        echo json_encode(["success" => "Tarea creada correctamente", "id" => $last_id, "accion" => "crear"]);
    } else {
        echo json_encode(["error" => "Error al crear la tarea"]);
    }
    mysqli_stmt_close($stmt);
}
elseif ($accion === 'editar') {

    $localId = $_POST['localId'] ?? 0;
    $titulo = trim($_POST['titulo'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $fechaCreacion = $_POST['fechaCreacion'] ?? 0;
    $fechaFinalizacion = $_POST['fechaFinalizacion'] ?? 0;
    $prioridad = $_POST['prioridad'] ?? 0;
    $usuarioId = $_POST['usuarioId'] ?? 0;
    $coordenadas = trim($_POST['coordenadas'] ?? '');

    if ($localId == 0 || empty($titulo) || $fechaCreacion == 0 || $fechaFinalizacion == 0 || $usuarioId == 0) {
        echo json_encode(["error" => "Faltan datos obligatorios para actualizar"]);
        exit();
    }

    // Actualizar la tarea en base al localId
    $query = "UPDATE tareas SET titulo = ?, descripcion = ?, fechaCreacion = ?, FechaFinalizacion = ?, prioridad = ?, localizacion = ? WHERE localId = ? AND usuarioId = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ssiiisii", $titulo, $descripcion, $fechaCreacion, $fechaFinalizacion, $prioridad, $coordenadas, $localId, $usuarioId);

    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["success" => "Tarea actualizada correctamente", "accion" => "editar"]);
    } else {
        echo json_encode(["error" => "Error al actualizar la tarea"]);
    }
    mysqli_stmt_close($stmt);
}
elseif ($accion === 'completar') {
    // Marcar la tarea como completada
    $usuarioId = $_POST['usuarioId'] ?? 0;
    $localId = $_POST['localId'] ?? 0;
    if ($localId == 0) {
        echo json_encode(["error" => "localId obligatorio"]);
        exit();
    }
    $query = "UPDATE tareas SET completado = 1 WHERE localId = ? AND usuarioId= ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ii", $localId, $usuarioId);
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["success" => "Tarea marcada como completada", "accion" => "completar"]);
    } else {
        echo json_encode(["error" => "Error al actualizar la tarea"]);
    }
    mysqli_stmt_close($stmt);
} elseif ($accion === 'eliminar') {
    // Eliminar la tarea
    $localId = $_POST['localId'] ?? 0;
    $usuarioId = $_POST['usuarioId'] ?? 0;
    if ($localId == 0) {
        echo json_encode(["error" => "localId obligatorio"]);
        exit();
    }
    $query = "DELETE FROM tareas WHERE localId = ? AND usuarioId= ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, "ii", $localId, $usuarioId);
    if (mysqli_stmt_execute($stmt)) {
        echo json_encode(["success" => "Tarea eliminada correctamente", "accion" => "eliminar"]);
    } else {
        echo json_encode(["error" => "Error al eliminar la tarea"]);
    }
    mysqli_stmt_close($stmt);
}
else{
    echo json_encode(["error" => "Acción no válida"]);
}

mysqli_close($con);
?>
