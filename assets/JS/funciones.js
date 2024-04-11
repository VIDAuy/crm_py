$(document).ready(function () {
  //alertas_de_vida_te_lleva();
  //setInterval(alertas_de_vida_te_lleva, 5000);

  alertar_gestion_bajas();
  setInterval(alertar_gestion_bajas, 5000);
});

const produccion = true;
const app = produccion ? "crm_py" : "crm_py_test";
const url_app = "http://192.168.1.250:82/" + app + "/PHP/AJAX/";

// Funciones pasivas


function alertar_gestion_bajas() {

  let usuario = $("#usuario_logueado_py").val();

  if (usuario == "Calidaduy" || usuario == "1707544") {
    $.ajax({
      type: "GET",
      url: `${url_app}/contar_pendientes_gestion_bajas.php?usuario=${usuario}`,
      dataType: "JSON",
      success: function (response) {
        if (response.error === false) {
          document.getElementById('cantidad_gestion_bajas_pendientes').innerHTML = response.cantidad + "+";
        }
      }
    });
  }
}

$(function () {
  $('[data-toggle="tooltip"]').tooltip();
  var refreshId = setInterval(function () {
    $("#q").css("visibility", "visible");
    $.ajax({
      data: {
        nivel: $("#nivel_py").val(),
      },
      url: url_app + "datos2.php",
      type: "POST",
      dataType: "json",
      success: function (content) {
        $("#bq").text(content.message);
      },
    });
  }, 5000);

  agregarFiliales();
});


function contar_alertas() {
  $("#bq").text(0);
  $("#q").css("visibility", "visible");
  $.ajax({
    data: {
      nivel: $("#nivel_py").val(),
    },
    url: url_app + "datos2.php",
    type: "POST",
    dataType: "json",
    success: function (content) {
      $("#bq").text(content.message);
    },
  });
}

// AJAX
function buscar() {
  if ($("#ci").val().length != 0) {
    $.ajax({
      url: url_app + "cargarDatos.php",
      type: "GET",
      dataType: "JSON",
      data: { CI: $("#ci").val() },
      beforeSend: function () {
        $(".contenido").css({ display: "none" });
        $("#historiaComunicacionDeCedulaDiv").css("display", "none");
        $("#historiaComunicacionDeCedulaDiv_funcionarios").css(
          "display",
          "none"
        );
        $("#b1").val("Coordinación");
        $("#b1").attr("disabled", false);
        $("#b2").val("Cobranza");
        $("#b2").attr("disabled", false);

        //noEsSocioRegistro
        $("#cedulasNSR").val("");
        $("#nombreNSR").val(null);
        $("#telefonoNSR").val(null);
        $("#observacionesNSR").val("");
        $("#avisarNSR").prop("selectedIndex", 0);
        $("#noEsSocioRegistro").css({ display: "none" });

        //noEsSocio
        $("#cedulasNS").val("");
        $("#nombreNS").val(null);
        $("#apellidoNS").val(null);
        $("#telefonoNS").val(null);
        $("#celularNS").val(null);
        $("#observacionesNS").val("");
        $("#avisarNS").prop("selectedIndex", 0);
        $("#noEsSocio").css({ display: "none" });

        //siEsSocio
        $("#cedulas").val("");
        $("#obser").val("");
        $("#ensec").prop("selectedIndex", 0);
        $("#siEsSocio").css({ display: "none" });
      },
    })
      .done(function (datos) {
        $("#cedulas").text($("#ci").val());
        historiaComunicacionDeCedula();
        if (datos.noSocioConRegistros) {
          error(datos.mensaje);
          $("#cedulasNSR").text($("#ci").val());
          $("#nombreNSR").val(datos.nombre);
          $("#telefonoNSR").val(datos.telefono);
          $("#noEsSocioRegistro").css({ display: "block" });
          $("#historiaComunicacionDeCedulaDiv").css("display", "block");
          $("#historiaComunicacionDeCedulaDiv_funcionarios").css(
            "display",
            "none"
          );
        } else if (datos.noSocio) {
          error(datos.mensaje);
          $("#cedulasNS").text($("#ci").val());
          $("#noEsSocio").css({ display: "block" });
          $("#historiaComunicacionDeCedulaDiv_funcionarios").css(
            "display",
            "none"
          );
        } else if (datos.bajaProcesada) {
          error(datos.mensaje);
          $("#cedulasNSR").text($("#ci").val());
          $("#nombreNSR").val(datos.nombre);
          $("#telefonoNSR").val(datos.telefono);
          $("#noEsSocioRegistro").css({ display: "block" });
          $("#historiaComunicacionDeCedulaDiv").css("display", "block");
          $("#historiaComunicacionDeCedulaDiv_funcionarios").css(
            "display",
            "none"
          );
        } else {
          $("#nom").text(datos.nombre);
          $("#telefono").text(datos.tel);
          $("#fechafil").text(datos.fecha_afiliacion);
          $("#radio").text(datos.radio);
          $("#sucursal").text(datos.sucursal);
          $("#inspira").text(datos.inspira);
          $("#siEsSocio").css({ display: "block" });
          if (!datos.mostrar_inspira)
            $("#div_inspira").css("display", "none");
          $("#historiaComunicacionDeCedulaDiv").css("display", "block");
          $("#historiaComunicacionDeCedulaDiv_funcionarios").css(
            "display",
            "none"
          );
        }
        $(".contenido").css({ display: "block" });

        datosCoordina();
        datosCobranza();
        datosProductos();
      })
      .fail(function (error) {
        error(error);
        error("Ha ocurrido un error, por favor comuníquese con el administrador");
      });
  } else {
    error("Debe ingresar la cédula de la persona que quiera buscar.");
  }
}

