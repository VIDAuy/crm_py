<?php
session_start();
$array_variables_sesion = ["usuario_py", "nivel_py", "filial_py", "id_py", "email_py", "avisar_a_py"];
foreach ($array_variables_sesion as $variable_sesion) {
    unset($_SESSION[$variable_sesion]);
}
header('location: login.php');
