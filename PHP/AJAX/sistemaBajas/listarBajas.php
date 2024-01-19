<?php
include '../../configuraciones.php';
$conexion = connection(DB);
$tabla = TABLA_BAJAS;


if (isset($_GET['id'])) {
	$id = $_GET['id'];
	$q = "SELECT id, idrelacion, fecha_ingreso_baja, filial_solicitud, nombre_funcionario, observaciones, nombre_socio, cedula_socio, filial_socio, servicio_contratado, horas_contratadas, importe, motivo_baja, nombre_contacto, apellido_contacto, telefono_contacto, celular_contacto, fecha_inicio_gestion, estado, nombre_funcionario_final, motivo_no_otorgada, observacion_final FROM {$tabla} WHERE id = $id";

	$r = mysqli_query($conexion, $q);
	$f = mysqli_fetch_assoc($r);
	if ($f['telefono_contacto'] == 'null') $f['telefono_contacto'] = '';
	if ($f['celular_contacto'] == 'null') $f['celular_contacto'] = '';
	mysqli_close($conexion);
	if ($f['filial_solicitud'] == $f['filial_socio']) {
		$conexion = connection(DB_ABMMOD);
		$nroFilial = $f['filial_solicitud'];
		$q2 = "SELECT filial 
					FROM filiales_codigos 
					WHERE nro_filial = '$nroFilial'";
		$r2 = mysqli_query($conexion, $q2);
		$f2 = mysqli_fetch_assoc($r2);
		$f['filial_solicitud'] 	= $f2['filial'];
		$f['filial_socio'] 		= $f2['filial'];
		mysqli_close($conexion);
	} else {
		$conexion = connection(DB_ABMMOD);
		$nroFilial = $f['filial_solicitud'];
		$q2 = "SELECT filial 
					FROM filiales_codigos 
					WHERE nro_filial = '$nroFilial'";
		$r2 = mysqli_query($conexion, $q2);
		$f2 = mysqli_fetch_assoc($r2);
		$f['filial_solicitud'] 	= $f2['filial'];
		$nroFilial = $f['filial_socio'];
		$q2 = "SELECT filial 
					FROM filiales_codigos 
					WHERE nro_filial = '$nroFilial'";
		$r2 = mysqli_query($conexion, $q2);
		$f2 = mysqli_fetch_assoc($r2);
		$f['filial_socio'] 	= $f2['filial'];
		mysqli_close($conexion);
	}

	$f['fecha_ingreso_baja'] = new DateTime($f['fecha_ingreso_baja']);
	$f['fecha_ingreso_baja'] = date_format($f['fecha_ingreso_baja'], 'd/m/Y');
	$respuesta = $f;
} else {

	$usuario = $_GET['usuario'];
	if ($usuario == 1) { //SI LA BAJA FUE REVISADA O REALIZADA POR UN SUPERVISOR
		$condicion = "AND fecha_gestion_supervisor IS NOT NULL AND fecha_fin_gestion IS NULL AND estado_supervisor = 'En Gestión'";
	} else if ($usuario == 2) { //SI LA BAJA NO FUE REVISADA O REALIZADA POR UN SUPERVISOR
		$condicion = "AND fecha_gestion_supervisor IS NULL AND fecha_fin_gestion IS NULL AND estado_supervisor != 'En Gestión'";
	}

	if (isset($_GET['where']) && ($_GET['where'] == 'En Gestión' || $_GET['where'] == 'Pendiente')) {
		$where = $_GET['where'];
		$q = "SELECT id, filial_solicitud, fecha_ingreso_baja, observaciones, nombre_socio, cedula_socio, motivo_baja, fecha_inicio_gestion, telefono_contacto, celular_contacto FROM {$tabla} WHERE estado = '$where' $condicion ORDER BY fecha_ingreso_baja DESC";
	} else {
		$q = "SELECT id, filial_solicitud, fecha_ingreso_baja, observaciones, nombre_socio, cedula_socio, motivo_baja, fecha_inicio_gestion, telefono_contacto, celular_contacto FROM {$tabla} WHERE activo = 1 $condicion ORDER BY fecha_ingreso_baja DESC";
	}

	$r = mysqli_query($conexion, $q);
	mysqli_close($conexion);
	$conexion = connection(DB_ABMMOD);
	if (mysqli_num_rows($r) != 0) {
		while ($f = mysqli_fetch_assoc($r)) {
			$id 				= $f['id'];
			$fecha 				= new DateTime($f['fecha_ingreso_baja']);
			$fecha 				= date_format($fecha, 'd/m/Y');
			$observaciones 		= $f['observaciones'];
			$nombreS 			= $f['nombre_socio'];
			$cedula 			= $f['cedula_socio'];
			$motivo 			= $f['motivo_baja'];
			$fechaGestion 		= ($f['fecha_inicio_gestion'] != null)
				? 'Sí'
				: 'No';
			$telefono = $f['telefono_contacto'] . ' ' . $f['celular_contacto'];
			$filial 			= $f['filial_solicitud'];
			$q = "SELECT filial
						FROM filiales_codigos
						WHERE nro_filial = $filial";
			$f2 = mysqli_fetch_assoc(mysqli_query($conexion, $q));
			$filial 			= $f2['filial'];
			$respuesta[] = array(
				'id' 				=> $id,
				'fecha' 			=> $fecha,
				'observaciones'		=> $observaciones,
				'nombre' 			=> $nombreS,
				'cedula' 			=> $cedula,
				'motivo' 			=> $motivo,
				'fechaGestion' 		=> $fechaGestion,
				'telefono'			=> $telefono,
				'filial_solicitud'	=> $filial
			);
		}
	} else {
		$respuesta = array(
			'error' => true,
			'mensaje' => 'Actualmente no hay bajas para gestionar.'
		);
	}
}

echo json_encode($respuesta);
