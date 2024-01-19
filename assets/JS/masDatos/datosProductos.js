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
		language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
	});
}