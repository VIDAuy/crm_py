<nav class="navbar navbar-dark bg-dark text-white mb-4">
	<a class="navbar-brand">Menú</a>
	<a class="navbar-brand"><i class="bi bi-person-circle"></i> <strong> <?= ucfirst($_SESSION['usuario_py']) ?> </strong></a>
	<form class="form-inline">
		<a class="btn btn-outline-danger" style="float: right" href="http://192.168.1.250:82/crm_py/PHP/cerrarSesion.php">Cerrar sesión</a>
	</form>
</nav>

<div class="container">
	<h1 class="text-center">CRM</h1>

	<form id="buscarCI">
		<div class="form-row">
			<div class="form-group col-10">
				<input type="text" id="CI" class="solo-numeros form-control" placeholder="Ingrese la cédula del empleado" maxlength="8" oninput="ocultarInformacion()">
			</div>
			<div class="form-group col-2">
				<input type="button" id="enviarCI" class="btn btn-primary btn-block" value="Buscar CI">
			</div>
		</div>
	</form>

	<div id="informacion">
		<h3>Datos personales:</h3>
		<div class="form-row">
			<div class="form-group col-4">
				<label for="nombreCompleto">Nombre completo:</label>
				<input type="text" id="nombreCompleto" class="form-control" readonly>
			</div>
			<div class="form-group col-2">
				<label for="telefono">Teléfono:</label>
				<input type="text" id="telefono" class="form-control" readonly>
			</div>
			<div class="form-group col-2">
				<label for="cedula">Cédula:</label>
				<input type="text" id="cedula" class="form-control" readonly>
			</div>
			<div class="form-group col-2">
				<label for="fechaNacimiento">Fecha nacimiento:</label>
				<input type="text" id="fechaNacimiento" class="form-control" readonly>
			</div>
			<div class="form-group col-2">
				<label for="departamento">Departamento:</label>
				<input type="text" id="departamento" class="form-control" readonly>
			</div>
		</div>
		<hr>
		<h3>Datos empleado:</h3>
		<div class="form-row">
			<div class="form-group col-2">
				<label for="fechaIngreso">Fecha ingreso:</label>
				<input type="text" id="fechaIngreso" class="form-control" readonly>
			</div>
			<div class="form-group col-2">
				<label for="fechaEgreso">Fecha egreso:</label>
				<input type="text" id="fechaEgreso" class="form-control" readonly>
			</div>
			<div class="form-group col-2">
				<label for="estado">Estado actual:</label>
				<input type="text" id="estado" class="form-control" readonly>
			</div>
		</div>
		<hr>
		<h3>Datos servicio:</h3>
		<div class="form-row">
			<div class="form-group col-2">
				<label for="estado">Estado:</label>
				<input type="text" class="form-control" id="estado" readonly>
			</div>
			<div class="form-group col-2">
				<label for="ultimoServicio">Último servicio:</label>
				<input type="text" class="form-control" id="ultimoServicio" readonly>
			</div>
			<div class="form-group col-2">
				<label for="proximoServicio">Proximo servicio:</label>
				<input type="text" class="form-control" id="proximoServicio" readonly>
			</div>
			<div class="form-group col-2">
				<label for="desde">Desde:</label>
				<input type="text" class="form-control" id="desde" readonly>
			</div>
			<div class="form-group col-2">
				<label for="hasta">Hasta:</label>
				<input type="text" class="form-control" id="hasta" readonly>
			</div>
		</div>
	</div>
</div>