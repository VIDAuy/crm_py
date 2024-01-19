<?php
include '../../configuraciones.php';


$cedula = $_GET['cedula'];
$opcion = $_GET['opcion'];


$consulta = consulta($cedula, $opcion);


if ($opcion == 'tabla') {

	$tabla["data"] = [];


	while ($resultado = mysqli_fetch_array($consulta)) {
		$observacion 	= $resultado['obs_socio'];
		$id 		= $resultado['id'];

		$response["data"][] = [
			'observacion' 	=> $observacion,
			'id' 			=> $id
		];
	}
} else {

	if ($consulta === false) {
		$response['error'] = true;
		$response['mensaje'] = "El usuario no tiene servicios en coordinación.";
		die(json_encode($response));
	}

	$response['error'] = false;
}


echo json_encode($response);




function consulta($cedula, $opcion)
{
	$conexion = connection(DB_COORDINACION);

	$consulta = mysqli_query($conexion, "SELECT obs_socio, id FROM pedido_acomp WHERE id_socio = $cedula ORDER BY id DESC");

	if ($opcion == 'tabla') {
		$respuesta = $consulta;
	} else {
		$respuesta = mysqli_fetch_array($consulta) != "" ? true : false;
	}
	return $respuesta;
}
