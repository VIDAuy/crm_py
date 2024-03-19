<?php
include '../configuraciones.php';

$id = $_REQUEST['id'];


$obtener_imagenes = obtener_imagenes($id);

$imagenes = [];
while ($row = mysqli_fetch_assoc($obtener_imagenes)) {
    array_push($imagenes, $row['nombre_imagen']);
}



$response['error'] = false;
$response['datos'] = $imagenes;


echo json_encode($response);




function obtener_imagenes($id)
{
    $conexion = connection(DB);
    $tabla = TABLA_IMAGENES_REGISTRO;

    $sql = "SELECT nombre_imagen FROM {$tabla} WHERE id_registro = '$id' AND activo = 1";
    $consulta = mysqli_query($conexion, $sql);

    return $consulta;
}
