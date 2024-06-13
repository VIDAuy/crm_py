<?php
include '../configuraciones.php';

$cedula = $_REQUEST['cedula'];

$ultima_fecha = obtener_fecha($cedula);
if ($ultima_fecha == null || $ultima_fecha == "") devolver_error("<span class='text-danger fw-bolder'> No hay registros </span>");


$fecha_ultima = $ultima_fecha['fecha_registro'];
$sector_ultima = $ultima_fecha['sector'];



$response['error'] = false;
$response['mensaje'] = "<span class='text-success'>" . date("d/m/Y H:i:s", strtotime($fecha_ultima)) . ": " . $sector_ultima . "</span>";
echo json_encode($response);




function obtener_fecha($cedula)
{
    $conexion = connection(DB);
    $tabla = TABLA_REGISTROS_PY;

    $sql = "SELECT fecha_registro, sector FROM {$tabla} WHERE cedula = $cedula ORDER BY fecha_registro DESC LIMIT 1";
    $consulta = mysqli_query($conexion, $sql);

    return mysqli_fetch_assoc($consulta);
}