function buscarDatos() {
  let cedula = $("#ci").val();
  let consultar = document.querySelector(
    'input[name="radioBuscar"]:checked'
  ).value;

  if (cedula.length != 0) {
    if (consultar === "socio" && comprobarCI(cedula)) {
      buscarSocio(cedula);
    } else if (consultar === "funcionario") {
      const regex_numeros = /^[0-9]*$/;
      if (regex_numeros.test(cedula)) {
        buscarFuncionario(cedula, "cedula");
      } else {
        buscarFuncionario(cedula, "pasaporte");
      }
    } else {
      alerta("Error!", "La cédula ingresada no es válida.", "error");
    }
  } else {
    alerta(
      "Error!",
      "Debe ingresar la cédula de la persona que quiera buscar.",
      "error"
    );
  }
}

function buscarSocio() {
  let cedula = $("#ci").val();

  $.ajax({
    url: url_app + "cargar_datos_socios.php",
    type: "GET",
    dataType: "JSON",
    data: { CI: cedula },
    beforeSend: function () {
      $(".contenido").css({ display: "none" });
      $("#acciones_socios_nivel_3").css("display", "block");
      $(".contenido_funcionario").css({ display: "none" });
      $("#historiaComunicacionDeCedulaDiv").css("display", "none");
      $("#historiaComunicacionDeCedulaDiv_funcionarios").css("display", "none");
      $("#b1").val("Coordinación");
      $("#b1").attr("disabled", false);
      $("#b2").val("Cobranza");
      $("#b2").attr("disabled", false);

      //noEsSocioRegistro
      $("#cedulasNSR").val("");
      $("#nombreNSR").val(null);
      $("#telefonoNSR").val(null);
      $("#observacionesNSR").val("");
      $("#avisarNSR").prop("selectedIndex", 0);
      $("#noEsSocioRegistro").css({ display: "none" });

      //noEsSocio
      $("#cedulasNS").val("");
      $("#nombreNS").val(null);
      $("#apellidoNS").val(null);
      $("#telefonoNS").val(null);
      $("#celularNS").val(null);
      $("#observacionesNS").val("");
      $("#avisarNS").prop("selectedIndex", 0);
      $("#noEsSocio").css({ display: "none" });

      //siEsSocio
      $("#cedulas").val("");
      $("#obser").val("");
      $("#ensec").prop("selectedIndex", 0);
      $("#siEsSocio").css({ display: "none" });
    },
  })
    .done(function (datos) {
      $("#cedulas").text(cedula);
      historiaComunicacionDeCedula();
      if (datos.noSocioConRegistros) {
        alerta("Problema!", datos.mensaje, "warning");
        $("#cedulasNSR").text($("#ci").val());
        $("#nombreNSR").val(datos.nombre);
        $("#telefonoNSR").val(datos.telefono);
        $("#noEsSocioRegistro").css({ display: "block" });
        $("#historiaComunicacionDeCedulaDiv").css("display", "block");
        $("#historiaComunicacionDeCedulaDiv_funcionarios").css(
          "display",
          "none"
        );
      } else if (datos.noSocio) {
        alerta(
          "<span style='color: #9C0404'>¿Está seguro de que la cédula pertenece un socio?</span>",
          datos.mensaje,
          "error"
        );
        $("#cedulasNS").text($("#ci").val());
        $("#noEsSocio").css({ display: "block" });
        $("#historiaComunicacionDeCedulaDiv_funcionarios").css(
          "display",
          "none"
        );
      } else if (datos.bajaProcesada) {
        alerta("Problema!", datos.mensaje, "warning");
        $("#cedulasNSR").text($("#ci").val());
        $("#nombreNSR").val(datos.nombre);
        $("#telefonoNSR").val(datos.telefono);
        $("#noEsSocioRegistro").css({ display: "block" });
        $("#historiaComunicacionDeCedulaDiv").css("display", "block");
        $("#historiaComunicacionDeCedulaDiv_funcionarios").css(
          "display",
          "none"
        );
      } else {
        $("#nom").text(datos.nombre);
        $("#telefono").text(datos.tel);
        $("#fechafil").text(datos.fecha_afiliacion);
        $("#radio").text(datos.radio);
        $("#sucursal").text(datos.sucursal);
        $("#inspira").text(datos.inspira);
        $("#siEsSocio").css({ display: "block" });
        if (!datos.mostrar_inspira) $("#div_inspira").css("display", "none");
        $("#historiaComunicacionDeCedulaDiv").css("display", "block");
        $("#historiaComunicacionDeCedulaDiv_funcionarios").css(
          "display",
          "none"
        );
      }
      $(".contenido").css({ display: "block" });

      ultima_comunicacion_crm(cedula);
      datosProductos();
      tabla_servicios_contratados();
      datosCoordina();
      datosCobranza();
    })
    .fail(function (error) {
      console.log(error);
      alerta(
        "Error!",
        "Ha ocurrido un error, por favor comuníquese con el administrador",
        "error"
      );
    });
}

