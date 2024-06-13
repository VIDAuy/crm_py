<?php
include '../configuraciones.php';

$cedula = $_REQUEST['cedula'];
$opcion = $_REQUEST['opcion'];


$productos_socio = obtener_productos($cedula);
if ($productos_socio == false) devolver_error("Ocurrieron errores al obtener los productos del socio");
$cantidad_filas = mysqli_num_rows($productos_socio);


if ($opcion == 1) {
	$result_bajas = obtener_datos_baja_si_existe($cedula);
	if ($result_bajas == false) devolver_error("Ocurrieron errores al obtener la información de la baja");

	if ($result_bajas['estado'] == "Otorgada") {
		$response['error2'] = 222;
		$response['mensaje'] = "La cédula ingresada no pertenece a un socio actual de Vida. \n Se mostrarán los servicios contratados previos a la baja.";
	}

	$response['error'] = false;
	echo json_encode($response);
}

if ($opcion == 2) {
	$tabla["data"] = [];

	//Si encuentra los productos en padrón
	if ($cantidad_filas > 0) {
		while ($row = mysqli_fetch_assoc($productos_socio)) {
			$nroServicio = $row['nro_servicio'];
			$servicio 	 = $row['servicio'];
			$horas 		 = $row['hora'];
			$importe 	 = $row['importe'];

			$tabla["data"][] = [
				'nroServicio' => $nroServicio,
				'servicio'	  => obtener_servicio($nroServicio),
				'horas'		  => $horas,
				'importe'	  => "$$importe",
			];
		}
	}

	//Si no encuentra los productos en padrón
	if ($cantidad_filas <= 0) {
		$result_bajas = obtener_datos_baja_si_existe($cedula);
		if ($result_bajas == false) devolver_error("Ocurrieron errores al obtener la información de la baja");
		$datos['servicio_contratado'] = explode(', ', $result_bajas['servicio_contratado']);
		$datos['horas_contratadas']   = explode(', ', $result_bajas['horas_contratadas']);
		$datos['importe'] 			  = explode(', ', $result_bajas['importe']);

		$i = 0;
		while (isset($datos['servicio_contratado'][$i]) && $datos['servicio_contratado'][$i] != null) {
			$nro_servicio = $datos['servicio_contratado'][$i];
			$horas_contratadas = $datos['horas_contratadas'][$i];
			$importe = $datos['importe'][$i];

			$tabla["data"][] = [
				'nroServicio' => $nro_servicio,
				'servicio' 	  => obtener_servicio($nro_servicio),
				'horas' 	  => $horas_contratadas,
				'importe'     => "$$importe",
			];
			++$i;
		}
	}

	echo json_encode($tabla);
}




function obtener_productos($cedula)
{
	$conexion = connection(DB_AFILIACION_PARAGUAY);
	$tabla1 = TABLA_PADRON_PRODUCTO_SOCIO;
	$tabla2 = TABLA_SERVICIOS_CODIGOS;

	$sql = "SELECT 
		pps.servicio AS nro_servicio, 
		sc.servicio, 
		pps.hora, 
		pps.importe 
	  FROM 
		{$tabla1} pps 
		INNER JOIN {$tabla2} sc ON pps.servicio = sc.nro_servicio 
	  WHERE 
		cedula = '$cedula' 
		ORDER BY pps.id DESC";
	$consulta = mysqli_query($conexion, $sql);

	return $consulta;
}


function obtener_datos_baja_si_existe($cedula)
{
	$conexion = connection(DB);
	$tabla = TABLA_BAJAS;

	$sql = "SELECT 
		servicio_contratado, 
		horas_contratadas, 
		importe,
		estado
	  FROM 
		{$tabla} 
	  WHERE 
		cedula_socio = '$cedula' 
		ORDER BY id DESC";
	$consulta = mysqli_query($conexion, $sql);
	$resultados = mysqli_fetch_assoc($consulta);

	return $resultados;
}


function obtener_servicio($nro_servicio)
{
	$conexion = connection(DB_MOTOR_PRECIOS_PY);
	$tabla = TABLA_SERVICIOS;

	$sql = "SELECT 
		nombre 
	  FROM 
		{$tabla} 
	  WHERE 
		numero_servicio = '$nro_servicio' 
		ORDER BY id DESC";
	$consulta = mysqli_query($conexion, $sql);
	$resultados = mysqli_fetch_assoc($consulta)['nombre'];

	return $resultados;
}
