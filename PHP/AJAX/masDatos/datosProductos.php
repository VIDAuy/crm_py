<?php
include '../../configuraciones.php';

$cedula = $_GET['cedula'];
$tabla["data"] = [];


$datos = obtener_productos($cedula);

while ($dato = mysqli_fetch_assoc($datos)) {

	$nro_servicio = $dato['nro_servicio'];
	$servicio = obtener_servicio($nro_servicio);
	$horas = $dato['hora'];
	$importe = $dato['importe'];
	$fecha_afiliacion = $dato['fecha_afiliacion'];


	$tabla["data"][] = [
		'nroServicio'      => $nro_servicio,
		'servicio'         => $servicio,
		'horas' 	       => $horas,
		'importe' 	  	   => $importe,
		'fecha_afiliacion' => date("d/m/Y", strtotime($fecha_afiliacion))
	];
}


echo json_encode($tabla);



function obtener_productos($cedula)
{
	$conexion = connection(DB_AFILIACION_PARAGUAY);
	$tabla = TABLA_PADRON_PRODUCTO_SOCIO;

	$sql = "SELECT
		pps.servicio AS nro_servicio,
		pps.hora,
		pps.importe,
		pps.fecha_afiliacion
	FROM
		{$tabla} pps 
	WHERE
		cedula = '$cedula'";

	return mysqli_query($conexion, $sql);
}


function obtener_servicio($nro_servicio)
{
	$conexion = connection(DB_ABMMOD);
	$tabla = TABLA_SERVICIOS_CODIGOS;

	$sql = "SELECT servicio FROM {$tabla} WHERE nro_servicio = '$nro_servicio'";
	$consulta = mysqli_query($conexion, $sql);

	return mysqli_fetch_assoc($consulta)['servicio'];
}