function buscarFuncionario(cedula, tipo) {
  $.ajax({
    url: url_app + "cargar_datos_funcionarios.php",
    type: "GET",
    dataType: "JSON",
    data: {
      CI: cedula,
      tipo: tipo,
    },
    beforeSend: function () {
      $(".contenido_funcionario").css({ display: "none" });
      $("#acciones_socios_nivel_3").css("display", "none");
      $(".contenido").css({ display: "none" });
      $("#historiaComunicacionDeCedulaDiv").css("display", "none");
      $("#b1").val("Coordinación");
      $("#b1").attr("disabled", false);
      $("#b2").val("Cobranza");
      $("#b2").attr("disabled", false);

      //noEsSocioRegistro
      $("#cedulasNSR").val("");
      $("#nombreNSR").val(null);
      $("#telefonoNSR").val(null);
      $("#observacionesNSR").val("");
      $("#avisarNSR").prop("selectedIndex", 0);
      $("#noEsSocioRegistro").css({ display: "none" });

      //noEsSocio
      $("#cedulasNS").val("");
      $("#nombreNS").val(null);
      $("#apellidoNS").val(null);
      $("#telefonoNS").val(null);
      $("#celularNS").val(null);
      $("#observacionesNS").val("");
      $("#avisarNS").prop("selectedIndex", 0);
      $("#noEsSocio").css({ display: "none" });

      //siEsSocio
      $("#cedulas").val("");
      $("#obser").val("");
      $("#ensec").prop("selectedIndex", 0);
      $("#siEsSocio").css({ display: "none" });
    },
  })
    .done(function (response) {
      $("#cedulas").text(cedula);
      if (response.error === false) {
        $("#cedula_funcionario").text(cedula);
        $("#numero_nodum").text(response.datos.id_nodum);
        $("#nombre_completo_funcionario").text(response.datos.nombre);
        $("#fecha_ingreso").text(response.datos.fecha_ingreso);
        $("#fecha_egreso").text(response.datos.fecha_egreso);
        $("#empresa_funcionario").text(response.datos.empresa);
        $("#estado_funcionario").text(response.datos.estado);
        $("#causal_de_baja_funcionario").text(response.datos.causa);
        $("#tipo_de_comisionamiento_funcionario").text(response.datos.planes);
        $("#filial_funcionario").text(response.datos.filial);
        $("#sub_filial_funcionario").text(response.datos.sub_filial);
        $("#cargo_funcionario").text(response.datos.cargo);
        $("#centro_de_costos_funcionario").text(response.datos.seccion);
        $("#tipo_de_trabajador_funcionario").text(
          response.datos.tipo_trabajador
        );
        $("#medio_de_pago_funcionario").text(response.datos.banco);
        $("#telefono_funcionario").text(response.datos.telefono);
        $("#correo_funcionario").text(response.datos.correo);

        $("#historiaComunicacionDeCedulaDiv").css("display", "none");
        $("#historiaComunicacionDeCedulaDiv_funcionarios").css(
          "display",
          "block"
        );
        $("#acciones_socios_nivel_3").css("display", "none");

        $(".contenido_funcionario").css({ display: "block" });

        historiaComunicacionDeCedula_funcionarios();
      } else {
        $("#acciones_socios_nivel_3").css("display", "none");
        alerta(
          "<span style='color: #9C0404'> No se han encontrado resultados! </span>",
          "Seguro que la cédula ingresada pertenece a un funcionario?",
          "error"
        );
      }
    })
    .fail(function (response) {
      alerta(
        "Error!",
        "Ha ocurrido un error, por favor comuníquese con el administrador",
        "error"
      );
    });
}

