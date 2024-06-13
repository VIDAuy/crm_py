function listar_servicios_del_socio(modal, baja_procesada = true) {
  let cedula = "";
  if (modal == 1) cedula = $("#MIDBcedula").val();
  if (modal == 2) cedula = $("#MMIHDBcedula").val();
  if (modal == 3) cedula = $("#cedula_socio").val();


  if (baja_procesada == false) {
    tabla_servicios_contratados(cedula);
  } else {

    $.ajax({
      type: "GET",
      url: `${url_ajax}listar_servicios.php?cedula=${cedula}&opcion=1`,
      dataType: "JSON",
      success: function (response) {
        if (response.error === true) {
          error(response.mensaje);
        } else {

          if (response.error2 == "222") warning(response.mensaje, "Aviso!");
          tabla_servicios_contratados(cedula);

        }
      }
    });

  }
}


function tabla_servicios_contratados(cedula) {
  $("#tabla_servicios_contratados").DataTable({
    ajax: `${url_ajax}listar_servicios.php?cedula=${cedula}&opcion=2`,
    columns: [
      { data: "nroServicio" },
      { data: "servicio" },
      { data: "horas" },
      { data: "importe" },
    ],
    order: [[0, "desc"]],
    bDestroy: true,
    language: {
      url: `${url_app}assets/js/lenguage.json`,
    },
  });

  $("#modalServiciosContratados").modal("show");
}