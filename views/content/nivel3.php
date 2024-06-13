<?php
$produccion = true; // para definir si es test o producción la APP
$protocol   = $produccion ? "http" : "http";
$host       = $produccion ? "192.168.1.250:82" : "192.168.1.250:82";
$app        = $produccion ? "crm_py" : "crm_py_test";
$url_app    = "$protocol://$host/$app/";
?>


<input type="hidden" id="usuario_logueado_py" value="<?= ucfirst($_SESSION['usuario_py']) ?>">
<input type="hidden" id="sector_py" value="<?= ucfirst($_SESSION['avisar_a_py']) ?>">
<input type="hidden" id="nivel_py" value="<?= $_SESSION['nivel_py'] ?>">
<input type="hidden" id="idrelacion">


<nav class="navbar bg-dark border-bottom border-body mb-4" data-bs-theme="dark">
	<div class="container-fluid">
		<a class="navbar-brand">Menú</a>
		<a class="navbar-brand">
			<div class="d-flex">
				<i class="bi bi-person-circle me-1"></i>
				<span class="fw-bolder"><?= ucfirst($_SESSION['avisar_a_py']) ?></span>
			</div>
		</a>
		<a class="btn btn-outline-danger" href='<?= $url_app . "cerrar_sesion.php" ?>'>Cerrar Sesión</a>
	</div>
</nav>





<div class="container" align="center">
	<h2 style="color:#FB0B0F">CRM PY</h2>
	<div style="display: flex; justify-content: end; align-items: end;">
		<button type="button" class="btn btn-primary position-relative" onclick="abrir_modal_alertas_pendientes()">
			Alertas
			<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="span_cantidad_alertas_pendientes">?</span>
		</button>
	</div>
</div>
<div class="d-flex justify-content-center">
	<div class="container mt-5 mb-3">
		<div class="row">
			<div class="col-lg-6">
				<div class="col-auto">
					<div class="input-group mb-3">
						<span class="bg-secondary text-white input-group-text">Cédula:</span>
						<input type="text" class="form-control solo_numeros" id="txt_cedula_buscar" name="txt_cedula_buscar" placeholder="Ingrese cédula a buscar ..." oninput="ocultarContenido()" maxlength="8">
						<button class="btn btn-danger input-group-text" onclick="buscarSocio()">Buscar 🔍</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<span style="float: right">
		<?php
		//SOLO CALIDAD Y BIENVENIDA PUEDEN GESTIONAR LAS BAJAS
		if ($_SESSION['usuario_py'] == 'Calidaduy' || $_SESSION['usuario_py'] == '1707544') {
			$usuario = $_SESSION['usuario_py'] == "Calidaduy" ? 1 : 2;
			echo '<button type="button" class="btn btn-success position-relative mr-3" onclick="corroborarBajas(' . $usuario . ');"> 
			    	Gestionar bajas
			    	<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cantidad_gestion_bajas_pendientes" value="' . $usuario . '"></span>
		  		  </button>';
		}
		?>
		<input type="button" id="botonHistorialDeBajas" class="btn btn-primary" value="Ver historial de bajas" onclick="historialDeBajas();">
	</span>

	<input type="button" class="btn btn-danger" value="Solicitar la baja" onclick="listarDatos($('#txt_cedula_buscar').val());">
</div>