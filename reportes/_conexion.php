<?php
$produccion = true;
$user = 'root';
$password = 'root';
$host = 'localhost';
$database = $produccion == false ? 'crm_py_test' : 'crm_py';

$mysqli = new mysqli($host, $user, $password, $database);

if (!$mysqli) die('Error al conectar a la base de datos');
