<?php
include '../configuraciones.php';
$conexion = connection(DB);
$tabla = TABLA_REGISTROS_PY;

$sector = $_SESSION['id_py'];

$q = "SELECT count(cedula) AS cantidad FROM {$tabla} WHERE envioSector = '$sector' AND activo = 1 AND cedula != ''";
$r = mysqli_query($conexion, $q);

$qtot = mysqli_fetch_assoc($r)['cantidad'];

$jsondata = ['message' => $qtot];

mysqli_close($conexion);
die(json_encode($jsondata));
