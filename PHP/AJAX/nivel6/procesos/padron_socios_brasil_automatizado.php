<?php
include('../../../configuraciones.php');


//Elimino y creo la tabla temporal de padrón de socios
$crear_tabla = crear_tabla_temporal();


if ($crear_tabla != true) {
  $response['error'] = true;
  $response['mensaje'] = "Error al crear la tabla temporal";
  die(json_encode($response));
}


$registro_socios = socios_brasil();


while ($resultado = mysqli_fetch_assoc($registro_socios)) {

  $id = $resultado['id'];
  $cpf_sacado = $resultado['cpf_sacado'];
  $telefono = $resultado['telefono'];
  $nome_sacado = $resultado['nome_sacado'];
  $endereco = $resultado['endereco'];
  $fecha_afiliacion = $resultado['fecha_afiliacion'];
  $horas_contratadas = $resultado['horas_contratadas'];
  $valor_nominal = $resultado['valor_nominal'];
  $barrio = $resultado['barrio'];
  $filial = $resultado['filial'];
  $cep = $resultado['cep'];


  $llenar_tabla_temporal = insertar_tabla_temporal($id, $cpf_sacado, $telefono, $nome_sacado, $endereco, $fecha_afiliacion, $horas_contratadas, $valor_nominal, $barrio, $filial, $cep);
}




mysqli_close($conexion);





function crear_tabla_temporal()
{
  $conexion = connection(DB_COORDINACION);

  $eliminar_tabla = mysqli_query($conexion, "DROP TABLE padron_socios_brasil");

  $crear_tabla_temporal = mysqli_query($conexion, "CREATE TABLE `padron_socios_brasil`  (
    `id` int(12) NOT NULL PRIMARY KEY AUTO_INCREMENT,
    `cpf_sacado` varchar(255) NULL DEFAULT NULL,
    `telefono` varchar(255) NULL DEFAULT NULL,
    `nome_sacado` varchar(255) NULL DEFAULT NULL,
    `endereco` varchar(255) NULL DEFAULT NULL,
    `fecha_afiliacion` varchar(255) NULL DEFAULT NULL,
    `horas_contratadas` varchar(12) NULL DEFAULT NULL,
    `valor_nominal` varchar(12) NULL DEFAULT NULL,
    `barrio` varchar(255) NULL DEFAULT NULL,
    `filial` varchar(255) NULL DEFAULT NULL,
    `cep` varchar(12) NULL DEFAULT NULL
  )
  ");

  return $crear_tabla_temporal;
  mysqli_close($conexion);
}


function socios_brasil()
{
  $conexion = connection(DB_BRASIL);

  $consulta = "SELECT 
    id,
    cpf_sacado,
    telefono,
    nome_sacado,
    endereco,
    fecha_afiliacion,
    horas_contratadas,
    valor_nominal,
    barrio,
    filial,
    cep 
    FROM padron_socios_brasil";

  return mysqli_query($conexion, $consulta);
  mysqli_close($conexion);
}


function insertar_tabla_temporal($id, $cpf_sacado, $telefono, $nome_sacado, $endereco, $fecha_afiliacion, $horas_contratadas, $valor_nominal, $barrio, $filial, $cep)
{
  $conexion = connection(DB_COORDINACION);

  $insert = mysqli_query($conexion, "INSERT INTO `padron_socios_brasil` 
    (id, cpf_sacado, telefono, nome_sacado, endereco, fecha_afiliacion, horas_contratadas, valor_nominal, barrio, filial, cep )
    VALUES
    ('$id', '$cpf_sacado', '$telefono', '$nome_sacado', '$endereco', '$fecha_afiliacion', '$horas_contratadas', '$valor_nominal', '$barrio', '$filial', '$cep' )
  ");

  return $insert;
  mysqli_close($conexion);
}
