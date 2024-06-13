<?php
include '../../configuraciones.php';

$tabla["data"] = [];

$obtener_bajas = obtener_bajas();


while ($row = mysqli_fetch_assoc($obtener_bajas)) {
	$id = $row['id'];
	$cedula = $row['cedula_socio'];
	$nombre = $row['nombre_socio'];
	$telefono = $row['telefono_contacto'] != "" ? $row['telefono_contacto'] : "-";
	$celular = $row['celular_contacto'] != "" ? $row['celular_contacto'] : "-";

	$datos_padron = obtener_datos_padron($cedula);
	$radio = $datos_padron != false ? $datos_padron['radio'] : ' - - ';

	$motivo = $row['motivo_baja'];
	$fecha_ingreso_baja = date("d/m/Y", strtotime($row['fecha_ingreso_baja']));
	$estado = $row['estado'];
	$filial = $row['filial_solicitud'];
	$filial = obtener_filiales_codigos($filial)['filial'];
	$btnAcciones = "<button class='btn btn-sm btn-outline-primary' onclick='modalMasInfoHistorialDeBajas(`" . $id . "`);'>+ info</button>";


	$tabla["data"][] = [
		'id' => $id,
		'cedula_socio' => $cedula,
		'nombre_socio' => $nombre,
		'telefono_contacto' => $telefono,
		'celular_contacto' => $celular,
		'radio' => $radio,
		'motivo_baja' => $motivo,
		'fecha_ingreso_baja' => $fecha_ingreso_baja,
		'estado' => $estado,
		'filial_solicitud' => $filial,
		'acciones' => $btnAcciones,
	];
}

echo json_encode($tabla);




function obtener_bajas()
{
	$conexion = connection(DB);
	$tabla = TABLA_BAJAS;

	$sql = "SELECT 
			 id, 
			 filial_solicitud, 
			 nombre_socio, 
			 cedula_socio, 
			 motivo_baja, 
			 fecha_ingreso_baja, 
			 telefono_contacto, 
			 celular_contacto, 
			 estado 
			FROM 
			 {$tabla}";
	$consulta = mysqli_query($conexion, $sql);

	return $consulta;
}


function obtener_filiales_codigos($filial)
{
	$conexion = connection(DB_ABMMOD);
	$tabla = TABLA_FILIALES_CODIGOS;

	$sql = "SELECT filial FROM {$tabla} WHERE nro_filial = $filial";
	$consulta = mysqli_query($conexion, $sql);
	$resultados = mysqli_fetch_assoc($consulta);

	return $resultados;
}


function obtener_datos_padron($cedula)
{
	$conexion = connection(DB_ABMMOD);
	$tabla = TABLA_PADRON_DATOS_SOCIO;

	$sql = "SELECT radio FROM {$tabla} WHERE cedula = '$cedula'";
	$consulta = mysqli_query($conexion, $sql);
	$resultados = mysqli_fetch_row($consulta) > 0 ? mysqli_fetch_assoc($consulta) : false;

	return $resultados;
}
