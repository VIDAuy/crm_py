const produccion = true;
const app = produccion ? "crm_py" : "crm_py_test";
const url_app = 'http://192.168.1.250:82/' + app + '/PHP/AJAX/nivel6/';
const url_app_procesos = 'http://192.168.1.250:82/' + app + '/PHP/AJAX/nivel6/procesos/';



$(document).ready(function () {
    ocultarTodoContenido();
    //mostrarContenido('contenido_funcionarios');
    //mostrarContenido('contenido_socios');
});


function buscarCedula() {


    if (cedula != "") {

        if (consultar === 'socio' && comprobarCI(cedula)) {

            buscarSocio(cedula);

        } else if (consultar === 'funcionario') {

            const regex_numeros = /^[0-9]*$/;
            if (regex_numeros.test(cedula)) {
                buscarFuncionario(cedula, 'cedula');
            } else {
                buscarFuncionario(cedula, 'pasaporte');
            }

        } else {
            alerta('Error!', 'La cédula ingresada no es válida.', 'error');
        }

    } else {
        alerta('Error!', 'Debe ingresar la cédula de la persona que quiera buscar.', 'error');
    }

}


function buscarCedula() {
    let cedula = $('#ci_personal').val();
    limpiarFechas();
    let consultar = document.querySelector('input[name="radioBuscar"]:checked').value;

    if (cedula.length != 0) {

        if (consultar === 'socio' && comprobarCI(cedula)) {

            buscarSocio(cedula);

        } else if (consultar === 'funcionario') {

            const regex_numeros = /^[0-9]*$/;
            if (regex_numeros.test(cedula)) {
                buscarFuncionario(cedula, 'cedula');
            } else {
                buscarFuncionario(cedula, 'pasaporte');
            }

        } else {
            alerta('Error!', 'La cédula ingresada no es válida.', 'error');
        }

    } else {
        alerta('Error!', 'Debe ingresar la cédula de la persona que quiera buscar.', 'error');
    }

}



function buscarSocio(cedula) {

    $.ajax(
        {
            url: url_app + 'cargar_datos_socios.php',
            type: 'GET',
            dataType: 'JSON',
            data: { CI: cedula },
            beforeSend: function () {
                //Oculto todos los contenedores
                ocultarTodoContenido();
                mostrarLoader();
            },
        })
        .done(function (response) {
            mostrarLoader("O");
            if (response.error === true) {
                ocultarTodoContenido('contenido_socios');
                alerta("<span style='color: #9C0404'> No se han encontrado resultados! </span>", "Seguro que la cédula ingresada pertenece a un socio?", "error");
            } else {
                $('#nom').text(response.nombre);
                $('#telefono').text(response.tel);
                $('#fechafil').text(response.fecha_afiliacion);
                $('#radio').text(response.radio);
                $('#sucursal').text(response.sucursal);
                $('#inspira').text(response.inspira);

                //Muestro el contenedor de los socios
                mostrarContenido('contenido_socios');
            }
        })
        .fail(function (error) {
            alerta('Error!', 'Ha ocurrido un error, por favor comuníquese con el administrador', 'error');
        })

}


