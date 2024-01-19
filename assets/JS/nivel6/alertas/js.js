$(document).ready(function () {
    $('#previsualizar_documento_seleccionado').css('display', 'none');
    $('#imagenmuestra').css('display', 'none');
    $('#pdfmuestra').css('display', 'none');

    alertar_funcionario();
    setInterval(alertar_funcionario, 5000);
});

function cargar_documento_y_alertar() {
    cargar_select_tipo_documento();
    cargar_select_area_a_avisar();
    $("#modalCargarDocumentos").modal("show");
}


/*
function cargar_select_tipo_documento() {

    document.getElementById("select_tipo_documento").innerHTML = '<option value="" selected disabled>Seleccione el tipo de documento ...</option>';

    $.ajax({
        url: url_app + "select_tipo_documento.php",
        dataType: "JSON",
        success: function (r) {
            $.each(r.datos, function (i, v) {
                let nuevaLinea =
                    '<option value="' + v.id + '">' + v.tipo + '</option>';
                $(nuevaLinea).appendTo('.select_tipo_documento');
            });
        }
    });
}
*/




$("#file_cargar_documento").change(function () {
    readURL(this);
});


function readURL(archivo) {
    if (archivo.files && archivo.files[0]) {

        let ext = $(archivo).val().match(/\.([^\.]+)$/)[1];
        let extencion = ext.toLowerCase();

        switch (extencion) {
            case "jpg":
                mostrar_imagen(archivo);
                break;
            case "jpeg":
                mostrar_imagen(archivo);
                break;
            case "png":
                mostrar_imagen(archivo);
                break;
            case "pdf":
                mostrar_pdf(archivo);
                break;
            default:
                alerta("Error!", "Archivo invalido solo .jpg, .jpeg, .png, .pdf", "error");
        }


    }
}


function mostrar_imagen(imagen) {

    var reader = new FileReader();
    reader.onload = function (e) {
        // Asignamos el atributo src a la tag de imagen
        $('#previsualizar_documento_seleccionado').css('display', 'block');
        $('#pdfmuestra').css('display', 'none');
        $('#imagenmuestra').css('display', 'block');
        $('#imagenmuestra').attr('src', e.target.result);

    }
    reader.readAsDataURL(imagen.files[0]);
}


function mostrar_pdf(archivo) {

    var reader = new FileReader();
    reader.onload = function (e) {
        // Asignamos el atributo src a la tag de imagen
        $('#previsualizar_documento_seleccionado').css('display', 'block');
        $('#imagenmuestra').css('display', 'none');
        $('#pdfmuestra').css('display', 'block');
        $('#pdfmuestra').attr('src', e.target.result);

    }
    reader.readAsDataURL(archivo.files[0]);
}



function cargar_documento() {

    let tipo_documento = $('#select_tipo_documento').val();
    let area = $('#select_area_a_avisar').val();


    if (tipo_documento == "") {

        alerta("Error!", "Debe seleccionar el tipo de documento", "error");

    } else if ($("#file_cargar_documento").prop('files').length == 0) {

        alerta("Error!", "Debe seleccionar la imagen que desee subir", "error");

    } else if (area == "") {

        alerta("Error!", "Debe seleccionar el area a quien desee avisar", "error");

    } else {


        let formData = new FormData();
        formData.append('tipo_documento', tipo_documento);
        formData.append('area', area);
        formData.append('documento', $("#file_cargar_documento").prop('files')[0]);

        $.ajax({
            type: "POST",
            url: url_app + 'cargar_documentos.php',
            data: formData,
            dataType: "JSON",
            contentType: false,
            processData: false,
            success: function (response) {
                if (response.error === false) {
                    alerta('Exito!', response.mensaje, 'success');
                } else {
                    alerta('Error!', response.mensaje, 'error');
                }
            }
        });

    }
}


function alertas_de_documentos_cargados() {
    tabla_documentos_cargados();
    tabla_alertas_respondidas();
    $("#modalAlertasFuncionarios").modal("show");
}


function tabla_documentos_cargados() {
    $('#tabla_documentos_cargados').DataTable({
        ajax: url_app + 'documentos_cargados.php',
        columns: [
            { data: 'nro_carga' },
            { data: 'tipo_documento' },
            { data: 'documento' },
            { data: 'area' },
            { data: 'fecha_hora' },
            { data: 'responder' },
        ],
        "bDestroy": true,
        "order": [[0, 'asc']],
        "lengthChange": false,
        "ordering": false,
        language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
    });
}


function tabla_alertas_respondidas() {
    $('#tabla_alertas_respondidas').DataTable({
        ajax: url_app + 'alertas_respondidas.php',
        columns: [
            { data: 'fila' },
        ],
        "bDestroy": true,
        "ordering": false,
        "lengthChange": false,
        "searching": false,
        language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
    });
}



function mostrar_documento(ruta) {
    Swal.fire({
        showDenyButton: true,
        confirmButtonText: 'Cerrar',
        denyButtonText: 'Descargar archivo',
        width: 1000,
        html: '<iframe src="' + ruta + '" style="width:100%; height:800;"></iframe>',
    }).then((result) => {
        if (result.isDenied) {
            descargar_archivo(ruta);
        }
    });
}


function descargar_archivo(ruta) {
    fetch(ruta).then(res => res.blob()).then(file => {
        let tempUrl = URL.createObjectURL(file);
        const aTag = document.createElement("a");
        aTag.href = tempUrl;
        aTag.download = ruta.replace(/^.*[\\\/]/, '');
        document.body.appendChild(aTag);
        aTag.click();
        URL.revokeObjectURL(tempUrl);
        aTag.remove();
    });
}


function responder_estado_documento(nro_carga) {

    (async () => {

        const { value: respuesta } = await Swal.fire({
            title: 'Selecciona una respuesta:',
            input: 'select',
            inputOptions: {
                cargado: 'Cargado',
                devuelto: 'Devuelto'
            },
            inputPlaceholder: 'Selecciona una respuesta:',
            showCancelButton: true,
            inputValidator: (value) => {
                return new Promise((resolve) => {
                    if (value === 'cargado') {
                        cambiar_estado_respuesta(nro_carga, 'cargado');
                    } else {
                        cambiar_estado_respuesta(nro_carga, 'devuelto');
                    }
                })
            }
        })

    })();
}


function cambiar_estado_respuesta(nro_carga, estado) {

    $.ajax({
        type: "POST",
        url: url_app + 'cambiar_estado_respuesta.php',
        data: {
            'nro_carga': nro_carga,
            'estado': estado
        },
        dataType: "JSON",
        success: function (response) {
            if (response.error === false) {
                alerta('Exito!', response.mensaje, 'success');
                tabla_alertas_respondidas();
            } else {
                alerta('Error!', response.mensaje, 'error');
            }
        }
    });

}


function alertar_funcionario() {
    $.ajax({
        type: "GET",
        url: url_app + 'contar_pendientes.php',
        dataType: "JSON",
        success: function (response) {
            if (response.error === false) {
                document.getElementById('cantidad_pendientes').innerHTML = response.cantidad + "+";
            }
        }
    });
}