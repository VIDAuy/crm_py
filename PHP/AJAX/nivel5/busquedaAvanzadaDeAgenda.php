<?php
include '../../configuraciones.php';
$conexion = connection(DB);


$prioridadLlamada  = ($_POST['prioridad'] != null)
    ? true
    : false;
$tipoDeCobro    = ($_POST['cartera'] != null)
    ? true
    : false;
$filial = ($_POST['filial'] != null)
    ? true
    : false;
$estado     = ($_POST['estado'] != null)
    ? true
    : false;
if ($_POST['filtroFecha'] != null) {
    if (($_POST['filtroFecha'] == 1 || $_POST['filtroFecha'] == 2) && $_POST['fechaCompletaDesde'] != null && $_POST['fechaCompletaHasta']) {
        $filtrarFecha = true;
    } else if ($_POST['filtroFecha'] == 3 && $_POST['mesDesde'] != null && $_POST['anhoDesde'] && $_POST['mesHasta'] != null && $_POST['anhoHasta']) {
        $filtrarFecha = true;
    } else {
        $filtrarFecha = false;
    }
} else {
    $filtrarFecha = false;
}

if ($prioridadLlamada || $tipoDeCobro || $filial || $estado || $filtrarFecha) {
    $q          = 'SELECT pr.id, pr.cedula, pr.nombre, pr.nro_talon, pr.mes_registro, pr.anho_registro, rpr.estado
                            FROM pagos_rechazados AS pr
                            LEFT JOIN registros_pagos_rechazados AS rpr
                                USING(cedula)';
    $filtros = ' WHERE ';
    if ($prioridadLlamada) {
        $prioridadLlamada  = $_POST['prioridad'];

        $filtros    .= "prioridad_llamada = $prioridadLlamada AND ";
    }
    if ($tipoDeCobro) {
        $tipoDeCobro = $_POST['cartera'];

        $filtros    .= "tipo_de_cobro = $tipoDeCobro AND ";
    }
    if ($estado) {
        $estado     = $_POST['estado'];

        if ($estado == 9) {
            $filtros    .= "rpr.cedula IS NULL AND ";
        } else {
            $filtros    .= "rpr.estado = $estado AND ";
        }
    }
    if ($filtrarFecha) {
        if ($_POST['filtroFecha'] == 1) {
            $_POST['fechaCompletaDesde'] = str_replace('/', '-', $_POST['fechaCompletaDesde']);
            $_POST['fechaCompletaHasta'] = str_replace('/', '-', $_POST['fechaCompletaHasta']);
            $desde      = new DateTime($_POST['fechaCompletaDesde']);
            $desde = $desde->format('Y-m-d');
            $hasta      = new DateTime($_POST['fechaCompletaHasta']);
            $hasta = $hasta->format('Y-m-d');

            $filtros    .= "rpr.fecha_opcional BETWEEN '$desde' AND '$hasta' AND ";
        } else if ($_POST['filtroFecha'] == 2) {
            $_POST['fechaCompletaDesde'] = str_replace('/', '-', $_POST['fechaCompletaDesde']);
            $_POST['fechaCompletaHasta'] = str_replace('/', '-', $_POST['fechaCompletaHasta']);
            $desde      = new DateTime($_POST['fechaCompletaDesde']);
            $desde = $desde->format('Y-m-d') . ' 00:00:00';
            $hasta      = new DateTime($_POST['fechaCompletaHasta']);
            $hasta = $hasta->format('Y-m-d') . ' 23:59:59';

            $filtros    .= "rpr.fecha_observacion BETWEEN '$desde' AND '$hasta' AND ";
        } else if ($_POST['filtroFecha'] == 3) {
            $mesDesde   = $_POST['mesDesde'];
            $mesHasta   = $_POST['anhoDesde'];
            $anhoDesde  = $_POST['anhoDesde'];
            $anhoHasta  = $_POST['anhoHasta'];

            $filtros    .= "pr.mes_registro BETWEEN '$mesDesde' AND '$mesHasta' AND pr.anho_registro '$anhoDesde' AND '$anhoHasta' AND ";
        }
    }
    if ($filial) {
        $filial     = $_POST['filial'];
        $cedulas = '';
        $conexion->close();
        $conexion = connection(DB_ABMMOD);

        $q2 = "SELECT cedula
                    FROM padron_datos_socio
                    WHERE sucursal = $filial";
        $r2 = mysqli_query($conexion, $q2);

        while ($row = $r2->fetch_assoc()) {
            $cedulas .= $row['cedula'] . ', ';
        }

        $cedulas = substr($cedulas, 0, -2);

        $conexion->close();
        $conexion = connection(DB);

        $filtros    .= "cedula IN ($cedulas)";
    }
    if (substr($filtros, -4) == 'AND ') {
        $filtros = substr($filtros, 0, -4);
    }
    $q = $q . $filtros;
    $r = $conexion->query($q);
    if ($r) {
        $f['correcto'] = true;
    } else {
        $f['error'] = true;
        $f['mensaje']      = 'Error al traer los registros, por favor intente luego.';
    }
    if ($r->num_rows != 0) {
        $f['conRegistros'] = true;
    } else {
        $f['sinRegistros'] = true;
        $f['mensaje']      = 'No se han encontrado registros con esos filtros.';
    }
    while ($row = $r->fetch_assoc()) {
        if ($row['estado'] != null) {
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
                    $row['estado'] = 'Promesa pago';
                    break;
                case '5':
                    $row['estado'] = 'Fecha contacto';
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
        } else {
            $row['estado'] = 'Sin gestionar';
        }
        $row['nombre'] = mb_convert_case($row['nombre'], MB_CASE_TITLE);
        $f[] = $row;
    }
} else if (($desde && !$hasta) || (!$desde && $hasta)) {
    $f['error'] = true;
    $f['mensaje']      = 'Debe seleccionar una fecha de inicio y una fecha de fin.';
} else {
    $f['error'] = true;
    $f['mensaje']      = 'Se debe de seleccionar al menos un filtro.';
}

echo json_encode($f);
