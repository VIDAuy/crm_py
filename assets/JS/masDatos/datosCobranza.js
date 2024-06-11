function datosCobranza() {

	let cedula = $('#cedulas').text();

	$('#tabla_datos_cobranzas').DataTable({
		ajax: 'PHP/AJAX/masDatos/datosCobranza.php?cedula=' + cedula,
		columns: [
			{ data: 'mes' },
			{ data: 'anho' },
			{ data: 'importe' },
			{ data: 'cobrado' },
		],
		"order": [[1, 'desc'], [0, 'desc']],
		"bDestroy": true,
		language: { url: `${url}assets/js/lenguage.json` },
	});
}