<?php
$version = '?v=1.0.43';
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
			echo '<script src="assets/JS/funciones.js' . $version . '"></script>';
			echo '<script src="assets/JS/funciones_complementarias.js' . $version . '"></script>';
			include('views/nivel1.php');

			include('views/includes/contenido_filiales.html');

			// MODALES DE INFORMACIÓN
			include('views/modals/modalDatosAlertas.html');
			echo '<script src="assets/JS/masDatos/datosAlertas.js' . $version . '"></script>';
			include('views/modals/modalDatosCobranza.html');
			echo '<script src="assets/JS/masDatos/datosCobranza.js' . $version . '"></script>';
			include('views/modals/modalDatosCoordina.html');
			echo '<script src="assets/JS/masDatos/datosCoordina.js' . $version . '"></script>';
			include('views/modals/modalDatosProductos.html');
			echo '<script src="assets/JS/masDatos/datosProductos.js' . $version . '"></script>';
			include('views/modals/modal_mostrar_imagenes.html');


			include('views/includes/historiaComunicacionDeCedula.html');

			// MODALES DE BAJA Y RELACIONADOS
			include('views/modals/modalInformacionDetalladaBaja.html');
			include('views/modals/modalHistoriaComunicacionDeCedula.html');
			include('views/modals/modalHistorialDeBajas.html');
			echo '<script src="assets/JS/sistemaBajas/historialDeBajas.js' . $version . '"></script>';
			include 'views/modals/modalSolicitarBajaFiliales.html';
			echo '<script src="assets/JS/sistemaBajas/solicitarBaja.js' . $version . '"></script>';
			include('views/modals/modalServiciosContratados.html');
			echo '<script src="assets/JS/serviciosContratados/js.js' . $version . '"></script>';
			break;
		case 2:
			echo '<script src="assets/JS/funciones.js' . $version . '"></script>';
			echo '<script src="assets/JS/funciones_complementarias.js' . $version . '"></script>';
			include('views/nivel2.php');
			include('views/includes/contenido.html');

			// MODALES DE INFORMACIÓN

			include('views/modals/modalDatosAlertas.html');
			echo '<script src="assets/JS/masDatos/datosAlertas.js' . $version . '"></script>';
			include('views/modals/modalDatosCobranza.html');
			echo '<script src="assets/JS/masDatos/datosCobranza.js' . $version . '"></script>';
			include('views/modals/modalDatosCoordina.html');
			echo '<script src="assets/JS/masDatos/datosCoordina.js' . $version . '"></script>';
			include('views/modals/modalDatosProductos.html');
			echo '<script src="assets/JS/masDatos/datosProductos.js' . $version . '"></script>';
			include('views/modals/modal_mostrar_imagenes.html');

			// MODALES DE BAJA Y RELACIONADOS

			include('views/modals/modalInformacionDetalladaBaja.html');
			include('views/modals/modalServiciosContratados.html');
			echo '<script src="assets/JS/serviciosContratados/js.js' . $version . '"></script>';
			break;


		case 3:
			echo '<script src="assets/JS/funciones.js' . $version . '"></script>';
			echo '<script src="assets/JS/funciones_complementarias.js' . $version . '"></script>';



			include('views/nivel3.php');

			include('views/includes/contenido.html');
			include('views/includes/historiaComunicacionDeCedula.html');
			include('views/includes/historiaComunicacionDeCedula_funcionarios.html');


			// MODALES DE INFORMACIÓN

			include('views/modals/modalDatosAlertas.html');
			echo '<script src="assets/JS/masDatos/datosAlertas.js' . $version . '"></script>';
			include('views/modals/modalDatosCobranza.html');
			echo '<script src="assets/JS/masDatos/datosCobranza.js' . $version . '"></script>';
			include('views/modals/modalDatosCoordina.html');
			echo '<script src="assets/JS/masDatos/datosCoordina.js' . $version . '"></script>';
			include('views/modals/modalDatosProductos.html');
			echo '<script src="assets/JS/masDatos/datosProductos.js' . $version . '"></script>';
			include('views/modals/modal_ver_mas_comentarios.html');
			include('views/modals/modal_licencia_acompanantes.html');
			include('views/modals/modal_faltas_acompanantes.html');
			include('views/modals/modal_horas_acompanantes.html');
			include('views/modals/modal_mostrar_imagenes.html');

			// MODALES DE BAJA Y RELACIONADOS

			include('views/modals/modalInformacionDetalladaBaja.html');
			include('views/modals/modalHistoriaComunicacionDeCedula.html');
			include('views/modals/modalHistorialDeBajas.html');
			echo '<script src="assets/JS/sistemaBajas/historialDeBajas.js' . $version . '"></script>';
			include('views/modals/modalListarBajas.html');
			echo '<script src="assets/JS/sistemaBajas/gestionarBajas.js' . $version . '"></script>';
			include 'views/modals/modalSolicitarBaja.html';
			echo '<script src="assets/JS/sistemaBajas/solicitarBaja.js' . $version . '"></script>';
			include('views/modals/modalServiciosContratados.html');
			echo '<script src="assets/JS/serviciosContratados/js.js' . $version . '"></script>';
			include('views/modals/modalCargarDocumentos.html');
			echo '<script src="assets/JS/enviar_documento_y_alerta/js.js' . $version . '"></script>';
			include('views/modals/modal_alertas_funcionarios.html');


			echo '<script src="assets/JS/index.js' . $version . '"></script>';

			break;
		case 4:
			echo '<script src="assets/JS/nivel4/js.js' . $version . '"></script>';
			include('views/nivel4.php');
			break;


		case 5:
			echo '<script src="assets/JS/nivel5/js.js' . $version . '"></script>';
			include('views/nivel5.php');

			// MODALES DE INFORMACIÓN

			include('views/modals/modalDatosCobranza.html');
			echo '<script src="assets/JS/masDatos/datosCobranza.js' . $version . '"></script>';
			include('views/modals/modalDatosCoordina.html');
			echo '<script src="assets/JS/masDatos/datosCoordina.js' . $version . '"></script>';
			include('views/modals/modalDatosCRM.html');
			echo '<script src="assets/JS/masDatos/datosCRM.js' . $version . '"></script>';
			include('views/modals/modalDatosProductos.html');
			echo '<script src="assets/JS/masDatos/datosProductos.js' . $version . '"></script>';


			include('views/modals/modalGestionCentralizado.html');
			include('views/modals/modalGestionDomiciliario.html');
			include('views/modals/modalHistoriaComunicacionDeCedula.html');
			include('views/modals/modalHistorialDeBajas.html');
			echo '<script src="assets/JS/sistemaBajas/historialDeBajas.js' . $version . '"></script>';
			include('views/modals/modalLlamadasPendientes.html');
			echo '<script src="assets/JS/sistemaBajas/gestionarBajas.js' . $version . '"></script>';
			include('views/modals/modalServiciosContratados.html');
			echo '<script src="assets/JS/serviciosContratados/js.js' . $version . '"></script>';
			include('views/modals/modal_mostrar_imagenes.html');
			break;


		case 6:
			echo '<script src="assets/JS/nivel6/js.js' . $version . '"></script>';
			echo '<script src="assets/JS/nivel6/alertas/js.js' . $version . '"></script>';

			include('views/nivel6.php');


			// MODALES DE INFORMACIÓN

			include('views/modals/nivel6/modal_licencia_acompanantes.html');
			include('views/modals/nivel6/modal_horas_acompanantes_personal.html');
			include('views/modals/nivel6/modal_faltas_acompanantes_personal.html');
			include('views/modals/nivel6/modalDatosCoordina_personal.html');
			include('views/modals/nivel6/modalDatosCobranza_personal.html');
			include('views/modals/nivel6/modalDatosProductos_personal.html');

			include('views/modals/nivel6/modal_todas_licencias_acompanantes.html');
			include('views/modals/nivel6/modal_todas_las_horas_acompanantes_personal.html');
			include('views/modals/nivel6/modal_todos_registros_faltas_acompanantes_personal.html');
			include('views/modals/nivel6/modal_alertas_funcionarios.html');
			include('views/modals/nivel6/modal_capacitacion_acompanantes.html');
			include('views/modals/modal_mostrar_imagenes.html');

			break;

		case 7:
			echo '<script src="./assets/JS/nivel7/js.js' . $version . '"></script>';

			include('views/nivel7.php');

			// MODALES DE INFORMACIÓN

			include('views/modals/nivel7/modalVerMasInfoRegistros.html');
			include('views/modals/modal_mostrar_imagenes.html');

			break;
	}
} else {
	include('views/log.html');
	echo '<script src="assets/JS/log.js' . $version . '"></script>';
}

include('views/footer.html');
