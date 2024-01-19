$('#boton').click(function(e) { 
    e.preventDefault();
    let mensaje = '';
    if($('#archivo').val().length == 0){
        mensaje += "Es necesario añadir un archivo para proceder.\n";
    }
    if($('#mes').val() == null){
        mensaje += "Es necesario añadir el mes de los registros para proceder.\n";
    }
    if($('#anho').val() == null){
        mensaje += "Es necesario añadir el año de los registros para proceder.";
    }
    if(mensaje != ''){
        alert(mensaje);
    }else{
        var documento = document.getElementById('archivo');
        var file = documento.files[0];
        var formData = new FormData();
        formData.append('archivo', file);
        formData.append('mes', $('#mes').val());
        formData.append('anho', $('#anho').val());
        $.ajax({
            type: "POST",
            url: "PHP/subirArchivo.php",
            data: formData,
            dataType: "JSON",
            processData: false,
            contentType: false,
            beforeSend: function () {
                $('#archivo').val('');
                alert('El proceso puede demorar hasta 5 minutos, por favor no cierre la pestaña del navegador hasta recibir una respuesta.\nPresione "aceptar" para continuar.');
            },
            success: function(response){
                if(response.correcto){
                    if(response.archivoSubido){
                        alert(response.mensaje);
                        $('#archivo').val('');
                    }
                }else if(response.error){
                    if(response.formatoIncorrecto){
                        alert(response.mensaje);
                        $('#archivo').val('');
                    } else if(response.errorDesconocido){
                        alert(response.mensaje);
                        $('#archivo').val('');
                    } else if(response.registroErroneo){
                        alert(response.mensaje);
                        $('#archivo').val('');
                    } else if(response.archivoExistente){
                        alert(response.mensaje);
                        $('#archivo').val('');
                    }
                }
            }
        });
    }
});