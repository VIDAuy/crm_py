// Funciones pasivas

$(function () {
	$('#botonMDDB').on('click', function (e) {
		corroborarDatos();
	});
});

// AJAX

function listarDatos(CI) {
	if (CI != '') {
		$.ajax({
			url: 'PHP/AJAX/sistemaBajas/listarDatos.php?CI=' + CI,
			dataType: 'JSON',
			success: function (response) {
				limpiar();

				if (response.error === false) {
					let datos = response.datos;
					let _cedula = datos.cedula;
					let cedula = "";
					let nombre = datos.nombre;
					let idrelacion = datos.idrelacion;
					let filial = datos.filial;
					let radio = datos.radio;
					let telefono = datos.telefono;
					let celular = datos.celular;
					let importe = datos.importe;

					if (_cedula.length == 7 || _cedula.substring(0, 1) == 0) {
						if (_cedula.substring(0, 1) == 0) _cedula = _cedula.substring(1, 8)
						c1 = _cedula.substring(0, 3);
						c2 = _cedula.substring(3, 6);
						c3 = _cedula.substring(6, 7);
						cedula = c1 + '.' + c2 + '-' + c3;
					}
					else {
						c1 = _cedula.substring(0, 1);
						c2 = _cedula.substring(1, 4);
						c3 = _cedula.substring(4, 7);
						c4 = _cedula.substring(7, 8);
						cedula = c1 + '.' + c2 + '.' + c3 + '-' + c4;
					}
					$('#cedulaTitulo').text(nombre + ' (CI: ' + cedula + ')');
					$('#idrelacion').val(idrelacion);
					$('#idrelacion2').val(idrelacion);
					$('#nombre_socio').val(nombre);
					$('#cedula_socio').val(_cedula);
					$('#filial_socio').val(filial);
					$('#radio_socio').val(radio);
					$('#telefono_contacto').val(telefono);
					$('#celular_contacto').val(celular);
					$('#importe').val(importe);
					$('#modalSolicitarBaja').modal('show');
					cargarMSBhiddenItems();
				} else {
					error(response.mensaje);
				}
			},
			error: function () {
				$('#txtResult').html('Ocurrio un error. Por favor vuelva a intentar en instantes.');
				$('#primaria').css('display', 'none');
			}
		});
	} else {
		error('Se debe de ingresar la cédula del usuario previamente.');
		$('#modalSolicitarBaja .close').click();
	}
}

function cargarMSBhiddenItems() {
	$.ajax(
		{
			url: 'PHP/AJAX/serviciosContratados/listarServicios.php',
			dataType: 'JSON',
			data:
			{
				cedula: $('#ci').val()
			},
			beforeSend: function () {
				$('#MSBhiddenItems tr').remove();
			},
			success: function (content) {
				if (content.error) error(content.mensaje);
				else {
					$.each(content, function (index, el) {
						nuevoServicio =
							'<tr>' +
							'<td><input type="hidden" name="nroServicio' + index + '" value="' + el.nroServicio + '"	></td>' +
							'<td><input type="hidden" name="servicio' + index + '" value="' + el.servicio + '"		></td>' +
							'<td><input type="hidden" name="horas' + index + '" value="' + el.horas + '"			></td>' +
							'<td><input type="hidden" name="importe' + index + '" value="' + el.importe + '"		></td>' +
							'</tr>';
						$(nuevoServicio).appendTo('#MSBhiddenItems');
					});
				}
			},
			error: function () {
				error('Ha ocurrido un error inesperado, por favor comuníquese con el administrador.');
			}
		});
}

function guardarDatos() {
	$data = $('#formModalBajas').serialize();
	$.ajax(
		{
			url: 'PHP/AJAX/sistemaBajas/guardarDatos.php?sector=' + $('#sector').val(),
			data: $data,
			method: 'POST',
			dataType: 'JSON',
			beforeSend: function () {
				mostrarLoader();
			},
			complete: function () {
				mostrarLoader("O");
			},
			success: function (content) {
				if (content.registroActivo) {
					limpiar();
					correcto(content.message);
					$('#modalSolicitarBaja .close').click();
				}
				else if (content.result) {
					limpiar();
					correcto(content.message);
					$('#modalSolicitarBaja .close').click();
				}
				else error(content.message);
			},
			error: function () {
				$('#txtResult').html('Ocurrio un error. Por favor vuelva a intentar en instantes.');
				$('#primaria').css("display", "none");
			}
		});
}

// Funciones complementarias

function limpiar() {
	$('#idrelacion').val('');
	$('#nombre_funcionario').val('');
	$('#observaciones').val('');
	$('#nombre_socio').val('');
	$('#cedula_socio').val('');
	$('#filial_socio').val(undefined);
	$('#servicio_contratado').val('');
	$('#horas_contratadas').val(undefined);
	$('#importe').val('');
	$('#motivo_baja').val(undefined);
	$('#nombre_contacto').val('');
	$('#apellido_contacto').val('');
	$('#telefono_contacto').val('');
	$('#celular_contacto').val('');
}

