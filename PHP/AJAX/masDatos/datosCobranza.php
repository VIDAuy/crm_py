<?php
include '../../configuraciones.php';

$cedula = $_GET['cedula'];
$tabla["data"] = [];


$datos = obtener_datos($cedula);

while ($row = mysqli_fetch_assoc($datos)) {

	$mes = $row['mes'];
	$mes = formatiar_mes($mes);

	$tabla["data"][] = [
		'mes' 		=> $mes,
		'anho' 		=> $row['ano'],
		'importe' 	=> $row['importe'],
		'cobrado' 	=> $row['cobrado_en_el_mes']
	];
}

echo json_encode($tabla);




function obtener_datos($cedula)
{
	$conexion = connection(DB_AFILIACION_PARAGUAY);
	$tabla = TABLA_COBRANZAS;

	$sql = "SELECT mes, ano, importe, cobrado_en_el_mes FROM {$tabla} WHERE cedula = '$cedula' ORDER BY ano DESC";

	return mysqli_query($conexion, $sql);
}



function formatiar_mes($mes)
{
	if (strlen($mes) == 1) {
		$mes = "0" . $mes;
	}

	return $mes;
}
