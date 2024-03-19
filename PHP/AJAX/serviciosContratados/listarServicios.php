<?php
include '../../configuraciones.php';
$conexion = connection(DB_AFILIACION_PARAGUAY);
$tabla1 = TABLA_PADRON_PRODUCTO_SOCIO;
$tabla2 = TABLA_SERVICIOS_CODIGOS;
$tabla3 = TABLA_BAJAS;
$cedula = $_GET['cedula'];


$productos_socio = obtener_productos($cedula);

//Si encuentra los productos en padrón
if (mysqli_num_rows($productos_socio) > 0) {

	while ($row = mysqli_fetch_assoc($productos_socio)) {
		$nroServicio = $row['nro_servicio'];
		$servicio 	 = $row['servicio'];
		$horas 		 = $row['hora'];
		$importe 	 = $row['importe'];

		$repuesta[] = array(
			'nroServicio' => $nroServicio,
			'servicio'	  => obtener_servicio($nroServicio),
			'horas'		  => $horas,
			'importe'	  => $importe
		);
	}
}

//Si no encuentra los productos en padrón
if (mysqli_num_rows($productos_socio) <= 0) {
	$result_bajas = obtener_datos_baja_si_existe($cedula);
	$datos['servicio_contratado'] = explode(', ', $result_bajas['servicio_contratado']);
	$datos['horas_contratadas']   = explode(', ', $result_bajas['horas_contratadas']);
	$datos['importe'] 			  = explode(', ', $result_bajas['importe']);

	$i = 0;
	$repuesta = array('error' => true);

	while (isset($datos['servicio_contratado'][$i]) && $datos['servicio_contratado'][$i] != null) {
		$servicio_contratado = $datos['servicio_contratado'][$i];
		$repuesta[] = array(
			'nroServicio' 	=> $datos['servicio_contratado'][$i],
			'servicio' 		=> $obtener_servicio($datos['servicio_contratado']),
			'horas' 		=> $datos['horas_contratadas'][$i],
			'importe' 		=> $datos['importe'][$i],
		);
		++$i;
	}
}


echo json_encode($repuesta);




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
		importe 
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
