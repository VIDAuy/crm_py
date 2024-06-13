<?php
session_start();
$array_variables_sesion = ["usuario_py", "nivel_py", "filial_py", "id_py", "email_py", "avisar_a_py"];
foreach ($array_variables_sesion as $variable_sesion) {
	unset($_SESSION[$variable_sesion]);
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="icon" href="./assets/img/favicon.png" type="image/png">
	<title>CRM_PY</title>

	<!-- Bootstrap 4.1.3 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
	<link href="https://getbootstrap.com/docs/5.3/assets/css/docs.css" rel="stylesheet">
	<!-- Archivo CSS -->
	<link rel="stylesheet" href="./assets/css/login.css">
	<link rel="stylesheet" href="./assets/css/tabla.css">
</head>

<body>

	<div class="container">
		<div class="row">
			<div class="col-sm-6 col-md-4 col-md-offset-4 mx-auto">
				<div class="account-wall">
					<div class="form-signin">
						<img class="profile-img mb-2" src="assets/img/globito.jpg" alt="Logo de vida" />
						<h2 class="text-center fw-bolder mb-3" id="titulo_login">CRM PY</h2>
						<input type="text" class="form-control" id="txt_usuario" placeholder="Usuario" autofocus>
						<input type="password" class="form-control mb-3" id="txt_password" placeholder="Código">
						<div class="text-center">
							<button class="btn text-white w-100" id="btn_login" onclick="login()">Ingresar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



	<!-- JQUERY 2.2.3 -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
	<!-- SweetAlert 2@10 -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
	<!-- Archivo JS -->
	<script src="./assets/js/funciones.js"></script>
	<script src="./assets/js/utils.js"></script>
	<script src="./assets/js/login.js"></script>

</body>

</html>