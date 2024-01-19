// DISPARADORES PASIVOS

$(function()
{
    llamadosPendientesParaHoy();
    agregarAnhos();

    $('#botonBusquedaAvanzada').click(function()
    {
        $('#BAdADiv').empty();
        cargarFiliales();
        $('#cedulas').text('');
        $('#CIValue').val('');
        $('#datosMoroso').hide('fast');
        $('#busquedaAvanzada').toggle('fast');
    });

    $('#buscarCI').bind('keypress', function(e)
    {
        if(e.which == 13){
            e.preventDefault();
            if(controlCI()){
                buscarCI();
            } else{
                alert('La cédula debe de estar completa.');
            }
        }
    });

    $('#buscarCI input:button').click(function(e)
    {
        e.preventDefault();
        $('#cedulas').text('');
        if(controlCI()){
            buscarCI();
        } else{
            alert('La cédula debe de estar completa.');
        }
    });

    $('#buscarCI input:text').keydown(function(e)
    {
        $('#datosMoroso').hide('fast');
    });

    $('#buscarBAdA').click(function(e)
    {
        e.preventDefault();
        $('#BAdADiv').empty();
        // if(){

        // }else
        let data = $('#formBAdA').serialize();
        $.ajax(
            {
            type: "POST",
            url: "PHP/AJAX/nivel5/busquedaAvanzadaDeAgenda.php",
            data: data,
            dataType: "JSON",
            success: function(r)
            {
                if(r.correcto){
                    if(r.conRegistros)
                    {
                        let crearTabla =
                            '<table id="BAdATabla">' +
                                '<thead>' +
                                    '<tr>' +
                                        '<th>Nombre:</th>' +
                                        '<th>Cédula:</th>' +
                                        '<th>Nro talon:</th>' +
                                        '<th>Mes/año:</th>' +
                                        '<th>Estado:</th>' +
                                        '<th>Ver</th>' +
                                    '</tr>' +
                                '</thead>' +
                                '<tbody id="BAdATBody">' +
                                '</tbody>' +
                            '</table>';
                        $('#BAdADiv').append(crearTabla);
                        $.each(r, function(i, v)
                        {
                            if(v.nombre != undefined)
                            {
                                let crearLinea =
                                    '<tr>' +
                                        '<td>' + v.nombre + '</td>' +
                                        '<td>' + v.cedula + '</td>' +
                                        '<td>' + v.nro_talon + '</td>' +
                                        '<td>' + v.mes_registro + '/' + v.anho_registro +'</td>' +
                                        '<td>' + v.estado + '</td>' +
                                        '<td><input type="button" onclick="informacionDetalladaDe(' + v.cedula + ')" class="btn btn-outline-primary" value="Ver más"></td>' +
                                    '</tr>';
                                $('#BAdATBody').append(crearLinea);
                            }
                        });
                        $('#BAdATabla').DataTable(
                            {
                            searching: true,
                            paging: true,
                            lengthChange: false,
                            ordering: true,
                            info: true,
                            language: {
                                zeroRecords: "No se encontraron registros.",
                                info: "Pagina _PAGE_ de _PAGES_",
                                infoEmpty: "No Hay Registros Disponibles",
                                infoFiltered: "(filtrado de _MAX_ hasta records)",
                                search:"Buscar:",
                                paginate: {
                                    first:      "Primero",
                                    last:       "Último",
                                    next:       "Siguiente",
                                    previous:   "Anterior"
                                },
                            }
                        })
                        stateSave: true
                        $('[type="search"]').addClass('form-control-static');
                        $('[type="search"]').css({borderRadius: '5px'});
                    } else if(r.sinRegistros)
                    {
                        alert(r.mensaje);
                    }
                }
                if(r.error)
                {
                    alert(r.mensaje);
                }
            }
        });
    });

    $('#estadoGC').change(function(e)
    {
        e.preventDefault();
        habilitarfechaOpcionalGC();
    });

    $('#estadoGD').change(function(e)
    {
        e.preventDefault();
        habilitarfechaOpcionalGD();
    });

    $('#filtroFecha').change(function(e)
    {
        e.preventDefault();
        if($('#filtroFecha').val() == 1)
        {
            $('#filtroJunto').css({display: 'inline'});
            $('#fechaCompletaDesde').datepicker(opcionesDatePicker);
            $('#fechaCompletaHasta').datepicker(opcionesDatePicker);
            $('#filtroInexistente').css({display: 'none'});
            $('#filtroSeparado').css({display: 'none'});
            $('#mesDesde').val('');
            $('#anhoDesde').val('');
            $('#mesHasta').val('');
            $('#anhoHasta').val('');
        }
        else if($('#filtroFecha').val() == 2)
        {
            $('#filtroJunto').css({display: 'inline'});
            $('#fechaCompletaDesde').datepicker(opcionesDatePicker);
            $('#fechaCompletaHasta').datepicker(opcionesDatePicker);
            $('#filtroInexistente').css({display: 'none'});
            $('#filtroSeparado').css({display: 'none'});
            $('#mesDesde').val('');
            $('#anhoDesde').val('');
            $('#mesHasta').val('');
            $('#anhoHasta').val('');
        }
        else if($('#filtroFecha').val() == 3)
        {
            $('#filtroSeparado').css({display: 'inline'});
            $('#filtroInexistente').css({display: 'none'});
            $('#filtroJunto').css({display: 'none'});
            $('#fechaCompletaDesde').val('');
            $('#fechaCompletaHasta').val('');
        }
        else
        {
            $('#filtroInexistente').css({display: 'inline'});
            $('#filtroJunto').css({display: 'none'});
            $('#fechaCompletaDesde').val('');
            $('#fechaCompletaHasta').val('');
            $('#filtroSeparado').css({display: 'none'});
            $('#mesDesde').val('');
            $('#anhoDesde').val('');
            $('#mesHasta').val('');
            $('#anhoHasta').val('');
        }
    });

    $('#guardarGC').click(function(e)
    {
        e.preventDefault();
        let mensaje = '';
        if ($('#estadoGC').val() == null)
        {
            mensaje += "El campo 'Estado' es obligatorio.\n";
        }
        if($('#observacionGC').val() == '')
        {
            mensaje += 'El campo \'Observación\' es obligatorio.';
        }
        if (mensaje != '')
        {
            alert("Tenga en cuenta lo siguiente:\n" + mensaje)
        }
        else
        {
            guardarDatosGC();
        }
    });

    $('#guardarGD').click(function(e)
    {
        e.preventDefault();
        let mensaje = '';
        if ($('#estadoGD').val() == null)
        {
            mensaje += "El campo 'Estado' es obligatorio.\n";
        }
        if($('#observacionGD').val() == '')
        {
            mensaje += 'El campo \'Observación\' es obligatorio.';
        }
        if (mensaje != '')
        {
            alert("Tenga en cuenta lo siguiente:\n" + mensaje)
        }
        else
        {
            guardarDatosGD();
        }
    });

    $('#gestionarAnterior').click(function(e)
    {
        e.preventDefault();
        $.ajax(
            {
            url: "PHP/AJAX/nivel5/cambiarCedula.php",
            data:
            {
                ci: $('#CIValue').val(),
                pasar: 'anterior'
            },
            dataType: "JSON",
            success: function(r)
            {
                $('#buscarCI input:text').val(r.cedula);
                $('#buscarCI input:button').click();
            }
        });
    });

    $('#gestionarSiguiente').click(function(e)
    {
        e.preventDefault();
        $.ajax({
            url: "PHP/AJAX/nivel5/cambiarCedula.php",
            data:
            {
                ci: $('#CIValue').val(),
                pasar: 'siguiente'
            },
            dataType: "JSON",
            success: function(r)
            {
                $('#buscarCI input:text').val(r.cedula);
                $('#buscarCI input:button').click();
            }
        });
    });
});