function buscarFuncionario(cedula, tipo) {

    $.ajax(
        {
            url: url_app + 'cargar_datos_funcionarios.php',
            type: 'GET',
            dataType: 'JSON',
            data: {
                "CI": cedula,
                "tipo": tipo
            },
            beforeSend: function () {
                //Oculto todos los contenedores
                ocultarTodoContenido();
                mostrarLoader();
            },
        })
        .done(function (response) {
            mostrarLoader("O");
            if (response.error === false) {
                $('#funcionario_numero_nodum_personal').text(response.datos.id_nodum);
                $('#funcionario_nombre_completo_personal').text(response.datos.nombre);
                $('#funcionario_fecha_ingreso_personal').text(response.datos.fecha_ingreso);
                $('#funcionario_fecha_egreso_personal').text(response.datos.fecha_egreso);
                $('#funcionario_empresa_personal').text(response.datos.empresa);
                $('#funcionario_estado_personal').text(response.datos.estado);
                $('#funcionario_causal_de_baja_personal').text(response.datos.causa);
                $('#funcionario_tipo_de_comisionamiento_personal').text(response.datos.planes);
                $('#funcionario_filial_personal').text(response.datos.filial);
                $('#funcionario_sub_filial_personal').text(response.datos.sub_filial);
                $('#funcionario_cargo_personal').text(response.datos.cargo);
                $('#funcionario_centro_de_costos_personal').text(response.datos.seccion);
                $('#funcionario_tipo_de_trabajador_personal').text(response.datos.tipo_trabajador);
                $('#funcionario_medio_de_pago_personal').text(response.datos.banco);
                $('#funcionario_telefono_personal').text(response.datos.telefono);
                $('#funcionario_correo_personal').text(response.datos.correo);

                //Muestro el contenedor de los funcionarios
                mostrarContenido('contenido_funcionarios');
            } else {
                alerta("<span style='color: #9C0404'> No se han encontrado resultados! </span>", "Seguro que la cédula ingresada pertenece a un funcionario?", "error");
            }

        })
        .fail(function (response) {
            alerta('Error!', 'Ha ocurrido un error, por favor comuníquese con el administrador', 'error');
        })
}


function consulta_por_cedula(consulta) {

    let cedula = $('#ci_personal').val();
    let fecha_desde = $('#cc_fecha_desde_personal').val();
    let fecha_hasta = $('#cc_fecha_hasta_personal').val();


    if (cedula == "") {
        alerta('Error!', 'Debe ingresar la cédula de la persona que quiera consultar.', 'error');
    } else {

        if (consulta == 'licencia') {
            licencia_acompanante();
        } else if (consulta == 'coordinacion') {
            coordinacion_socio(cedula);
        } else if (consulta == 'cobranza') {
            cobranza_socio(cedula);
        } else if (consulta == 'productos') {
            productos_socio(cedula);
        } else {

            if (consulta == 'horas' && fecha_desde != "" && fecha_hasta != "") {
                horas_acompanante(cedula, fecha_desde, fecha_hasta);
            } else if (consulta == 'faltas' && fecha_desde != "" && fecha_hasta != "") {
                faltas_acompanante(cedula, fecha_desde, fecha_hasta);
            } else {
                alerta('Error!', 'Para consultar las horas y faltas del acompañante debe procurar ingresar la fecha desde y fecha hasta', 'error');
            }

        }

    }

}


function licencia_acompanante() {

    let cod_trabajador = $('#funcionario_numero_nodum_personal').text();

    $.ajax({
        type: "GET",
        url: url_app + "licencia_acompanante.php",
        data: {
            "cod_trabajador": cod_trabajador,
            "opcion": "consulta"
        },
        dataType: "JSON",
        beforeSend: function () {
            mostrarLoader();
        },
        complete: function () {
            mostrarLoader("O");
        },
        success: function (response) {
            if (response.error === false) {

                let groupColumn = 0;

                $('#tabla_licencia_personal').DataTable({
                    ajax: url_app + 'licencia_acompanante.php?cod_trabajador=' + cod_trabajador + '&opcion=tabla',
                    columnDefs: [{ visible: false, targets: groupColumn }],
                    columns: [
                        { data: 'anio' },
                        { data: 'fecha_inicio' },
                        { data: 'fecha_fin' },
                        { data: 'dias_generados' },
                        { data: 'dias_tomados' },
                        { data: 'dias_restantes' },
                    ],
                    "bDestroy": true,
                    order: [[groupColumn, 'asc']],
                    drawCallback: function (settings) {
                        let api = this.api();
                        let rows = api.rows({ page: 'current' }).nodes();
                        let last = null;


                        api.column(groupColumn, { page: 'current' }).data().each(function (group, i) {
                            if (last !== group) {

                                $(rows)
                                    .eq(i)
                                    .before('<tr class="group">' +
                                        '<td colspan="5" style="background-color: #6F934F; color: white; font-weight: bolder;">' +
                                        group +
                                        '</td></tr>');


                                last = group;
                            }
                        });


                    },
                    language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
                    dom: 'Bfrtip',
                    buttons: ['excel'],
                    "footerCallback": function (row, data, start, end, display) {


                        total_tomados = this.api()
                            .column(4)
                            .data()
                            .reduce(function (a, b) {
                                return parseInt(a) + parseInt(b);
                            }, 0);

                        $(this.api().column(4).footer()).html(total_tomados);


                        total_restantes = this.api()
                            .column(5)
                            .data()
                            .reduce(function (a, b) {
                                return parseInt(b);
                            }, 0);

                        $(this.api().column(5).footer()).html(total_restantes);


                    },
                    rowGroup: {
                        dataSrc: 'anio'
                    }
                });



                $('#modalDatoslicencia_personal').modal('show');

            } else {
                alerta("Error!", response.mensaje, "error");
            }
        }


    });


}


