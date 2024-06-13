<?php
session_start();
date_default_timezone_set('America/Montevideo');

include('views/header.php');


$nivel = isset($_SESSION['nivel_py']) ? $_SESSION['nivel_py'] : "";


if ($nivel == "") echo '<script>window.location.replace("login.php");</script>';




if ($nivel == 3) {
	/** Carga JS **/
	$array_js = [
		"mas_datos.js",
		"sistemaBajas/historial_de_bajas.js",
		"sistemaBajas/gestionar_bajas.js",
		"sistemaBajas/solicitar_baja.js",
		"servicios_contratados.js",
		"index.js",
	];
	cargar_archivos("./assets/js/", $array_js, "js", $version);
	/** End Carga JS **/


	/** Carga Contenido **/
	$array_contenido = [
		"nivel3.php",
		"si_es_socio.php",
		"no_es_socio.php",
		"no_es_socio_registros.php",
		"registros_de_socio.php",
		"seccion_mas_datos.php",
	];
	cargar_archivos("./views/content/", $array_contenido, "content", $version);
	/** End Carga Contenido **/


	/** Carga Modals **/
	$array_modal = [
		"modal_datos_alertas.html",
		"modal_registros_de_socios.html",
		"modal_informacion_detallada_baja.html",
		"modal_mas_informacion_detallada_baja.html",
		"modal_listar_bajas.html",
		"modal_mostrar_imagenes.html",
		"modal_solicitar_baja.html",
		"modal_ver_mas_comentarios.html",
		"modal_historial_de_bajas.html",
		"modal_servicios_contratados.html",
	];
	cargar_archivos("./views/modals/", $array_modal, "modals", $version);
	/** End Carga Modals **/
}



if ($nivel == 7) {
	/** Carga JS **/
	$array_js = [
		"nivel7/js.js",
	];
	cargar_archivos("./assets/js/", $array_js, "js", $version);
	/** End Carga JS **/


	/** Carga Contenido **/
	$array_contenido = [
		"nivel7.php",
	];
	cargar_archivos("./views/content/", $array_contenido, "content", $version);
	/** End Carga Contenido **/


	/** Carga Modals **/
	$array_modal = [
		"nivel7/modalVerMasInfoRegistros.html",
		"modal_mostrar_imagenes.html",
	];
	cargar_archivos("./views/modals/", $array_modal, "modals", $version);
	/** End Carga Modals **/
}




include('views/footer.php');




function cargar_archivos($ruta, $array_archivos, $tipo, $version)
{
	if ($tipo == "js") {
		foreach ($array_archivos as $archivo) {
			echo '<script src="' . $ruta . $archivo . $version . '"></script>';
		}
	} else {
		foreach ($array_archivos as $archivo) {
			include("$ruta" . "$archivo");
		}
	}
}
