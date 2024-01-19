<?php
include '../configuraciones.php';
$conexion = connection(DB);


error_reporting(E_ALL);

if (empty($_REQUEST["hasta"])) die(json_encode(["data" => []]));

$desde = date("Y/m/d", strtotime($_REQUEST["desde"]));

$hasta = date("Y-m-d", strtotime($_REQUEST["hasta"]));

$d = new DateTime($_REQUEST["desde"]);

$h =  new DateTime($_REQUEST["hasta"]);

$diferencia = $d->diff($h);


if ($diferencia->m >= 3) die(json_encode(["data" => []]));

$consulta = "SELECT
    *
FROM
    bajas
WHERE
    (
        CAST(fecha_ingreso_baja AS date) >= '{$desde}'
        AND CAST(fecha_ingreso_baja AS date) <= '{$hasta}'
    );";

$tabla  = [];

$bajas = mysqli_query($conexion, $consulta);


if (mysqli_num_rows($bajas) > 0) {

    while ($baja = mysqli_fetch_assoc($bajas)) {

        $tabla[]  = [
            "id" => $baja["id"],
            "observaciones" => utf8Convertir(recortarCampo($baja["observaciones"])),
            "nombre_funcionario" =>  utf8Convertir($baja["nombre_funcionario"]),
            "cedula_socio" => $baja["cedula_socio"],
            "fecha_ingreso_baja" => $baja["fecha_ingreso_baja"],
            "nombre_socio" =>  utf8Convertir($baja["nombre_socio"]),
            "estado" => $baja["estado"],
            "importe" => $baja["importe"],
            "nombre_contacto" =>  utf8Convertir($baja["nombre_contacto"]),
            "telefono_contacto" => $baja["telefono_contacto"],
            "nombre_funcionario_final" => $baja["nombre_funcionario_final"],
            "observacion_final" => recortarCampo($baja["observacion_final"]),
            "fecha_fin_gestion" => $baja["fecha_fin_gestion"],
            "area_fin_gestion" => $baja["area_fin_gestion"],
            "motivo_baja" => $baja["motivo_baja"],
            "motivo_no_otorgada" => $baja["motivo_no_otorgada"],
            "sucursal" => $baja["filial_socio"],
        ];
    }
}


die(json_encode(["data" => $tabla], JSON_UNESCAPED_UNICODE));


function utf8Convertir($campo)
{
    return mb_convert_encoding($campo, 'UTF-8', 'UTF-8');
}

function recortarCampo($campo, $largo = 120)
{
    if (strlen($campo) > $largo) {
        $br  = array("<br />", "<br>", "<br/>");
        $campo = str_ireplace($br, "\r\n", $campo);

        $reclamo_sin_editar = $campo;
        $string = substr($campo, 0, $largo);
        $campo = $string . " ...<a href='#' onclick='verMasTabla(event,`" . $reclamo_sin_editar . "`);'> Ver Más</a>";
        $campo = mb_convert_encoding($campo, 'UTF-8', 'UTF-8');
    }
    return $campo;
}