// AJAX

function agregarAnhos()
{
    $.ajax(
    {
        url: "PHP/AJAX/nivel5/anhos.php",
        dataType: "JSON",
        success: function(r)
        {
            $.each(r, function(i, v)
            {
                let nuevaLinea = '<option value="'+ v.anho +'">'+ v.anho +' </option>';
                $('#anhoDesde').append(nuevaLinea);
                $('#anhoHasta').append(nuevaLinea);
            });
        }
    });
}

function llamadosPendientesParaHoy()
{
    $.ajax(
    {
        url: "PHP/AJAX/nivel5/llamadasPendientes.php",
        dataType: "JSON",
        success: function(r)
        {
            if(r.correcto){
                $('#llamadosPendientesParaHoy').val(r.mensaje);
                if(r.registros)
                {
                    $('#llamadosPendientesParaHoy').click(function(e)
                    {
                        e.preventDefault();
                        mostrarModalLlmadasPendientes();
                    });
                }
                else
                {
                    $('#llamadosPendientesParaHoy').off('click');
                }
            }
            else if(r.error)
            {
                $('#llamadosPendientesParaHoy').val(r.mensaje);
                $('#llamadosPendientesParaHoy').click(function(e)
                {
                    e.preventDefault();
                });
                $('#llamadosPendientesParaHoy').off('click');
            }
        }
    });
}

