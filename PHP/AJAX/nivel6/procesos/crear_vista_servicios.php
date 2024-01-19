<?php
include('../../../configuraciones.php');


//Elimino y creo la tabla temporal de padrón de socios
$crear_vista = vista_servicios();


if ($crear_vista != true) {
  $response['error'] = true;
  $response['mensaje'] = "Error al crear la tabla remporal";
  die(json_encode($response));
}




$response['error'] = false;
$response['mensaje'] = "Se creo la vista de servicios con exito";
echo json_encode($response);


mysqli_close($conexion);





function vista_servicios()
{
  $conexion = connection(DB_COORDINACION);

  $eliminar_tabla = mysqli_query($conexion, "DROP VIEW vista_servicios");

  $consulta = mysqli_query($conexion, "CREATE VIEW vista_servicios AS
  SELECT
    s.id AS id_servicio,
    s.idinfo,
    s.fecha AS fecha_servicio,
    REPLACE ( p.lugar, '_', ' ' ) AS lugar,
    p.tipo AS tipo_servicio,
    p.fechafin,
    p.fecha_carga,
    p.telefono,
    p.usuario1 AS usuario,
    p.id_socio,
    p.nombre_socio,
    h.hora_inicio,
    h.hora_fin,
    h.nombre_acompanante,
    h.cedula_acompanante,
    h.horario_cortado,
    h.total_horas,
    h.asilamiento_reclamado 
  FROM
    servicios_new s,
    pedido_acomp p,
    horarios h
  WHERE
    p.id = s.idinfo AND
    h.id_servicio = s.id AND 
    s.activo = 1 AND 
    h.activo = 1 AND 
    h.sin_acompanante = 0 
  GROUP BY s.id
  ORDER BY s.fecha DESC");

  return $consulta;
  mysqli_close($conexion);
}
