<?php
include '../configuraciones.php';


if (isset($_SESSION['id_py'])) {
    $id = $_SESSION['id_py'];

    $datos = obtener_datos($id);

    while ($row = mysqli_fetch_assoc($datos)) {
        $row['usuario'] = strtolower($row['usuario']);
        $row['usuario'] = ucfirst($row['usuario']);
        $f[] = $row;
    }
    $respuesta = array(
        'datos' => $f
    );
    echo json_encode($respuesta);
} else {
    header('location: ../../');
}


function obtener_datos($id)
{
    $conexion = connection(DB);
    $tabla = TABLA_USUARIOS;

    $sql = "SELECT
            id,
            avisar_a AS 'usuario'
        FROM
            {$tabla} 
        WHERE
            id != $id AND
            avisar = 1
        ORDER BY id ASC";

    return mysqli_query($conexion, $sql);
}
