<?php
include '../../configuraciones.php';
$conexion = connection(DB_AFILIACION_PARAGUAY);
$tabla1 = TABLA_PADRON_PRODUCTO_SOCIO;
$tabla2 = TABLA_SERVICIOS_CODIGOS;
$tabla3 = TABLA_BAJAS;
$cedula = $_GET['cedula'];

$q = "SELECT pps.servicio AS nro_servicio, sc.servicio, pps.hora, pps.importe FROM {$tabla1} AS pps INNER JOIN {$tabla2} AS sc ON pps.servicio = sc.nro_servicio WHERE cedula = {$cedula} ORDER BY pps.id DESC";
$r = mysqli_query($conexion, $q);
if (mysqli_num_rows($r) != 0) {
	while ($f = mysqli_fetch_assoc($r)) {
		$nroServicio 	= $f['nro_servicio'];
		$servicio 		= $f['servicio'];
		$horas 			= $f['hora'];
		$importe 		= $f['importe'];

		$repuesta[] = array(
			'nroServicio'	=> $nroServicio,
			'servicio'		=> $servicio,
			'horas'			=> $horas,
			'importe'		=> $importe
		);
	}
} else {

	$conexion = connection(DB);
	$q = "SELECT servicio_contratado, horas_contratadas, importe FROM {$tabla3} WHERE cedula_socio = {$cedula} ORDER BY id DESC";
	$r = mysqli_query($conexion, $q);
	$f1 = mysqli_fetch_assoc($r);
	$f['servicio_contratado'] 	= explode(', ', $f1['servicio_contratado']);
	$f['horas_contratadas'] 	= explode(', ', $f1['horas_contratadas']);
	$f['importe'] 				= explode(', ', $f1['importe']);

	$i = 0;
	$repuesta = array('error' => true);
	$conexion = connection(DB_AFILIACION_PARAGUAY);
	while (isset($f['servicio_contratado'][$i]) && $f['servicio_contratado'][$i] != null) {
		$servicio_contratado = $f['servicio_contratado'][$i];
		$q = "SELECT servicio FROM {$tabla2} WHERE nro_servicio = {$servicio_contratado} ORDER BY id DESC LIMIT 1";
		$r = mysqli_query($conexion, $q);
		$f1 = mysqli_fetch_assoc($r);
		$repuesta[] = array(
			'nroServicio' 	=> $f['servicio_contratado'][$i],
			'servicio' 		=> $f1['servicio'],
			'horas' 		=> $f['horas_contratadas'][$i],
			'importe' 		=> $f['importe'][$i],
		);
		++$i;
	}
}
echo json_encode($repuesta);