function mostrarModalLlmadasPendientes()
{
    $.ajax(
    {
        url: "PHP/AJAX/nivel5/llamadasPendientes.php?mostrar",
        dataType: "JSON",
        success: function(r) {
            $('#tbodymLlP').empty();
            $.each(r, function(i, v)
            {
                 let nuevaLinea =   '<tr>' +
                                        '<td>' + v.nombre + '</td>' +
                                        '<td>' + v.cedula + '</td>' +
                                        '<td>' + v.telefono + '</td>' +
                                        '<td>' + v.observacion + '</td>' +
                                        '<td><input type="button" class="btn btn-outline-danger" value="Gestionar" onclick="gestionarLlamadaPendiente('+ v.id +')"</td>' +
                                    '</tr>';
                $('#tbodymLlP').append(nuevaLinea);
            });
            $('#modalLlamadasPendientes').modal('show');
        }
    });
}

function gestionarLlamadaPendiente(id)
{
    $.ajax(
    {
        url: "PHP/AJAX/nivel5/gestionarLlamadaPendiente.php",
        data:
        {
            id: id
        },
        dataType: "JSON",
        success: function(r)
        {
            if(r.correcto){
                $('#buscarCI input:text').val(r.cedula);
                $('#modalLlamadasPendientes .close').click();
                llamadosPendientesParaHoy();
                buscarCI();
            }
            else if(r.error)
            {
                alert(r.mensaje);
            }
        }
    });
}

function buscarCI()
{
    $('#busquedaAvanzada').hide('fast');
    $('#datosMoroso').hide('fast');
    $.ajax(
    {
        type: "POST",
        url: "PHP/AJAX/nivel5/listarDatos.php",
        data:
        {
            ci: $('#buscarCI input:text').val()
        },
        dataType: "JSON",
        success: function(f)
        {
            if(f.correcto){
                if(f.conRegistros)
                {
                    $('#cedulas').text(f.cedula);
                    $('#ci').val(f.cedula);
                    $('#cedula').val(f.cedula);
                    $('#nombre').val(f.nombre);
                    $('#valorCuota').val(f.valorCuota);
                    $('#direccion').val(f.direccion);
                    $('#telefono').val(f.tel);
                    $('#filial').val(f.filial);
                    $('#ruta').val(f.ruta);
                    $('#radio').val(f.radio);
                    $('#fechaAfiliacion').val(f.fechaAfiliacion);
                    $('#b1').prop("disabled", false);
                    $('#b1').val('Datos Coordina');
                    $('#b2').prop("disabled", false);
                    $('#b2').val('Datos Cobranza');
                    $('#b3').prop("disabled", false);
                    $('#b3').val('Datos Productos');
                    $('#b4').prop("disabled", false);
                    $('#b4').val('Datos CRM');
                    $('#datosMoroso').show('fast');
                    if (f.tipoDeCobro == 0)
                    {
                        gestionCentralizado();
                    }
                    else
                    {
                        gestionDomiciliario();
                    }
                    historialDeMora();
                }
            }
            if(f.error)
            {
                if(f.sinRegistros)
                {
                    error(f.mensaje);
                }
                if(f.sinDeudas)
                {
                    error(f.mensaje);
                }
            }
        }
    });
}

