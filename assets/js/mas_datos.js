function datos_cobranza() {
    let cedula = $('#span_cedula_socio').text();

    $('#tabla_datos_cobranzas').DataTable({
        ajax: `${url_ajax}masDatos/datos_de_cobranzas.php?cedula=${cedula}`,
        columns: [
            { data: 'mes' },
            { data: 'anho' },
            { data: 'importe' },
            { data: 'cobrado' },
        ],
        "order": [[1, 'desc'], [0, 'desc']],
        "bDestroy": true,
        language: {
            url: `${url_app}assets/js/lenguage.json`,
        },
    });
}


function datos_coordina() {
    let cedula = $('#span_cedula_socio').text();

    $('#tabla_servicios_coordinacion').DataTable({
        ajax: `${url_ajax}masDatos/datos_coordina.php?cedula=${cedula}`,
        columns: [
            { data: 'id' },
            { data: 'observacion' },
        ],
        "order": [[0, 'asc']],
        "bDestroy": true,
        language: {
            url: `${url_app}assets/js/lenguage.json`,
        },
    });
}


function datos_productos() {
    let cedula = $('#span_cedula_socio').text();

    $('#tabla_productos_contratados').DataTable({
        ajax: `${url_ajax}masDatos/productos_contratados.php?cedula=${cedula}`,
        columns: [
            { data: 'nroServicio' },
            { data: 'servicio' },
            { data: 'horas' },
            { data: 'importe' },
            { data: 'fecha_afiliacion' },
        ],
        "order": [[0, 'asc']],
        "bDestroy": true,
        language: {
            url: `${url_app}assets/js/lenguage.json`,
        },
    });
}


function tabla_servicios_utilizados() {
    let cedula = $('#span_cedula_socio').text();

    $('#tabla_servicios').DataTable({
        ajax: `${url_ajax}masDatos/servicios_utilizados.php?cedula=${cedula}`,
        columns: [
            { data: 'id' },
            { data: 'fecha_inicio' },
            { data: 'fecha_fin' },
            { data: 'horas_x_dia' },
            { data: 'hora_inicio' },
            { data: 'hora_fin' },
        ],
        columnDefs: [{
            "targets": [0],
            "visible": false,
            "searchable": false
        }],
        "order": [0, 'desc'],
        "bDestroy": true,
        language: {
            "decimal": ",",
            "thousands": ".",
            "lengthMenu": "Mostrar _MENU_ registros",
            "zeroRecords": "<span style='font-weight: bolder; color: red'> ¡No Tuvo Servicios! </span>",
            "info": "Mostrando _START_ a _END_ de _TOTAL_ registros",
            "infoEmpty": "Mostrando 0 a 0 de 0 registros",
            "infoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "sProcessing": "Cargando..."
        }
    });
}