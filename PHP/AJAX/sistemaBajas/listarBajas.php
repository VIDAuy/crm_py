<?php
include '../../configuraciones.php';


if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$f = obtener_datos_bajas($id);
	$f['telefono_contacto'] = !is_null($f['telefono_contacto']) ? $f['telefono_contacto'] : "";
	$f['celular_contacto'] = !is_null($f['celular_contacto']) ? $f['celular_contacto'] : "";

	if ($f['filial_solicitud'] == $f['filial_socio']) {
		$nroFilial = $f['filial_solicitud'];
		$f2 = obtener_filiales_codigos($nroFilial);
		$f['filial_solicitud'] = $f2 != false ? $f2['filial'] : "";
		$f['filial_socio'] 	   = $f2 != false ? $f2['filial'] : "";
	} else {
		$nroFilial = $f['filial_solicitud'];
		$f2 = obtener_filiales_codigos($nroFilial);
		$f['filial_solicitud'] = $f2 != false ? $f2['filial'] : "";
		$nroFilial = $f['filial_socio'];
		$f2 = obtener_filiales_codigos($nroFilial);
		$f['filial_socio'] = $f2 != false ? $f2['filial'] : "";
	}

	$f['fecha_ingreso_baja'] = date("d/m/Y", strtotime($f['fecha_ingreso_baja']));
	$respuesta = $f;
} else {

	/* 
	$usuario == 1 //Si la baja fue revisada o realizada por un supervisor
	$usuario == 2 //Si la baja no fue revisada o realizada por un supervisor
	*/
	$condicion = "";
	if (isset($_REQUEST['usuario'])) {
		$condicion = $_REQUEST['usuario'] == 1 ?
			"AND fecha_gestion_supervisor IS NOT NULL AND fecha_fin_gestion IS NULL AND estado_supervisor = 'En Gestión'" :
			"AND fecha_gestion_supervisor IS NULL AND fecha_fin_gestion IS NULL AND estado_supervisor != 'En Gestión'";
	}

	$obtener_bajas = obtener_bajas($condicion);
	if (mysqli_num_rows($obtener_bajas) <= 0) devolver_error("Actualmente no hay bajas para gestionar.");


	while ($row = mysqli_fetch_assoc($obtener_bajas)) {
		$id 		   = $row['id'];
		$fecha 		   = date("d/m/Y", strtotime($row['fecha_ingreso_baja']));
		$observaciones = $row['observaciones'];
		$nombreS 	   = $row['nombre_socio'];
		$cedula 	   = $row['cedula_socio'];
		$motivo 	   = $row['motivo_baja'];
		$fechaGestion  = !is_null($row['fecha_inicio_gestion']) ? 'Sí' : 'No';
		$telefono      = $row['telefono_contacto'] . ' ' . $row['celular_contacto'];
		$filial        = obtener_filiales_codigos($row['filial_solicitud']);
		$filial        = $filial != false ? $filial['filial'] : "";


		$respuesta[] = [
			'id' 				=> $id,
			'fecha' 			=> $fecha,
			'observaciones'		=> $observaciones,
			'nombre' 			=> $nombreS,
			'cedula' 			=> $cedula,
			'motivo' 			=> $motivo,
			'fechaGestion' 		=> $fechaGestion,
			'telefono'			=> $telefono,
			'filial_solicitud'	=> $filial
		];
	}
}

echo json_encode($respuesta);




function obtener_datos_bajas($id)
{
	$conexion = connection(DB);
	$tabla = TABLA_BAJAS;

	$sql = "SELECT 
			 id, 
			 idrelacion, 
			 fecha_ingreso_baja, 
			 filial_solicitud, 
			 nombre_funcionario, 
			 observaciones, 
			 nombre_socio, 
			 cedula_socio, 
			 filial_socio, 
			 servicio_contratado, 
			 horas_contratadas, 
			 importe, 
			 motivo_baja, 
			 nombre_contacto, 
			 apellido_contacto, 
			 telefono_contacto, 
			 celular_contacto, 
			 fecha_inicio_gestion, 
			 estado, 
			 nombre_funcionario_final, 
			 motivo_no_otorgada, 
			 observacion_final 
			FROM 
			 {$tabla} 
			WHERE 
			 id = $id";
	$consulta = mysqli_query($conexion, $sql);
	$resultados = mysqli_fetch_assoc($consulta);

	return $resultados;
}


function obtener_filiales_codigos($nroFilial)
{
	$conexion = connection(DB_ABMMOD);
	$tabla = TABLA_FILIALES_CODIGOS;

	$sql = "SELECT filial FROM {$tabla} WHERE nro_filial = '$nroFilial'";
	$consulta = mysqli_query($conexion, $sql);
	$resultados = mysqli_fetch_row($consulta) >= 0 ? mysqli_fetch_assoc($consulta) : false;

	return $resultados;
}


function obtener_bajas($condicion)
{
	$conexion = connection(DB);
	$tabla = TABLA_BAJAS;

	if (isset($_GET['where']) && ($_GET['where'] == 'En Gestión' || $_GET['where'] == 'Pendiente')) {
		$where = $_GET['where'];
		$sql = "SELECT id, filial_solicitud, fecha_ingreso_baja, observaciones, nombre_socio, cedula_socio, motivo_baja, fecha_inicio_gestion, telefono_contacto, celular_contacto FROM {$tabla} WHERE estado = '$where' $condicion AND estado_supervisor != 'Continua' ORDER BY fecha_ingreso_baja DESC";
	} else {
		$sql = "SELECT id, filial_solicitud, fecha_ingreso_baja, observaciones, nombre_socio, cedula_socio, motivo_baja, fecha_inicio_gestion, telefono_contacto, celular_contacto FROM {$tabla} WHERE activo = 1 $condicion AND estado_supervisor != 'Continua' ORDER BY fecha_ingreso_baja DESC";
	}

	$consulta = mysqli_query($conexion, $sql);

	return $consulta;
}