function consultas(tipo) {
  let cedula = $("#ci").val();
  let fecha_desde = $("#fecha_desde").val();
  let fecha_hasta = $("#fecha_hasta").val();

  if (cedula == "") {
    alerta("Error!", "Debe ingresar una cédula", "error");
  } else if (fecha_desde == "") {
    alerta("Error!", "Debe ingresar una fecha desde", "error");
  } else if (fecha_hasta.length == "") {
    alerta("Error!", "Debe ingresar una fecha hasta", "error");
  } else {
    tipo == "horas"
      ? buscarHorasAcompanante(cedula, fecha_desde, fecha_hasta)
      : buscarFaltasAcompanante(cedula, fecha_desde, fecha_hasta);
  }
}

function consulta_licencias() {
  let cod_trabajador = $("#numero_nodum").text();

  $.ajax({
    type: "GET",
    url: url_app + "licencia_acompanante.php",
    data: {
      cod_trabajador: cod_trabajador,
      opcion: "consulta",
    },
    dataType: "JSON",
    success: function (response) {
      if (response.error === false) {
        let groupColumn = 0;

        $("#tabla_licencia_personal").DataTable({
          ajax:
            url_app +
            "licencia_acompanante.php?cod_trabajador=" +
            cod_trabajador +
            "&opcion=tabla",
          columnDefs: [{ visible: false, targets: groupColumn }],
          columns: [
            { data: "anio" },
            { data: "fecha_inicio" },
            { data: "fecha_fin" },
            { data: "dias_generados" },
            { data: "dias_tomados" },
            { data: "dias_restantes" },
          ],
          bDestroy: true,
          order: [[groupColumn, "asc"]],
          ordering: false,
          searching: false,
          drawCallback: function (settings) {
            let api = this.api();
            let rows = api.rows({ page: "current" }).nodes();
            let last = null;

            api
              .column(groupColumn, { page: "current" })
              .data()
              .each(function (group, i) {
                if (last !== group) {
                  $(rows)
                    .eq(i)
                    .before(
                      '<tr class="group">' +
                      '<td colspan="5" style="background-color: #6F934F; color: white; font-weight: bolder;">' +
                      group +
                      "</td></tr>"
                    );

                  last = group;
                }
              });
          },
          language: {
            url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
          },
          footerCallback: function (row, data, start, end, display) {
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
            dataSrc: "anio",
          },
        });

        $("#modalDatoslicencia_acompanantes").modal("show");
      } else {
        alerta("Error!", response.mensaje, "error");
      }
    },
  });
}

