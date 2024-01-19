<?php
include '../../configuraciones.php';


$cedula = $_GET['cedula'];
$opcion = $_GET['opcion'];


$consulta = consulta($cedula, $opcion);


if ($opcion == 'tabla') {

	$response["data"] = [];

	while ($resultado = mysqli_fetch_array($consulta)) {
		$nroServicio 	= $resultado['nro_servicio'];
		$servicio 		= $resultado['servicio'];
		$horas 			= $resultado['hora'];
		$importe 		= $resultado['importe'];

		$response["data"][] = [
			'nroServicio' 	=> $nroServicio,
			'servicio' 		=> $servicio,
			'horas' 		=> $horas,
			'importe' 		=> $importe
		];
	}
} else {

	if ($consulta === false) {
		$response['error'] = true;
		$response['mensaje'] = "Ha ocurrido un error al tratar de traer los registros.";
		die(json_encode($response));
	}

	$response['error'] = false;
}


echo json_encode($response);



function consulta($cedula, $opcion)
{
	$conexion = connection(DB_ABMMOD);

	$consulta = mysqli_query($conexion, "SELECT pps.servicio AS nro_servicio, sc.servicio, pps.hora, pps.importe FROM padron_producto_socio AS pps INNER JOIN servicios_codigos AS sc ON pps.servicio = sc.nro_servicio WHERE cedula = $cedula");

	if ($opcion == 'tabla') {
		$respuesta = $consulta;
	} else {
		$respuesta = mysqli_fetch_array($consulta) != "" ? true : false;
	}
	return $respuesta;
}
