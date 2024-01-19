function datosAlertas()
{
	if ($('#bq').text() == 0)
	{
		error('No hay ningún mensaje que visualizar.');
	}
	else
	{
		$.ajax(
		{
			url: 'PHP/AJAX/masDatos/datosAlertas.php',
			dataType: 'JSON',
		})
		.done(function(datos)
		{
			$('#tbodyMDA tr').remove();
			$.each(datos, function(index, el)
			{
				let nuevaLinea =
					'<tr>' +
						'<td class="text-center">' + el.sector + '</td>' +
						'<td class="text-center">' + el.cedula + '</td>' +
						'<td class="text-center">' + el.nombre + '</td>' +
						'<td class="text-center">' + el.telefono + '</td>' +
						'<td class="text-center"><button class="btn btn-primary btn-sm" onclick="ver('+ el.cedula + ',' + el.idRegistro +')">Ver más</button></td>' +
					'</tr>';
				$(nuevaLinea).appendTo('#tbodyMDA');
			});
			$('#modalDatosAlertas').modal('show');
		})
		.fail(function()
		{
			error("error");
		})
	}
}

function ver(CI, idRegistro)
{
	$.ajax(
	{
		data:
		{
			CI: CI,
			idRegistro
		},
		url:   'PHP/AJAX/masDatos/datosAlertas.php',
		type:  'POST',
		dataType: 'JSON',
		success: function(content)
		{
			b = content.message;
			$('#ci').val(CI);
			$('#modalDatosAlertas .close').click();
			$('#buscarCI').click();
		}
	});
}