function buscarHorasAcompanante(cedula, fecha_desde, fecha_hasta) {
  $.ajax({
    type: "GET",
    url: url_app + "calcular_total_horas_funcionario.php",
    data: {
      cedula: cedula,
      fecha_desde: fecha_desde,
      fecha_hasta: fecha_hasta,
    },
    dataType: "JSON",
    success: function (response) {
      if (response.error === false) {
        $("#tabla_horas_acompanantes").DataTable({
          ajax:
            url_app +
            "horas_acompanantes.php?cedula=" +
            cedula +
            "&fecha_desde=" +
            fecha_desde +
            "&fecha_hasta=" +
            fecha_hasta,
          columns: [
            { data: "fecha_filtro" },
            { data: "id_info" },
            { data: "hora_inicio" },
            { data: "hora_fin" },
            { data: "fecha_servicio" },
            { data: "suma_horas" },
            { data: "descanso" },
            { data: "aislamiento" },
          ],
          columnDefs: [
            {
              targets: [0],
              visible: false,
              searchable: false,
            },
          ],
          order: [[0, "asc"]],
          bDestroy: true,
          language: {
            url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
          },
        });

        $("#modalHorasAcompanantes").modal("show");
        $("#total_horas_acompañante").text(response.datos + " " + "en total");
      } else {
        $("#modalHorasAcompanantes").modal("show");
      }
    },
  });
}

function buscarFaltasAcompanante(cedula, fecha_desde, fecha_hasta) {
  $("#tabla_faltas_acompanantes").DataTable({
    ajax:
      url_app +
      "faltas_acompanantes.php?cedula=" +
      cedula +
      "&fecha_desde=" +
      fecha_desde +
      "&fecha_hasta=" +
      fecha_hasta,
    columns: [
      { data: "trabajador" },
      { data: "tipo_falta" },
      { data: "actividad" },
      { data: "empresa" },
      { data: "fecha_inicio" },
      { data: "fecha_final" },
    ],
    bDestroy: true,
    order: [[0, "asc"]],
    language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json" },
  });

  $("#modalFaltasAcompanantes").modal("show");
}

function agregarFiliales() {
  $.ajax({
    url: url_app + "agregarFiliales.php",
    dataType: "JSON",
    success: function (r) {
      $.each(r.datos, function (i, v) {
        let nuevaLinea =
          '<option value="' + v.id + '">' + v.usuario + "</option>";
        $(nuevaLinea).appendTo(".agregarFiliales");
      });
    },
  });
}

function historiaComunicacionDeCedula() {
  $("#example1").DataTable().destroy();
  $.ajax({
    url: `${url_app}historiaComunicacionDeCedula.php`,
    type: "GET",
    dataType: "JSON",
    data: { CI: $("#ci").val() },
    beforeSend: function () {
      $("#historiaComunicacionDeCedula tr").remove();
    },
  }).done(function (datos) {
    if (!datos.noRegistros) {
      $.each(datos, function (index, el) {
        let nuevaLinea = "<tr>";
        nuevaLinea += "<td>" + el.id + "</td>";
        nuevaLinea += "<td>" + el.fecha + "</td>";
        nuevaLinea += "<td>" + el.sector + "</td>";
        nuevaLinea += "<td>" + el.socio + "</td>";
        nuevaLinea += "<td>" + el.baja + "</td>";
        nuevaLinea += "<td>" + el.observacion + "</td>";
        nuevaLinea += "<td>" + el.avisar_a + "</td>";
        nuevaLinea += '<td class="text-center">' + el.imagen + '</td>';
        nuevaLinea += '<td class="text-center">' + el.mas_info + '</td>';
        nuevaLinea += "</tr>";
        $(nuevaLinea).appendTo("#historiaComunicacionDeCedula");
      });
      $("#example1").DataTable({
        pageLength: 5,
        searching: true,
        paging: true,
        lengthChange: false,
        info: true,
        order: [[0, "desc"]],
        language: {
          url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json",
        },
      });
      stateSave: true;
      $('[type="search"]').addClass("form-control-static");
      $('[type="search"]').css({ borderRadius: "5px" });
    }
  });
}

