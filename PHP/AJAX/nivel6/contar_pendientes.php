<?php
include '../../configuraciones.php';


$usuario = $_SESSION['usuario_py'];
$cantidad_pendientes = consulta($usuario);


if ($cantidad_pendientes == 0) {
    $response['error'] = true;
    die(json_encode($response));
}


$response['error'] = false;
$response['cantidad'] = $cantidad_pendientes['Cantidad'];


echo json_encode($response);



function consulta($usuario)
{
    $conexion = connection(DB);

    $consulta = mysqli_query($conexion, "SELECT count(r.respuesta) AS Cantidad FROM carga_documentos AS c, respuesta_carga_documento AS r WHERE c.id = r.nro_carga AND r.respuesta = 1 AND c.avisar_a = '$usuario'");
    $resultado = mysqli_fetch_array($consulta);

    return $resultado;
}
