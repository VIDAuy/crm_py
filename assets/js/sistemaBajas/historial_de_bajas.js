// AJAX
function historialDeBajas() {
	$.ajax({
		url: `${url_ajax}sistemaBajas/historialDeBajas.php`,
		dataType: 'JSON',
		beforeSend: function () {
			$('#botonHistorialDeBajas').val('Cargando el historial...');
			$('#botonHistorialDeBajas').attr('disabled', true);
		},
		success: function (r) {
			$('#botonHistorialDeBajas').val('Ver historial de bajas');
			$('#botonHistorialDeBajas').attr('disabled', false);

			$("#tabla_historial_de_bajas").DataTable({
				ajax: `${url_ajax}sistemaBajas/historialDeBajas.php`,
				columns: [
					{ data: "id" },
					{ data: "cedula_socio" },
					{ data: "nombre_socio" },
					{ data: "telefono_contacto" },
					{ data: "celular_contacto" },
					{ data: "radio" },
					{ data: "motivo_baja" },
					{ data: "fecha_ingreso_baja" },
					{ data: "estado" },
					{ data: "filial_solicitud" },
					{ data: "acciones" },
				],
				order: [[0, "desc"]],
				bDestroy: true,
				language: {
					url: `${url_app}assets/js/lenguage.json`,
				},
			});

			$('#modalHistorialDeBajas').modal('show');
		},
	});
}



function modalMasInfoHistorialDeBajas(param) {
	$.ajax(
		{
			url: `${url_ajax}sistemaBajas/listarBajas.php`,
			data: { id: param },
			dataType: 'JSON',
			success: function (content) {
				if (content.error) {
					error(content.mensaje);
				}
				else {
					$('#txt_cedula_buscar').val(content.cedula_socio);
					if (content.cedula_socio.length == 7 || content.cedula_socio.substring(0, 1) == 0) {
						if (content.cedula_socio.substring(0, 1) == 0) {
							content.cedula_socio = content.cedula_socio.substring(1, 8)
						}
						c1 = content.cedula_socio.substring(0, 3);
						c2 = content.cedula_socio.substring(3, 6);
						c3 = content.cedula_socio.substring(6, 7);
						cedula = c1 + '.' + c2 + '-' + c3;
					} else {
						c1 = content.cedula_socio.substring(0, 1);
						c2 = content.cedula_socio.substring(1, 4);
						c3 = content.cedula_socio.substring(4, 7);
						c4 = content.cedula_socio.substring(7, 8);
						cedula = c1 + '.' + c2 + '.' + c3 + '-' + c4;
					}

					$('#idrelacion').val(content.idrelacion);

					$('#MMIHDBtitulo').text('Detalles de la baja de: ' + content.nombre_socio + ' (' + cedula + ')');
					$('#MMIHDBestadoActual').text('Estado actual: ' + content.estado);

					// INFORMACIÓN DEL SOCIO

					$('#MMIHDBidrelacion').val(content.idrelacion);
					$('#MMIHDBnombre').val(content.nombre_socio);
					$('#MMIHDBcedula').val(content.cedula_socio);
					$('#MMIHDBfilialS').val(content.filial_socio);
					$('#MMIHDBmotivoB').val(content.motivo_baja);

					// INFORMACIÓN DE CONTACTO

					$('#MMIHDBnombreC').val(content.nombre_contacto);
					$('#MMIHDBapellido').val(content.apellido_contacto);
					$('#MMIHDBtel').val(content.telefono_contacto);
					$('#MMIHDBcel').val(content.celular_contacto);

					// INFORMACIÓN DE GESTIÓN

					$('#MMIHDBnombreF').val(content.nombre_funcionario);
					$('#MMIHDBfilialF').val(content.filial_solicitud);
					$('#MMIHDBobs').val(content.observaciones);
					$('#MMIHDBfechaIngreso').val(content.fecha_ingreso_baja);

					// ACTUALIZACIÓN DE GESTIÓN

					$('#MMIHDBnombreFA').val(content.nombre_funcionario_final);
					$('#MMIHDBestado').val(content.estado);
					$('#MMIHDBmno').val(content.motivo_no_otorgada);
					$('#MMIHDBobservacion').val(content.observacion_final);

					$('#modalMasInfoHistorialDeBajas').modal('show');
				}
			},
			error: function () {
				error('Ocurrio un error. Por favor vuelva a intentar en instantes.');
			}
		});
}

// Funciones complementarias