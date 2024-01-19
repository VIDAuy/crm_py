<?php
include('../../../configuraciones.php');


//Elimino y creo la tabla temporal de padrón de socios
$crear_tabla = crear_tabla_temporal();


if ($crear_tabla != true) {
  $response['error'] = true;
  $response['mensaje'] = "Error al crear la tabla remporal";
  die(json_encode($response));
}


$datos_padrones = obtener_datos_padron();
//die(var_dump($datos_padrones));

while ($resultado = mysqli_fetch_assoc($datos_padrones)) {

  $id = $resultado['id'];
  $cedula = $resultado['cedula'];
  $cod_filial = $resultado['cod_filial'];
  $cod_empresa = $resultado['cod_empresa'];
  $cod_servicio = $resultado['cod_servicio'];
  $fecha_nacimiento = $resultado['fecha_nacimiento'];
  $count = $resultado['count'];
  $horas_contratadas = $resultado['horas_contratadas'];
  $comentario = $resultado['comentario'];
  $idrelacion = $resultado['idrelacion'];


  $llenar_tabla_temporal = insertar_tabla_temporal($id, $cedula, $cod_filial, $cod_empresa, $fecha_nacimiento, $count, $horas_contratadas, $comentario, $cod_servicio, $idrelacion);
}




mysqli_close($conexion);





function crear_tabla_temporal()
{
  $conexion = connection(DB_COORDINACION);

  $eliminar_tabla = mysqli_query($conexion, "DROP TABLE padron_datos_socio_temporal");

  $crear_tabla_temporal = mysqli_query($conexion, "CREATE TABLE `padron_datos_socio_temporal` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `cedula` varchar(11) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
    `sucursal` varchar(255) NULL DEFAULT NULL,
    `fecha_nacimiento` date NULL DEFAULT NULL,
    `count` int(11) NULL DEFAULT NULL,
    `observaciones` varchar(300) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
    `empresa_rut` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
    `hora` varchar(2) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
    `servicio` varchar(255) CHARACTER SET latin1 COLLATE latin1_swedish_ci NULL DEFAULT NULL,
    `idrelacion` varchar(20) CHARACTER SET latin1 COLLATE latin1_swedish_ci NOT NULL DEFAULT '',
    PRIMARY KEY (`id`) USING BTREE,
    INDEX `cedula`(`cedula`) USING BTREE
  )
  ");

  return $crear_tabla_temporal;
  mysqli_close($conexion);
}


function obtener_datos_padron()
{
  $conexion = connection(DB_ABMMOD);

  $consulta = mysqli_query($conexion, "SELECT
    d.id,
    d.cedula,
    d.sucursal AS 'cod_filial',
	  d.empresa_rut AS 'cod_empresa',
	  d.fecha_nacimiento AS 'fecha_nacimiento',
	  d.count AS 'count',
	  p.hora AS 'horas_contratadas',
	  d.observaciones AS 'comentario',
    p.servicio AS 'cod_servicio',
    d.idrelacion
  FROM
    padron_datos_socio AS d INNER JOIN 
    padron_producto_socio AS p ON d.cedula = p.cedula
  WHERE
    d.activo = 1
  GROUP BY
    d.cedula");

  return $consulta;
  mysqli_close($conexion);
}


function insertar_tabla_temporal($id, $cedula, $filial_socio, $empresa_socio, $fecha_nacimiento, $count, $horas_contratadas, $comentario, $tipo_producto, $idrelacion)
{
  $conexion = connection(DB_COORDINACION);

  $insert = mysqli_query($conexion, "INSERT INTO `padron_datos_socio_temporal` 
  (id, cedula, sucursal, fecha_nacimiento, count, observaciones, empresa_rut, hora, servicio, idrelacion)
  VALUES
  ('$id', '$cedula', '$filial_socio', '$fecha_nacimiento', '$count', '$comentario', '$empresa_socio', '$horas_contratadas', '$tipo_producto', '$idrelacion')
  ");

  return $insert;
  mysqli_close($conexion);
}
