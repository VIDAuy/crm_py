<!DOCTYPE html>
<html lang="es">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<title>CRM</title>

	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.css" integrity="sha512-aOG0c6nPNzGk+5zjwyJaoRUgCdOrfSDhmMID2u4+OIslr0GjpLKo7Xm0Ao3xmpM4T8AmIouRkqwj1nrdVsLKEQ==" crossorigin="anonymous" />
	<link rel="icon" href="views/images/favicon.png" type="image/png">
</head>

<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="navbarTogglerDemo01">
			<input type="button" class="btn btn-outline-success my-2 my-sm-0" id="botonBusquedaAvanzada" value="Búsqueda avanzada de agenda">
			<input type="button" id="botonHistorialDeBajas" class="btn btn-outline-primary ml-2" value="Historial de bajas" onclick="historialDeBajas();">
			<input type="button" id="llamadosPendientesParaHoy" class="btn btn-outline-success ml-2">
			<form class="form-inline my-2 my-lg-0 ml-2" id="buscarCI">
				<input type="text" id="CIValue" class="form-control mr-sm-2 solo_numeros col-7" placeholder="Buscar por cédula">
				<input type="button" class="btn btn-outline-success my-2 my-sm-0" value="Buscar">
			</form>
		</div>
		<a href="http://192.168.1.250:82/crm_py/PHP/cerrarSesion.php" class="btn btn-outline-danger ml-2">Cerrar sesión</a>
	</nav>


	<div style="display: none">
		<input type="hidden" id="ci">
		<span id="cedulas"></span>
	</div>
	<div class="container" style="display: none" id="datosMoroso">
		<div class="d-block mx-auto">
			<h1>Datos de contacto:</h1>
			<fieldset disabled>
				<div class="form-row">
					<div class="form-group col-4">
						<label for="cedula" class="col-form-label">Cedula:</label>
						<input type="text" id="cedula" class="form-control">
					</div>
					<div class="form-group col-4">
						<label for="nombre" class="col-form-label">Nombre:</label>
						<input type="text" id="nombre" class="form-control">
					</div>
					<div class="form-group col-4">
						<label for="valorCuota" class="col-form-label">Valor de cuota:</label>
						<input type="text" id="valorCuota" class="form-control">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-4">
						<label for="direccion" class="col-form-label">Direccion:</label>
						<textarea id="direccion" rows="1" class="form-control"></textarea>
					</div>
					<div class="form-group col-4">
						<label for="telefono" class="col-form-label">Telefono:</label>
						<input type="text" id="telefono" class="form-control">
					</div>
					<div class="form-group col-4">
						<label for="filial" class="col-form-label">Filial:</label>
						<input type="text" id="filial" class="form-control">
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-4">
						<label for="ruta" class="col-form-label">Ruta:</label>
						<input type="text" id="ruta" class="form-control">
					</div>
					<div class="form-group col-4">
						<label for="radio" class="col-form-label">Radio:</label>
						<input type="text" id="radio" class="form-control">
					</div>
					<div class="form-group col-4">
						<label for="fechaAfiliacion" class="col-form-label">Fecha de afiliacion:</label>
						<input type="text" id="fechaAfiliacion" class="form-control">
					</div>
				</div>
			</fieldset>
			<div class="form-row">
				<div class="form-group col-3">
					<input type="button" class="btn btn-block btn-outline-secondary" id="gestionarAnterior" value="Anterior">
				</div>
				<div class="form-group col-6">
					<input type="button" class="btn btn-block btn-danger" id="abrirModalGestion" value="Gestionar">
				</div>
				<div class="form-group col-3">
					<input type="button" class="btn btn-block btn-outline-secondary" id="gestionarSiguiente" value="Siguiente">
				</div>
			</div>
			<div class="form-row">
				<div class="form-group col-3">
					<input type="button" id="b1" type="button" class="btn btn-primary btn-block mr-1" onclick="datosCoordina()" value="Datos Coordina">
				</div>
				<div class="form-group col-3">
					<input type="button" id="b2" type="button" class="btn btn-primary btn-block mx-1" onclick="datosCobranza()" value="Datos Cobranza">
				</div>
				<div class="form-group col-3">
					<input type="button" id="b3" type="button" class="btn btn-primary btn-block mx-1" onclick="datosProductos()" value="Datos Productos">
				</div>
				<div class="form-group col-3">
					<input type="button" id="b4" type="button" class="btn btn-primary btn-block ml-1" onclick="datosCRM()" value="Datos CRM">
				</div>
			</div>
			<div id="datosMorosoDiv">
			</div>
		</div>
	</div>
	<div class="container" style="display: none" id="busquedaAvanzada">
		<div class="d-block mx-auto">
			<form id="formBAdA">
				<div class="form-row">
					<div class="form-group col-4">
						<label for="prioridad" class="col-form-label">Prioridad:</label>
						<select name="prioridad" class="form-control">
							<option value="">Sin especificar</option>
							<option value="1">Mínima</option>
							<option value="2">Máxima</option>
						</select>
					</div>
					<div class="form-group col-4">
						<label for="cartera" class="col-form-label">Cartera:</label>
						<select name="cartera" class="form-control">
							<option value="">Sin especificar</option>
							<option value="0">Centralizado</option>
							<option value="1">Domiciliario</option>
						</select>
					</div>
					<div class="form-group col-4">
						<label for="filial" class="col-form-label">Filial:</label>
						<select name="filial" id="filialBAdA" class="form-control">
							<option value="">Sin especificar</option>
						</select>
					</div>
				</div>
				<div class="form-row">
					<div class="form-group col-4">
						<label for="estado" class="col-form-label">Estado:</label>
						<select name="estado" class="form-control">
							<option value="">Sin especificar</option>
							<option value="1">Inubicable</option>
							<option value="2">Fallecido</option>
							<option value="3">Auditoria</option>
							<option value="4">Promesa pago</option>
							<option value="5">Fecha contacto</option>
							<option value="6">Baja pendiente</option>
							<option value="7">Baja</option>
							<option value="8">Pago</option>
							<option value="9">Sin gestionar</option>
						</select>
					</div>
					<div class="form-group col-2">
						<label for="" class="col-form-label">Filtro fecha:</label>
						<select name="filtroFecha" id="filtroFecha" class="form-control">
							<option value="">Sin filtrar</option>
							<option value="1">Promesa pago</option>
							<option value="2">Gestión</option>
							<option value="3">Fecha ingreso</option>
						</select>
					</div>
					<div id="filtroInexistente" class="col-6" style="display: inline;">
						<div class="form-row">
						</div>
					</div>
					<div id="filtroJunto" class="col-6" style="display: none;">
						<div class="form-row">
							<div class="form-group col-6">
								<label for="" class="col-form-label">Desde:</label>
								<input type="text" name="fechaCompletaDesde" id="fechaCompletaDesde" class="form-control" readonly>
							</div>
							<div class="form-group col-6">
								<label for="" class="col-form-label">Hasta:</label>
								<input type="text" name="fechaCompletaHasta" id="fechaCompletaHasta" class="form-control" readonly>
							</div>
						</div>
					</div>
					<div id="filtroSeparado" class="col-6" style="display: none;">
						<div class="form-row">
							<div class="form-group col-4">
								<label for="mesDesde" class="col-form-label">Mes:</label>
								<select name="mesDesde" id="mesDesde" class="form-control">
									<option value="">Desde</option>
									<option value="01">Enero</option>
									<option value="02">Febrero</option>
									<option value="03">Marzo</option>
									<option value="04">Abril</option>
									<option value="05">Mayo</option>
									<option value="06">Junio</option>
									<option value="07">Julio</option>
									<option value="08">Agosto</option>
									<option value="09">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
							</div>
							<div class="form-group col-2">
								<label for="anhoDesde" class="col-form-label">Año:</label>
								<select name="anhoDesde" id="anhoDesde" class="form-control">
									<option value="">Desde</option>
								</select>
							</div>
							<div class="form-group col-4">
								<label for="mesHasta" class="col-form-label">Mes:</label>
								<select name="mesHasta" id="mesHasta" class="form-control">
									<option value="">Hasta</option>
									<option value="01">Enero</option>
									<option value="02">Febrero</option>
									<option value="03">Marzo</option>
									<option value="04">Abril</option>
									<option value="05">Mayo</option>
									<option value="06">Junio</option>
									<option value="07">Julio</option>
									<option value="08">Agosto</option>
									<option value="09">Septiembre</option>
									<option value="10">Octubre</option>
									<option value="11">Noviembre</option>
									<option value="12">Diciembre</option>
								</select>
							</div>
							<div class="form-group col-2">
								<label for="anhoHasta" class="col-form-label">Año:</label>
								<select name="anhoHasta" id="anhoHasta" class="form-control">
									<option value="">Hasta</option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="d-block text-center">
					<input type="button" class="btn btn-outline-danger" id="buscarBAdA" value="Buscar">
				</div>
			</form>
			<div id="BAdADiv">
			</div>
		</div>
	</div>
</body>

</html>