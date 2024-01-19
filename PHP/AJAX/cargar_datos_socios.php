<?php
include '../configuraciones.php';

$tabla = TABLA_REGISTROS_PY;
$tabla1 = TABLA_PADRON_DATOS_SOCIO;
$tabla2 = TABLA_PADRON_PRODUCTO_SOCIO;


$sucursales_inspira = ['1372', '1373', '1374', '1375', '1376'];
$mostrar_inspira = $_SESSION['id_py'] == 2 || $_SESSION['id_py'] == 34 ? true : false;

$conexion = connection(DB_AFILIACION_PARAGUAY);
$cedula 	= $_GET['CI'];
$q = "SELECT pds.nombre, pds.tel, pds.cedula, pps.fecha_afiliacion, pds.sucursal, pds.radio
			FROM {$tabla1}
				AS pds
			INNER JOIN {$tabla2}
				AS pps
				USING(cedula)
			WHERE cedula = $cedula ORDER BY pds.id DESC LIMIT 1";

$r = mysqli_query($conexion, $q);
$f = mysqli_fetch_assoc($r);

$f['fecha_afiliacion'] = (new DateTime($f['fecha_afiliacion']))->format('d/m/Y');

if (mysqli_num_rows($r) === 0) {
	mysqli_close($conexion);

	$conexion = connection(DB);

	$q = "SELECT nombre, telefono
				FROM {$tabla}
					WHERE cedula = '$cedula'";

	$r = mysqli_query($conexion, $q);
	if (mysqli_num_rows($r)) {
		$f2 = mysqli_fetch_assoc($r);
		$f =
			[
				'noSocioConRegistros' 	=> true,
				'mensaje' 				=> "La cédula ingresada no pertenece a un socio pero ya tiene registros.\nSe le mostrará un formulario diferente.",
				'nombre' 				=> $f2["nombre"],
				'telefono' 				=> corregirTelefono($f2['telefono']),
			];
	} else {
		$f =
			[
				'noSocio' 	=> true,
				'mensaje' 	=> "De ser así, por favor, rellene los campos que se le solicitará a continuación o de lo contrario consulte un funcionario."
			];
	}
} else {
	$inspira = in_array($f['sucursal'], $sucursales_inspira) ? 'SI' : 'NO';
	$f['inspira'] = $inspira;

	$f2 = mysqli_fetch_assoc($r);
	$q = "SELECT abmactual, abm
				FROM {$tabla1}
				WHERE cedula = '$cedula'
					AND abmactual = 1
					AND abm = 'BAJA'";
	$r = mysqli_query($conexion, $q);
	if (mysqli_num_rows($r) == 1) {


		$conexion = connection(DB);

		$q = "SELECT nombre, telefono
					FROM {$tabla}
						WHERE cedula = '$cedula'";
		$r = mysqli_query($conexion, $q);
		$f2 = mysqli_fetch_assoc($r);
		$f =
			[
				'bajaProcesada'	=> true,
				'nombre' 		=> $f2['nombre'],
				'telefono' 		=> corregirTelefono($f2['telefono']),
				'mensaje'		=> "La cédula ingresada no pertenece a un socio pero ya tiene registros.\nSe le mostrará un formulario diferente."
			];
	} else $f['tel'] = corregirTelefono($f['tel']);
}
$f['mostrar_inspira'] = $mostrar_inspira;

echo json_encode($f);

function corregirTelefono($var)
{

	// CORRECCIÓN Y ASIGNACIÓN DE TELÉFONO
	if (strlen($var) === 0)
		return 'Sin datos';

	//EN CASO DE QUE EL TELÉFONO EMPIECE CON '0' Y UN ESPACIO LOS QUITA DE LA VARIABLE
	if (mb_substr($var, 0, 2) == '0 ') $var = mb_substr($var, 2, 20);

	//REEMPLAZA TODOS LOS ESPACIOS QUE TENGA LA VARIABLE
	$var = str_replace(' ', '', $var);

	if ($var[0] == 9) $var = 0 . $var;

	//EN CASO DE QUE EL NÚMERO EMPIECE CON 09 Y TENGA MÁS DE 8 CARACTÉRES SE LE ASIGNA ESE VALOR A LA VARIABLE CELULAR
	if (mb_substr($var, 0, 2) == '09' && strlen($var) > 8) {
		if (strlen($var) == 10) {
			$celularFuncion = mb_substr($var, 0, 10);
		} else {
			$celularFuncion = mb_substr($var, 0, 9);
		}
	}

	///EN CASO DE QUE EL NÚMERO EMPIECE CON 2 O 4 Y TENGA MÁS DE 7 CARACTÉRES SE LE ASIGNA ESE VALOR A LA VARIABLE TELEFONO
	if (($var[0] == 2 || $var[0] == 4) && strlen($var) > 7) $telefonoFuncion = mb_substr($var, 0, 8);

	//SI EL LARGO DE LA VARIABLE ES IGUAL A 17 (LA SUMA DE LOS 9 CARACTERES DE UN TELÉFONO MÁS LOS 8 DE UN CELULAR) DIVIDE EL STRING
	if (strlen($var) == 17) {
		//EN CASO DE QUE CONTENGA LA SINTÁXIS DE TELÉFONO SE LE ASIGNA LA mb_substrING A LA VARIABLE TELEFONO
		if (isset($var[9]) && ($var[9] == 2 || $var[9] == 4) && mb_substr($var, 7, 9) != '09') $telefonoFuncion = mb_substr($var, 9, 18);
		//EN CASO DE QUE CONTENGA LA SINTÁXIS DE CELULAR SE LE ASIGNA LA mb_substrING A LA VARIABLE CELULAR
		if (isset($var[8]) && mb_substr($var, 8, 2) == '09') $celularFuncion = mb_substr($var, 8, 18);
	}

	//EN CASO DE QUE LA VARIABLE CELULAR NO SE HAYA DEFINIDO LE ASIGNA UN STRING VACÍO PARA NO GENERAR CONFLICTOS EN LA QUERY

	if (!isset($celularFuncion)) $celularFuncion = null;
	if (!isset($telefonoFuncion)) $telefonoFuncion = null;

	if ($telefonoFuncion != null && $celularFuncion != null) {
		$telFuncion = $telefonoFuncion;
		$telFuncion .= ' ';
		$telFuncion .= $celularFuncion;
	} else if ($telefonoFuncion != null && $celularFuncion == '') $telFuncion = $telefonoFuncion;
	else if ($telefonoFuncion == '' && $celularFuncion != null) $telFuncion = $celularFuncion;
	else $telFuncion = '';

	unset($telefonoFuncion);
	unset($celularFuncion);

	return $telFuncion;
}
