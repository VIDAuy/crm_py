<?php
header('Access-Control-Allow-Origin: *');
include('../../../configuraciones.php');


//Elimino y creo la tabla temporal de padrón de socios
$crear_tabla = crear_tabla_temporal();


if ($crear_tabla != true) {
    $response['error'] = true;
    $response['mensaje'] = "Error al crear la tabla temporal";
    die(json_encode($response));
}


$registro_socios = socios_comag();


while ($resultado = mysqli_fetch_assoc($registro_socios)) {

    $id = $resultado['id'];
    $cedula = $resultado['cedula'];
    $nombre = $resultado['nombre'];
    $nacimiento = $resultado['nacimiento'];
    $direccion = $resultado['direccion'];
    $localidad = $resultado['localidad'];
    $departamento = $resultado['departamento'];
    $telefono = $resultado['telefono'];
    $celular = $resultado['celular'];
    $fecha = $resultado['fecha'];


    $llenar_tabla_temporal = insertar_tabla_temporal($id, $cedula, $nombre, $nacimiento, $direccion, $localidad, $departamento, $telefono, $celular, $fecha);
}




mysqli_close($conexion);





function crear_tabla_temporal()
{
    $conexion = connection(DB_COORDINACION);

    $eliminar_tabla = mysqli_query($conexion, "DROP TABLE padron_socios_comag");

    $crear_tabla_temporal = mysqli_query($conexion, "CREATE TABLE `padron_socios_comag`  (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `cedula` varchar(8) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
        `nombre` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL,
        `nacimiento` date NULL DEFAULT NULL,
        `direccion` varchar(80) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
        `localidad` varchar(50) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
        `departamento` varchar(30) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
        `telefono` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
        `celular` varchar(15) CHARACTER SET utf8 COLLATE utf8_general_ci NULL DEFAULT NULL,
        `fecha` timestamp NULL DEFAULT NULL,
        PRIMARY KEY (`id`) USING BTREE
      )
  ");

    return $crear_tabla_temporal;
    mysqli_close($conexion);
}


function socios_comag()
{
    $conexion = connection(DB_COMAG);

    $consulta = "SELECT 
    id,
    cedula,
    nombre,
    nacimiento,
    direccion,
    localidad,
    departamento,
    telefono,
    celular,
    fecha 
    FROM padron_comag";

    return mysqli_query($conexion, $consulta);
    mysqli_close($conexion);
}


function insertar_tabla_temporal($id, $cedula, $nombre, $nacimiento, $direccion, $localidad, $departamento, $telefono, $celular, $fecha)
{
    $conexion = connection(DB_COORDINACION);

    $insert = mysqli_query($conexion, "INSERT INTO `padron_socios_comag` 
    (id, cedula, nombre, nacimiento, direccion, localidad, departamento, telefono, celular, fecha )
    VALUES
    ('$id', '$cedula', '$nombre', '$nacimiento', '$direccion', '$localidad', '$departamento', '$telefono', '$celular', '$fecha' )");

    return $insert;
    mysqli_close($conexion);
}
