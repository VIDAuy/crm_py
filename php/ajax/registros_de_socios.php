<?php
include '../configuraciones.php';

$cedula = $_GET['CI'];
$usuario = $_SESSION['usuario_py'];
$sector = $_SESSION['nivel_py'] != 3  ? "AND sector='$usuario'"  : '';

$tabla["data"] = [];


$obtener_registros = obtener_registros_socio($cedula, $sector);

while ($row = mysqli_fetch_assoc($obtener_registros)) {

	$id = $row['id'];
	$cedula = $row['cedula'];
	$nombre = $row['nombre'];
	$telefono = $row['telefono'];
	$fecha_registro = date("d/m/Y H:i:s", strtotime($row['fecha_registro']));
	$sector = $row['sector'];
	$observacion = $row['observaciones'];
	$resumen_observacion = strlen($row['observaciones']) > 29 ? $row['observaciones'] = mb_substr($row['observaciones'], 0, 40) . ' ' . '(...)' : $row['observaciones'];
	$socio = $row['socio'] == 1 ? "Si" : "<span class='text-danger'>No</span>";
	$baja = $row['baja'] == 1 ? "<span class='text-danger'>Si</span>" : "No";
	$envioSector = $row['envioSector'] != "" ? ucfirst(obtener_area_avisada($row['envioSector'])) : "-";
	$imagenes = obtener_imagenes($id);
	$btnImagen = strlen($imagenes) > 0 ? "<button class='btn btn-sm btn-info' onclick='modal_ver_imagen_registro(`" . URL_DOCUMENTOS . "`, `" . $imagenes . "`);'>Ver Archivos</button>" : "-";
	$btnMasInfo = "<button class='btn btn-sm btn-primary' onclick='abrir_modal_ver_mas_registro(`" . $id . "`, `" . $cedula . "`, `" . $nombre . "`, `" . $telefono . "`, `" . $fecha_registro . "`, `" . $sector . "`, `" . $observacion . "`, `" . $row['socio'] . "`, `" . $row['baja'] . "`);'>Más Info</button>";

	$tabla["data"][] = [
		'id'			=> $id,
		'fecha' 		=> $fecha_registro,
		'sector' 		=> $sector,
		'socio' 		=> $socio,
		'baja' 			=> $baja,
		'observacion'	=> $resumen_observacion,
		'avisar_a'	    => $envioSector,
		'imagen' 	    => $btnImagen,
		'mas_info'      => $btnMasInfo,
	];
}


echo json_encode($tabla);




function obtener_registros_socio($cedula, $sector)
{
	$conexion = connection(DB);
	$tabla = TABLA_REGISTROS_PY;

	$sql = "SELECT 
			 id, 
			 cedula, 
			 nombre, 
			 telefono, 
			 fecha_registro, 
			 sector, 
			 observaciones, 
			 socio, 
			 baja, 
			 nombre_imagen, 
			 envioSector 
			FROM 
			 {$tabla} 
			WHERE 
			 cedula = $cedula $sector 
			ORDER BY id DESC";
	$consulta = mysqli_query($conexion, $sql);

	return $consulta;
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

	$sql = "SELECT nombre_imagen FROM {$tabla} WHERE id_registro = '$id' AND activo = 1";
	$consulta = mysqli_query($conexion, $sql);

	$imagenes = "";
	while ($row = mysqli_fetch_assoc($consulta)) {
		$imagenes .= $imagenes == "" ? $row['nombre_imagen'] : ", " . $row['nombre_imagen'];
	}

	return $imagenes;
}