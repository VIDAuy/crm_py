<?php
include '../../configuraciones.php';
$conexion = connection(DB_ABMMOD);


if ($_POST['ci']) {
    $cedula = $_POST['ci'];
    $q = "SELECT pds.cedula, pds.nombre, pds.direccion, pds.tel, fc.filial, pds.ruta, pds.radio, pps.fecha_afiliacion, SUM(pps.importe) AS cuota
                FROM padron_datos_socio AS pds
                    INNER JOIN padron_producto_socio AS pps
                        USING(cedula)
                    INNER JOIN filiales_codigos AS fc
                        ON fc.nro_filial = pds.sucursal
                WHERE pds.cedula = $cedula
                ORDER BY pps.fecha_afiliacion";
    $r = $conexion->query($q);
    $f = $r->fetch_assoc();
    if ($f['cedula'] != null) {
        $conexion->select_db('crm');
        $q = "SELECT tipo_de_cobro 
                    FROM pagos_rechazados
                    WHERE cedula = $cedula
                    ORDER BY id DESC
                    LIMIT 1";
        $r = $conexion->query($q);
        if ($r->num_rows != 0) {
            $f['fecha_afiliacion'] = new DateTime($f['fecha_afiliacion']);
            $tDC = $r->fetch_assoc();
            $tDC = $tDC['tipo_de_cobro'];
            $respuesta = array(
                'correcto'          => true,
                'cedula'            => $f['cedula'],
                'conRegistros'      => true,
                'direccion'         => $f['direccion'],
                'fechaAfiliacion'   => $f['fecha_afiliacion']->format('d/m/Y'),
                'filial'            => $f['filial'],
                'nombre'            => $f['nombre'],
                'radio'             => $f['radio'],
                'ruta'              => $f['ruta'],
                'tel'               => corregirTelefono($f['tel']),
                'tipoDeCobro'       => $tDC,
                'valorCuota'        => '$' . $f['cuota']
            );
        } else {
            $respuesta = array(
                'error'         => true,
                'sinDeudas'     => true,
                'mensaje'       => 'La cédula ingresada siempre ha pagado en fecha.'
            );
        }
    } else {
        $respuesta = array(
            'error'         => true,
            'sinRegistros'  => true,
            'mensaje'       => 'La cédula ingresada no tiene registros en el padrón.'
        );
    }
}

$conexion->close();
echo json_encode($respuesta);

function corregirTelefono($param)
{
    if (strlen($param) > 7) {
        //EN CASO DE QUE EL TELÉFONO EMPIECE CON '0' Y UN ESPACIO LOS QUITA DE LA VARIABLE
        if (mb_substr($param, 0, 2) == '0 ') {
            $param = mb_substr($param, 2, 20);
        }

        //REEMPLAZA TODOS LOS ESPACIOS QUE TENGA LA VARIABLE
        $param = str_replace(' ', '', $param);

        //EN CASO DE QUE EL NÚMERO EMPIECE CON 09 Y TENGA MÁS DE 8 CARACTÉRES SE LE ASIGNA ESE VALOR A LA VARIABLE CELULAR
        if (mb_substr($param, 0, 2) == '09' && strlen($param) > 8) {
            if (strlen($param) == 10) {
                $celular = mb_substr($param, 0, 10);
            } else {
                $celular = mb_substr($param, 0, 9);
            }
        }

        ///EN CASO DE QUE EL NÚMERO EMPIECE CON 2 O 4 Y TENGA MÁS DE 7 CARACTÉRES SE LE ASIGNA ESE VALOR A LA VARIABLE TELEFONO
        if (($param[0] == 2 || $param[0] == 4) && strlen($param) > 7) {
            $telefono = mb_substr($param, 0, 8);
        }

        //SI EL LARGO DE LA VARIABLE ES IGUAL A 17 (LA SUMA DE LOS 9 CARACTERES DE UN TELÉFONO MÁS LOS 8 DE UN CELULAR) DIVIDE EL STRING
        if (strlen($param) == 17) {
            //EN CASO DE QUE CONTENGA LA SINTÁXIS DE TELÉFONO SE LE ASIGNA LA mb_substrING A LA VARIABLE TELEFONO
            if (isset($param[9]) && ($param[9] == 2 || $param[9] == 4) && mb_substr($param, 7, 10) != '09') {
                $telefono = mb_substr($param, 10, 18);
            }
            //EN CASO DE QUE CONTENGA LA SINTÁXIS DE CELULAR SE LE ASIGNA LA mb_substrING A LA VARIABLE CELULAR
            if (isset($param[8]) && mb_substr($param, 8, 2) == '09') {
                $celular = mb_substr($param, 8, 18);
            }
        }
    }

    if (isset($telefono) && isset($celular)) {
        return $telefono . " " . $celular;
    } else if (isset($telefono)) {
        return $telefono;
    } else if (isset($celular)) {
        return $celular;
    } else {
        return null;
    }
}