function gestionCentralizado()
{
    $.ajax(
    {
        type: "POST",
        url: "PHP/AJAX/nivel5/gestion.php",
        data:
        {
            gestion: 0,
            ci: $('#buscarCI input:text').val()
        },
        dataType: "JSON",
        success: function(r)
        {
            habilitarfechaOpcionalGC();
            limpiarModalGC();
            $('#abrirModalGestion').attr('onclick', "$('#modalGestionCentralizado').modal('show')");
            // Título del modal
            $('#tituloGC').text('Deuda total: $' + r.deuda_total + ' (' + r.mes_registro + '/' + r.anho_registro + ')');
            // Datos del socio
            $('#idGC').val(r.id);
            $('#nombreGC').val(r.nombre);
            $('#cedulaGC').val(r.cedula);
            $('#telefonoGC').val(r.telefono);
            $('#nroTalonGC').val(r.nro_talon);
            // Datos tarjeta
            $('#nroTarjetaGC').val(r.nro_tarjeta);
            $('#CITitularGC').val(r.cedula_titular);
            if(r.motivo_de_rechazo == null) {r.motivo_de_rechazo = '(Sin especificar)'}
            $('#mDRGC').val(r.motivo_de_rechazo);
            // Cuotas anteriores
            r.mes_seis      = (r.mes_seis == null)      ? 'Pagó' : '$' + r.mes_seis;
            $('#cuota6GC').val(r.mes_seis);
            r.mes_cinco     = (r.mes_cinco == null)     ? 'Pagó' : '$' + r.mes_cinco;
            $('#cuota5GC').val(r.mes_cinco);
            r.mes_cuatro    = (r.mes_cuatro == null)    ? 'Pagó' : '$' + r.mes_cuatro;
            $('#cuota4GC').val(r.mes_cuatro);
            r.mes_tres      = (r.mes_tres == null)      ? 'Pagó' : '$' + r.mes_tres;
            $('#cuota3GC').val(r.mes_tres);
            r.mes_dos       = (r.mes_dos == null)       ? 'Pagó' : '$' + r.mes_dos;
            $('#cuota2GC').val(r.mes_dos);
            r.mes_anterior  = (r.mes_anterior == null)  ? 'Pagó' : '$' + r.mes_anterior;
            $('#cuota1GC').val(r.mes_anterior);
            $('#mes6GC').text(r.mes6);
            $('#mes5GC').text(r.mes5);
            $('#mes4GC').text(r.mes4);
            $('#mes3GC').text(r.mes3);
            $('#mes2GC').text(r.mes2);
            $('#mes1GC').text(r.mes1);
        }
    });
}

function gestionDomiciliario()
{
    $.ajax(
    {
        type: "POST",
        url: "PHP/AJAX/nivel5/gestion.php",
        data:
        {
            gestion: 1,
            ci: $('#buscarCI input:text').val()
        },
        dataType: "JSON",
        success: function(r)
        {
            habilitarfechaOpcionalGD();
            limpiarModalGD();
            $('#abrirModalGestion').attr('onclick', "$('#modalGestionDomiciliario').modal('show')");
            // Título del modal
            $('#tituloGD').text('Deuda total: $' + r.deuda_total + ' (' + r.mes_registro + '/' + r.anho_registro + ')');
            // Datos del socio
            $('#idGD').val(r.id);
            $('#nombreGD').val(r.nombre);
            $('#cedulaGD').val(r.cedula);
            $('#telefonoGD').val(r.telefono);
            $('#nroTalonGD').val(r.nro_talon);
            // Cuotas anteriores
            r.mes_seis      = (r.mes_seis == null)      ? 'Pagó' : '$' + r.mes_seis;
            $('#cuota6GD').val(r.mes_seis);
            r.mes_cinco     = (r.mes_cinco == null)     ? 'Pagó' : '$' + r.mes_cinco;
            $('#cuota5GD').val(r.mes_cinco);
            r.mes_cuatro    = (r.mes_cuatro == null)    ? 'Pagó' : '$' + r.mes_cuatro;
            $('#cuota4GD').val(r.mes_cuatro);
            r.mes_tres      = (r.mes_tres == null)      ? 'Pagó' : '$' + r.mes_tres;
            $('#cuota3GD').val(r.mes_tres);
            r.mes_dos       = (r.mes_dos == null)       ? 'Pagó' : '$' + r.mes_dos;
            $('#cuota2GD').val(r.mes_dos);
            r.mes_anterior  = (r.mes_anterior == null)  ? 'Pagó' : '$' + r.mes_anterior;
            $('#cuota1GD').val(r.mes_anterior);
            $('#mes6GD').text(r.mes6);
            $('#mes5GD').text(r.mes5);
            $('#mes4GD').text(r.mes4);
            $('#mes3GD').text(r.mes3);
            $('#mes2GD').text(r.mes2);
            $('#mes1GD').text(r.mes1);
        }
    });
}

