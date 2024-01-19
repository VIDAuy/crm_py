<?php
include '../configuraciones.php';


$cedula = $_REQUEST['cedula'];

$tabla["data"] = [];

$consulta = consulta_general($cedula);


while ($resultado = mysqli_fetch_array($consulta)) {

	$id = $resultado['id'];
	$fecha = date('d/m/Y H:i:s', strtotime($resultado['fecha_registro']));
	$sector = $resultado['sector'];
	$observacion = $resultado['observaciones'];


	if (strlen($observacion) > 20) {
		$br  = array("<br />", "<br>", "<br/>");
		$observacion = str_ireplace($br, "\r\n", $observacion);

		$observacion_sin_editar = $observacion;
		$observacion = substr($observacion, 0, 20) . " ...<button class='btn btn-link' onclick='verMasTabla(`" . $observacion_sin_editar . "`);'>Ver Más</button>";
		$observacion = mb_convert_encoding($observacion, 'UTF-8', 'UTF-8');
	}


	$tabla["data"][] = [
		"id" => $id,
		"fecha" => $fecha,
		"sector" => $sector,
		"observacion" => $observacion,
	];
}



echo json_encode($tabla);






function consulta_general($cedula)
{
	$conexion = connection(DB);

	$consulta = mysqli_query($conexion, "SELECT 
	id,
    fecha_registro,
	sector,
	observaciones
    FROM registros_funcionarios
    WHERE
	cedula = '$cedula'");

	return $consulta;
}
