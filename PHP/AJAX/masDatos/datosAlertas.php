<?php
include '../../configuraciones.php';
$conexion = connection(DB);
$tabla = TABLA_HISTORICO_ALERTA;
$tabla1 = TABLA_REGISTROS_PY;


session_start();
$sector = $_SESSION['id_py'];

if (isset($_POST['CI'])) {
	$cedula = $_POST['CI'];
	$id_registro = $_POST['idRegistro'];

	$q = "UPDATE {$tabla1} SET activo='0' WHERE cedula='$cedula'";
	$r = mysqli_query($conexion, $q);
	$jsondata = array();
	$jsondata['message'] = "ok";

	// registra quien lee la alerta
	$query = "INSERT INTO {$tabla}(id, id_registro, sector) VALUES(null, $id_registro, '$sector')";
	mysqli_query($conexion, $query);

	echo json_encode($jsondata);
	exit();
} else {
	$q 	= "SELECT sector, nombre, telefono, cedula, id FROM {$tabla1} WHERE activo=1 AND envioSector = $sector AND cedula != ''";
	$r 	= mysqli_query($conexion, $q);
	while ($row = mysqli_fetch_array($r)) {
		$f[] = array(
			'idRegistro' => $row['id'],
			'sector'	=> $row['sector'],
			'nombre'	=> $row['nombre'],
			'telefono'	=> corregirTelefono($row['telefono']),
			'cedula'	=> $row['cedula']
		);
	}
	echo json_encode($f);
}

function corregirTelefono($var)
{
	// CORRECCIÓN Y ASIGNACIÓN DE TELÉFONO

	//EN CASO DE QUE EL TELÉFONO EMPIECE CON '0' Y UN ESPACIO LOS QUITA DE LA VARIABLE
	if (mb_substr($var, 0, 2) == '0 ') {
		$var = mb_substr($var, 2, 20);
	}

	//REEMPLAZA TODOS LOS ESPACIOS QUE TENGA LA VARIABLE
	$var = str_replace(' ', '', $var);

	if ($var[0] == 9) {
		$var = 0 . $var;
	}

	//EN CASO DE QUE EL NÚMERO EMPIECE CON 09 Y TENGA MÁS DE 8 CARACTÉRES SE LE ASIGNA ESE VALOR A LA VARIABLE CELULAR
	if (mb_substr($var, 0, 2) == '09' && strlen($var) > 8) {
		if (strlen($var) == 10) {
			$celularFuncion = mb_substr($var, 0, 10);
		} else {
			$celularFuncion = mb_substr($var, 0, 9);
		}
	}

	///EN CASO DE QUE EL NÚMERO EMPIECE CON 2 O 4 Y TENGA MÁS DE 7 CARACTÉRES SE LE ASIGNA ESE VALOR A LA VARIABLE TELEFONO
	if (($var[0] == 2 || $var[0] == 4) && strlen($var) > 7) {
		$telefonoFuncion = mb_substr($var, 0, 8);
	}

	//SI EL LARGO DE LA VARIABLE ES IGUAL A 17 (LA SUMA DE LOS 9 CARACTERES DE UN TELÉFONO MÁS LOS 8 DE UN CELULAR) DIVIDE EL STRING
	if (strlen($var) == 17) {
		//EN CASO DE QUE CONTENGA LA SINTÁXIS DE TELÉFONO SE LE ASIGNA LA mb_substrING A LA VARIABLE TELEFONO
		if (isset($var[9]) && ($var[9] == 2 || $var[9] == 4) && mb_substr($var, 7, 10) != '09') {
			$telefonoFuncion = mb_substr($var, 10, 18);
		}
		//EN CASO DE QUE CONTENGA LA SINTÁXIS DE CELULAR SE LE ASIGNA LA mb_substrING A LA VARIABLE CELULAR
		if (isset($var[8]) && mb_substr($var, 8, 2) == '09') {
			$celularFuncion = mb_substr($var, 8, 18);
		}
	}

	//EN CASO DE QUE LA VARIABLE CELULAR NO SE HAYA DEFINIDO LE ASIGNA UN STRING VACÍO PARA NO GENERAR CONFLICTOS EN LA QUERY

	if (!isset($celularFuncion)) {
		$celularFuncion = null;
	}
	if (!isset($telefonoFuncion)) {
		$telefonoFuncion = null;
	}

	if ($telefonoFuncion != null && $celularFuncion != null) {
		$telFuncion = $telefonoFuncion;
		$telFuncion .= ' ';
		$telFuncion .= $celularFuncion;
	} else if ($telefonoFuncion != null && $celularFuncion == '') {
		$telFuncion = $telefonoFuncion;
	} else if ($telefonoFuncion == '' && $celularFuncion != null) {
		$telFuncion = $celularFuncion;
	} else {
		$telFuncion = '';
	}

	unset($telefonoFuncion);
	unset($celularFuncion);

	return $telFuncion;
}
