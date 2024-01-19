<?php
include '../configuraciones.php';
$conexion = connection_nodum();


$cod_trabajador = $_GET['cod_trabajador'];
$opcion = $_GET['opcion'];


$response["data"] = [];

$total_dias_restantes = 0;

$licencia_generada = licencias_generadas($cod_trabajador, $opcion);

$verificar_tomadas = verificar_licencias_liquidadas($cod_trabajador);


if ($opcion == 'tabla') {


    if ($verificar_tomadas != "") {


        //Dias Generados
        while ($resultado = sqlsrv_fetch_array($licencia_generada)) {
            $anio_generado = $resultado['anio'];
            $fecha_inicio_generado = $resultado['fec_inicio']->FORMAT('d/m/Y');
            $fecha_fin_generado = $resultado['fec_final']->FORMAT('d/m/Y');
            $dias_generados = ROUND($resultado['dias_licencia']);

            $dias_restantes = 0;


            $consulta_liquidada = licencias_liquidadas($cod_trabajador, $anio_generado);

            //Dias Restantes
            while ($liquidada = sqlsrv_fetch_array($consulta_liquidada)) {

                $fecha_inicio  = $liquidada['fec_inicio']->FORMAT('d/m/Y');
                $fecha_fin     = $liquidada['fec_final']->FORMAT('d/m/Y');
                $dias_tomados = ROUND($liquidada['dias_licencia']);
                $dias_restantes = $dias_restantes == 0 ? $dias_generados - $dias_tomados : $dias_restantes - $dias_tomados;


                $response["data"][] = [
                    'anio'            => $anio_generado,
                    'fecha_inicio'    => $fecha_inicio,
                    'fecha_fin'       => $fecha_fin,
                    'dias_generados'  => $dias_generados,
                    'dias_tomados'    => $dias_tomados,
                    'dias_restantes'  => $dias_restantes,
                ];
            }
        }
    } else {
        while ($resultado = sqlsrv_fetch_array($licencia_generada)) {
            $anio_generado = $resultado['anio'];
            $fecha_inicio_generado = $resultado['fec_inicio']->FORMAT('d/m/Y');
            $fecha_fin_generado = $resultado['fec_final']->FORMAT('d/m/Y');
            $dias_generados = ROUND($resultado['dias_licencia']);


            $response["data"][] = [
                'anio'            => $anio_generado,
                'fecha_inicio'    => $fecha_inicio_generado,
                'fecha_fin'       => $fecha_fin_generado,
                'dias_generados'  => $dias_generados,
                'dias_tomados'    => "",
                'dias_restantes'  => $dias_generados,
            ];
        }
    }
} else {

    if ($licencia_generada === false) {
        $response['error'] = true;
        $response['mensaje'] = "La cédula consultada no cuenta con licencias para mostrar.";
        die(json_encode($response));
    }

    $response['error'] = false;
}



echo json_encode($response);





function licencias_generadas($cod_trabajador, $opcion)
{
    $conexion = connection_nodum();

    $consulta = sqlsrv_query($conexion, "SELECT
	nro_ingreso_lic,
	anio,
	fec_inicio,
	fec_final,
	dias_licencia
    FROM
	cpt_RHLicencias 
    WHERE
    cod_trabajador = '$cod_trabajador' AND 
	cod_accion_lic LIKE '%generar%'
    ORDER BY
	fec_inicio ASC");


    if ($opcion == 'tabla') {
        $respuesta = $consulta;
    } else {
        $respuesta = sqlsrv_fetch_array($consulta);
    }
    return $respuesta;
}


function licencias_liquidadas($cod_trabajador, $anio_consultar)
{
    $conexion = connection_nodum();

    $consulta = sqlsrv_query($conexion, "SELECT
	nro_ingreso_lic,
	anio,
	fec_inicio,
	fec_final,
	dias_licencia,
	cod_accion_lic 
    FROM
	cpt_RHLicencias 
    WHERE
    cod_trabajador = '$cod_trabajador' AND 
	cod_accion_lic LIKE '%aliquidar%' AND
	anio = '$anio_consultar'
    ORDER BY
	fec_inicio ASC");


    return $consulta;
}



function verificar_licencias_liquidadas($cod_trabajador)
{
    $conexion = connection_nodum();

    $consulta = sqlsrv_query($conexion, "SELECT
	nro_ingreso_lic,
	anio,
	fec_inicio,
	fec_final,
	dias_licencia,
	cod_accion_lic 
    FROM
	cpt_RHLicencias 
    WHERE
    cod_trabajador = '$cod_trabajador' AND 
	cod_accion_lic LIKE '%aliquidar%'
    ORDER BY
	fec_inicio ASC");


    return sqlsrv_fetch_array($consulta);
}
