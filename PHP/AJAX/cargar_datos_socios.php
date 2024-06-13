<?php
include '../configuraciones.php';

$sucursales_inspira = ['1372', '1373', '1374', '1375', '1376'];
$mostrar_inspira = $_SESSION['id_py'] == 2 || $_SESSION['id_py'] == 34 ? true : false;
$cedula = $_GET['CI'];


$r = obtener_datos_padron($cedula);
$f = mysqli_fetch_assoc($r);

if (mysqli_num_rows($r) > 0) {
	$f['fecha_afiliacion'] = (new DateTime($f['fecha_afiliacion']))->format('d/m/Y');
}


if (mysqli_num_rows($r) === 0) {
	$r = obtener_datos_registros($cedula);
	if (mysqli_num_rows($r)) {
		$f2 = mysqli_fetch_assoc($r);
		$f = [
			'noSocioConRegistros' => true,
			'mensaje' 			  => "La cédula ingresada no pertenece a un socio pero ya tiene registros.\nSe le mostrará un formulario diferente.",
			'nombre' 			  => $f2["nombre"],
			'telefono' 			  => $f2['telefono'] == "" || $f2['telefono'] == 0 ? 0 : $f2['telefono'],
		];
	} else {
		$f = [
			'noSocio' => true,
			'mensaje' => "De ser así, por favor, rellene los campos que se le solicitará a continuación."
		];
	}
} else {
	$inspira = in_array($f['sucursal'], $sucursales_inspira) ? "<span class='text-success'>Si</span>" : "<span class='text-danger'>No</span>";
	$f['inspira'] = $inspira;

	$f2 = mysqli_fetch_assoc($r);

	$r = comprobar_baja_padron($cedula);
	if (mysqli_num_rows($r) == 1) {

		$r = obtener_datos_registros($cedula);
		$f2 = mysqli_fetch_assoc($r);
		$f = [
			'bajaProcesada'	=> true,
			'nombre' 		=> $f2['nombre'],
			'telefono' 		=> $f2['telefono'] == "" || $f2['telefono'] == 0 ? 0 : $f2['telefono'],
			'mensaje'		=> "La cédula ingresada no pertenece a un socio pero ya tiene registros.\nSe le mostrará un formulario diferente."
		];
	} else {
		$f['tel'] = $f['tel'] == "" || $f['tel'] == 0 ? 0 : $f['tel'];
	}
}
$f['mostrar_inspira'] = $mostrar_inspira;

echo json_encode($f);




function obtener_datos_padron($cedula)
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
			 cedula = $cedula 
			ORDER BY pds.id DESC 
			LIMIT 1";

	$consulta = mysqli_query($conexion, $sql);

	return $consulta;
}


function comprobar_baja_padron($cedula)
{
	$conexion = connection(DB_AFILIACION_PARAGUAY);
	$tabla = TABLA_PADRON_DATOS_SOCIO;

	$sql = "SELECT abmactual, abm FROM {$tabla} WHERE cedula = '$cedula' AND abmactual = 1 AND abm = 'BAJA'";
	$consulta = mysqli_query($conexion, $sql);

	return $consulta;
}


function obtener_datos_registros($cedula)
{
	$conexion = connection(DB);
	$tabla = TABLA_REGISTROS_PY;

	$sql = "SELECT nombre, telefono FROM {$tabla} WHERE cedula = '$cedula'";
	$consulta = mysqli_query($conexion, $sql);

	return $consulta;
}