function horas_acompanante(cedula, fecha_desde, fecha_hasta) {

    $.ajax({
        type: "GET",
        url: url_app + "calcular_total_horas_funcionario.php",
        data: {
            "cedula": cedula,
            "fecha_desde": fecha_desde,
            "fecha_hasta": fecha_hasta
        },
        dataType: "JSON",
        beforeSend: function () {
            mostrarLoader();
        },
        complete: function () {
            mostrarLoader("O");
        },
        success: function (response) {
            if (response.error === false) {

                $('#tabla_horas_acompanantes_personal').DataTable({
                    ajax: url_app + 'horas_acompanantes.php?cedula=' + cedula + '&fecha_desde=' + fecha_desde + '&fecha_hasta=' + fecha_hasta,
                    columns: [
                        { data: 'fecha_filtro' },
                        { data: 'id_servicio' },
                        { data: 'hora_inicio' },
                        { data: 'hora_fin' },
                        { data: 'fecha_servicio' },
                        { data: 'suma_horas' },
                        { data: 'descanso' },
                        { data: 'aislamiento' },
                    ],
                    columnDefs: [
                        {
                            "targets": [0],
                            "visible": false,
                            "searchable": false
                        }],
                    "order": [[0, 'asc']],
                    "bDestroy": true,
                    language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
                    dom: 'Bfrtip',
                    buttons: ['excel'],
                });

                $('#modalHorasAcompanantes_personal').modal('show');
                $("#total_horas_acompañante_personal").text(response.datos + " " + "en total");

            } else {
                alerta("Error!", response.mensaje, "error");
            }
        }


    });

}


function faltas_acompanante(cedula, fecha_desde, fecha_hasta) {

    $('#tabla_faltas_acompanantes_personal').DataTable({
        ajax: url_app + 'faltas_acompanantes.php?cedula=' + cedula + '&fecha_desde=' + fecha_desde + '&fecha_hasta=' + fecha_hasta,
        columns: [
            { data: 'trabajador' },
            { data: 'tipo_falta' },
            { data: 'actividad' },
            { data: 'empresa' },
            { data: 'fecha_inicio' },
            { data: 'fecha_final' },
        ],
        "bDestroy": true,
        "order": [[0, 'asc']],
        language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
        dom: 'Bfrtip',
        buttons: ['excel'],
    });

    $('#modalFaltasAcompanantes_personal').modal('show');

}


