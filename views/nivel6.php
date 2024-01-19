<script>
	$(function() {
		$('#ci').keypress(function(e) {
			if (e.which == 13) {
				buscar();
			}
		});
	})
</script>
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
<div class="container mb-5" align="center">
	<h2 style="color:#FB0B0F">CRM</h2>
</div>


<!-- Contenedor General -->
<div class="container">


	<!-- Consultas por cédula -->
	<div class="alert alert-info border-info" role="alert">
		<div class="d-flex justify-content-end">
			<button type="button" class="btn btn-primary position-relative" onclick="alertas_de_documentos_cargados()">
				Alertas de funcionarios 🔔
				<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" id="cantidad_pendientes">
				</span>
			</button>
		</div>
		<h3 class="text-center mb-3"><u>Consultar Cédula</u></h3>
		<div class="d-flex justify-content-center">
			<div class="container mt-5 mb-3">
				<!-- Consultas de Socios y Funcionarios -->
				<div class="row">
					<div class="col-lg-6">
						<div class="col-auto">
							<div class="input-group">
								<div class="input-group-prepend">
									<label class="input-group-text bg-secondary text-white" for="inputGroupSelect01">Cédula:</label>
								</div>
								<input type="text" class="form-control" id="ci_personal" name="ci_personal" placeholder="Ingrese cédula a buscar ..." oninput="ocultarTodoContenido()" maxlength="8">
								<div class="input-group-prepend">
									<input type="button" class="btn btn-danger rounded-end" value="Buscar 🔍" title="Buscar" onclick="buscarCedula();" id="buscarCI" style="padding: 3px 10px; border: 5px; border-top-right-radius: 15px; border-bottom-right-radius: 15px;">
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="form-check">
							<input class="form-check-input" type="radio" name="radioBuscar" id="buscarSocio_personal" value="socio" checked="">
							<label class="form-check-label" for="buscarSocio">
								Socio
							</label>
						</div>
					</div>
					<div class="col-lg-3">
						<div class="form-check">
							<input class="form-check-input" type="radio" name="radioBuscar" id="buscarFuncionario_personal" value="funcionario">
							<label class="form-check-label" for="buscarFuncionario">
								Funcionario
							</label>
						</div>
					</div>
				</div>
				<!-- End Consultas de Socios y Funcionarios -->

				<hr class="style6 container mb-5">

				<!-- Vista de funcionarios -->
				<div class="container" id="contenido_funcionarios">
					<div class="alert alert-success border-success" role="alert">
						<h3 class="text-center mb-5"><u><strong>Listado de datos del acompañante</strong></u></h3>
						<!-- Listado de funcionarios -->
						<div class="form-row text-center">
							<div class="form-group col-lg-2">
								<label for="" class="col-form-label"><u>Número nodum:</u></label>
								<p id="funcionario_numero_nodum_personal" style="font-weight: bold;"></p>
							</div>
							<div class="form-group col-lg-2">
								<label for="" class="col-form-label"><u>Nombre completo:</u></label>
								<p id="funcionario_nombre_completo_personal" style="font-weight: bold;"></p>
							</div>
							<div class="form-group col-lg-2">
								<label for="" class="col-form-label"><u>Teléfono:</u></label>
								<p id="funcionario_telefono_personal" style="font-weight: bold;"></p>
							</div>
							<div class="form-group col-3">
								<label for="" class="col-form-label"><u>Correo electrónico:</u></label>
								<p id="funcionario_correo_personal" style="font-weight: bold;"></p>
							</div>
							<div class="form-group col-lg-2">
								<label for="" class="col-form-label"><u>Fecha de ingreso:</u></label>
								<p id="funcionario_fecha_ingreso_personal" style="font-weight: bold;"></p>
							</div>
							<div class="form-group col-lg-2">
								<label for="" class="col-form-label"><u>Fecha de egreso:</u></label>
								<p id="funcionario_fecha_egreso_personal" style="font-weight: bold;"></p>
							</div>
							<div class="form-group col-lg-2">
								<label for="" class="col-form-label"><u>Empresa:</u></label>
								<p id="funcionario_empresa_personal" style="font-weight: bold;"></p>
							</div>
							<div class="form-group col-lg-2">
								<label for="" class="col-form-label"><u>Estado:</u></label>
								<p id="funcionario_estado_personal" style="font-weight: bold;"></p>
							</div>
							<div class="form-group col-lg-2">
								<label for="" class="col-form-label"><u>Cargo:</u></label>
								<p id="funcionario_cargo_personal" style="font-weight: bold;"></p>
							</div>
							<div class="form-group col-lg-2">
								<label for="" class="col-form-label"><u>Centro de costos:</u></label>
								<p id="funcionario_centro_de_costos_personal" style="font-weight: bold;"></p>
							</div>
							<div class="form-group col-lg-3">
								<label for="" class="col-form-label"><u>Tipo de comisionamiento:</u></label>
								<p id="funcionario_tipo_de_comisionamiento_personal" style="font-weight: bold;"></p>
							</div>
							<div class="form-group col-lg-2">
								<label for="" class="col-form-label"><u>Filial:</u></label>
								<p id="funcionario_filial_personal" style="font-weight: bold;"></p>
							</div>
							<div class="form-group col-lg-2">
								<label for="" class="col-form-label"><u>Sub Filial:</u></label>
								<p id="funcionario_sub_filial_personal" style="font-weight: bold;"></p>
							</div>
							<div class="form-group col-lg-2">
								<label for="" class="col-form-label"><u>Tipo de trabajador:</u></label>
								<p id="funcionario_tipo_de_trabajador_personal" style="font-weight: bold;"></p>
							</div>
							<div class="form-group col-lg-2">
								<label for="" class="col-form-label"><u>Causal de baja:</u></label>
								<p id="funcionario_causal_de_baja_personal" style="font-weight: bold;"></p>
							</div>
							<div class="form-group col-lg-2">
								<label for="" class="col-form-label"><u>Medio de pago:</u></label>
								<p id="funcionario_medio_de_pago_personal" style="font-weight: bold;"></p>
							</div>
						</div>
					</div>
					<!-- End Listado de funcionarios -->

					<hr class="style5 container">


					<div class="alert alert-warning border-warning" role="alert">
						<h3 class="text-center mb-4"><u><strong>Más Consultas</strong></u></h3>

						<div class="d-flex justify-content-center mb-5">
							<button class="btn btn-info" onclick="consulta_por_cedula('licencia')">Licencias</button>
						</div>

						<div class="d-flex justify-content-center">
							<!-- Consultas de horas y faltas -->
							<div class="row">
								<div class="col-auto mb-3">
									<div class="input-group">
										<div class="input-group-prepend">
											<label class="input-group-text bg-danger text-white" for="inputGroupSelect01">Desde</label>
										</div>
										<input type="date" class="form-control" id="cc_fecha_desde_personal">
									</div>
								</div>
								<div class="col-auto mb-3">
									<div class="input-group">
										<div class="input-group-prepend">
											<label class="input-group-text bg-danger text-white" for="inputGroupSelect01">Hasta</label>
										</div>
										<input type="date" class="form-control" id="cc_fecha_hasta_personal">
									</div>
								</div>
								<div class="col-auto mb-3">
									<button type="button" class="btn btn-info" onclick="consulta_por_cedula('horas')">Horas de acompañantes</button>
								</div>
								<div class="col-auto mb-3">
									<button type="button" class="btn btn-info" onclick="consulta_por_cedula('faltas')">Reporte de faltas</button>
								</div>
							</div>
							<!-- End Consultas de Horas y Faltas -->
						</div>
					</div>
				</div>
				<!-- End Vista de funcionarios -->

				<!-- Vista de socios -->
				<div class="container" id="contenido_socios">
					<!-- Listado de socios -->
					<div class="alert alert-success border-success" role="alert">
						<h3 class="text-center mb-5"><u><strong>Listado de datos del socio</strong></u></h3>
						<div class="form-row text-center">
							<div class="form-group col-4">
								<label for="" class="col-form-label">Nombre completo:</label>
								<p id="nom" style="font-weight: bold;"></p>
							</div>
							<div class="form-group col-4">
								<label for="" class="col-form-label">Teléfono:</label>
								<p id="telefono" style="font-weight: bold;"></p>
							</div>
							<div class="form-group col-4">
								<label for="" class="col-form-label">Fecha de afiliación:</label>
								<p id="fechafil" style="font-weight: bold;"></p>
							</div>
						</div>
						<div class="form-row text-center">
							<div class="form-group col-4">
								<label for="" class="col-form-label">Radio:</label>
								<p id="radio" style="font-weight: bold;"></p>
							</div>
							<div class="form-group col-4">
								<label for="" class="col-form-label">Sucursal:</label>
								<p id="sucursal" style="font-weight: bold;"></p>
							</div>
							<div class="form-group col-4" id="div_inspira" style="display: none;">
								<label for="" class="col-form-label">Inspira?:</label>
								<p id="inspira" style="font-weight: bold;"></p>
							</div>
						</div>
					</div>
					<!-- End Listado de socios -->

					<hr class="style5 container">


					<div class="alert alert-warning border-warning" role="alert">
						<h3 class="text-center mb-4"><u><strong>Más Consultas</strong></u></h3>

						<div class="d-flex justify-content-center">
							<!-- Consultas de horas y faltas -->
							<div class="row">
								<div class="col-auto">
									<input id="b1" type="button" class="btn btn-primary btn-sm mx-auto" onclick="consulta_por_cedula('coordinacion')" style="margin-bottom: 2px;" value="Coordinación">
								</div>
								<div class="col-auto">
									<input id="b2" type="button" class="btn btn-primary btn-sm mx-auto" onclick="consulta_por_cedula('cobranza')" style="margin-bottom: 2px;" value="Cobranza">
								</div>
								<div class="col-auto">
									<input id="b3" type="button" class="btn btn-primary btn-sm mx-auto" onclick="consulta_por_cedula('productos')" style="margin-bottom: 2px;" value="Servicios">
								</div>
							</div>
							<!-- End Consultas de Horas y Faltas -->
						</div>
					</div>
				</div>
				<!-- End Vista de socios -->
			</div>
		</div>
	</div>
	<!-- End Consultas por cédula -->

	<hr class="style5 container">

	<!-- Consultas Generales -->
	<div class="alert alert-secondary border-secondary" role="alert">
		<h3 class="text-center mb-5"><u>Consultas Generales</u></h3>
		<div class="d-flex justify-content-center mb-3">
			<div class="form-row">
				<div class="form-group">
					<div class="row g-3 align-items-center">
						<div class="col-auto">
							<div class="input-group">
								<div class="input-group-prepend">
									<label class="input-group-text bg-danger text-white" for="inputGroupSelect01">Desde</label>
								</div>
								<input type="date" class="form-control" id="cg_fecha_desde_personal">
							</div>
						</div>
						<div class="col-auto">
							<div class="input-group">
								<div class="input-group-prepend">
									<label class="input-group-text bg-danger text-white" for="inputGroupSelect01">Hasta</label>
								</div>
								<input type="date" class="form-control" id="cg_fecha_hasta_personal">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="d-flex justify-content-center mb-3">
			<div class="form-row">
				<div class="form-group">
					<div class="row g-3 align-items-center">
						<div class="col-auto mb-3">
							<button type="button" class="btn btn-info mr-4" onclick="consultas_generales('horas')">Horas de acompañantes ⏳</button>
						</div>
						<div class="col-auto mb-3">
							<button type="button" class="btn btn-info mr-4" onclick="consultas_generales('faltas')">Reporte de faltas 📕</button>
						</div>
						<div class="col-auto mb-3">
							<button class="btn btn-info" onclick="consultas_generales('licencia')">Licencias 📗</button>
						</div>
						<div class="col-auto mb-3">
							<button class="btn btn-info mr-4" onclick="consultas_generales('capacitacion_acompanantes')">Capacitación de acompañantes 📒</button>
						</div>
						<div class="col-auto mb-3">
							<button class="btn btn-danger mr-4" onclick="consultas_generales('viaticos_descontar')">Viáticos a descontar 📓</button>
						</div>
						<div class="col-auto mb-3">
							<button class="btn btn-danger" onclick="consultas_generales('listado_radios')">Listado de radios 📔</button>
						</div>
						<div class="col-auto mb-3">
							<button class="btn btn-danger mr-4" onclick="consultas_generales('archivos_cobranza')">Archivos de cobranza 📚</button>
						</div>
						<div class="col-auto mb-3">
							<button class="btn btn-danger mr-4" onclick="consultas_generales('corte_producto_abm')">Corte de producto ABM 📋</button>
						</div>
						<div class="col-auto mb-3">
							<button class="btn btn-danger" onclick="consultas_generales('resultado_comision')">Resultado de comisión 📊</button>
						</div>
						<div class="col-auto mb-3">
							<button class="btn btn-danger mr-4" onclick="consultas_generales('retenciones_socios')">Retenciones de socios 📂</button>
						</div>
						<div class="col-auto mb-3">
							<button class="btn btn-danger mr-4" onclick="consultas_generales('horas_auxiliares_limpieza')">Horas auxiliares de limpieza ⏳</button>
						</div>
						<div class="col-auto mb-3">
							<button class="btn btn-danger" onclick="consultas_generales('horas_particulares')">Horas particulares ⏳</button>
						</div>
						<div class="col-auto mb-3">
							<button class="btn btn-danger mr-4" onclick="consultas_generales('control_satisfaccion_paraguay')">Control de satisfacción Paraguay 📜</button>
						</div>
						<div class="col-auto mb-3">
							<button class="btn btn-danger mr-4" onclick="consultas_generales('uniformes_descontar')">Uniformes a descontar 📝</button>
						</div>
						<div class="col-auto mb-3">
							<button class="btn btn-danger" onclick="consultas_generales('capacitacion_comercial')">Capacitación comercial 📄</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Consultas Generales -->

	<hr class="style5 container">

	<!-- Consultas Cierre De Horas Personalizado -->
	<div class="alert alert-secondary border-secondary" role="alert">
		<h3 class="text-center mb-5"><u>Cierre de horas personalizado</u></h3>
		<form id="form1" method="post" name="form1">
			<div class="d-flex justify-content-center mb-4">
				<div class="form-row">
					<div class="form-group">
						<div class="row g-3 align-items-center">
							<div class="col-auto">
								<div class="input-group">
									<div class="input-group-prepend">
										<label class="input-group-text bg-danger text-white" for="desde">Desde:</label>
									</div>
									<input type="date" class="form-control" id="desde" name="desde">
								</div>
							</div>
							<div class="col-auto">
								<div class="input-group">
									<div class="input-group-prepend">
										<label class="input-group-text bg-danger text-white" for="hasta">Hasta:</label>
									</div>
									<input type="date" class="form-control" id="hasta" name="hasta">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="d-flex justify-content-center mb-4">
				<div class="form-check">
					<input class="form-check-input" type="checkbox" value="1" id="enBruto" name="enBruto">
					<label class="form-check-label" for="enBruto">
						Obtener Reporte En Bruto
					</label>
				</div>
			</div>
			<div class="d-flex justify-content-center">
				<div class="form-row">
					<div class="form-group">
						<div class="row g-3 align-items-center">
							<div class="col-auto mb-3">
								<button type="button" onclick="exportar_cierre_personalizado('exportarMVD')" class="btn btn-info mr-4">VIDA/ACOMPAÑAR/INSPIRA</button>
							</div>
							<div class="col-auto mb-3">
								<button type="button" onclick="exportar_cierre_personalizado('exportarBR')" class="btn btn-info mr-4">BRASIL</button>
							</div>
							<div class="col-auto mb-3">
								<button type="button" onclick="exportar_cierre_personalizado('exportarCOMAP')" class="btn btn-info mr-4">COMAP</button>
							</div>
							<div class="col-auto mb-3">
								<button type="button" onclick="exportar_cierre_personalizado('exportarPY')" class="btn btn-info">PARAGUAY</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
	<!-- End Consultas Cierre De Horas Personalizado -->

	<hr class="style5 container">





</div>
<!-- End Contenedor General -->