<?php

/** Obtener el mail del usuario **/
function obtener_email($usuario)
{
    $conexion = connection(DB);
    $tabla = TABLA_USUARIOS;

    $sql = "SELECT email FROM {$tabla} WHERE usuario = '{$usuario}'";
    $consuta = mysqli_query($conexion, $sql);

    return mysqli_fetch_assoc($consuta)['email'];
}


/** Obtener mail envio sector **/
function obtener_email_envioSector($envioSector)
{
    $conexion = connection(DB);
    $tabla = TABLA_USUARIOS;

    $sql = "SELECT email, avisar_a FROM {$tabla} WHERE id = '$envioSector'";
    $consulta = mysqli_query($conexion, $sql);

    return mysqli_fetch_assoc($consulta);
}


/** Generar hash **/
function generarHash($largo)
{
    $caracteres_permitidos = '0123456789abcdefghijklmnopqrstuvwxyz';
    return substr(str_shuffle($caracteres_permitidos), 0, $largo);
}


/** Controlar extención del archivo **/
function controlarExtension($files, $tipo)
{
    $validar_extension = $tipo;
    $valido = 0;
    for ($i = 0; $i < count($files["name"]); $i++) {
        $extension_archivo = strtolower(pathinfo(basename($files["name"][$i]), PATHINFO_EXTENSION));

        if (in_array($extension_archivo, $validar_extension)) {
            $valido++;
        } else {
            $valido = 0;
        }
    }
    return $valido;
}


function corregirTelefono($var)
{
    // CORRECCIÓN Y ASIGNACIÓN DE TELÉFONO
    if (strlen($var) === 0) return 'Sin datos';

    //EN CASO DE QUE EL TELÉFONO EMPIECE CON '0' Y UN ESPACIO LOS QUITA DE LA VARIABLE
    if (mb_substr($var, 0, 2) == '0 ') $var = mb_substr($var, 2, 20);

    //REEMPLAZA TODOS LOS ESPACIOS QUE TENGA LA VARIABLE
    $var = str_replace(' ', '', $var);
    if ($var[0] == 9) $var = 0 . $var;

    //EN CASO DE QUE EL NÚMERO EMPIECE CON 09 Y TENGA MÁS DE 8 CARACTÉRES SE LE ASIGNA ESE VALOR A LA VARIABLE CELULAR
    if (mb_substr($var, 0, 2) == '09' && strlen($var) > 8) {
        $celularFuncion = strlen($var) == 10 ? mb_substr($var, 0, 10) : mb_substr($var, 0, 9);
    }

    ///EN CASO DE QUE EL NÚMERO EMPIECE CON 2 O 4 Y TENGA MÁS DE 7 CARACTÉRES SE LE ASIGNA ESE VALOR A LA VARIABLE TELEFONO
    if (($var[0] == 2 || $var[0] == 4) && strlen($var) > 7) $telefonoFuncion = mb_substr($var, 0, 8);

    //SI EL LARGO DE LA VARIABLE ES IGUAL A 17 (LA SUMA DE LOS 9 CARACTERES DE UN TELÉFONO MÁS LOS 8 DE UN CELULAR) DIVIDE EL STRING
    if (strlen($var) == 17) {
        //EN CASO DE QUE CONTENGA LA SINTÁXIS DE TELÉFONO SE LE ASIGNA LA mb_substrING A LA VARIABLE TELEFONO
        if (isset($var[9]) && ($var[9] == 2 || $var[9] == 4) && mb_substr($var, 7, 9) != '09') $telefonoFuncion = mb_substr($var, 9, 18);
        //EN CASO DE QUE CONTENGA LA SINTÁXIS DE CELULAR SE LE ASIGNA LA mb_substrING A LA VARIABLE CELULAR
        if (isset($var[8]) && mb_substr($var, 8, 2) == '09') $celularFuncion = mb_substr($var, 8, 18);
    }

    //EN CASO DE QUE LA VARIABLE CELULAR NO SE HAYA DEFINIDO LE ASIGNA UN STRING VACÍO PARA NO GENERAR CONFLICTOS EN LA QUERY
    if (!isset($celularFuncion)) $celularFuncion = null;
    if (!isset($telefonoFuncion)) $telefonoFuncion = null;

    if ($telefonoFuncion != null && $celularFuncion != null) {
        $telFuncion = $telefonoFuncion;
        $telFuncion .= ' ';
        $telFuncion .= $celularFuncion;
    } else if ($telefonoFuncion != null && $celularFuncion == '') {
        $telFuncion = $telefonoFuncion;
    } else if ($telefonoFuncion == '' && $celularFuncion != null) {
        $telFuncion = $celularFuncion;
    } else {
        $telFuncion = '';
    }

    unset($telefonoFuncion);
    unset($celularFuncion);

    return $telFuncion;
}



function devolver_error($mensaje)
{
    $response['error'] = true;
    $response['mensaje'] = $mensaje;
    die(json_encode($response));
}


/** Registrar errores en la base de datos **/
function registrar_errores($consulta, $nombre_archivo, $error)
{
    $conexion = connection(DB);
    $tabla = TABLA_LOG_ERRORES;

    $consulta = str_replace("'", '"', $consulta);
    $error = str_replace("'", '"', $error);

    $sql = "INSERT INTO {$tabla} (consulta, nombre_archivo, error, fecha_registro) VALUES ('$consulta', '$nombre_archivo', '$error', NOW())";
    $consulta = mysqli_query($conexion, $sql);

    return $consulta;
}