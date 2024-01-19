<?php
include '../../configuraciones.php';


$cedula = $_GET['cedula'];
$opcion = $_GET['opcion'];


$consulta = consulta($cedula, $opcion);


if ($opcion == 'tabla') {

	$response["data"] = [];

	while ($resultado = mysqli_fetch_array($consulta)) {
		$mes 	 = $resultado['MES'];
		$ano 	 = $resultado['ANO'];
		$importe = $resultado['IMPORTE'];
		$cobrado = $resultado['COBRADO_EN_EL_MES'];

		$response["data"][] = [
			'mes' 		=> $mes,
			'anho' 		=> $ano,
			'importe' 	=> $importe,
			'cobrado' 	=> $cobrado
		];
	}
} else {

	if ($consulta === false) {
		$response['error'] = true;
		$response['mensaje'] = "Esta persona no tiene registros en cobranzas.";
		die(json_encode($response));
	}

	$response['error'] = false;
}


echo json_encode($response);



function consulta($cedula, $opcion)
{
	$conexion = connection(DB_COBRA);

	$consulta = mysqli_query($conexion, "SELECT MES, ANO, IMPORTE, COBRADO_EN_EL_MES FROM cobrado WHERE CEDULA = $cedula ORDER BY ANO DESC");

	if ($opcion == 'tabla') {
		$respuesta = $consulta;
	} else {
		$respuesta = mysqli_fetch_array($consulta) != "" ? true : false;
	}
	return $respuesta;
}