function modalHistoriaComunicacionDeCedula(CIParam) {
  $("#modalHistoriaComunicacionDeCedula").modal("show");
  $.ajax({
    url: url_app + "historiaComunicacionDeCedula.php",
    dataType: "JSON",
    data: {
      ID: CIParam,
    },
    beforeSend: function () {
      $("#MHCDCtitulo").text(null);
      $("#MHCDCcedula").val(null);
      $("#MHCDCnombre").val(null);
      $("#MHCDCtelefono").val(null);
      $("#MHCDCfecha_registro").val(null);
      $("#MHCDCsector").val(null);
      $("#MHCDCobservaciones").text(null);
      $("#MHCDCsocio").val(null);
      $("#MHCDCbaja").val(null);
    },
  })
    .done(function (datos) {
      $("#MHCDCtitulo").text(datos.id);
      $("#MHCDCcedula").val(datos.cedula);
      $("#MHCDCnombre").val(datos.nombre);
      $("#MHCDCtelefono").val(datos.telefono);
      $("#MHCDCfecha_registro").val(datos.fecha_registro);
      $("#MHCDCsector").val(datos.sector);
      $("#MHCDCobservaciones").text(datos.observaciones);
      if (datos.socio == "No") {
        $("#MHCDCsocio").css({ color: "red" });
      } else {
        $("#MHCDCsocio").css({ color: "black" });
      }
      $("#MHCDCsocio").val(datos.socio);
      if (datos.baja == "Sí") {
        $("#MHCDCbaja").css({ color: "red" });
      } else {
        $("#MHCDCbaja").css({ color: "black" });
      }
      $("#MHCDCbaja").val(datos.baja);
      $("#modalHistoriaComunicacionDeCedula").modal("show");
    })
    .fail(function () {
      alerta(
        "Error!",
        'Ha ocurrido un error al cargar "modalHistoriaComunicacionDeCedula", por favor cominíqueselo al administrador',
        "error"
      );
    });
}

function cargo(param, socioParam) {

  let nombre = param == 0 ? $("#nombreNSR").val() :
    param == 1 ? $("#nombreNS").val() + " " + $("#apellidoNS").val() :
      $("#nom").text();

  let telefono = param == 0 ? $("#telefonoNSR").val() :
    param == 1 ? $("#telefonoNS").val() + " " + $("#celularNS").val() :
      $("#telefono").text();

  let observacion = param == 0 ? $("#observacionesNSR").val() :
    param == 1 ? $("#observacionesNS").val() :
      $("#obser").val();

  let ensec = param == 0 ? $("#avisarNSR").val() :
    param == 1 ? $("#avisarNS").val() :
      $("#ensec").val();

  cedulas = $("#cedulas").text();
  sector = $("#sector_py").val();

  if (controlCargo(param) != "") {
    error("Ha ocurrido lo siguiente:\n" + controlCargo(param));
  } else if (ensec == "sin_seleccion") {
    error("Debe seleccionar a quien desea avisar");
  } else {

    var form_data = new FormData();
    form_data.append("nombre", nombre);
    form_data.append("telefono", telefono);
    form_data.append("observacion", observacion);
    form_data.append("ensec", ensec);
    form_data.append("cedulas", cedulas);
    form_data.append("sector", sector);
    form_data.append("socio", socioParam);

    if (param == 0) {
      let totalImagenes = $("#cargar_imagen_registro_1").prop("files").length;
      for (let i = 0; i < totalImagenes; i++) {
        form_data.append("imagen[]", $("#cargar_imagen_registro_1").prop("files")[i]);
      }
    } else if (param == 1) {
      let totalImagenes = $("#cargar_imagen_registro_2").prop("files").length;
      for (let i = 0; i < totalImagenes; i++) {
        form_data.append("imagen[]", $("#cargar_imagen_registro_2").prop("files")[i]);
      }
    } else {
      let totalImagenes = $("#cargar_imagen_registro_3").prop("files").length;
      for (let i = 0; i < totalImagenes; i++) {
        form_data.append("imagen[]", $("#cargar_imagen_registro_3").prop("files")[i]);
      }
    }

    $.ajax({
      type: "POST",
      data: form_data,
      url: `${url_app}datos.php`,
      dataType: "JSON",
      contentType: false,
      processData: false,
      beforeSend: function () {
        mostrarLoader();
      },
      complete: function () {
        mostrarLoader("O");
      },
      success: function (content) {
        if (content.error === false) {
          alerta_ancla("Exito!", content.message, "success");
          historiaComunicacionDeCedula();
          $("#cargar_imagen_registro_1").val("");
          $("#cargar_imagen_registro_2").val("");
          $("#cargar_imagen_registro_3").val("");
          $("#obser").val("");
          $("#observacionesNSR").val("");
        } else {
          error(content.message);
        }
      },
    });

  }
}

