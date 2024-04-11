<?php
include '../configuraciones.php';
$conexion = connection(DB);
$tabla = TABLA_REGISTROS_PY;


if (isset($_GET['ID'])) {
	$id = $_GET['ID'];

	$q = "SELECT id, cedula, nombre, telefono, fecha_registro, sector, observaciones, socio, baja FROM {$tabla} WHERE id = $id";
	$r = mysqli_query($conexion, $q);
	$f = mysqli_fetch_assoc($r);

	//MODIFICAR LOS RESULTADOS PARA MEJOR LECTURA

	$f['fecha_registro'] = new DateTime($f['fecha_registro']);
	$f['fecha_registro'] = $f['fecha_registro']->format('Y/m/d H:i:s');
	$f['telefono'] 	= corregirTelefono($f['telefono']);
	$f['socio'] = ($f['socio'] == 1) ? 'Sí' : 'No';
	$f['baja'] = ($f['baja'] == 1) ? 'Sí' : 'No';
} else {
	if (!isset($_SESSION)) session_start();

	$cedula = $_GET['CI'];
	$usuario = $_SESSION['usuario_py'];
	$sector = $_SESSION['nivel_py'] != 3  ? "AND sector='$usuario'"  : '';
	$q = "SELECT id, fecha_registro, sector, observaciones, socio, baja, nombre_imagen, envioSector FROM {$tabla} WHERE cedula = $cedula $sector ORDER BY id DESC";
	$r = mysqli_query($conexion, $q);
	if (mysqli_num_rows($r) != 0) {
		while ($row = mysqli_fetch_assoc($r)) {

			//MODIFICAR LOS RESULTADOS PARA MEJOR LECTURA

			$row['fecha_registro'] = new DateTime($row['fecha_registro']);
			$row['fecha_registro'] = $row['fecha_registro']->format('Y/m/d H:i:s');
			$row['socio'] = ($row['socio'] == 1)
				? 'Sí'
				: 'No';
			$row['baja'] = ($row['baja'] == 1)
				? 'Sí'
				: 'No';
			if (strlen($row['observaciones']) > 29) $row['observaciones'] = mb_substr($row['observaciones'], 0, 40) . ' ' . '(...)';


			$id = $row['id'];
			$socio = $row['socio'];
			$socio = $socio == 'SI' ? "<span>$socio</span>" : "<span class='text-danger'>$socio</span>";
			$baja = $row['baja'];
			$baja = $baja == 'SI' ? "<span class='text-danger'>$baja</span>" : "<span>$baja</span>";
			$nombre_imagen = $row['nombre_imagen'];
			$ruta_imagen = URL_DOCUMENTOS . "/" . $nombre_imagen;
			$imagenes = obtener_imagenes($id);
			$imagen = count($imagenes) > 0 ? "<button class='btn btn-sm btn-info' onclick='modal_ver_imagen_registro(`" . URL_DOCUMENTOS . "`, `" . $id . "`);'>Ver Archivos</button>" : "-";
			$id_avisar_a = $row['envioSector'];
			$avisar_a = obtener_area_avisada($id_avisar_a);

			$f[] = array(
				'id'			=> $row['id'],
				'fecha' 		=> date("d/m/Y H:i:s", strtotime($row['fecha_registro'])),
				'sector' 		=> $row['sector'],
				'observacion'	=> $row['observaciones'],
				'avisar_a'	    => $avisar_a != "" ? ucfirst($avisar_a) : "-",
				'socio' 		=> $socio,
				'baja' 			=> $baja,
				'imagen' 	    => $imagen,
				'mas_info'      => "<button class='btn btn-sm btn-primary' onclick='modalHistoriaComunicacionDeCedula(`" . $id . "`);'>Más Info</button>",
			);
		}
	} else $f = ['noRegistros' => true];
}

echo json_encode($f);

function corregirTelefono($var)
{
	// CORRECCIÓN Y ASIGNACIÓN DE TELÉFONO

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
		if (isset($var[9]) && ($var[9] == 2 || $var[9] == 4) && mb_substr($var, 7, 10) != '09') $telefonoFuncion = mb_substr($var, 10, 18);
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

	return $telFuncion;
}

function obtener_area_avisada($id)
{
	$conexion = connection(DB);
	$tabla = TABLA_USUARIOS;

	$sql = "SELECT avisar_a FROM {$tabla} WHERE id = '$id'";
	$consulta = mysqli_query($conexion, $sql);

	return mysqli_fetch_assoc($consulta)['avisar_a'];
}

function obtener_imagenes($id)
{
	$conexion = connection(DB);
	$tabla = TABLA_IMAGENES_REGISTRO;

	$sql = "SELECT nombre_imagen FROM imagenes_registro WHERE id_registro = '$id' AND activo = 1";
	$consulta = mysqli_query($conexion, $sql);

	$imagenes = [];
	while ($row = mysqli_fetch_assoc($consulta)) {
		array_push($imagenes, $row['nombre_imagen']);
	}

	return $imagenes;
}
