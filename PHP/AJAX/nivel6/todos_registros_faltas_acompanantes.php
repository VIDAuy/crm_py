<?php
include '../../configuraciones.php';


$conexion = connection_nodum_Modelo_RSE();
$fecha_desde = $_REQUEST['fecha_desde'];
$fecha_hasta = $_REQUEST['fecha_hasta'];



$tabla["data"] = [];

$consulta = sqlsrv_query($conexion, "SELECT
c.cod_trabajador,
c.doc_persona,
t.nombre1,
t.nombre2,
t.apellido1,
t.apellido2,
c.cod_tipo_licen,
c.cod_actividad,
c.cod_emp,
c.fec_inicio,
c.fec_final
FROM
cpp_RHLicencias as c,
v_RHTrabajador as t
WHERE
(
    ( CAST ( c.fec_inicio AS DATE ) BETWEEN '$fecha_desde' AND '$fecha_hasta' ) 
    OR ( CAST ( c.fec_final AS DATE ) BETWEEN '$fecha_desde' AND '$fecha_hasta' ) 
    OR ( CAST ( c.fec_inicio AS DATE ) <= '$fecha_desde' AND CAST ( c.fec_final AS DATE ) >= '$fecha_hasta' ) 
)
AND c.doc_persona = t.doc_persona
");




while ($resultado = sqlsrv_fetch_array($consulta)) {

    $trabajador = $resultado['cod_trabajador'];
    $cedula = $resultado['doc_persona'];
    $nombre_1_verificar = TRIM($resultado['nombre1']);
    $nombre1 = verificar_letras($nombre_1_verificar) == true ? $nombre_1_verificar : "";
    $nombre_2_verificar = TRIM($resultado['nombre2']);
    $nombre2 = verificar_letras($nombre_2_verificar) == true ? $nombre_2_verificar : "";
    $apellido1_verificar = TRIM($resultado['apellido1']);
    $apellido1 = verificar_letras($apellido1_verificar) == true ? $apellido1_verificar : "";
    $apellido2_verificar = TRIM($resultado['apellido2']);
    $apellido2 = verificar_letras($apellido2_verificar) == true ? $apellido2_verificar : "";
    $nombre_completo = $nombre1 . " " . $nombre2 . " " . $apellido1 . " " . $apellido2;
    $tipo_licencia = convertTipoLicencia($resultado['cod_tipo_licen']);
    $actividad = convertTipoActividad($resultado['cod_actividad']);
    $empresa = convertTipoEmpresa($resultado['cod_emp']);
    $fecha_inicio = $resultado['fec_inicio']->FORMAT('d/m/Y');
    $fecha_final = $resultado['fec_final']->FORMAT('d/m/Y');

    $tabla["data"][] = [
        "trabajador" => $trabajador,
        "cedula" => $cedula,
        "nombre" => $nombre_completo,
        "tipo_falta" => $tipo_licencia,
        "actividad" => $actividad,
        "empresa" => $empresa,
        "fecha_inicio" => $fecha_inicio,
        "fecha_final" => $fecha_final,
    ];
}



echo json_encode($tabla);






function convertTipoLicencia($texto)
{
    switch (trim($texto)) {
        case 'accidente':
            $texto = 'Accidente';
            break;
        case 'anual':
            $texto = 'Anual';
            break;
        case 'casamiento':
            $texto = 'Casamiento';
            break;
        case 'donasang':
            $texto = 'Donar sangre';
            break;
        case 'duelo':
            $texto = 'Duelo';
            break;
        case 'enfermedad':
            $texto = 'Enfermedad';
            break;
        case 'estudio':
            $texto = 'Estudio';
            break;
        case 'Fal_c/avis':
            $texto = 'Falta CON aviso';
            break;
        case 'Fal_s/avis':
            $texto = 'Falta SIN aviso';
            break;
        case 'Librexmeta':
            $texto = 'Libre por meta';
            break;
        case 'licencia':
            $texto = 'Licencia';
            break;
        case 'mater.parc':
            $texto = 'Maternal parcial';
            break;
        case 'maternal':
            $texto = 'Maternal';
            break;
        case 'Mediafalta':
            $texto = 'Media falta';
            break;
        case 'papanico':
            $texto = 'Papanicolau';
            break;
        case 'paro':
            $texto = 'Paro';
            break;
        case 'paternal':
            $texto = 'Paternal';
            break;
        case 'suspensión':
            $texto = 'Suspensión';
            break;
        default:
            break;
    }

    return $texto;
}

function convertTipoActividad($texto)
{
    switch (trim($texto)) {
        case 0:
            $texto = 'No asignada';
            break;
        case 3:
            $texto = 'Administración';
            break;
        case 4:
            $texto = 'Comercial';
            break;
        case 5:
            $texto = 'Servicios';
            break;
        case 6:
            $texto = 'Sistemas';
            break;
        case 7:
            $texto = 'Directorio';
            break;
        case 8:
            $texto = 'Legal';
            break;
        default:
            break;
    }

    return $texto;
}

function convertTipoEmpresa($texto)
{
    switch (trim($texto)) {
        case '01':
            $texto = 'Compañía de Logística SRL';
            break;
        case '02':
            $texto = 'Central de Acompañantes S.A.';
            break;
        case '03':
            $texto = 'Afilsa S.A.';
            break;
        case '04':
            $texto = 'Compañía y Servicios S.A.';
            break;
        case '05':
            $texto = 'Servicios de Acompañantes del Litoral SA';
            break;
        case '06':
            $texto = 'Vabiloy S.A.';
            break;
        case '08':
            $texto = 'Gabolir S.A.';
            break;
        case '09':
            $texto = 'Compañìa Colonia Ltda.';
            break;
        case '20':
            $texto = 'Aficent SRL';
            break;
        case '21':
            $texto = 'Adecco Uruguay S.A';
            break;
        case '29':
            $texto = 'Davi S.A';
            break;
        default:
            break;
    }

    return $texto;
}

function verificar_letras($cadena)
{
    if (preg_match("/^(?=.{3,18}$)[a-zñA-ZÑ](\s?[a-zñA-ZÑ])*$/", $cadena)) {
        $respuesta = true;
    } else {
        $respuesta = false;
    }

    return $respuesta;
}
