<?php
include '../../PHP/configuraciones.php';

$respuesta;
$directorio         = getcwd() . "/../xlsx/";
$formatosAdmitidos  = ['xlsx'];

$nombreArchivo      = str_replace(' ', '_', $_FILES['archivo']['name']);
$tmpnameArchivo     = $_FILES['archivo']['tmp_name'];
$formatoArchivo     = $_FILES['archivo']['type'];
$extensionArchivo   = explode('.', $nombreArchivo);
$extensionArchivo   = strtolower(end($extensionArchivo));

$directorioArchivo  = $directorio . basename($nombreArchivo);

$archivoCSV = explode('xlsx', $nombreArchivo);
$archivoCSV = $archivoCSV[0] . 'csv';

if (isset($_POST)) {
    if (file_exists(dirname(dirname(__FILE__)) . '/csv/' . $archivoCSV)) {
        $respuesta = array('error' => true, 'archivoExistente' => true, 'mensaje' => 'Ya se ha subido ese archivo previamente.');
    } else {
        if (!in_array($extensionArchivo, $formatosAdmitidos)) {
            $respuesta = array('error' => true, 'formatoIncorrecto' => true, 'mensaje' => 'El formato del archivo no es válido, actualmente sólo se admiten archivos de excel.');
        } else {
            if (move_uploaded_file($tmpnameArchivo, $directorioArchivo)) {
                $respuesta = array('correcto' => true, 'archivoSubido' => true, 'mensaje' => 'El archivo fue subido correctamente.');
                require_once('../PHPExcel.php');
                require_once('../PHPExcel/Writer/CSV.php');

                $inputFileType  = PHPExcel_IOFactory::identify($directorioArchivo);
                $reader = PHPExcel_IOFactory::createReader($inputFileType);
                $reader->setReadDataOnly(false);
                $excel = $reader->load($directorioArchivo);

                $writer = PHPExcel_IOFactory::createWriter($excel, 'CSV');
                $writer->setDelimiter(';');
                $writer->save(dirname(dirname(__FILE__)) . '/CSV/' . $archivoCSV);

                $conexion = connection(DB_ABMMOD);
                $q = "INSERT INTO pagos_rechazados 
                            (cedula, nombre, telefono, tipo_de_cobro, motivo_de_rechazo, mes_seis, mes_cinco, mes_cuatro, mes_tres, mes_dos, mes_anterior, deuda_total, nro_talon, prioridad_llamada, nro_tarjeta, cedula_titular, mes_registro, anho_registro) 
                            VALUES 
                            (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                $r = $conexion->prepare($q);
                $mes = $_POST['mes'];
                $anho = $_POST['anho'];
                $csvFile = file(dirname(dirname(__FILE__)) . '/CSV/' . $archivoCSV);
                $i = 1;
                $tipoDeArchivo = str_getcsv($csvFile[0], ";");
                $tipoDeArchivo = ($tipoDeArchivo[0] == 'Convenio')
                    ? 0
                    : 1;
                if ($tipoDeArchivo == 0) {
                    foreach ($csvFile as $line) {
                        $data = str_getcsv($line, ";");
                        if (!is_numeric($data[1])) {
                            continue;
                        }
                        $tipoDeCobro        = explode("-", $data[6]);
                        if ($tipoDeCobro[1] != 'VIDA') continue;
                        else if ($tipoDeCobro[0] == 'CENTRALIZADO') $tipoDeCobro = 0;
                        else if ($tipoDeCobro[0] == 'DOMICILIARIO') $tipoDeCobro = 1;
                        $cedula             = $data[1];
                        $nombre             = $data[2];
                        $telefono           = (strlen($data[3]) > 6)    ? corregirTelefono($data[3])    : null;
                        $motivoDeRechazo    = (!empty($data[10]))       ? $data[10]                     : null;
                        $mesSeis            = ($data[11] != 0)          ? $data[11]                     : null;
                        $mesCinco           = ($data[13] != 0)          ? $data[13]                     : null;
                        $mesCuatro          = ($data[14] != 0)          ? $data[14]                     : null;
                        $mesTres            = ($data[15] != 0)          ? $data[15]                     : null;
                        $mesDos             = ($data[16] != 0)          ? $data[16]                     : null;
                        $mesAnterior        = ($data[17] != 0)          ? $data[17]                     : null;
                        $deudaTotal         = $data[18];
                        $nroTalon           = $data[20];
                        $prioridadLlamada   = $data[21];
                        $nroTarjeta         = ($data[24] != 0)          ? $data[24]                     : null;
                        $cedulaTitular      = ($data[25] != 0)          ? $data[25]                     : null;
                        // if(validarCedula($cedula)){
                        $datos[$i] = array($cedula, $nombre, $telefono, $tipoDeCobro, $motivoDeRechazo, $mesSeis, $mesCinco, $mesCuatro, $mesTres, $mesDos, $mesAnterior, $deudaTotal, $nroTalon, $prioridadLlamada, $nroTarjeta, $cedulaTitular);
                        // } else {
                        //     if(strlen($cedula) != 0 && $i !== 1){
                        //         $errorCI = (!isset($errorCI))
                        //         ? $i
                        //         : $errorCI . ', ' . $i;
                        //     }
                        // }
                        $i++;
                    }
                } else if ($tipoDeArchivo == 1) {
                    $motivoDeRechazo = null;
                    $mesSeis = null;
                    $nroTarjeta = null;
                    $cedulaTitular = null;
                    foreach ($csvFile as $line) {
                        $data = str_getcsv($line, ";");
                        if (!is_numeric($data[0])) continue;
                        if ($data[7] != 'DOMICILIARIO-VIDA') continue;
                        else $tipoDeCobro = 1;
                        $cedula             = $data[0];
                        $nombre             = $data[1];
                        $telefono           = (strlen($data[2]) > 6)    ? corregirTelefono($data[2])    : null;
                        $mesCinco           = ($data[13] != 0)          ? $data[13]                     : null;
                        $mesCuatro          = ($data[14] != 0)          ? $data[14]                     : null;
                        $mesTres            = ($data[15] != 0)          ? $data[15]                     : null;
                        $mesDos             = ($data[16] != 0)          ? $data[16]                     : null;
                        $mesAnterior        = ($data[17] != 0)          ? $data[17]                     : null;
                        $deudaTotal         = $data[18];
                        $nroTalon           = $data[21];
                        $prioridadLlamada   = $data[22];
                        // if(validarCedula($cedula)){
                        $datos[$i] = array($cedula, $nombre, $telefono, $tipoDeCobro, $motivoDeRechazo, $mesSeis, $mesCinco, $mesCuatro, $mesTres, $mesDos, $mesAnterior, $deudaTotal, $nroTalon, $prioridadLlamada, $nroTarjeta, $cedulaTitular);
                        // } else {
                        //     if(strlen($cedula) != 0 && $i !== 1){
                        //         $errorCI = (!isset($errorCI))
                        //         ? $i
                        //         : $errorCI . ', ' . $i;
                        //     }
                        // }
                        $i++;
                    }
                }
                // if(isset($errorCI)){
                //     $respuesta = (count(explode(',', $errorCI)) != 1)
                //     ? array('error' => true, 'registroErroneo' => true, 'mensaje' => "La cédula de los registros: $errorCI son incorrectas.\nPor favor corrija esos registros y vuelva a intentar.")
                //     : array('error' => true, 'registroErroneo' => true, 'mensaje' => "La cédula del registro $errorCI es incorrecta.\nPor favor corrija ese registro y vuelva a intentar.");
                // } else{
                for ($j = 1; $j < $i; $j++) {
                    $cedula             = $datos[$j][0];
                    $nombre             = $datos[$j][1];
                    $telefono           = $datos[$j][2];
                    $tipoDeCobro        = $datos[$j][3];
                    $motivoDeRechazo    = $datos[$j][4];
                    $mesSeis            = $datos[$j][5];
                    $mesCinco           = $datos[$j][6];
                    $mesCuatro          = $datos[$j][7];
                    $mesTres            = $datos[$j][8];
                    $mesDos             = $datos[$j][9];
                    $mesAnterior        = $datos[$j][10];
                    $deudaTotal         = $datos[$j][11];
                    $nroTalon           = $datos[$j][12];
                    $prioridadLlamada   = $datos[$j][13];
                    $nroTarjeta         = $datos[$j][14];
                    $cedulaTitular      = $datos[$j][15];
                    $r->bind_param('issisiiiiiisiiiiii', $cedula, $nombre, $telefono, $tipoDeCobro, $motivoDeRechazo, $mesSeis, $mesCinco, $mesCuatro, $mesTres, $mesDos, $mesAnterior, $deudaTotal, $nroTalon, $prioridadLlamada, $nroTarjeta, $cedulaTitular, $mes, $anho);
                    $r->execute();
                }
                $respuesta  = array('correcto' => true, 'archivoSubido' => true, 'mensaje' => 'Archivo subido de forma correcta.');
                //}
            } else {
                unlink(dirname(dirname(__FILE__)) . '/CSV/' . $archivoCSV);
                $respuesta = array('error' => true, 'errorDesconocido' => true, 'mensaje' => 'Ha ocurrido un error inesperado, por favor comuníquese con el administrador');
            }
        }
        unlink(dirname(dirname(__FILE__)) . '/xlsx/' . $nombreArchivo);
    }
} else $respuesta = array('error' => true, 'noProcesaDatos' => true, 'mensaje' => 'Ha ocurrido un error inesperado, por favor comuníquese con el administrador');

echo json_encode($respuesta);

function corregirTelefono($param)
{
    if (strlen($param) > 7) {
        //EN CASO DE QUE EL TELÉFONO EMPIECE CON '0' Y UN ESPACIO LOS QUITA DE LA VARIABLE
        if (mb_substr($param, 0, 2) == '0 ') $param = mb_substr($param, 2, 20);

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
        if (($param[0] == 2 || $param[0] == 4) && strlen($param) > 7) $telefono = mb_substr($param, 0, 8);

        //SI EL LARGO DE LA VARIABLE ES IGUAL A 17 (LA SUMA DE LOS 9 CARACTERES DE UN TELÉFONO MÁS LOS 8 DE UN CELULAR) DIVIDE EL STRING
        if (strlen($param) == 17) {
            //EN CASO DE QUE CONTENGA LA SINTÁXIS DE TELÉFONO SE LE ASIGNA LA mb_substrING A LA VARIABLE TELEFONO

            if (isset($param[9]) && ($param[9] == 2 || $param[9] == 4) && mb_substr($param, 7, 10) != '09') $telefono = mb_substr($param, 10, 18);

            //EN CASO DE QUE CONTENGA LA SINTÁXIS DE CELULAR SE LE ASIGNA LA mb_substrING A LA VARIABLE CELULAR
            if (isset($param[8]) && mb_substr($param, 8, 2) == '09') $celular = mb_substr($param, 8, 18);
        }
    }

    if (isset($telefono) && isset($celular)) return $telefono . " " . $celular;
    else if (isset($telefono)) return $telefono;
    else if (isset($celular)) return $celular;
    else return null;
}

function validarCedula($CedulaDeIdentidad)
{
    $regexCI = '/^([0-9]{1}[.]?[0-9]{3}[.]?[0-9]{3}[-]?[0-9]{1}|[0-9]{3}[.]?[0-9]{3}[-]?[0-9]{1})$/';
    if (!preg_match($regexCI, $CedulaDeIdentidad)) return false;
    else {
        $numeroCedulaDeIdentidad = preg_replace("/[^0-9]/", "", $CedulaDeIdentidad);
        $arrayCoeficiente = [2, 9, 8, 7, 6, 3, 4, 1];
        $suma = 0;
        $lenghtArrayCoeficiente = 8;
        $lenghtCedulaDeIdentidad = strlen($numeroCedulaDeIdentidad);
        if ($lenghtCedulaDeIdentidad == 7) {
            $numeroCedulaDeIdentidad = 0 . $numeroCedulaDeIdentidad;
            $lenghtCedulaDeIdentidad++;
        }
        for ($i = 0; $i < $lenghtCedulaDeIdentidad; $i++) {
            $digito = substr($numeroCedulaDeIdentidad, $i, 1);
            $digitoINT = intval($digito);
            $coeficiente = $arrayCoeficiente[$i];
            $suma = $suma + $digitoINT * $coeficiente;
        }
        return (($suma % 10) == 0);
    }
}
