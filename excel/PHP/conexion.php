<?php
$produccion = true;

$host = 'localhost';
$user = 'root';
$pass = 'root';
$base = $produccion ? 'crm_py' : 'crm_py_test';

if (mysqli_connect($host, $user, $pass, $base)) {
    return $mysqli = new mysqli($host, $user, $pass, $base);
} else {
    return mysqli_connect_errno() . PHP_EOL;
}
