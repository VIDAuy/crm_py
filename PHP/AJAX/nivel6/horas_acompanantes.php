<?php
include '../../configuraciones.php';


$cedula = $_REQUEST['cedula'];
$fecha_desde = $_REQUEST['fecha_desde'];
$fecha_hasta = $_REQUEST['fecha_hasta'];


$tabla["data"] = [];

$consulta = consulta_general($cedula, $fecha_desde, $fecha_hasta);


while ($resultado = mysqli_fetch_array($consulta)) {

    $id_servicio = $resultado['id_servicio'];
    $hora_inicio = $resultado['hora_inicio'];
    $hora_fin = $resultado['hora_fin'];
    $fecha_servicio = $resultado['fecha_servicio'];
    $horario_cortado = $resultado['horario_cortado'];
    $idinfo = $resultado['idinfo'];
    $aislamiento = $resultado['asilamiento_reclamado'];

    $_fecha = $fecha_servicio;


    if ($horario_cortado == 1) {
        $_fecha = date('Y-m-d', strtotime($fecha_servicio . '+1 day'));
        $query = "SELECT h.hora_fin 
                  FROM servicios_new s INNER JOIN horarios h ON s.id = h.id_servicio 
                  WHERE h.activo = 1 
                      AND h.hora_inicio = '00:00' 
                      AND h.horario_cortado = 1 
                      AND s.activo = 1 
                      AND s.fecha = '$_fecha' 
                      AND s.idinfo='$idinfo'
                      AND h.cedula_acompanante = '$cedula'";
        $hora_fin = mysqli_fetch_assoc(mysqli_query($conexion, $query))['hora_fin'];
    }


    $suma_horas = strtotime($_fecha . " " . $hora_fin) - strtotime($fecha_servicio . " " . $hora_inicio);
    $suma_horas = $suma_horas / 60 / 60;



    $aislamiento = $aislamiento == 1 ? "✔" : "";


    $consulta_descansos = consulta_descansos($cedula, $fecha_servicio);
    $descanso = $consulta_descansos != "" ? "✔" : "";




    $tabla["data"][] = [
        "fecha_filtro" => $fecha_servicio,
        "id_servicio" => $idinfo,
        "hora_inicio" => $hora_inicio,
        "hora_fin" => $hora_fin,
        "fecha_servicio" => date('d/m/Y', strtotime($fecha_servicio)),
        "suma_horas" => $suma_horas,
        "descanso" => $descanso,
        "aislamiento" => $aislamiento,
    ];
}



echo json_encode($tabla);







function consulta_general($cedula, $fecha_desde, $fecha_hasta)
{
    $conexion = connection(DB_COORDINACION);

    $consulta = mysqli_query($conexion, "SELECT 
    s.id AS 'id_servicio', 
    h.hora_inicio, 
    h.hora_fin, 
    h.horario_cortado,
    h.asilamiento_reclamado,
    s.idinfo,
    s.fecha AS 'fecha_servicio'
    FROM servicios_new s 
    INNER JOIN pedido_acomp p ON p.id = s.idinfo 
    INNER JOIN horarios h ON h.id_servicio = s.id
    WHERE s.activo = 1 
    AND h.activo = 1
    AND h.sin_acompanante = 0 
    AND s.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta'
    AND (horario_cortado = 0 OR (horario_cortado = 1 AND hora_fin = '23:59'))
    AND h.cedula_acompanante = '$cedula'
    ORDER BY fecha_servicio ASC");

    return $consulta;
}

function consulta_descansos($cedula, $fecha_servicio)
{
    $conexion = connection(DB_STATUS);

    $consulta = mysqli_query($conexion, "SELECT
    DATE(d.fecha_inicio) AS fecha_inicio
    FROM
    acompanantes AS a
    INNER JOIN acompanantes_descansos AS d ON a.id = d.id_acompanante 
    WHERE
    a.estado = 1 
    AND d.descanso_trabajado = 1
	AND a.documento = '$cedula'
	AND DATE(d.fecha_inicio) = '$fecha_servicio'");

    $resultado = mysqli_fetch_array($consulta);

    return $resultado['fecha_inicio'];
}
