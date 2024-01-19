<?php
include '../../configuraciones.php';


$fecha_desde = $_GET['fecha_desde'];
$fecha_hasta = $_GET['fecha_hasta'];
$opcion = $_GET['opcion'];


$lic_generada = licencias_generadas($fecha_desde, $fecha_hasta, $opcion);


if ($opcion == 'tabla') {

    $response["data"] = [];

    while ($resultado = sqlsrv_fetch_array($lic_generada)) {

        $anio = $resultado['anio'];
        $cod_trabajador = $resultado['cod_trabajador'];
        $fecha_inicio = $resultado['fec_inicio']->FORMAT('d/m/Y');
        $fecha_fin = $resultado['fec_final']->FORMAT('d/m/Y');
        $cant_dias = ROUND($resultado['dias_licencia']);
        $cod_accion_lic = TRIM($resultado['cod_accion_lic']);

        $cedula = $resultado['cedula'];
        $nombre_1_verificar = TRIM($resultado['nombre1']);
        $nombre1 = verificar_letras($nombre_1_verificar) == true ? $nombre_1_verificar : "";
        $nombre_2_verificar = TRIM($resultado['nombre2']);
        $nombre2 = verificar_letras($nombre_2_verificar) == true ? $nombre_2_verificar : "";
        $apellido1_verificar = TRIM($resultado['apellido1']);
        $apellido1 = verificar_letras($apellido1_verificar) == true ? $apellido1_verificar : "";
        $apellido2_verificar = TRIM($resultado['apellido2']);
        $apellido2 = verificar_letras($apellido2_verificar) == true ? $apellido2_verificar : "";
        $nombre_completo = $nombre1 . " " . $nombre2 . " " . $apellido1 . " " . $apellido2;




        $response["data"][] = [
            'anio'            => $anio,
            'cedula'          => $cedula,
            'nombre_completo' => $nombre_completo,
            'fecha_inicio'    => $fecha_inicio,
            'fecha_fin'       => $fecha_fin,
            'cant_dias'       => $cant_dias,
            'tipo_licencia'   => $tipo = $cod_accion_lic == 'generar' ? '<span class="text-success">Generada</span>' : '<span class="text-danger">Tomada</span>',
        ];
    }
} else {

    if ($lic_generada === false) {
        $response['error'] = true;
        $response['mensaje'] = "No hay licencias para mostrar dentro del periodo ingresado.";
        die(json_encode($response));
    }

    $response['error'] = false;
}


echo json_encode($response);






function licencias_generadas($fecha_desde, $fecha_hasta, $opcion)
{
    $conexion = connection_nodum();

    $consulta = sqlsrv_query($conexion, "SELECT
	v.doc_persona_nro AS 'cedula',
	v.nombre1, 
	v.nombre2, 
	v.apellido1, 
	v.apellido2,
	l.anio,
	l.cod_trabajador,
	l.fec_inicio,
	l.fec_final,
	l.dias_licencia,
	l.cod_accion_lic 
    FROM
	cpt_RHLicencias AS l,
	v_RHTrabajador AS v
    WHERE
	l.cod_trabajador = v.cod_trabajador
	AND l.fec_inicio >= '$fecha_desde' 
	AND l.fec_final <= '$fecha_hasta' 
	AND l.cod_accion_lic NOT LIKE '%liquidada%' 
	AND l.cod_accion_lic NOT LIKE '%descdias%'
    ORDER BY anio ASC");

    if ($opcion == 'tabla') {
        $respuesta = $consulta;
    } else {
        $respuesta = sqlsrv_fetch_array($consulta) != "" ? true : false;
    }
    return $respuesta;
}



function verificar_letras($cadena)
{
    if (preg_match("/^(?=.{3,18}$)[a-zñA-ZÑ](\s?[a-zñA-ZÑ])*$/", $cadena)) {
        $respuesta = true;
    } else {
        $respuesta = false;
    }

    return $respuesta;
}
