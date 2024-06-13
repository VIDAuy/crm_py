<?php
include '../../configuraciones.php';

$cedula = $_GET['cedula'];
$tabla["data"] = [];


$datos = obtener_datos($cedula);

while ($row = mysqli_fetch_assoc($datos)) {

	$id = $row['id'];
	$observacion = $row['obs_socio'];

	$tabla["data"][] = [
		"id" 	   	  => $id,
		"observacion" => $observacion,
	];
}



echo json_encode($tabla);


function obtener_datos($cedula)
{
	$conexion = connection(DB_COORDINA_PARAGUAY);
	$tabla = TABLA_PEDIDO_ACOMP;

	$sql = "SELECT id, obs_socio FROM {$tabla} WHERE id_socio = '$cedula' ORDER BY id DESC";

	return mysqli_query($conexion, $sql);
}
