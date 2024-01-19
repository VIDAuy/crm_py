$(document).ready(function () {
    $('#previsualizar_documento_seleccionado').css('display', 'none');
    $('#imagenmuestra').css('display', 'none');
    $('#pdfmuestra').css('display', 'none');


    //setInterval(alertar_funcionario_estado_documento, 1000);
});

function cargar_documento_y_alertar() {
    cargar_select_tipo_documento();
    cargar_select_area_a_avisar();
    $("#modalCargarDocumentos").modal("show");
}


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


function cargar_select_area_a_avisar() {

    document.getElementById("select_area_a_avisar").innerHTML = '<option value="Sin avisar">Sin aviso</option>';
    document.getElementById("select_area_a_avisar").innerHTML += '<option value="Personal">Personal</option>';

    /*
    $.ajax({
        url: url_app + "agregarFiliales.php",
        dataType: "JSON",
        success: function (r) {
            $.each(r.datos, function (i, v) {
                let nuevaLinea =
                    '<option value="' + v.id + '">' + v.usuario + '</option>';
                $(nuevaLinea).appendTo('.select_area_a_avisar');
            });
        }
    });
    */
}


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
    let texto_tipo_documento = $('#select_tipo_documento').find('option:selected').text();
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
        formData.append('texto_tipo_documento', texto_tipo_documento);
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
        width: 1000,
        html: '<iframe src="' + ruta + '" style="width:100%; height:800;"></iframe>',
    });
}


function alertar_funcionario_estado_documento() {
    $.ajax({
        type: "GET",
        url: url_app + 'contar_cambios_estados_documentos.php',
        dataType: "JSON",
        success: function (response) {
            if (response.error === false) {
                document.getElementById('cantidad_pendientes_leer').innerHTML = response.cantidad + "+";

                /*
                if (response.cantidad > 0) {
                    notificacion_escritorio();
                }
                */
            }
        }
    });
}


$(document).on('click', '.check_leido', function () {
    let nro_cargo = $(this).val();

    if ($(this).is(':checked')) {
        $.ajax({
            type: "POST",
            url: url_app + 'marcar_alerta_leida.php',
            data: {
                "nro_cargo": nro_cargo
            },
            dataType: "JSON",
            success: function (response) {
                if (response.error === false) {
                    alerta("Exito!", response.mensaje, "success");
                    tabla_alertas_respondidas();
                } else {
                    alerta("Error!", response.mensaje, "error");
                }
            }
        });
    }

});


/*
async function notificacion_escritorio() {
    Notification.requestPermission(function (permission) {

        let opciones = {
            body: " Presiona aqui para ver los documentos",
            icon: url_app + "assets/img/alerta_crm.png",
        };
        let notificacion = new Notification("CRM Funcionarios - Tienes Alertas pendientes", opciones);
        notificacion.onclick = function () {
            window.location.replace(url_app + "index.php");

        }
    });
}
*/