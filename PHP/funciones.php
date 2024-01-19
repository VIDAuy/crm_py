<?php

function obtener_email($usuario)
{
    $conexion = connection(DB);
    $tabla = TABLA_USUARIOS;

    $sql = "SELECT email FROM {$tabla} WHERE usuario = '{$usuario}'";
    $consuta = mysqli_query($conexion, $sql);

    return mysqli_fetch_assoc($consuta)['email'];
}
