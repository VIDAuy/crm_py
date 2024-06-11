<?php
include '../configuraciones.php';


$usuario = $_REQUEST['usuario'];


$response['error'] = false;
$response['cantidad'] = cantidad_gestiones_pendientes($usuario);


echo json_encode($response);



function cantidad_gestiones_pendientes($usuario)
{
    $conexion = connection(DB);
    $tabla = TABLA_BAJAS;

    if ($usuario == "1707544") {
        $sql = "SELECT count(id) AS cantidad FROM {$tabla} WHERE fecha_fin_gestion IS NULL AND fecha_gestion_supervisor IS NULL AND activo = 1";
    } else if ($usuario == "Calidaduy") {
        //$sql = "SELECT count(id) AS cantidad FROM {$tabla} WHERE estado_supervisor = 'En Gestión' AND fecha_fin_gestion IS NULL AND fecha_gestion_supervisor IS NOT NULL AND activo = 1";
        $sql = "SELECT count(id) AS cantidad FROM {$tabla} WHERE estado = 'Pendiente' AND estado_supervisor != 'Continua' AND activo = 1";
    }

    $consulta = mysqli_query($conexion, $sql);
    $resultado = mysqli_fetch_assoc($consulta)['cantidad'];

    return $resultado;
}
