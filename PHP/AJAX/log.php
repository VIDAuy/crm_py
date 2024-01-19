<?php
include '../configuraciones.php';
$conexion = connection(DB);


if (isset($_SESSION['usuario_py'])) session_destroy();

session_start();

$usuario = $_POST['usuario'];
$password = $_POST['password'];

$q = "SELECT 
		id, 
		nivel, 
		filial,
		email,
		avisar_a
	FROM
		usuarios
	WHERE
		usuario = '$usuario' AND
		codigo = '$password'";

$r = mysqli_query($conexion, $q);

if (mysqli_num_rows($r) != 1) {
	$respuesta = array('result' => false, 'error' => true, 'message' => 'Usuario o contraseña incorrecta.');
} else {
	$f = mysqli_fetch_assoc($r);
	$_SESSION['usuario_py']  = ucfirst(strtolower($usuario));
	$_SESSION['nivel_py'] 	 = $f['nivel'];
	$_SESSION['filial_py'] 	 = $f['filial'];
	$_SESSION['id_py']		 = $f['id'];
	$_SESSION['email_py']	 = $f['email'];
	$_SESSION['avisar_a_py'] = $f['avisar_a'];
	$respuesta = array('result' => true);
}

echo json_encode($respuesta);
