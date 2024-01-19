function datosCRM(){
	$('#b4').prop("disabled", true);
    $('#b4').val('Cargando...');
    $.ajax({
        type: "POST",
        url: "PHP/AJAX/masDatos/datosCRM.php",
        data: {
            ci: $('#cedulas').text()
        },
        dataType: "JSON",
        success: function (r) {
            $('#tbodyMDCRM').empty();
            if(r.correcto){
                if(r.registros){
                    $.each(r.f, function (i, v) {
                        let nuevaLinea = (v['socio'] == 'Sí')
                            ?   '<tr>' +
                                    '<td>'+ v['fecha_registro'] +'</td>' +
                                    '<td>'+ v['sector']         +'</td>' +
                                    '<td>'+ v['socio']          +'</td>' +
                                    '<td id="observacion'+ v.id +'">'+ v['observaciones']  +'</td>' +
                                    '<td> <input type="button" class="btn btn-outline-primary" value="Más info" id="info'+ v.id +'" onclick="masInfoMDCRM('+ v.id +')"> </td>' +
                                '</tr>'
                            :   '<tr>' +
                                    '<td>'+                     v['fecha_registro'] +'</td>' +
                                    '<td>'+                     v['sector']         +'</td>' +
                                    '<td style="color: red;">'+ v['socio']          +'</td>' +
                                    '<td id="observacion'+ v.id +'">'+                     v['observaciones']  +'</td>' +
                                    '<td> <input type="button" class="btn btn-outline-primary" value="Más info" id="info'+ v.id +'" onclick="masInfoMDCRM('+ v.id +')"> </td>' +
                                '</tr>';
                            $('#tbodyMDCRM').append(nuevaLinea);
                    });
                    $('#b4').val('Datos CRM');
                    $('#b4').prop("disabled", false);
                    $('#b4').val('Datos CRM');
                    $('#modalDatosCRM').modal('show');
                    $('.modal-backdrop').css({
                        'z-index': 1
                    });
                }
            } else if(r.error){
                if(r.sinRegistros){
                    alert(r.mensaje);
                    $('#b4').val('Sin registros en CRM');
                }
            }
        }
    });
}

function masInfoMDCRM(id) {
    $.ajax({
        type: "POST",
        url: "PHP/AJAX/masDatos/datosCRM.php",
        data: {
            menosInfo: 0,
            id: id
        },
        dataType: "JSON",
        success: function (r) {
            if (r.correcto) {
                $('#info' + id).val('Menos info');
                $('#info' + id).attr('onclick', 'menosInfoCRM('+ id +')');
                $('#observacion' + id).text(r.observacion);
            } else if(r.error){
                alert(r.mensaje);
            }
        }
    });
}

function menosInfoCRM(id){
    $.ajax({
        type: "POST",
        url: "PHP/AJAX/masDatos/datosCRM.php",
        data: {
            menosInfo: 1,
            id: id
        },
        dataType: "JSON",
        success: function (r) {
            if (r.correcto) {
                $('#info' + id).val('Más info');
                $('#info' + id).attr('onclick', 'masInfoMDCRM('+ id +')');
                $('#observacion' + id).text(r.observacion);
            } else if(r.error){
                alert(r.mensaje);
            }
        }
    });
}