<?php
include '../../configuraciones.php';

$cedula = $_GET['cedula'];
$tabla["data"] = [];


$datos = obtener_datos($cedula);

while ($row = mysqli_fetch_assoc($datos)) {

	$id = $row["id"];
	$fecha_inicio = date("d/m/Y", strtotime($row["fecha_ini"]));
	$fecha_fin = date("d/m/Y", strtotime($row["fechafin"]));
	$horas_x_dia = $row["hs_x_dia"];
	$hora_inicio = $row["hs_ini"];
	$hora_fin = $row["hrfin"];

	$tabla["data"][] = [
		'id' => $id,
		'fecha_inicio' => $fecha_inicio,
		'fecha_fin'    => $fecha_fin,
		'horas_x_dia'  => $horas_x_dia,
		'hora_inicio'  => formatiar_hora($hora_inicio),
		'hora_fin'     => $hora_fin,
	];
}

echo json_encode($tabla);




function obtener_datos($cedula)
{
	$conexion = connection(DB_COORDINA_PARAGUAY);
	$tabla = TABLA_PEDIDO_ACOMP;

	$sql = "SELECT id, fecha_ini, fechafin, hs_x_dia, hs_ini, hrfin FROM {$tabla} WHERE id_socio = '$cedula'";

	return mysqli_query($conexion, $sql);
}


function formatiar_hora($hora)
{
	if (strlen($hora) == 3) {
		$hora = "0" . $hora;
	}

	return date("H:i:s", strtotime($hora));
}