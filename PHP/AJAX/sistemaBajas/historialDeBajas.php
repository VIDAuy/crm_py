<?php
include '../../configuraciones.php';
$conexion = connection(DB);
$tabla = TABLA_BAJAS;


$q = "SELECT id, filial_solicitud, nombre_socio, cedula_socio, motivo_baja, fecha_ingreso_baja, telefono_contacto, celular_contacto, estado FROM {$tabla}";
$r = mysqli_query($conexion, $q);
mysqli_close($conexion);


$conexion = connection(DB_ABMMOD);

while ($row = mysqli_fetch_assoc($r)) {
	$row['fecha_ingreso_baja'] = new DateTime($row['fecha_ingreso_baja']);
	$row['fecha_ingreso_baja'] = $row['fecha_ingreso_baja']->format('d/m/Y');
	$filial = $row['filial_solicitud'];
	$q = "SELECT filial
				FROM filiales_codigos
				WHERE nro_filial = $filial";
	$f2 = mysqli_fetch_assoc(mysqli_query($conexion, $q));
	$row['filial_solicitud'] = $f2['filial'];

	$cedula = $row['cedula_socio'];
	$q = "SELECT radio
				FROM padron_datos_socio
				WHERE cedula = '$cedula'";
	$f2 = mysqli_fetch_assoc(mysqli_query($conexion, $q));
	$row['radio'] = ($f2['radio'] != null)
		? $f2['radio']
		: ' - - ';
	$f[] = $row;
}

echo json_encode($f);
