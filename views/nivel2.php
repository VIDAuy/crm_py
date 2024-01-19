<input type="hidden" id="sector_py" value="<?= ucfirst($_SESSION['usuario_py']) ?>">
<input type="hidden" id="nivel_py" value="<?= $_SESSION['nivel_py'] ?>">
<input type="hidden" id="idrelacion">
<nav class="navbar navbar-dark bg-dark text-white mb-4">
	<a class="navbar-brand">Menú</a>
	<a class="navbar-brand"><i class="bi bi-person-circle"></i> <strong> <?= ucfirst($_SESSION['usuario_py']) ?> </strong></a>
	<form class="form-inline">
		<a class="btn btn-outline-danger" style="float: right" href="http://192.168.1.250:82/crm_py/PHP/cerrarSesion.php">Cerrar sesión</a>
	</form>
</nav>
<div class="container" align="center">
	<h2 style="color:#FB0B0F">CRM</h2>
	<div style="display: flex; justify-content: space-between; align-items: center;">
		<div id="q" style="visibility: hidden;">
			<button type="button" class="btn btn-primary" onclick="datosAlertas()">ALERTAS <span id="bq" class="badge badge-light rounded-circle">?</span></button>
		</div>
	</div>
</div>
<div class="container mt-3">
	<input type="text" class="form-control solo_numeros" id="ci" name="ci" placeholder="Ingrese Cedula a Buscar" oninput="ocultarContenido()" style="margin-bottom: 3px;" maxlength="8">
</div>
<div class="container my-1">
	<input type="button" class="btn btn-primary btn-block" value="Buscar" title="Buscar" onclick="buscar();" id="buscarCI">
</div>