function cargo_registro_fucionario() {
  let cedula = $("#cedula_funcionario").text();
  let nombre = $("#nombre_completo_funcionario").text();
  let telefono = $("#telefono_funcionario").text();
  let observacion = $("#obser_funcionarios").val();
  let avisar = $("#ensec_funcionarios").val();

  if (cedula == "") {
    alerta("Error!", "Debe ingresar una cedula", "error");
  } else if (observacion == "") {
    alerta("Error!", "Debe ingresar una observación", "error");
  } else {
    $.ajax({
      type: "POST",
      url: url_app + "datos_funcionarios.php",
      data: {
        cedula: cedula,
        nombre: nombre,
        tel: telefono,
        observacion: observacion,
        avisar: avisar,
      },
      dataType: "JSON",
      success: function (response) {
        if (response.error == false) {
          alerta("Exito!", response.mensaje, "success");
          $("#obser_funcionarios").val("");
          $("#ensec_funcionarios").val("");
          historiaComunicacionDeCedula_funcionarios();
        } else {
          alerta("Error!", response.mensaje, "error");
        }
      },
    });
  }
}

function historiaComunicacionDeCedula_funcionarios() {
  let cedula = $("#ci").val();
  $("#tabla_historia_comunicacion_de_cedula_funcionario").DataTable().destroy();
  $("#tabla_historia_comunicacion_de_cedula_funcionario").DataTable({
    ajax:
      url_app +
      "historiaComunicacionDeCedula_funcionarios.php?cedula=" +
      cedula,
    columns: [
      { data: "id" },
      { data: "fecha" },
      { data: "sector" },
      { data: "observacion" },
    ],
    bDestroy: true,
    order: [[0, "desc"]],
    language: { url: "//cdn.datatables.net/plug-ins/1.13.4/i18n/es-ES.json" },
  });
}

function ultima_comunicacion_crm(cedula) {
  $.ajax({
    type: "GET",
    url: url_app + "ultima_comunicacion_crm.php?cedula=" + cedula,
    dataType: "JSON",
    success: function (response) {
      document.getElementById("ultima_comunicacion_crm").innerHTML = response.mensaje;
    }
  });
}

