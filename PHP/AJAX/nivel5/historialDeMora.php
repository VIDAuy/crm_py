<?php
    include '../../configuraciones.php';
    $conexion = connection(DB);


    $cedula = $_POST['ci'];
    $q      = "SELECT estado, observacion, fecha_observacion, fecha_opcional
                FROM registros_pagos_rechazados
                WHERE cedula = $cedula";
    $r      = $conexion->query($q);
    while($row = $r->fetch_assoc()){
        switch ($row['estado']) {
            case '1':
                $row['estado'] = 'Inubicable';
                break;
            case '2':
                $row['estado'] = 'Fallecido';
                break;
            case '3':
                $row['estado'] = 'Auditoria';
                break;
            case '4':
                $fecha = new DateTime($row['fecha_opcional']);
                $fecha = $fecha->format('d/m/Y');
                $row['estado'] = 'Promesa pago '. '(' . $fecha . ')';
                break;
            case '5':
                $fecha = new DateTime($row['fecha_opcional']);
                $fecha = $fecha->format('d/m/Y');
                $row['estado'] = 'Fecha contacto '. '(' . $fecha . ')';
                break;
            case '6':
                $row['estado'] = 'Baja pendiente';
                break;
            case '7':
                $row['estado'] = 'Baja';
                break;
            case '8':
                $row['estado'] = 'Pago';
                break;
        }
        $row['fecha_observacion'] = new DateTime($row['fecha_observacion']);
        $row['fecha_observacion'] = $row['fecha_observacion']->format('d/m/Y H:i:s');
        $respuesta[] = array(
            'estado'            => $row['estado'],
            'observacion'       => $row['observacion'],
            'fecha_observacion' => $row['fecha_observacion']
        );
    }
    if(!isset($respuesta)){
        $respuesta = null;
    }
    echo json_encode($respuesta);