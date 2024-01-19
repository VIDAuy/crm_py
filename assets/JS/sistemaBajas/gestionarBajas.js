// AJAX

function MIDBactualizarDatos() {
	let usuario = $('#sector_py').val();
	let id_relacion = $('#MIDBidrelacion').val();
	let nombre_funcionario = $('#MIDBnombreFA').val();
	let estado = $('#MIDBestado').val();
	let motivo = $('#MIDBmno').val();
	let observacion = $('#MIDBobservacion').val();


	if (nombre_funcionario == '') {
		error('El campo "Nombre funcionario" es obligatorio.\n');
	} else if (estado == undefined) {
		error('El campo "Estado" es obligatorio.\n');
	} else if (estado == 'No Otorgada' && motivo == undefined) {
		error('El campo "Motivo No Otorgada" es obligatorio.\n');
	} else if (observacion == '') {
		error('El campo "Observaciones" es obligatorio.\n');
	} else {

		$.ajax({
			type: "POST",
			url: "PHP/AJAX/sistemaBajas/actualizarBaja.php",
			data: {
				usuario: usuario,
				id_relacion: id_relacion,
				nombre_funcionario: nombre_funcionario,
				estado: estado,
				motivo: motivo,
				observacion: observacion
			},
			dataType: "JSON",
			beforeSend: function () {
				mostrarLoader();
			},
			complete: function () {
				mostrarLoader("O");
			},
			success: function (response) {
				if (response.error === false) {

					Swal.fire({
						title: 'Exito!',
						text: response.mensaje,
						icon: 'success',
						confirmButtonColor: '#3085d6',
						confirmButtonText: 'Ok'
					}).then((result) => {
						if (result.isConfirmed) {
							let usuario_logueado = usuario == "Calidaduy" ? 1 : 2;
							corroborarBajas(usuario_logueado);
						}
					})

					$('#modalInformacionDetalladaBaja .close').click();
					limpiarMIDB();
				} else {
					error(response.mensaje);
				}
			}
		});
	}
}


function retener_baja_socio() {
	alert("Retener baja");
}


function masInfoMLB(id) {
	$.ajax(
		{
			url: 'PHP/AJAX/sistemaBajas/listarBajas.php',
			data: { id: id },
			dataType: 'JSON',
			success: function (content) {
				if (content.error) alert(content.mensaje);
				else {
					if (content.cedula_socio.length == 7 || content.cedula_socio.substring(0, 1) == 0) {
						if (content.cedula_socio.substring(0, 1) == 0) content.cedula_socio = content.cedula_socio.substring(1, 8);
						c1 = content.cedula_socio.substring(0, 3);
						c2 = content.cedula_socio.substring(3, 6);
						c3 = content.cedula_socio.substring(6, 7);
						cedula = c1 + '.' + c2 + '-' + c3;
					}
					else {
						c1 = content.cedula_socio.substring(0, 1);
						c2 = content.cedula_socio.substring(1, 4);
						c3 = content.cedula_socio.substring(4, 7);
						c4 = content.cedula_socio.substring(7, 8);
						cedula = c1 + '.' + c2 + '.' + c3 + '-' + c4;
					}

					$('#idrelacion').val(content.idrelacion);

					$('#MIDBtitulo').text('Detalles de la baja de: ' + content.nombre_socio + ' (' + cedula + ')');
					$('#MIDBestadoActual').text('Estado actual: ' + content.estado);

					// INFORMACIÓN DEL SOCIO

					$('#MIDBidrelacion').val(content.idrelacion);
					$('#MIDBnombre').val(content.nombre_socio);
					$('#MIDBcedula').val(content.cedula_socio);
					$('#MIDBfilialS').val(content.filial_socio);
					$('#MIDBmotivoB').val(content.motivo_baja);

					// INFORMACIÓN DE CONTACTO

					$('#MIDBnombreC').val(content.nombre_contacto);
					$('#MIDBapellido').val(content.apellido_contacto);
					$('#MIDBtel').val(content.telefono_contacto);
					$('#MIDBcel').val(content.celular_contacto);

					// INFORMACIÓN DE GESTIÓN

					$('#MIDBnombreF').val(content.nombre_funcionario);
					$('#MIDBfilialF').val(content.filial_solicitud);
					$('#MIDBobs').val(content.observaciones);
					$('#MIDBfechaIngreso').val(content.fecha_ingreso_baja);

					// INFORMACIÓN DE ACTUALIZACIÓN DE LA GESTIÓN

					if (content.estado != 'Pendiente')
						$('#MIDBestado').val(content.estado);
					$('#MIDBobservacion').val(content.observacion_final);


					//Función para llenar select estado gestionar bajas
					llenar_select_estado_gestion_baja();


					$('#modalInformacionDetalladaBaja').modal('show');
				}
			},
			error: function () {
				alert('Ocurrio un error. Por favor vuelva a intentar en instantes.');
			}
		});
}


function llenar_select_estado_gestion_baja() {
	let sector = $("#sector_py").val();

	document.getElementById('MIDBestado').innerHTML = "<option value='' disabled selected>Seleccione el estado</option>";

	if (sector == "Bienvenidapy") {
		document.getElementById('MIDBestado').innerHTML += "<option value='Continua'>Continua</option>";
		document.getElementById('MIDBestado').innerHTML += "<option value='En Gestión'>En Gestión</option>";
	} else {
		document.getElementById('MIDBestado').innerHTML += "<option value='Continua'>Continua</option>";
		document.getElementById('MIDBestado').innerHTML += "<option value='En Gestión'>En Gestión</option>";
		document.getElementById('MIDBestado').innerHTML += "<option value='Otorgada'>Otorgada</option>";
		document.getElementById('MIDBestado').innerHTML += "<option value='No Otorgada'>No Otorgada</option>";
	}

}




