<?php
include '../../configuraciones.php';


$fecha_desde = $_GET['fecha_desde'];
$fecha_hasta = $_GET['fecha_hasta'];
$opcion = $_GET['opcion'];

$response["data"] = [];


$capacitacion = capacitaciones($fecha_desde, $fecha_hasta, $opcion);


if ($capacitacion === false) {
    $response["error"] = true;
    $response['mensaje'] = "No hay capacitadas para mostrar";
    die(json_encode($response));
}


if ($opcion == 'tabla') {

    //Dias Generados
    while ($resultado = mysqli_fetch_array($capacitacion)) {

        $cant_pruebas = $resultado['cant_pruebas'];
        $nombre = $resultado['nombre'];
        $apellido = $resultado['apellido'];
        $nombre_completo = $nombre . " " . $apellido;
        $cedula = $resultado['cedula'];
        $filial = consulta_filial($cedula);
        $fecha_inicio = $resultado['fecha_inicio'];

        $verificar = primera_vez($cedula);
        $fecha_minima = $verificar['fecha_minima'];
        $fecha_maxima = $verificar['fecha_maxima'];

        if ($fecha_minima == $fecha_maxima) {

            $response["data"][] = [
                'nombre_completo'  => $nombre_completo,
                'cedula'           => $cedula,
                'filial'           => $filial,
                'fecha'            => $fecha_inicio
            ];
        }
    }
} else {

    $response['error'] = false;
}


echo json_encode($response);






function capacitaciones($fecha_desde, $fecha_hasta, $opcion)
{
    $conexion = connection(DB_MOODLE_VIDA);

    $consulta = mysqli_query($conexion, "SELECT 
        username AS 'cedula',
        u.firstname AS 'nombre',
        u.lastname AS 'apellido',
        COUNT(userid) AS 'cant_pruebas',
        CAST(FROM_UNIXTIME(qa.timestart) AS DATE) AS 'fecha_inicio',
        CAST(FROM_UNIXTIME(qa.timefinish) AS DATE) AS 'Fecha_fin'
    FROM 
        mdl_user AS u
        INNER JOIN mdl_quiz_attempts AS qa ON u.id = qa.userid
    WHERE
        state = 'finished' AND
        CAST(FROM_UNIXTIME(qa.timestart) AS DATE) BETWEEN '$fecha_desde' AND '$fecha_hasta'
    GROUP BY username");


    if ($opcion == 'consulta') {
        $respuesta = mysqli_fetch_array($consulta)['cant_pruebas'] <= 6 ? true : false;
    } else {
        $respuesta = $consulta;
    }


    return $respuesta;

    mysqli_close($conexion);
}

function consulta_filial($cedula)
{
    $conexion = connection(DB_COORDINACION);

    $consulta = mysqli_query($conexion, "SELECT departamento FROM acompanantes_nodum WHERE documento = '$cedula'");
    $respuesta = mysqli_fetch_array($consulta)['departamento'];
    $departamento = explode("-", $respuesta);
    $filial = $departamento[1];

    return $filial;
    mysqli_close($conexion);
}

function primera_vez($cedula)
{
    $conexion = connection(DB_MOODLE_VIDA);

    $consulta = mysqli_query($conexion, "SELECT 
    MIN(CAST(FROM_UNIXTIME(qa.timestart) AS DATE)) AS 'fecha_minima',
    MAX(CAST(FROM_UNIXTIME(qa.timefinish) AS DATE)) AS 'fecha_maxima'
    FROM 
    mdl_user AS u
    INNER JOIN mdl_quiz_attempts AS qa ON u.id = qa.userid
    WHERE 
    u.username = '$cedula'");

    $respuesta = mysqli_fetch_array($consulta);

    return $respuesta;
    mysqli_close($conexion);
}
