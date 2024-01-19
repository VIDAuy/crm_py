$(function()
{
	$(document).keypress(function(e)
	{
		if(e.which == 13)
		{
			e.preventDefault();
			buscarCI();
		}
	});

	$('#enviarCI').on('click', function() {
		buscarCI();
	});
})

function buscarCI(){
	if ($('#CI').val().length != 0)
	{
		if (comprobarCI($('#CI').val()))
		{
			$.ajax(
			{
				url: 'PHP/AJAX/nivel4/listaEmpleados.php',
				dataType: 'JSON',
				data: {CI: $('#CI').val()},
			})
			.done(function(r)
			{
				if (r.sinRegistros)
					alert('La cédula no pertenece a un acompañante activo.');
				else
				{
					$('#informacion').css({display: 'block'});
					$('#nombreCompleto').val(r.nombre_completo);
					$('#telefono').val(r.telefono);
					$('#cedula').val(r.doc_persona);
					$('#departamento').val(r.nom_dpto);
					$('#fechaNacimiento').val(r.fec_nac);
					$('#fechaIngreso').val(r.fingreso);
					$('#fechaEgreso').val(r.fegreso);
					$('#estado').val(r.estado_trab);
				}
			})
			.fail(() =>
			{
				alert('Ha ocurrido un error, por favor comuníquese con el administrador.');
			});
		}
		else
			alert('La cédula ingresada es incorrecta.');
	}
	else
		alert('Debe ingresar una cédula.');

}

function ocultarInformacion(){
	$('#informacion').css({display: 'none'})
}

// -------------------- CONTROLES

function comprobarCI(cedi){
	if(cedi == "93233611" || cedi == "78183625") return true;

	var arrCoefs = [2,9,8,7,6,3,4,1];
	var suma = 0;
	var difCoef = parseInt(arrCoefs.length - cedi.length);
	for(var i=cedi.length - 1; i> -1;i--){
		var dig 	= cedi.substring(i, i+1);
		var digInt 	= parseInt(dig);
		var coef 	= arrCoefs[i+difCoef];
		suma = suma + digInt * coef;
	}
	if ((suma % 10) == 0) {
		return true;
	}else{
		return false;
	}
}


$(".solo_numeros").keydown(function (e) {
	if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 40]) !== -1 || (e.keyCode >= 35 && e.keyCode <= 39)) {
		return;
	}
	if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
		e.preventDefault();
	}
	if(e.altKey){
		return false;
	}
});