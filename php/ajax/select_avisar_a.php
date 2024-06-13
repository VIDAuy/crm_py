<?php
include '../configuraciones.php';


if (!isset($_SESSION['id_py']))
    header('location: ../../');


$id = $_SESSION['id_py'];
$datos = obtener_datos($id);

while ($row = mysqli_fetch_assoc($datos)) {
    $row['usuario'] = strtolower($row['usuario']);
    $row['usuario'] = ucfirst($row['usuario']);
    $f[] = $row;
}


$respuesta = [
    'datos' => $f
];
echo json_encode($respuesta);




function obtener_datos($id)
{
    $conexion = connection(DB);
    $tabla = TABLA_USUARIOS;

    $sql = "SELECT id, avisar_a 'usuario' FROM {$tabla} WHERE id != $id AND avisar = 1 ORDER BY id ASC";
    $consulta = mysqli_query($conexion, $sql);

    return $consulta;
}
