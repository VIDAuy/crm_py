<?php
include '../../configuraciones.php';
$cedula = $_GET['CI'];

$validarCedula = validarExisteCedula($cedula);

if ($validarCedula != 1) {
	$response['result'] = false;
	$response['cedula'] = true;
	$response['mensaje'] = "La cédula ingresada no pertenece a un socio actual de Vida.";
	die(json_encode($response));
}


$datos = obtener_datos($cedula);

if (mysqli_num_rows($datos) === 0) {
	$response['result'] = false;
	$response['cedula'] = true;
	$response['mensaje'] = "La cédula ingresada no pertenece a un socio actual de Vida.";
	die(json_encode($response));
}


$dato = mysqli_fetch_assoc($datos);
$idrelacion = $dato['idrelacion'];
$tel        = $dato['tel'];
$nombre 	= mb_convert_case($dato['nombre'], MB_CASE_TITLE, 'UTF-8');
$filial 	= $dato['sucursal'];
$importe 	= $dato['importe'];
$radio 		= $dato['radio'];


$verificar_baja_padron = comprobar_baja_padron($idrelacion);

if (mysqli_num_rows($verificar_baja_padron) != 0) {
	$response['result'] = false;
	$response['bajaGestionada'] = true;
	$response['mensaje'] = "La cédula ingresada ya fue dada de baja.";
	die(json_encode($response));
}



$verificar_baja_crm = comprobar_baja_crm($idrelacion);

if (mysqli_num_rows($verificar_baja_crm) != 0) {
	$response['result'] = false;
	$response['baja'] = true;
	$response['mensaje'] = "Ya se le está gestionando la baja al socio ingresado.";
	die(json_encode($response));
}



// CORRECCIÓN Y ASIGNACIÓN DE TELÉFONO
if (strlen($tel) > 7) {
	//EN CASO DE QUE EL TELÉFONO EMPIECE CON '0' Y UN ESPACIO LOS QUITA DE LA VARIABLE
	if (mb_substr($tel, 0, 2) == '0 ') {
		$tel = mb_substr($tel, 2, 20);
	}

	//REEMPLAZA TODOS LOS ESPACIOS QUE TENGA LA VARIABLE
	$tel = str_replace(' ', '', $tel);

	//EN CASO DE QUE EL NÚMERO EMPIECE CON 09 Y TENGA MÁS DE 8 CARACTÉRES SE LE ASIGNA ESE VALOR A LA VARIABLE CELULAR
	if (mb_substr($tel, 0, 2) == '09' && strlen($tel) > 8) {
		$celular = mb_substr($tel, 0, 9);
	}

	///EN CASO DE QUE EL NÚMERO EMPIECE CON 2 O 4 Y TENGA MÁS DE 7 CARACTÉRES SE LE ASIGNA ESE VALOR A LA VARIABLE TELEFONO
	if (($tel[0] == 2 || $tel[0] == 4) && strlen($tel) > 7) {
		$telefono = mb_substr($tel, 0, 8);
	}

	//SI EL LARGO DE LA VARIABLE ES IGUAL A 17 (LA SUMA DE LOS 9 CARACTERES DE UN TELÉFONO MÁS LOS 8 DE UN CELULAR) DIVIDE EL STRING
	if (strlen($tel) == 17) {
		//EN CASO DE QUE CONTENGA LA SINTÁXIS DE TELÉFONO SE LE ASIGNA LA mb_substrING A LA VARIABLE TELEFONO
		if (isset($tel[9]) && ($tel[9] == 2 || $tel[9] == 4) && mb_substr($tel, 7, 9) != '09') {
			$telefono = mb_substr($tel, 9, 18);
		}
		//EN CASO DE QUE CONTENGA LA SINTÁXIS DE CELULAR SE LE ASIGNA LA mb_substrING A LA VARIABLE CELULAR
		if (isset($tel[8]) && mb_substr($tel, 8, 2) == '09') {
			$celular = mb_substr($tel, 8, 18);
		}
	}
}

//EN CASO DE QUE LA VARIABLE CELULAR NO SE HAYA DEFINIDO LE ASIGNA UN STRING VACÍO PARA NO GENERAR CONFLICTOS EN LA QUERY
if (!isset($celular)) {
	$celular = '';
}
//EN CASO DE QUE LA VARIABLE TELEFONO NO SE HAYA DEFINIDO LE ASIGNA UN STRING VACÍO PARA NO GENERAR CONFLICTOS EN LA QUERY
if (!isset($telefono)) {
	$telefono = '';
}


$response['error'] = false;
$response['datos'] = [
	"result" 	 => true,
	"cedula" 	 => $cedula,
	"nombre" 	 => $nombre,
	"idrelacion" => $idrelacion,
	"filial" 	 => $filial,
	"celular" 	 => $celular,
	"telefono" 	 => $telefono,
	"importe" 	 => $importe,
	"radio" 	 => $radio
];




echo json_encode($response);




function validarExisteCedula($cedula)
{
	$conexion = connection(DB_AFILIACION_PARAGUAY);
	$tabla1 = TABLA_PADRON_DATOS_SOCIO;

	$sql = "SELECT
			cedula
		FROM
			{$tabla1}
		WHERE
			cedula = '$cedula'
		LIMIT 1";
	$consulta = mysqli_query($conexion, $sql);

	return mysqli_num_rows($consulta);
}

function obtener_datos($cedula)
{
	$conexion = connection(DB_AFILIACION_PARAGUAY);
	$tabla1 = TABLA_PADRON_DATOS_SOCIO;
	$tabla2 = TABLA_PADRON_PRODUCTO_SOCIO;

	$sql = "SELECT 
		pds.id, 
		pds.nombre, 
		pds.tel, 
		pds.radio, 
		pds.sucursal, 
		pps.idrelacion, 
		SUM(pps.importe) AS importe 
	FROM 
		{$tabla1} AS pds 
		LEFT JOIN {$tabla2} AS pps USING(idrelacion) 
	WHERE 
		pds.cedula = '$cedula'
	LIMIT 1";

	return mysqli_query($conexion, $sql);
}

function comprobar_baja_padron($idrelacion)
{
	$conexion = connection(DB_AFILIACION_PARAGUAY);
	$tabla1 = TABLA_PADRON_DATOS_SOCIO;

	$sql = "SELECT 
		abmactual,
		abm 
	FROM
		{$tabla1}
	WHERE
		idrelacion = '$idrelacion' AND 
		abmactual = 1 AND
		abm = 'BAJA'
	LIMIT 1";

	return mysqli_query($conexion, $sql);
}

function comprobar_baja_crm($idrelacion)
{
	$conexion = connection(DB);
	$tabla = TABLA_BAJAS;

	$sql = "SELECT
			idrelacion
		FROM
		    {$tabla}
		WHERE
			idrelacion = '$idrelacion' AND
			activo = 1";

	return mysqli_query($conexion, $sql);
}
