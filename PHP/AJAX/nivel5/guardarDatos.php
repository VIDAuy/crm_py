<?php
include '../../configuraciones.php';
$conexion = connection(DB);


$estado      = $_POST['estado'];
$mostrarOpcional = ($estado == 5)
    ? 1
    : null;
$observacion = $_POST['observacion'];
$fecha       = date('Y-m-d H:i:s');
$cedula      = $_POST['cedula'];
$sector      = 'Morosos';
$nombre      = mb_convert_case($_POST['nombre'], MB_CASE_TITLE, 'UTF-8');
$telefono    = $_POST['telefono'];
$socio       = 1;
$q           = "INSERT INTO registros_pagos_rechazados (estado, observacion, fecha_observacion, cedula) VALUES (?, ?, ?, ?)";
$r = $conexion->prepare($q);
$r->bind_param('isss', $estado, $observacion, $fecha, $cedula);
$respuesta = ($r->execute())
    ? array(
        'correcto'  => true,
        'mensaje'   => 'Datos actualizados con éxito.'
    )
    : array(
        'error'     => true,
        'mensaje'   => 'Ha ocurrido un error al enviar los datos, por favor comuníquese con el administrador.'
    );
$q = "INSERT INTO registros(cedula, nombre, telefono, fecha_registro, sector, observaciones, socio)
    VALUES(?, ?, ?, ?, ?, ?, ?)";
$r = $conexion->prepare($q);
$observacion = "Registro de morosos: $observacion";
$r->bind_param('ssssssi', $cedula, $nombre, $telefono, $fecha, $sector, $observacion, $socio);
$r->execute();
echo json_encode($respuesta);
