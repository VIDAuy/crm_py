<script>
	$(function() {
		$('#ci').keypress(function(e) {
			if (e.which == 13) {
				buscar();
			}
		});
	})
</script>
<input type="hidden" id="usuario_logueado_py" value="<?= ucfirst($_SESSION['usuario_py']) ?>">
<input type="hidden" id="sector_py" value="<?= ucfirst($_SESSION['avisar_a_py']) ?>">
<input type="hidden" id="nivel_py" value="<?= $_SESSION['nivel_py'] ?>">
<input type="hidden" id="idrelacion">

<nav class="navbar navbar-dark bg-dark text-white mb-4">
	<a class="navbar-brand">Menú</a>
	<a class="navbar-brand"><i class="bi bi-person-circle"></i> <strong> <?= ucfirst($_SESSION['avisar_a_py']) ?> </strong></a>
	<form class="form-inline">
		<a class="btn btn-outline-danger" style="float: right" href="http://192.168.1.250:82/crm_py/PHP/cerrarSesion.php">Cerrar sesión</a>
	</form>
</nav>
<div class="container" align="center">
	<h2 style="color:#FB0B0F">CRM</h2>
	<div style="display: flex; justify-content: end; align-items: end;">
		<!--	
	<a href="http://192.168.1.13/call/panel_calidad" class="btn btn-primary">Afiliaciones competencia/convenios especiales <span class="badge badge-light rounded-circle" id="badgeAfiliacionesCompetencia">0</span></a>
	-->
		<div id="q" style="visibility: hidden;">
			<button type="button" class="btn btn-sm btn-primary rounded" onclick="datosAlertas()">ALERTAS <span id="bq" class="badge badge-light rounded-circle">?</span></button>
		</div>
	</div>
</div>
<div class="d-flex justify-content-center">
	<div class="container mt-5 mb-3">
		<div class="row">
			<div class="col-lg-6">
				<div class="col-auto">
					<div class="input-group">
						<div class="input-group-prepend">
							<label class="input-group-text bg-secondary text-white" for="inputGroupSelect01">Cédula:</label>
						</div>
						<?php
						if ($_SESSION['usuario_py'] == 'Morosos' || $_SESSION['usuario_py'] == 'Calidad_interna') {
							echo '<input type="text" class="form-control" id="ci" name="ci" placeholder="Ingrese cédula a buscar ..." oninput="ocultarContenido()" maxlength="8">';
						} else {
							echo '<input type="text" class="form-control solo_numeros" id="ci" name="ci" placeholder="Ingrese cédula a buscar ..." oninput="ocultarContenido()" maxlength="8">';
						}
						?>

						<div class="input-group-prepend">
							<?php
							if ($_SESSION['usuario_py'] == 'Morosos' || $_SESSION['usuario_py'] == 'Calidad_interna') {
								echo '<input type="button" class="btn btn-danger rounded-end" value="Buscar 🔍" title="Buscar" onclick="buscarDatos();" id="buscarCI" style="padding: 3px 10px; border: 5px; border-top-right-radius: 15px; border-bottom-right-radius: 15px;">';
							} else {
								echo '<input type="button" class="btn btn-danger rounded-end" value="Buscar 🔍" title="Buscar" onclick="buscarSocio();" id="buscarCI" style="padding: 3px 10px; border: 5px; border-top-right-radius: 15px; border-bottom-right-radius: 15px;">';
							}
							?>
						</div>
					</div>
				</div>
			</div>
			<?php
			if ($_SESSION['usuario_py'] == 'Morosos' || $_SESSION['usuario_py'] == 'Calidad_interna') {
				echo '<div class="col-lg-3"><div class="form-check">
					<input class="form-check-input" type="radio" name="radioBuscar" id="buscarSocio" value="socio" checked>
					<label class="form-check-label" for="buscarSocio">
						Socio
					</label>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="form-check">
					<input class="form-check-input" type="radio" name="radioBuscar" id="buscarFuncionario" value="funcionario">
					<label class="form-check-label" for="buscarFuncionario">
						Funcionario
					</label>
				</div>
			</div>';
			}
			?>
		</div>
	</div>
</div>
<div class="container">
	<span style="float: right">
		<?php
		//SOLO CALIDAD Y BIENVENIDA PUEDEN GESTIONAR LAS BAJAS
		if ($_SESSION['usuario_py'] == 'Calidaduy' || $_SESSION['usuario_py'] == '1707544') {
			$usuario = $_SESSION['usuario_py'] == "Calidaduy" ? 1 : 2;
			echo '
				<button type="button" class="btn btn-success position-relative mr-3" onclick="corroborarBajas(' . $usuario . ');"> 
			    	Gestionar bajas
			    		<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cantidad_gestion_bajas_pendientes" value="' . $usuario . '">
		  				</span>
		  		  </button>';
		}
		?>
		<input type="button" id="botonHistorialDeBajas" class="btn btn-primary" value="Ver historial de bajas" onclick="historialDeBajas();">
	</span>

	<input type="button" class="btn btn-danger" value="Solicitar la baja" onclick="listarDatos($('#ci').val());">
</div>


<?php
if ($_SESSION['usuario_py'] == 'Morosos' || $_SESSION['usuario_py'] == 'Calidad_interna') {
	echo '<br>
	<div class="container">
		<hr class="style5 container">
			<div class="alert alert-dark border border-dark" role="alert">
				<button class="btn btn-primary" onclick="cargar_documento_y_alertar()">Cargar Documento 📃</button>
					<span style="float: right">
						<button type="button" class="btn btn-primary position-relative" onclick="alertas_de_documentos_cargados()">
							Alertas de funcionarios 🔔
								<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cantidad_pendientes_leer"></span>
						</button>
					</span>
			</div>
	</div>';
}

?>