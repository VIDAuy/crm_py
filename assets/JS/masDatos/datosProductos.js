function datosProductos() {

	let cedula = $('#cedulas').text();

	$('#tabla_servicios_contratados').DataTable({
		ajax: 'PHP/AJAX/masDatos/datosProductos.php?cedula=' + cedula,
		columns: [
			{ data: 'nroServicio' },
			{ data: 'servicio' },
			{ data: 'horas' },
			{ data: 'importe' },
			{ data: 'fecha_afiliacion' },
		],
		"order": [[0, 'asc']],
		"bDestroy": true,
		language: { url: `${url}assets/js/lenguage.json` },
	});
}