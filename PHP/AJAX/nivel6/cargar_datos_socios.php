<?php
include '../../configuraciones.php';


$sucursales_inspira = ['1372', '1373', '1374', '1375', '1376'];
$mostrar_inspira = $_SESSION['id_py'] == 2 || $_SESSION['id_py'] == 34 ? true : false;
$cedula = $_GET['CI'];


$consulta = consulta_general($cedula);

//die(var_dump($consulta));


if ($consulta === false) {
	$response['error'] = true;
	die(json_encode($response));
}



$response['nombre'] = $consulta['nombre'];
$response['fecha_afiliacion'] = (new DateTime($consulta['fecha_afiliacion']))->format('d/m/Y');
$response['inspira'] = in_array($consulta['sucursal'], $sucursales_inspira) ? 'SI' : 'NO';
$response['tel'] = corregirTelefono($consulta['tel']);
$response['mostrar_inspira'] = $mostrar_inspira;
$response['radio'] = $consulta['radio'];
$response['sucursal'] = $consulta['sucursal'];


echo json_encode($response);







function consulta_general($cedula)
{
	$conexion = connection(DB_ABMMOD);

	$consulta = mysqli_query($conexion, "SELECT pds.nombre, pds.tel, pds.cedula, pps.fecha_afiliacion, pds.sucursal, pds.radio FROM padron_datos_socio AS pds INNER JOIN padron_producto_socio AS pps USING(cedula) WHERE cedula = $cedula ORDER BY pds.id DESC LIMIT 1");
	$resultado = mysqli_fetch_assoc($consulta);

	return is_array($resultado) ? $resultado : false;
	mysqli_close($conexion);
}

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
	if (($var[0] == 2 || $var[0] == 4) && strlen($var) < 8) $telefonoFuncion = $var;

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
