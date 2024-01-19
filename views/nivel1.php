<script>
	$(function() {
		$('#ci').keypress(function(e) {
			if (e.which == 13) {
				listarDatos($('#ci').val());
			}
		});
	})
</script>

<input type="hidden" id="filial_py" value="<?= $_SESSION['filial_py'] ?>">
<input type="hidden" id="sector_py" value="<?= ucfirst($_SESSION['usuario_py']) ?>">
<input type="hidden" id="nivel_py" value="<?= $_SESSION['nivel_py'] ?>">
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
	<div class="form-row my-3">
		<input type="text" class="form-control solo_numeros" id="ci" name="ci" placeholder="Ingrese Cedula a Buscar" maxlength="8">
	</div>
	<div class="form-row justify-content-between">
		<span style="float: right">
			<input type="button" class="btn btn-primary" value="Buscar" title="Buscar" onclick="buscar();" id="buscarCI">
			<!--<input type="button" id="botonHistorialDeBajas" class="btn btn-primary" value="Ver historial de bajas" onclick="historialDeBajas();">-->
		</span>
		<input type="button" class="btn btn-danger" value="Solicitar la baja" onclick="listarDatos($('#ci').val());">
	</div>
</div>