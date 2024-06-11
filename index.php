<?php
$version = '?v=1.0.47';
session_start();
date_default_timezone_set('America/Montevideo');

//	DEPENDIENDO DE CON QUE USUARIO ESTÉ LOGUEADO LA PÁGINA QUE CARGA

include('views/header.html');

if (isset($_SESSION['nivel_py'])) {
	$fecha = date("Y-m-d");

	include './PHP/configuraciones.php';
	$conexion = connection(DB);

	$id = $_SESSION['id_py'];
	switch ($_SESSION['nivel_py']) {
		case 1:
			$array_js = [
				"funciones.js",
				"funciones_complementarias.js",
				"masDatos/datosAlertas.js",
				"masDatos/datosCobranza.js",
				"masDatos/datosCoordina.js",
				"masDatos/datosProductos.js",
				"sistemaBajas/historialDeBajas.js",
				"sistemaBajas/solicitarBaja.js",
				"serviciosContratados/js.js",
			];
			foreach ($array_js as $archivo) {
				echo '<script src="./assets/JS/' . $archivo . $version . '"></script>';
			}


			$array_modal = [
				"modalDatosAlertas.html",
				"modalDatosCobranza.html",
				"modalDatosCoordina.html",
				"modalDatosProductos.html",
				"modal_mostrar_imagenes.html",
				"modalInformacionDetalladaBaja.html",
				"modalHistoriaComunicacionDeCedula.html",
				"modalHistorialDeBajas.html",
				"modalSolicitarBajaFiliales.html",
				"modalServiciosContratados.html",
			];
			foreach ($array_modal as $modal) {
				include('./views/modals/' . $modal);
			}


			include('views/nivel1.php');
			include('views/includes/contenido_filiales.html');
			include('views/includes/historiaComunicacionDeCedula.html');



			break;
		case 2:
			$array_js = [
				"funciones.js",
				"funciones_complementarias.js",
				"masDatos/datosAlertas.js",
				"masDatos/datosCobranza.js",
				"masDatos/datosCoordina.js",
				"masDatos/datosProductos.js",
				"sistemaBajas/historialDeBajas.js",
				"sistemaBajas/solicitarBaja.js",
				"serviciosContratados/js.js",
			];
			foreach ($array_js as $archivo) {
				echo '<script src="./assets/JS/' . $archivo . $version . '"></script>';
			}


			$array_modal = [
				"modalDatosAlertas.html",
				"modalDatosCobranza.html",
				"modalDatosCoordina.html",
				"modalDatosProductos.html",
				"modal_mostrar_imagenes.html",
				"modalInformacionDetalladaBaja.html",
				"modalServiciosContratados.html",
			];
			foreach ($array_modal as $modal) {
				include('./views/modals/' . $modal);
			}


			include('views/nivel2.php');
			include('views/includes/contenido.html');



			break;
		case 3:
			/*
			echo '<script src="assets/JS/funciones.js' . $version . '"></script>';
			echo '<script src="assets/JS/funciones_complementarias.js' . $version . '"></script>';
			echo '<script src="assets/JS/masDatos/datosAlertas.js' . $version . '"></script>';
			echo '<script src="assets/JS/masDatos/datosCobranza.js' . $version . '"></script>';
			echo '<script src="assets/JS/masDatos/datosCoordina.js' . $version . '"></script>';
			echo '<script src="assets/JS/masDatos/datosProductos.js' . $version . '"></script>';
			echo '<script src="assets/JS/sistemaBajas/historialDeBajas.js' . $version . '"></script>';
			echo '<script src="assets/JS/sistemaBajas/gestionarBajas.js' . $version . '"></script>';
			echo '<script src="assets/JS/sistemaBajas/solicitarBaja.js' . $version . '"></script>';
			echo '<script src="assets/JS/serviciosContratados/js.js' . $version . '"></script>';
			echo '<script src="assets/JS/enviar_documento_y_alerta/js.js' . $version . '"></script>';
			echo '<script src="assets/JS/index.js' . $version . '"></script>';


			include('views/nivel3.php');
			include('views/includes/contenido.html');
			include('views/includes/historiaComunicacionDeCedula.html');
			include('views/includes/historiaComunicacionDeCedula_funcionarios.html');


			// MODALES DE INFORMACIÓN
			include('views/modals/modalDatosAlertas.html');
			include('views/modals/modalDatosCobranza.html');
			include('views/modals/modalDatosCoordina.html');
			include('views/modals/modalDatosProductos.html');
			include('views/modals/modal_ver_mas_comentarios.html');
			include('views/modals/modal_licencia_acompanantes.html');
			include('views/modals/modal_faltas_acompanantes.html');
			include('views/modals/modal_horas_acompanantes.html');
			include('views/modals/modalInformacionDetalladaBaja.html');
			include('views/modals/modalHistoriaComunicacionDeCedula.html');
			include('views/modals/modalHistorialDeBajas.html');
			include('views/modals/modalListarBajas.html');
			include('views/modals/modalSolicitarBaja.html');
			include('views/modals/modalServiciosContratados.html');
			include('views/modals/modalCargarDocumentos.html');
			include('views/modals/modal_alertas_funcionarios.html');
*/


			$array_js = [
				"funciones.js",
				"funciones_complementarias.js",
				"masDatos/datosAlertas.js",
				"masDatos/datosCobranza.js",
				"masDatos/datosCoordina.js",
				"masDatos/datosProductos.js",
				"sistemaBajas/historialDeBajas.js",
				"sistemaBajas/gestionarBajas.js",
				"sistemaBajas/solicitarBaja.js",
				"serviciosContratados/js.js",
				"enviar_documento_y_alerta/js.js",
				"index.js",
			];
			foreach ($array_js as $archivo) {
				echo '<script src="./assets/JS/' . $archivo . $version . '"></script>';
			}


			include('views/nivel3.php');
			include('views/includes/contenido.html');
			include('views/includes/historiaComunicacionDeCedula.html');
			include('views/includes/historiaComunicacionDeCedula_funcionarios.html');


			$array_modal = [
				"modalDatosAlertas.html",
				"modalDatosCobranza.html",
				"modalDatosCoordina.html",
				"modalDatosProductos.html",
				"modal_ver_mas_comentarios.html",
				"modal_licencia_acompanantes.html",
				"modal_faltas_acompanantes.html",
				"modal_horas_acompanantes.html",
				"modal_mostrar_imagenes.html",
				"modalInformacionDetalladaBaja.html",
				"modalHistoriaComunicacionDeCedula.html",
				"modalHistorialDeBajas.html",
				"modalListarBajas.html",
				"modalSolicitarBaja.html",
				"modalServiciosContratados.html",
				"modalCargarDocumentos.html",
				"modal_alertas_funcionarios.html",
			];
			foreach ($array_modal as $modal) {
				include('./views/modals/' . $modal);
			}



			break;
		case 4:
			echo '<script src="assets/JS/nivel4/js.js' . $version . '"></script>';
			include('views/nivel4.php');
			break;
		case 5:
			$array_js = [
				"nivel5/js.js",
				"masDatos/datosCobranza.js",
				"masDatos/datosCoordina.js",
				"masDatos/datosCRM.js",
				"masDatos/datosProductos.js",
				"sistemaBajas/historialDeBajas.js",
				"sistemaBajas/gestionarBajas.js",
				"serviciosContratados/js.js",
			];
			foreach ($array_js as $archivo) {
				echo '<script src="./assets/JS/' . $archivo . $version . '"></script>';
			}


			$array_modal = [
				"modalDatosCobranza.html",
				"modalDatosCoordina.html",
				"modalDatosCRM.html",
				"modalDatosProductos.html",
				"modalGestionCentralizado.html",
				"modalGestionDomiciliario.html",
				"modalHistoriaComunicacionDeCedula.html",
				"modalHistorialDeBajas.html",
				"modalLlamadasPendientes.html",
				"modalServiciosContratados.html",
				"modal_mostrar_imagenes.html",
			];
			foreach ($array_modal as $modal) {
				include('./views/modals/' . $modal);
			}


			include('views/nivel5.php');



			break;


		case 6:
			$array_js = [
				"nivel6/js.js",
				"nivel6/alertas/js.js",
			];
			foreach ($array_js as $archivo) {
				echo '<script src="./assets/JS/' . $archivo . $version . '"></script>';
			}


			$array_modal = [
				"nivel6/modal_licencia_acompanantes.html",
				"nivel6/modal_horas_acompanantes_personal.html",
				"nivel6/modal_faltas_acompanantes_personal.html",
				"nivel6/modalDatosCoordina_personal.html",
				"nivel6/modalDatosCobranza_personal.html",
				"nivel6/modalDatosProductos_personal.html",
				"nivel6/modal_todas_licencias_acompanantes.html",
				"nivel6/modal_todas_las_horas_acompanantes_personal.html",
				"nivel6/modal_todos_registros_faltas_acompanantes_personal.html",
				"nivel6/modal_alertas_funcionarios.html",
				"nivel6/modal_capacitacion_acompanantes.html",
				"modal_mostrar_imagenes.html",
			];
			foreach ($array_modal as $modal) {
				include('./views/modals/' . $modal);
			}


			include('views/nivel6.php');



			break;

		case 7:
			$array_js = [
				"nivel7/js.js",
			];
			foreach ($array_js as $archivo) {
				echo '<script src="./assets/JS/' . $archivo . $version . '"></script>';
			}


			$array_modal = [
				"nivel7/modalVerMasInfoRegistros.html",
				"modal_mostrar_imagenes.html",
			];
			foreach ($array_modal as $modal) {
				include('./views/modals/' . $modal);
			}


			include('views/nivel7.php');



			break;
	}
} else {
	include('views/log.html');
	echo '<script src="assets/JS/log.js' . $version . '"></script>';
}

include('views/footer.html');
