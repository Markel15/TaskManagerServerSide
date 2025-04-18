<?php
// Datos de conexión
$DB_SERVER = "localhost";
$DB_USER = "Xmhernandez141";
$DB_PASS = "6QtFXIJ4rh";
$DB_DATABASE = "Xmhernandez141_usuarios";

// Conexión
$con = mysqli_connect($DB_SERVER, $DB_USER, $DB_PASS, $DB_DATABASE);
if (mysqli_connect_errno()) {
    echo json_encode(["error" => "Error de conexión: " . mysqli_connect_error()]);
    exit();
}

?>