function coordinacion_socio(cedula) {

    $('#b1').prop("disabled", true);
    $('#b1').val('Cargando...');

    $.ajax({
        type: "GET",
        url: url_app + 'datosCoordina.php',
        data: {
            "cedula": cedula,
            "opcion": "consulta"
        },
        dataType: "JSON",
        beforeSend: function () {
            mostrarLoader();
        },
        complete: function () {
            mostrarLoader("O");
        },
        success: function (response) {
            if (response.error === false) {
                $('#tabla_coordinacion_socio_personal').DataTable({
                    ajax: url_app + 'datosCoordina.php?cedula=' + cedula + '&opcion=tabla',
                    columns: [
                        { data: 'observacion' },
                        { data: 'id' },
                    ],
                    "bDestroy": true,
                    "order": [[0, 'asc']],
                    language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
                    dom: 'Bfrtip',
                    buttons: ['excel'],
                });

                abrirModal('modalDatosCoordina_personal');
                $('#b1').prop("disabled", false);
                $('#b1').val('Coordinación');

            } else {
                $('#b1').val('Coordinación (sin registros)');
                alerta("Error!", response.mensaje, "error");
            }
        }
    });

}


function cobranza_socio(cedula) {

    $('#b2').prop("disabled", true);
    $('#b2').val('Cargando...');

    $.ajax({
        type: "GET",
        url: url_app + 'datosCobranza.php',
        data: {
            "cedula": cedula,
            "opcion": "consulta"
        },
        dataType: "JSON",
        beforeSend: function () {
            mostrarLoader();
        },
        complete: function () {
            mostrarLoader("O");
        },
        success: function (response) {
            if (response.error === false) {
                $('#tabla_cobranza_socio_personal').DataTable({
                    ajax: url_app + 'datosCobranza.php?cedula=' + cedula + '&opcion=tabla',
                    columns: [
                        { data: 'mes' },
                        { data: 'anho' },
                        { data: 'importe' },
                        { data: 'cobrado' },
                    ],
                    "bDestroy": true,
                    "order": [[0, 'desc']],
                    language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
                    dom: 'Bfrtip',
                    buttons: ['excel'],
                });


                abrirModal('modalDatosCobranza_personal');
                $('#b2').prop("disabled", false);
                $('#b2').val('Cobranza');

            } else {
                $('#b2').val('Sin datos de cobranza');
                alerta("Error!", response.mensaje, "error");
            }
        }
    });

}


function productos_socio(cedula) {

    $('#b2').prop("disabled", true);
    $('#b2').val('Cargando...');

    $.ajax({
        type: "GET",
        url: url_app + 'datosCobranza.php',
        data: {
            "cedula": cedula,
            "opcion": "consulta"
        },
        dataType: "JSON",
        beforeSend: function () {
            mostrarLoader();
        },
        complete: function () {
            mostrarLoader("O");
        },
        success: function (response) {
            if (response.error === false) {
                $('#tabla_productos_socio_personal').DataTable({
                    ajax: url_app + 'datosProductos.php?cedula=' + cedula + '&opcion=tabla',
                    columns: [
                        { data: 'nroServicio' },
                        { data: 'servicio' },
                        { data: 'horas' },
                        { data: 'importe' },
                    ],
                    "bDestroy": true,
                    "order": [[0, 'asc']],
                    language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
                    dom: 'Bfrtip',
                    buttons: ['excel'],
                });



                abrirModal('modalDatosProductos_personal');
                $('#b2').prop("disabled", false);
                $('#b2').val('Cobranza');

            } else {
                $('#b2').val('Sin datos de cobranza');
                alerta("Error!", response.mensaje, "error");
            }
        }
    });

}


