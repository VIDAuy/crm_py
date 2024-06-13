$(document).ready(function () {

	$('#txt_password').keypress(function (e) {
		if (e.which == 13) login();
	});

});



function login() {
	let usuario = $('#txt_usuario').val();
	let password = $('#txt_password').val();

	if (usuario == "") {
		error("El campo usuario esta vacío");
	} else if (password == "") {
		error("El campo codigo esta vacío");
	} else {

		$.ajax({
			type: "POST",
			url: `${url_ajax}login.php`,
			data: {
				usuario,
				password
			},
			dataType: "JSON",
			success: function (response) {
				if (response.error === false) {
					location.href = "index.php";
					localStorage.setItem("sesion_crm_py", true);
				} else {
					error(response.mensaje);
				}
			}
		});

	}
}