<?php
include '../../configuraciones.php';

$tabla["data"] = [];


$datos = obtener_registros();

while ($row = mysqli_fetch_assoc($datos)) {

	$nombre = $row['nombre'];
	$cedula = $row['cedula'];
	$telefono = $row['telefono'] == 0 ? "" : $row['telefono'];
	$socio = $row['socio'] == 1 ? "Sí" : "No";
	$baja = $row['baja'] == 1 ? "Sí" : "No";
	$sector = $row['sector'];
	$fecha_registro = date('d/m/Y H:i:s', strtotime($row['fecha_registro']));
	$observacion = $row['observaciones'];
	$observacion_acotada = mb_substr($observacion, 0, 40);

	$tabla["data"][] = [
		'fecha_registro' => $fecha_registro,
		'cedula'         => $cedula,
		'sector'         => $sector,
		'socio'          => $socio,
		'baja'           => $baja,
		'observaciones'  => strlen($observacion) > 29 ? "- {$observacion_acotada}. (...)" : "- {$observacion}",
		'mas_info'       => "<button class='btn btn-primary btn-sm' onclick='abrir_modal_mas_info(`" . $nombre . "`, `" . $cedula . "`, `" . $telefono . "`, `" . $socio . "`, `" . $baja . "`, `" . $sector . "`, `" . $fecha_registro . "`, `" . $observacion . "`);'>Más Info</button>",
	];
}


echo json_encode($tabla);




function obtener_registros()
{
	$conexion = connection(DB);
	$tabla = TABLA_REGISTROS_PY;

	$sql = "SELECT
			id,
			cedula,
			nombre,
			telefono,
			fecha_registro,
			sector,
			observaciones,
			socio,
			baja
		FROM
			{$tabla}";

	return mysqli_query($conexion, $sql);
}