function consultas_generales(consulta) {
    let fecha_desde = $("#cg_fecha_desde_personal").val();
    let fecha_hasta = $("#cg_fecha_hasta_personal").val();

    if (fecha_desde == "") {
        alerta("Error!", "Debe ingresar una fecha desde", "error");
    } else if (fecha_hasta == "") {
        alerta("Error!", "Debe ingresar una fecha hasta", "error");
    } else if (fecha_desde > fecha_hasta) {
        alerta("Error!", "La fecha desde tiene que ser menor a la fecha hasta", "error");
    } else {

        if (consulta == 'horas') {
            registro_completo_horas_acompanantes(fecha_desde, fecha_hasta);
        } else if (consulta == 'faltas') {
            registro_completo_faltas_acompanantes(fecha_desde, fecha_hasta);
        } else if (consulta == 'licencia') {
            registro_completo_licencias_acompanantes(fecha_desde, fecha_hasta);
        } else if (consulta == 'capacitacion_acompanantes') {
            registro_capacitacion_acompanantes(fecha_desde, fecha_hasta);
        } else if (consulta == 'viaticos_descontar') {
            registro_viaticos_descontar_acompanantes(fecha_desde, fecha_hasta);
        } else if (consulta == 'listado_radios') {
            registro_listado_radios(fecha_desde, fecha_hasta);
        } else if (consulta == 'archivos_cobranza') {
            registro_archivos_cobranza(fecha_desde, fecha_hasta);
        } else if (consulta == 'corte_producto_abm') {
            registro_corte_producto_abm(fecha_desde, fecha_hasta);
        } else if (consulta == 'resultado_comision') {
            registro_resultado_comision(fecha_desde, fecha_hasta);
        } else if (consulta == 'retenciones_socios') {
            registro_retenciones_socios(fecha_desde, fecha_hasta);
        } else if (consulta == 'horas_auxiliares_limpieza') {
            registro_horas_auxiliares_limpieza(fecha_desde, fecha_hasta);
        } else if (consulta == 'horas_particulares') {
            registro_horas_particulares(fecha_desde, fecha_hasta);
        } else if (consulta == 'control_satisfaccion_paraguay') {
            registro_control_satisfaccion_paraguay(fecha_desde, fecha_hasta);
        } else if (consulta == 'uniformes_descontar') {
            registro_uniformes_descontar(fecha_desde, fecha_hasta);
        } else if (consulta == 'capacitacion_comercial') {
            registro_capacitacion_comercial(fecha_desde, fecha_hasta);
        }

    }

}


function exportar_cierre_personalizado(consulta) {
    let fecha_desde = $("#desde").val();
    let fecha_hasta = $("#hasta").val();

    if (fecha_desde == "") {
        alerta("Error!", "Debe ingresar una fecha desde", "error");
    } else if (fecha_hasta == "") {
        alerta("Error!", "Debe ingresar una fecha hasta", "error");
    } else if (fecha_desde > fecha_hasta) {
        alerta("Error!", "La fecha desde tiene que ser menor a la fecha hasta", "error");
    } else {

        if (consulta == 'exportarMVD') {
            exportarMVD();
        } else if (consulta == 'exportarBR') {
            exportarBR();
        } else if (consulta == 'exportarCOMAP') {
            exportarCOMAP();
        } else if (consulta == 'exportarPY') {
            exportarPY();
        }

    }
}


function exportarMVD() {
    $('#form1').attr('action', url_app_procesos + 'procesoMVD.php');
    $("#form1").submit();
}


function exportarBR() {
    $('#form1').attr('action', url_app_procesos + 'procesoBR.php');
    $("#form1").submit();
}


function exportarCOMAP() {
    $('#form1').attr('action', url_app_procesos + 'procesoCOMAG.php');
    $("#form1").submit();
}


function exportarPY() {
    $('#form1').attr('action', url_app_procesos + 'procesoPY.php');
    $("#form1").submit();
}


