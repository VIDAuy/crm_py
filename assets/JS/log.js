$(document).keypress(function (e) {
	if (e.which == 13) log();
});

$('#boton').on('click', function () {
	log();
});

function log() {
	let usuario = $('#usuario').val();
	let password = $('#password').val();

	if (usuario == "") {
		alerta("Error!", "El campo usuario esta vacío", "error");
	} else if (password == "") {
		alerta("Error!", "El campo codigo esta vacío", "error");
	} else {

		let $data = $('#form').serialize();
		$.ajax(
			{
				url: 'PHP/AJAX/log.php',
				data: $data,
				method: 'POST',
				dataType: 'JSON',
				success: function (r) {
					if (r.result) location.reload(true);
					else if (r.error) alerta("Error!", r.message, "error");
				},
				error: function () {
					alert('Ocurrio un error. Por favor vuelva a intentar en instantes.');
				}
			});
	}
}




function alerta(titulo, mensaje, icono) {
	Swal.fire({ title: titulo, html: mensaje, icon: icono });
}