<?php
include '../../configuraciones.php';

$sector = $_SESSION['id_py'];

$alertas_pendientes = obtener_alertas_pendientes($sector);
if ($alertas_pendientes == false) devolver_error("Ocurrieron errores al obtener las alertas pendientes");
$cantidad_pendientes = mysqli_num_rows($alertas_pendientes);



$response['error'] = false;
$response['cantidad'] = $cantidad_pendientes;
echo json_encode($response);




function obtener_alertas_pendientes($sector)
{
    $conexion = connection(DB);
    $tabla = TABLA_REGISTROS_PY;

    $sql = "SELECT * FROM {$tabla} WHERE envioSector = '$sector' AND activo = 1 AND cedula != ''";
    $consulta = mysqli_query($conexion, $sql);

    return $consulta;
}
