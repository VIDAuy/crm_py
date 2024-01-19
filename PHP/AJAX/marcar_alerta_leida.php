<?php
include '../configuraciones.php';


$nro_cargo = $_POST['nro_cargo'];

$marcar_alerta_como_leida = marcar_leido($nro_cargo);


if ($marcar_alerta_como_leida === false) {
    $response['error'] = true;
    $response['mensaje'] = 'Ha ocurrido un error, contacte al administrador';
    die(json_encode($response));
}


$response['error'] = false;
$response['mensaje'] = 'Se ha cargado el documento con exito!';



echo json_encode($response);




function marcar_leido($nro_cargo)
{
    $conexion = connection(DB);

    $consulta = "UPDATE respuesta_carga_documento SET leido = 2 WHERE nro_carga = '$nro_cargo'";
    $respuesta = mysqli_query($conexion, $consulta);

    return $respuesta == false ? false : true;
}
