<?php
if (isset($_SESSION['usuario_py'])) {
	$array_variables_sesion = ["usuario_py", "nivel_py", "filial_py", "id_py", "email_py", "avisar_a_py"];
	foreach ($array_variables_sesion as $variable_sesion) {
		unset($_SESSION[$variable_sesion]);
	}
}
session_start();


include '../configuraciones.php';
$conexion = connection(DB);


$usuario = $_POST['usuario'];
$password = $_POST['password'];

if ($usuario == "" || $password == "") devolver_error(ERROR_GENERAL);



$comprobar_usuario = comprobar_usuario($usuario, $password);
if (mysqli_num_rows($comprobar_usuario) <= 0) devolver_error("Usuario o contraseña incorrecta.");


$row = mysqli_fetch_assoc($comprobar_usuario);
$_SESSION['usuario_py']  = ucfirst(strtolower($usuario));
$_SESSION['nivel_py'] 	 = $row['nivel'];
$_SESSION['filial_py'] 	 = $row['filial'];
$_SESSION['id_py']		 = $row['id'];
$_SESSION['email_py']	 = $row['email'];
$_SESSION['avisar_a_py'] = $row['avisar_a'];


$response['error'] = false;
$response['mensaje'] = "Bienvenido.";
echo json_encode($response);




function comprobar_usuario($usuario, $password)
{
	$conexion = connection(DB);
	$tabla = TABLA_USUARIOS;

	$sql = "SELECT id, nivel, filial, email, avisar_a FROM {$tabla} WHERE usuario = '$usuario' AND codigo = '$password'";
	$consulta = mysqli_query($conexion, $sql);

	return $consulta;
}
