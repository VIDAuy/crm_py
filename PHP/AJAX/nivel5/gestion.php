<?php
include '../../configuraciones.php';
$conexion = connection(DB);


$cedula = $_POST['ci'];
$q = ($_POST['gestion'] === '0')
        ? "SELECT id, nombre, cedula, telefono, nro_talon, mes_seis, mes_cinco, mes_cuatro, mes_tres, mes_dos, mes_anterior, deuda_total, mes_registro, anho_registro, nro_tarjeta, cedula_titular, motivo_de_rechazo
                FROM pagos_rechazados
                WHERE cedula = $cedula
                ORDER BY anho_registro DESC, mes_registro DESC
                LIMIT 1"
        : "SELECT id, nombre, cedula, telefono, nro_talon, mes_seis, mes_cinco, mes_cuatro, mes_tres, mes_dos, mes_anterior, deuda_total, mes_registro, anho_registro
                FROM pagos_rechazados
                WHERE cedula = $cedula
                ORDER BY anho_registro DESC, mes_registro DESC
                LIMIT 1";
$r = $conexion->query($q);
$f = $r->fetch_assoc();
switch ($f['mes_registro']) {
        case '12':
                $f['mes6'] = 'Junio:';
                $f['mes5'] = 'Julio:';
                $f['mes4'] = 'Agosto:';
                $f['mes3'] = 'Septiembre:';
                $f['mes2'] = 'Octubre:';
                $f['mes1'] = 'Noviembre:';
                break;
        case '11':
                $f['mes6'] = 'Mayo:';
                $f['mes5'] = 'Junio:';
                $f['mes4'] = 'Julio:';
                $f['mes3'] = 'Agosto:';
                $f['mes2'] = 'Septiembre:';
                $f['mes1'] = 'Octubre:';
                break;
        case '10':
                $f['mes6'] = 'Abril:';
                $f['mes5'] = 'Mayo:';
                $f['mes4'] = 'Junio:';
                $f['mes3'] = 'Julio:';
                $f['mes2'] = 'Agosto:';
                $f['mes1'] = 'Septiembre:';
                break;
        case '09':
                $f['mes6'] = 'Marzo:';
                $f['mes5'] = 'Abril:';
                $f['mes4'] = 'Mayo:';
                $f['mes3'] = 'Junio:';
                $f['mes2'] = 'Julio:';
                $f['mes1'] = 'Agosto:';
                break;
        case '08':
                $f['mes6'] = 'Febrero:';
                $f['mes5'] = 'Marzo:';
                $f['mes4'] = 'Abril:';
                $f['mes3'] = 'Mayo:';
                $f['mes2'] = 'Junio:';
                $f['mes1'] = 'Julio:';
                break;
        case '07':
                $f['mes6'] = 'Enero:';
                $f['mes5'] = 'Febrero:';
                $f['mes4'] = 'Marzo:';
                $f['mes3'] = 'Abril:';
                $f['mes2'] = 'Mayo:';
                $f['mes1'] = 'Junio:';
                break;
        case '06':
                $f['mes6'] = 'Diciembre:';
                $f['mes5'] = 'Enero:';
                $f['mes4'] = 'Febrero:';
                $f['mes3'] = 'Marzo:';
                $f['mes2'] = 'Abril:';
                $f['mes1'] = 'Mayo:';
                break;
        case '05':
                $f['mes6'] = 'Noviembre:';
                $f['mes5'] = 'Diciembre:';
                $f['mes4'] = 'Enero:';
                $f['mes3'] = 'Febrero:';
                $f['mes2'] = 'Marzo:';
                $f['mes1'] = 'Abril:';
                break;
        case '04':
                $f['mes6'] = 'Octubre:';
                $f['mes5'] = 'Noviembre:';
                $f['mes4'] = 'Diciembre:';
                $f['mes3'] = 'Enero:';
                $f['mes2'] = 'Febrero:';
                $f['mes1'] = 'Marzo:';
                break;
        case '03':
                $f['mes6'] = 'Septiembre:';
                $f['mes5'] = 'Octubre:';
                $f['mes4'] = 'Noviembre:';
                $f['mes3'] = 'Diciembre:';
                $f['mes2'] = 'Enero:';
                $f['mes1'] = 'Febrero:';
                break;
        case '02':
                $f['mes6'] = 'Agosto:';
                $f['mes5'] = 'Septiembre:';
                $f['mes4'] = 'Octubre:';
                $f['mes3'] = 'Noviembre:';
                $f['mes2'] = 'Diciembre:';
                $f['mes1'] = 'Enero:';
                break;
        case '01':
                $f['mes6'] = 'Julio:';
                $f['mes5'] = 'Agosto:';
                $f['mes4'] = 'Septiembre:';
                $f['mes3'] = 'Octubre:';
                $f['mes2'] = 'Noviembre:';
                $f['mes1'] = 'Diciembre:';
                break;
}
echo json_encode($f);
