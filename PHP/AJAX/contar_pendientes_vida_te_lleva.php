<?php
include '../configuraciones.php';


$cantidad_pendientes = consulta();


if ($cantidad_pendientes === false) {
    $response['error'] = true;
    die(json_encode($response));
}


$response['error'] = false;
$response['cantidad'] = $cantidad_pendientes;


echo json_encode($response);



function consulta()
{
    $conexion = connection(DB_VIDA_TE_LLEVA);
    $tabla = TABLA_REGISTROS;

    $consulta = mysqli_query($conexion, "SELECT
        COUNT(id) AS 'Cantidad'
    FROM
        {$tabla}
    WHERE 
        leido = 0
    GROUP BY id");

    $resultado = mysqli_num_rows($consulta);
    return $resultado;
}
