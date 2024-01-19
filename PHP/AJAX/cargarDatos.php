<?php
include '../configuraciones.php';

$tabla = TABLA_REGISTROS_PY;
$tabla1 = TABLA_PADRON_DATOS_SOCIO;

$sucursales_inspira = ['1372', '1373', '1374', '1375', '1376'];
$mostrar_inspira = $_SESSION['id_py'] == 2 || $_SESSION['id_py'] == 34 ? true : false;

$cedula = $_GET['CI'];


$r = obtener_datos($cedula);
$f = mysqli_fetch_assoc($r);

$f['fecha_afiliacion'] = (new DateTime($f['fecha_afiliacion']))->format('d/m/Y');

if (mysqli_num_rows($r) === 0) {
	mysqli_close($conexion);


	$conexion = connection(DB);

	$q = "SELECT nombre, telefono FROM {$tabla} WHERE cedula = '$cedula'";
	$r = mysqli_query($conexion, $q);
	if (mysqli_num_rows($r)) {
		$f2 = mysqli_fetch_assoc($r);

		$f = [
			'noSocioConRegistros' 	=> true,
			'mensaje' 				=> "La cédula ingresada no pertenece a un socio pero ya tiene registros.\nSe le mostrará un formulario diferente.",
			'nombre' 				=> $f2["nombre"],
			'telefono' 				=> corregirTelefono($f2['telefono']),
		];
	} else {
		$f = [
			'noSocio' 	=> true,
			'mensaje' 	=> "La cédula ingresada no pertenece a un socio.\nPor favor rellene los campos que se le solicitará a continuación."
		];
	}
} else {
	$inspira = in_array($f['sucursal'], $sucursales_inspira) ? 'SI' : 'NO';
	$f['inspira'] = $inspira;

	$f2 = mysqli_fetch_assoc($r);
	$q = "SELECT abmactual, abm FROM {$tabla1} WHERE cedula = '$cedula' AND abmactual = 1 AND abm = 'BAJA'";
	$r = mysqli_query($conexion, $q);
	if (mysqli_num_rows($r) == 1) {


		$conexion = connection(DB);

		$q = "SELECT nombre, telefono FROM {$tabla} WHERE cedula = '$cedula'";
		$r = mysqli_query($conexion, $q);
		$f2 = mysqli_fetch_assoc($r);

		$f = [
			'bajaProcesada'	=> true,
			'nombre' 		=> $f2['nombre'],
			'telefono' 		=> corregirTelefono($f2['telefono']),
			'mensaje'		=> "La cédula ingresada no pertenece a un socio pero ya tiene registros.\nSe le mostrará un formulario diferente."
		];
	} else $f['tel'] = corregirTelefono($f['tel']);
}
$f['mostrar_inspira'] = $mostrar_inspira;


echo json_encode($f);





function obtener_datos($cedula)
{
	$conexion = connection(DB_AFILIACION_PARAGUAY);
	$tabla1 = TABLA_PADRON_DATOS_SOCIO;
	$tabla2 = TABLA_PADRON_PRODUCTO_SOCIO;

	$sql = "SELECT
		pds.nombre,
		pds.tel,
		pds.cedula,
		pps.fecha_afiliacion,
		pds.sucursal,
		pds.radio
	FROM 
		{$tabla1} pds
		INNER JOIN {$tabla2} pps USING(cedula)
	WHERE
		cedula = '$cedula'
	ORDER BY pds.id DESC
	LIMIT 1";

	return mysqli_query($conexion, $sql);
}