function registro_completo_licencias_acompanantes(fecha_desde, fecha_hasta) {

    $.ajax({
        type: "GET",
        url: url_app + 'todas_licencias_acompanantes.php',
        data: {
            "fecha_desde": fecha_desde,
            "fecha_hasta": fecha_hasta,
            "opcion": "consulta"
        },
        dataType: "JSON",
        beforeSend: function () {
            mostrarLoader();
        },
        complete: function () {
            mostrarLoader("O");
        },
        success: function (response) {
            if (response.error === false) {

                let groupColumn = 0;

                $('#tabla_todas_licencias_personal').DataTable({
                    ajax: url_app + 'todas_licencias_acompanantes.php?fecha_desde=' + fecha_desde + '&fecha_hasta=' + fecha_hasta + '&opcion=tabla',
                    columnDefs: [{ visible: false, targets: groupColumn }],
                    columns: [
                        { data: 'anio' },
                        { data: 'cedula' },
                        { data: 'nombre_completo' },
                        { data: 'fecha_inicio' },
                        { data: 'fecha_fin' },
                        { data: 'cant_dias' },
                        { data: 'tipo_licencia' },
                    ],
                    "bDestroy": true,
                    order: [[groupColumn, 'asc']],
                    "ordering": false,
                    searching: false,
                    drawCallback: function (settings) {
                        let api = this.api();
                        let rows = api.rows({ page: 'current' }).nodes();
                        let last = null;


                        api.column(groupColumn, { page: 'current' }).data().each(function (group, i) {
                            if (last !== group) {

                                $(rows)
                                    .eq(i)
                                    .before('<tr class="group">' +
                                        '<td colspan="6" style="background-color: #6F934F; color: white; font-weight: bolder;">' +
                                        group +
                                        '</td></tr>');


                                last = group;
                            }
                        });


                    },
                    language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
                    dom: 'Bfrtip',
                    buttons: ['excel'],
                    rowGroup: {
                        dataSrc: 'anio'
                    }
                });

                $('#modalDatosTodaslicencias_personal').modal('show');

            } else {
                alerta("Error!", response.mensaje, "error");
            }
        }


    });


}

function registro_completo_faltas_acompanantes(fecha_desde, fecha_hasta) {

    $('#tabla_todas_faltas_acompanantes_personal').DataTable({
        ajax: url_app + 'todos_registros_faltas_acompanantes.php?fecha_desde=' + fecha_desde + '&fecha_hasta=' + fecha_hasta,
        columns: [
            { data: 'trabajador' },
            { data: 'cedula' },
            { data: 'nombre' },
            { data: 'tipo_falta' },
            { data: 'actividad' },
            { data: 'empresa' },
            { data: 'fecha_inicio' },
            { data: 'fecha_final' },
        ],
        "bDestroy": true,
        "order": [[0, 'desc']],
        language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
        dom: 'Bfrtip',
        buttons: ['excel'],
    });


    abrirModal('modalTodasFaltasAcompanantes_personal');

}


function registro_completo_horas_acompanantes(fecha_desde, fecha_hasta) {

    $('#tabla_todas_horas_acompanantes_personal').DataTable({
        ajax: url_app + 'todas_horas_acompanantes.php?fecha_desde=' + fecha_desde + '&fecha_hasta=' + fecha_hasta,
        columns: [
            { data: 'fecha_filtro' },
            { data: 'id_servicio' },
            { data: 'cedula' },
            { data: 'nombre' },
            { data: 'hora_inicio' },
            { data: 'hora_fin' },
            { data: 'fecha_servicio' },
            { data: 'suma_horas' },
            { data: 'descanso' },
            { data: 'aislamiento' },
        ],
        columnDefs: [
            {
                "targets": [0],
                "visible": false,
                "searchable": false
            }],
        "order": [[0, 'asc']],
        "bDestroy": true,
        language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
        dom: 'Bfrtip',
        buttons: ['excel'],
    });



    abrirModal('modalTodasHorasAcompanantes_personal');

}


