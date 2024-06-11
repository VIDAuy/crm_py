function datosCoordina() {

	let cedula = $('#cedulas').text();
	
	$('#tabla_servicios_coordinacion').DataTable({
		ajax: 'PHP/AJAX/masDatos/datosCoordina.php?cedula=' + cedula,
		columns: [
			{ data: 'id' },
			{ data: 'observacion' },
		],
		"order": [[0, 'asc']],
		"bDestroy": true,
		language: { url: `${url}assets/js/lenguage.json` },
	});
}