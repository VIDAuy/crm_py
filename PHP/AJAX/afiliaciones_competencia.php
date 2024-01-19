<?php
include '../configuraciones.php';

$conexion = connection(DB_CALL);
$response = array('success' => false);
$query = 'SELECT count(*)  AS cantidad from padron_datos_socio WHERE estado IN (673, 686, 690)';

if (($res = mysqli_query($conexion, $query)) && mysqli_num_rows($res) > 0) {
    $response = array('success' => true, 'cantidad' => mysqli_fetch_assoc($res)['cantidad']);
}

mysqli_close($conexion);
die(json_encode($response));
