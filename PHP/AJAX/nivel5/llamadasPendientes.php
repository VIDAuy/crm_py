<?php
    include '../../configuraciones.php';
    $conexion = connection(DB);


    $hoy = date('Y-m-d');

    if(isset($_GET['mostrar'])){
        $q = "SELECT rpr.id, pr.nombre, pr.cedula, pr.telefono, rpr.observacion 
                FROM registros_pagos_rechazados AS rpr
                INNER JOIN pagos_rechazados AS pr
                    ON pr.id = rpr.id_referencia
                WHERE rpr.estado = 5
                    AND rpr.fecha_opcional = '$hoy'
                    AND mostrar_opcional = 1";
        $r = $conexion->query($q);
        while($row = $r->fetch_assoc()){
            $row['observacion'] = (strlen($row['observacion']) > 29)
                ? mb_substr($row['observacion'], 0, 30) . '... (continua)'
                : $row['observacion'];
            $respuesta[] = array(
                'id'            => $row['id'],
                'nombre'        => $row['nombre'],
                'cedula'        => $row['cedula'],
                'telefono'      => $row['telefono'],
                'observacion'   => $row['observacion']
            );
        }
    } else {
        $q = "SELECT COUNT(estado)
                FROM registros_pagos_rechazados
                WHERE estado = 5
                    AND fecha_opcional = '$hoy'
                    AND mostrar_opcional = 1";
        $r = $conexion->query($q);
    
        if($r) {
            $f = $r->fetch_row();
            $respuesta['correcto'] = true;
            if($f[0] != 0){
                $respuesta['mensaje'] = ($f[0] == 1)
                    ? "Hay $f[0] llamada pendiente para hoy"
                    : "Hay $f[0] llamadas pendientes para hoy";
                $respuesta['registros'] = true;
            } else{
                $respuesta = array(
                    'correcto' => true,
                    'mensaje' => 'No hay llamadas pedientes para hoy'
                );
            }
        } else {
            $respuesta = array(
                'error' => true,
                'mensaje' => 'Ha ocurrido un error, por favor comuníquese con el administrador'
            );
        }
    }

    echo json_encode($respuesta);