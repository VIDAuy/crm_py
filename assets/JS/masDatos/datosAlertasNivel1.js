function datosAlertas(){
	if ($('#bq').text() == 0)
	{
		alert('No hay ningún mensaje que visualizar.');
	}
	else
	{
		$.ajax(
		{
			url: 'PHP/AJAX/masDatos/datosAlertasNivel1.php',
			dataType: 'JSON',
		})
		.done(function(datos)
		{
			$('#tbodyMDA tr').remove();
			$.each(datos, function(index, el)
			{
				let nuevaLinea =
							'<tr>' +
								'<td>' + el.sector + '</td>' +
								'<td>' + el.cedula + '</td>' +
								'<td>' + el.nombre + '</td>' +
								'<td>' + el.telefono + '</td>' +
								'<td>' + el.observacion + '</td>' +
								'<td class="d-flex justify-content-center"><button class="btn btn-success btn-sm" onclick="ver('+ el.cedula +')">Visto✓✓</button></td>' +
							'</tr>';
				$(nuevaLinea).appendTo('#tbodyMDA');
			});
			$('#modalDatosAlertasNivel1').modal('show');
		})
		.fail(function()
		{
			console.log("error");
		})
	}
}

function ver(CI)
{
	$.ajax(
	{
		data:  {CI: CI},
		url:   'PHP/AJAX/masDatos/datosAlertasNivel1.php',
		type:  'POST',
		dataType: 'JSON',
		success: function(content)
		{
			b = content.message;
			$('#ci').val(CI);
			$('#modalDatosAlertasNivel1 .close').click();
		}
	});
}