//USUARIO LOGUEADO 1 ES CALIDAD Y 2 ES MIRIAN
function corroborarBajas(usuario_logueado) {
	$('#tablaMLB').DataTable().destroy();
	$("#tablaMLB tbody").html("");
	$('#tablaMLB tbody').empty();
	$.ajax(
		{
			url: 'PHP/AJAX/sistemaBajas/listarBajas.php?usuario=' + usuario_logueado,
			dataType: 'JSON',
			beforeSend: function () {
				$('#where').val('2');
			},
			success: function (content) {
				if (content.error) {
					error(content.mensaje);
					$('#modalListarBajas .close').click();
				} else {
					$.each(content, function (index, el) {
						nuevaBaja =
							'<tr>' +
							'<th class="text-center">' + el.nombre + '</th>' +
							'<td class="text-center">' + el.cedula + '</td>' +
							'<td class="text-center">' + el.telefono + '</td>' +
							'<td class="text-center">' + el.fecha + '</td>' +
							'<td class="text-center">' + el.motivo + '</td>' +
							'<td class="text-center">' + el.fechaGestion + '</td>' +
							'<td>' + el.observaciones + '</td>' +
							'<td class="text-center">' + el.filial_solicitud + '</td>' +
							'<td><input type="button" class="btn btn-primary center-block" value="Información detallada" onclick="masInfoMLB(' + el.id + ')"></td>' +
							'</tr>';
						$(nuevaBaja).appendTo('#tbodyMLB');
					});
					$("#tablaMLB").DataTable(
						{
							searching: true,
							paging: true,
							lengthChange: false,
							bSort: false,
							info: true,
							language:
							{
								zeroRecords: "No se encontraron registros.",
								info: "Pagina _PAGE_ de _PAGES_",
								infoEmpty: "No Hay Registros Disponibles",
								infoFiltered: "(filtrado de _MAX_ hasta records)",
								search: "Buscar:",
								paginate:
								{
									first: "Primero",
									last: "Último",
									next: "Siguiente",
									previous: "Anterior"
								},
							}
						});
					stateSave: true
					$('[type="search"]').addClass('form-control-static');
					$('[type="search"]').css({ borderRadius: '5px' });
					$('#modalListarBajas').modal('show');
				}
			},
			error: function () {
				error('Ocurrio un error. Por favor vuelva a intentar en instantes.');
			}
		});
}

function corroborarBajasWhere() {
	$('#tablaMLB').DataTable().destroy();
	$("#tablaMLB tbody").html("");
	$.ajax(
		{
			url: 'PHP/AJAX/sistemaBajas/listarBajas.php',
			data: { where: $('#where').val() },
			dataType: 'JSON',
			success: function (content) {
				if (content.error) error('Actualmente no hay bajas que apliquen con ese filtro');
				else {
					$.each(content, function (index, el) {
						nuevaBaja =
							'<tr>' +
							'<th class="text-center">' + el.nombre + '</th>' +
							'<td class="text-center">' + el.cedula + '</td>' +
							'<td class="text-center">' + el.telefono + '</td>' +
							'<td class="text-center">' + el.fecha + '</td>' +
							'<td class="text-center">' + el.motivo + '</td>' +
							'<td class="text-center">' + el.fechaGestion + '</td>' +
							'<td>' + el.observaciones + '</td>' +
							'<td class="text-center">' + el.filial_solicitud + '</td>' +
							'<td><input type="button" class="btn btn-primary center-block" value="Información detallada" onclick="masInfoMLB(' + el.id + ')"></td>' +
							'</tr>';
						$(nuevaBaja).appendTo('#tbodyMLB');
					});
					$("#tablaMLB").DataTable(
						{
							searching: true,
							paging: true,
							lengthChange: false,
							bSort: false,
							info: true,
							language:
							{
								zeroRecords: "No se encontraron registros.",
								info: "Pagina _PAGE_ de _PAGES_",
								infoEmpty: "No Hay Registros Disponibles",
								infoFiltered: "(filtrado de _MAX_ hasta records)",
								search: "Buscar:",
								paginate:
								{
									first: "Primero",
									last: "Último",
									next: "Siguiente",
									previous: "Anterior"
								},
							}
						});
					stateSave: true
					$('[type="search"]').addClass('form-control-static');
					$('[type="search"]').css({ borderRadius: '5px' });
					$('#modalListarBajas').modal('show');
				}
			},
			error: function () {
				error('Ocurrio un error. Por favor vuelva a intentar en instantes.');
			}
		});
}

// Funciones complementarias

function limpiarMIDB() {
	$('#MIDBnombreFA').val('');
	$('#MIDBestado').val('');
	$('#MIDBmno').val('');
	$('#MIDBobservacion').val('');
}

function cambiarMIDBidrelacion() {
	$('#MIDBidrelacion').val($('#idrelacion').val())
}

// Funciones de control

function corroborarMIDBestado() {
	if ($('#MIDBestado').val() == 'No Otorgada') $('#MIDBmno').prop('disabled', false);
	else {
		$('#MIDBmno').prop('disabled', true);
		$('#MIDBmno').val('');
	}
}