function cargarFiliales()
{
    $.ajax(
    {
        type: "POST",
        url: "PHP/AJAX/cargarFiliales.php",
        dataType: "JSON",
        success: function(r)
        {
            $.each(r, function(i, v)
            {
                let nuevaLinea = '<option value="'+ v.nro_filial +'">'+ v.filial +'</option>';
                $('#filialBAdA').append(nuevaLinea);
            });
        }
    });
}

function guardarDatosGC()
{
    $.ajax(
    {
        type: "POST",
        url: "PHP/AJAX/nivel5/guardarDatos.php",
        data:
        {
            nombre:      $('#nombreGC').val(),
            telefono:    $('#telefonoGC').val(),
            cedula:      $('#cedulaGC').val(),
            estado:      $('#estadoGC').val(),
            observacion: $('#observacionGC').val()
        },
        dataType: "JSON",
        success: function(r)
        {
            if(r.correcto)
            {
                historialDeMora();
                alert(r.mensaje);
                $('#modalGestionCentralizado .close').click();
            }
            if(r.error)
            {
                alert(r.mensaje);
            }
        }
    });
}

function guardarDatosGD()
{
    $.ajax(
    {
        type: "POST",
        url: "PHP/AJAX/nivel5/guardarDatos.php",
        data:
        {
            nombre:      $('#nombreGD').val(),
            telefono:    $('#telefonoGD').val(),
            cedula:      $('#cedulaGD').val(),
            estado:      $('#estadoGD').val(),
            observacion: $('#observacionGD').val()
        },
        dataType: "JSON",
        success: function(r)
        {
            if(r.correcto){
                historialDeMora();
                alert(r.mensaje);
                $('#modalGestionDomiciliario .close').click();
            }
            if(r.error)
            {
                alert(r.mensaje);
            }
        }
    });
}

function historialDeMora()
{
    $.ajax(
    {
        type: "POST",
        url: "PHP/AJAX/nivel5/historialDeMora.php",
        data:
        {
            ci: $('#buscarCI input:text').val()
        },
        dataType: "JSON",
        success: function(r)
        {
            crearTabla();
            $.each(r, function(i, v)
            {
                let nuevaLinea =
                    '<tr>' +
                        '<td width="20%">' + v.fecha_observacion + '</td>' +
                        '<td width="25%">' + v.estado + '</td>' +
                        '<td width="55%">' + v.observacion + '</td>' +
                    '</tr>';
                $('#datosMorosoTBody').append(nuevaLinea);
            });
            $('#datosMorosoTabla').DataTable(
            {
				searching: true,
				paging: true,
				lengthChange: false,
				ordering: true,
				info: true,
				order: [0,'asc'],
                language:
                {
					zeroRecords: "No se encontraron registros.",
					info: "Pagina _PAGE_ de _PAGES_",
					infoEmpty: "No Hay Registros Disponibles",
					infoFiltered: "(filtrado de _MAX_ hasta records)",
					search:"Buscar:",
					paginate: {
						first:      "Primero",
						last:       "Último",
						next:       "Siguiente",
						previous:   "Anterior"
					},
				}
			});
			stateSave: true
			$('[type="search"]').addClass('form-control-static');
			$('[type="search"]').css({borderRadius: '5px'});
        }
    });
}

