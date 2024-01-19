<?php
include '../configuraciones.php';


$usuario = $_SESSION['usuario_py'];
$cantidad_pendientes = consulta($usuario);

if ($cantidad_pendientes == 0) {
    $response['error'] = true;
    die(json_encode($response));
}


$response['error'] = false;
$response['cantidad'] = $cantidad_pendientes['cantidad'];


echo json_encode($response);



function consulta($usuario)
{
    $conexion = connection(DB);

    $consulta = mysqli_query($conexion, "SELECT 
    count(r.leido) AS cantidad 
    FROM 
    carga_documentos AS c, 
    respuesta_carga_documento AS r 
    WHERE 
    c.id = r.nro_carga AND 
    r.leido = 1 AND 
    c.carga = '$usuario'
    ");

    return mysqli_fetch_array($consulta);
}
