<?php
include '../../configuraciones.php';

$sector = $_SESSION['id_py'];


if (isset($_POST['CI'])) {
	$cedula = $_POST['CI'];
	$id_registro = $_POST['idRegistro'];

	$dar_baja_alerta = dar_baja_alerta($cedula);
	if ($dar_baja_alerta == false) devolver_error("Ocurrieron errores al desactivar la alerta");

	// registra quien lee la alerta
	$registrar_historial_alerta = registrar_historial_alerta($id_registro, $sector);
	if ($registrar_historial_alerta == false) devolver_error("Ocurrieron errores al registrar en el historial de alertas");

	$response['error'] = false;
	$response['mensaje'] = "ok";
	echo json_encode($response);
} else {

	$tabla["data"] = [];

	$datos_registros = obtener_datos_registros($sector);
	if ($datos_registros == false) devolver_error("Ocurrieron errores al obtener las alertas");

	while ($row = mysqli_fetch_assoc($datos_registros)) {
		$id = $row['id'];
		$sector = $row['sector'];
		$nombre = $row['nombre'];
		$telefono = $row['telefono'];
		$cedula = $row['cedula'];
		$btnAcciones = "<button class='btn btn-primary btn-sm' onclick='ver_alerta_pendiente(`" . $cedula . "`, `" . $id . "`)'>Ver más</button>";

		$tabla["data"][] = [
			'idRegistro' => $row['id'],
			'sector'	 => $row['sector'],
			'nombre'	 => $row['nombre'],
			'telefono'	 => corregirTelefono($row['telefono']),
			'cedula'	 => $row['cedula'],
			'acciones'   => $btnAcciones,
		];
	}
	echo json_encode($tabla);
}




function dar_baja_alerta($cedula)
{
	$conexion = connection(DB);
	$tabla = TABLA_REGISTROS_PY;

	$sql = "UPDATE {$tabla} SET activo='0' WHERE cedula='$cedula'";
	$consulta = mysqli_query($conexion, $sql);

	return $consulta;
}


function registrar_historial_alerta($id_registro, $sector)
{
	$conexion = connection(DB);
	$tabla = TABLA_HISTORICO_ALERTA;

	$sql = "INSERT INTO {$tabla}(id, id_registro, sector) VALUES(null, '$id_registro', '$sector')";
	$consulta = mysqli_query($conexion, $sql);

	return $consulta;
}


function obtener_datos_registros($sector)
{
	$conexion = connection(DB);
	$tabla = TABLA_REGISTROS_PY;

	$sql = "SELECT 
			 id,
			 sector, 
			 nombre, 
			 telefono, 
			 cedula 
			FROM 
			 {$tabla} 
			WHERE 
			 activo = 1 AND 
			 envioSector = $sector AND 
			 cedula != ''";
	$consulta = mysqli_query($conexion, $sql);

	return $consulta;
}
