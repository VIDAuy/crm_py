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