function registro_capacitacion_acompanantes(fecha_desde, fecha_hasta) {
    $.ajax({
        type: "GET",
        url: url_app + "capacitacion_acompanantes.php",
        data: {
            "fecha_desde": fecha_desde,
            "fecha_hasta": fecha_hasta,
            "opcion": "consulta"
        },
        dataType: "JSON",
        beforeSend: function () {
            mostrarLoader();
        },
        complete: function () {
            mostrarLoader("O");
        },
        success: function (response) {
            if (response.error === false) {

                $('#tabla_capacitacion_acompanantes').DataTable({
                    ajax: url_app + 'capacitacion_acompanantes.php?opcion=tabla' + '&fecha_desde=' + fecha_desde + '&fecha_hasta=' + fecha_hasta,
                    columns: [
                        { data: 'nombre_completo' },
                        { data: 'cedula' },
                        { data: 'filial' },
                        { data: 'fecha' }
                    ],
                    "order": [[3, 'desc']],
                    "bDestroy": true,
                    language: { url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json' },
                    dom: 'Bfrtip',
                    buttons: ['excel'],
                });

                $('#modalCapacitacionAcompanantes').modal('show');

            } else {
                alerta("Error!", response.mensaje, "error");
            }
        }


    });
}


function registro_viaticos_descontar_acompanantes(fecha_desde, fecha_hasta) {
    alert("Los Viáticos a descontar aún no están disponibles!");
}


function registro_listado_radios(fecha_desde, fecha_hasta) {
    alert("Los Listados de radios aún no está disponible!");
}

function registro_archivos_cobranza(fecha_desde, fecha_hasta) {
    alert("Los Archivos de cobranza aún no están disponibles!");
}

function registro_corte_producto_abm(fecha_desde, fecha_hasta) {
    alert("El Corte de producto ABM aún no está disponible!");
}

function registro_resultado_comision(fecha_desde, fecha_hasta) {
    alert("Los Resultados de comisión aún no están disponibles!");
}

function registro_retenciones_socios(fecha_desde, fecha_hasta) {
    alert("Las Retenciones de socios aún no están disponibles!");
}

function registro_horas_auxiliares_limpieza(fecha_desde, fecha_hasta) {
    alert("Las Horas auxiliares de limpieza aún no están disponibles!");
}

function registro_horas_particulares(fecha_desde, fecha_hasta) {
    alert("Las Horas particulares aún no están disponibles!");
}

function registro_control_satisfaccion_paraguay(fecha_desde, fecha_hasta) {
    alert("El Control de satisfacción Paraguay aún no está disponible!");
}

function registro_uniformes_descontar(fecha_desde, fecha_hasta) {
    alert("Los Uniformes a descontar aún no están disponibles!");
}

function registro_capacitacion_comercial(fecha_desde, fecha_hasta) {
    alert("La Capacitación comercial aún no están disponibles!");
}




//Funciones Generales

function alerta(titulo, mensaje, icono) {
    Swal.fire({ title: titulo, html: mensaje, icon: icono });
}


function mostrarLoader(opcion = "M") {
    $loader =
        opcion == "M"
            ? Swal.fire({
                title: "Cargando...",
                allowEscapeKey: false,
                allowOutsideClick: false,
                didOpen: () => {
                    swal.showLoading();
                },
            })
            : $loader.close();
}


function abrirModal(modal) {
    $('#' + modal).modal('show');
}

function cerrarModal(modal) {
    $('#' + modal).modal('hide');
}


function ocultarTodoContenido() {
    $('#contenido_funcionarios').css('display', 'none');
    $('#contenido_socios').css('display', 'none');
}

function ocultarContenido(div) {
    $('#' + div).css('display', 'none');
}

function mostrarContenido(div) {
    $('#' + div).css('display', 'block');
}

function limpiarFechas() {
    $('#cc_fecha_desde_personal').val('');
    $('#cc_fecha_hasta_personal').val('');
}


//Funciones de control

function comprobarCI(cedi) {
    if (cedi == "93233611" || cedi == "78183625") return true;

    let arrCoefs = [2, 9, 8, 7, 6, 3, 4, 1];
    let suma = 0;
    let difCoef = parseInt(arrCoefs.length - cedi.length);
    for (let i = cedi.length - 1; i > -1; i--) {
        let dig = cedi.substring(i, i + 1);
        let digInt = parseInt(dig);
        let coef = arrCoefs[i + difCoef];
        suma = suma + digInt * coef;
    }
    return (suma % 10) == 0;
}