// FUNCIONES SUPLEMENTARIAS

function crearTabla()
{
    $('#datosMorosoDiv').empty();
    let crearTabla =
        '<table id="datosMorosoTabla">' +
			'<thead>' +
				'<tr>' +
					'<th width="20%">Fecha de observación:</th>' +
					'<th width="25%">Estado:</th>' +
					'<th width="55%">Observación:</th>' +
				'</tr>' +
			'</thead>' +
			'<tbody id="datosMorosoTBody">' +
			'</tbody>' +
		'</table>';
	$('#datosMorosoDiv').append(crearTabla);
}

function informacionDetalladaDe(cedula)
{
    $('#buscarCI input:text').val(cedula);
    $('#buscarCI input:button').click();
}

// CONTROLES

function controlCI()
{
    return $('#buscarCI input:text').val().length > 6;
}

function habilitarfechaOpcionalGC()
{
    if($('#estadoGC').val() == 4)
    {
        $('#fechaOpcionalGC').prop({disabled: false});
        $('#fechaOpcionalGC').datepicker(opcionesDatePicker);
        $('#fechaOpcionalGCLabel').text('Fecha promesa pago:');
    }
    else if($('#estadoGC').val() == 5)
    {
        $('#fechaOpcionalGC').prop({disabled: false});
        $('#fechaOpcionalGC').datepicker(opcionesDatePicker);
        $('#fechaOpcionalGCLabel').text('Llamar el:');
    }
    else
    {
        $('#fechaOpcionalGC').prop({disabled: true});
        $('#fechaOpcionalGC').val('');
        $('#fechaOpcionalGCLabel').text('Campo innecesario:');
    }
}

function habilitarfechaOpcionalGD()
{
    if($('#estadoGD').val() == 4)
    {
        $('#fechaOpcionalGD').prop({disabled: false});
        $('#fechaOpcionalGD').datepicker(opcionesDatePicker);
        $('#fechaOpcionalGDLabel').text('Fecha promesa pago:');
    }
    else if($('#estadoGD').val() == 5)
    {
        $('#fechaOpcionalGD').prop({
            disabled: false
        });
        $('#fechaOpcionalGD').datepicker(opcionesDatePicker);
        $('#fechaOpcionalGDLabel').text('Llamar el:');
    }
    else
    {
        $('#fechaOpcionalGD').prop({
            disabled: true
        });
        $('#fechaOpcionalGD').val('');
        $('#fechaOpcionalGDLabel').text('Campo innecesario:');
    }
}

function limpiarModalGC()
{
    $('#observacionGC').val('');
    $('#estadoGC [value="disabled"]').prop(
    {
        disabled: false,
        selected: false,
        selected: true,
        disabled: true,
    });
    $('#fechaOpcionalGC').prop({disabled: true});
    $('#fechaOpcionalGC').val('');
    $('#fechaOpcionalGCLabel').text('Campo innecesario:');
}

function limpiarModalGD(){
    $('#observacionGD').val('');
    $('#estadoGD [value="disabled"]').prop({
        disabled: false,
        selected: false,
        selected: true,
        disabled: true,
    });
    $('#fechaOpcionalGD').prop({disabled: true});
    $('#fechaOpcionalGD').val('');
    $('#fechaOpcionalGDLabel').text('Campo innecesario:');
}

$(".solo_numeros").keydown(function(e) {
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 40]) !== -1 || (e.keyCode >= 35 && e.keyCode <= 39))
    {
		return;
	}
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105))
    {
		e.preventDefault();
	}
    if(e.altKey)
    {
		return false;
	}
});

// VARIABLES GLOBALES

var opcionesDatePicker = {
    dateFormat: 'dd/mm/yy',
    closeText: 'Cerrar',
    prevText: 'Mes anterior',
    nextText: 'Mes siguiente',
    currentText: 'Hoy',
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mié', 'Juv', 'Vie', 'Sáb'],
    dayNamesMin: ['Do', 'Lu', 'Ma', 'Mi', 'Ju', 'Vi', 'Sá'],
    weekHeader: 'Sm',
    dateFormat: 'dd/mm/yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''
}