function tabla_servicios_contratados() {

  let cedula = $('#cedulas').text();

  $('#tabla_servicios').DataTable({
    ajax: 'PHP/AJAX/masDatos/datosServicios.php?cedula=' + cedula,
    columns: [
      { data: 'id' },
      { data: 'fecha_inicio' },
      { data: 'fecha_fin' },
      { data: 'horas_x_dia' },
      { data: 'hora_inicio' },
      { data: 'hora_fin' },
    ],
    columnDefs: [
      {
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



/** Funciones complementarias **/
function ocultarContenido() {
  if ($("#ci").val() != $("cedulas").text()) {
    $(".contenido").css("display", "none");
    $(".contenido_funcionario").css("display", "none");
    $("#historiaComunicacionDeCedulaDiv").css("display", "none");
    $("#historiaComunicacionDeCedulaDiv_funcionarios").css("display", "none");
    $("#acciones_socios_nivel_3").css("display", "none");
  }
}
/** End Funciones complementarias **/



/** Funciones de control **/
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
  return suma % 10 == 0;
}

function controlCargo(param) {
  let mensaje = "";
  if (param == 0) {
    if ($("#observacionesNSR").val() == "")
      mensaje += "Es necesario que agregue una observación.";
  } else if (param == 1) {
    if ($("#nombreNS").val() == "")
      mensaje += 'Es necesario que llene el campo "nombre".\n';
    if ($("#apellidoNS").val() == "")
      mensaje += 'Es necesario que llene el campo "apellido".\n';
    if ($("#telefonoNS").val() == "" && $("#celularNS").val() == "")
      mensaje += "Es necesario que agregue un teléfono o un celular.\n";
    else {
      if ($("#telefonoNS").val() != "") {
        if (!/^([0-9])*$/.test($("#telefonoNS").val()))
          mensaje += 'El campo "Telefono" sólo puede contener números.\n';
        else if ($("#telefonoNS").val().length < 6 || $("#telefonoNS").val().length > 7)
          mensaje += 'El campo "Teléfono contacto" debe contener entre 6 y 7 dígitos.\n';
        else if (
          $("#telefonoNS").val().substring(0, 1) != 2 &&
          $("#telefonoNS").val().substring(0, 1) != 4
        )
          mensaje += 'El telefono ingresado en el campo "Teléfono" es inválido.\n';
      }
      if ($("#celularNS").val() != "") {
        if (!/^([0-9])*$/.test($("#celularNS").val()))
          mensaje += 'El campo "Celular" sólo puede contener números.\n';
        else if ($("#celularNS").val().length != 10)
          mensaje += 'El campo "Celular" debe de tener 10 números.\n';
        else if ($("#celularNS").val().substring(0, 2) != 09)
          mensaje +=
            'El celular ingresado en el campo "Celular" es inválido.\n';
      }
    }
    if ($("#observacionesNS").val() == "")
      mensaje += "Es necesario que agregue una observación.";
  } else {
    if ($("#obser").val() == "")
      mensaje = "Es necesario que agregue una observación.";
  }

  return mensaje;
}

function alertas_de_vida_te_lleva() {
  $.ajax({
    type: "GET",
    url: url_app + "contar_pendientes_vida_te_lleva.php",
    dataType: "JSON",
    success: function (response) {
      if (response.error === false) {
        document.getElementById("cantidad_pendientes_vida_te_lleva").innerHTML =
          response.cantidad + "+";
      }
    },
  });
}

function ir_a_vida_te_lleva() {
  let url = "https://vida-apps.com/vida_te_lleva/panel_calidad/index.html";
  window.open(url, "_blank");
}

function verMasTabla(observacion) {
  $("#todo_comentario_funcionarios").val(observacion);
  $("#modalVerMasFuncionarios").modal("show");
}

function alerta(titulo, mensaje, icono) {
  Swal.fire({ title: titulo, html: mensaje, icon: icono });
}

function alerta_ancla(titulo, mensaje, icono) {
  Swal.fire({
    icon: icono,
    title: titulo,
    html: mensaje,
  }).then((result) => {
    if (result.isConfirmed) {
      location.reload();
    }
  });
}
/** End Funciones de control **/

function modal_ver_imagen_registro(ruta, id) {
  document.getElementById('mostrar_imagenes_relamos').innerHTML = '';

  $.ajax({
    type: 'GET',
    url: `${url_app}imagenes_de_registros.php`,
    data: {
      id: id,
    },
    dataType: 'JSON',
    success: function (response) {
      if (response.error === false) {
        let imagenes = response.datos;

        imagenes.map((val) => {
          let separar_nombre_archivo = val.split('.');
          let extencion_archivo = separar_nombre_archivo[1];

          if (extencion_archivo != 'pdf') {
            document.getElementById(
              'mostrar_imagenes_relamos'
            ).innerHTML += `<img src="${ruta}/${val}" style="width: 100%; height: auto"> <br> <br>`;
          } else {
            document.getElementById(
              'mostrar_imagenes_relamos'
            ).innerHTML += `<iframe src="${ruta}/${val}" width=100% height=600></iframe>`;
          }
        });
      } else {
        error(response.mensaje);
      }
    },
  });

  $('#modalVerImagenesRegistro').modal('show');
}