// Funciones de control
function corroborarDatos() {
	mensaje = '';

	// CONTROLES DE INFORMACIÓN DE USUARIO --

	// CONTROLES DEL INPUT -- nombre_socio --

	if ($('#nombre_socio').val() == '')
		mensaje += 'El campo "Nombre socio" no puede estar vacío. \n';
	else if (!/^([a-zA-Z_ÑñáéíóúÁÉÍÓÚ ])*$/.test($('#nombre_socio').val()))
		mensaje += 'El campo "Nombre socio" sólo puede contener letras. \n';

	// CONTROLES DEL INPUT -- cedula_socio --

	if ($('#cedula_socio').val() == '')
		mensaje += 'El campo "C.I. del socio" no puede estar vacío. \n';
	else if (!/^([0-9])*$/.test($('#cedula_socio').val()))
		mensaje += 'El campo "C.I. del socio" sólo puede contener números. \n';

	// CONTROLES DEL INPUT -- filial_socio --

	if ($('#filial_socio').val() == '')
		mensaje += 'El campo "Filial socio" no puede estar vacío. \n';

	// CONTROLES DEL INPUT -- motivo_baja --

	if ($('#motivo_baja').val() == undefined)
		mensaje += 'Debe seleccionar un motivo de la baja.. \n';

	// CONTROLES DE INFORMACIÓN DE CONTACTO --

	// CONTROLES DEL INPUT -- nombre_contacto --

	if ($('#nombre_contacto').val() == '')
		mensaje += 'El campo "Nombre contacto" no puede estar vacío. \n';
	else if (!/^([a-zA-Z_ÑñáéíóúÁÉÍÓÚ ])*$/.test($('#nombre_contacto').val()))
		mensaje += 'El campo "Nombre contacto" sólo puede contener letras. \n';

	// CONTROLES DEL INPUT -- apellido_contacto --

	if ($('#apellido_contacto').val() == '')
		mensaje += 'El campo "Apellido contacto" no puede estar vacío. \n';
	else if (!/^([a-zA-Z_ÑñáéíóúÁÉÍÓÚ ])*$/.test($('#apellido_contacto').val()))
		mensaje += 'El campo "Apellido contacto" sólo puede contener letras. \n';

	// CONTROLES DE LOS INPUT -- telefono_contacto Y celular_contacto --

	if ($('#telefono_contacto').val() == '' && $('#celular_contacto').val() == '')
		mensaje += 'Se debe ingresar un teléfono o un celular de contacto. \n';
	else {
		if ($('#telefono_contacto').val() != '') {
			if (!/^([0-9])*$/.test($('#telefono_contacto').val()))
				mensaje += 'El campo "Teléfono contacto" sólo puede contener números.\n';
			else if ($('#telefono_contacto').val().length < 6 || $('#telefono_contacto').val().length > 7)
				mensaje += 'El campo "Teléfono contacto" debe contener entre 6 y 7 dígitos.\n';
			else if ($('#telefono_contacto').val().substring(0, 1) != 2 && $('#telefono_contacto').val().substring(0, 1) != 4)
				mensaje += 'El telefono ingresado en el campo "Teléfono contacto" es inválido.\n';
		}
		if ($('#celular_contacto').val() != '') {
			if (!/^([0-9])*$/.test($('#celular_contacto').val()))
				mensaje += 'El campo "Celular contacto" sólo puede contener números.\n';
			else if ($('#celular_contacto').val().length != 10)
				mensaje += 'El campo "Celular contacto" debe de tener 9 números.\n';
			else if ($('#celular_contacto').val().substring(0, 2) != 09)
				mensaje += 'El celular ingresado en el campo "Celular contacto" es inválido.\n';
		}
	}

	// CONTROLES DE INFORMACIÓN DE GESTIÓN --

	// CONTROLES DEL INPUT -- nombre_funcionario --

	if ($('#nombre_funcionario').val() == '')
		mensaje += 'El campo "Nombre de funcionario" no puede estar vacío. \n';
	else if (!/^([a-zA-Z_ÑñáéíóúÁÉÍÓÚ ])*$/.test($('#nombre_funcionario').val()))
		mensaje += 'El campo "Nombre de funcionario" sólo puede contener letras. \n';

	// CONTROLES DEL INPUT -- observaciones --

	if ($('#observaciones').val() == '')
		mensaje += 'El campo "Observaciones" no puede estar vacío. \n';

	// CORROBORA QUE NO HAYA OCURRIDO NINGÚN ERROR, EN CASO DE QUE SÍ LOS ENLISTA EN UN ALERT
	// DE LO CONTRARIO INGRESA LOS DATOS EN LA DB

	if (mensaje != "") error("Han ocurrido los siguientes errores: \n" + mensaje);
	else guardarDatos();
}

function motivoEstado() {
	if ($('#estado').val() != "No Otorgada") {
		$('#motivo_no_otorgada').prop('disabled', true);
		$('#motivo_no_otorgada').val('Seleccione un motivo');
	}
	else $('#motivo_no_otorgada').prop('disabled', false);
}


$('.solo_numeros').keydown(function (e) {
	if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 40]) !== -1 || (e.keyCode >= 35 && e.keyCode <= 39)) return;
	if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) e.preventDefault();
	if (e.altKey) return false;
});