$(".botonMSC").click(function (e) {
  e.preventDefault();
  idrelacion = $("#idrelacion").val();

  let cedula1 = $("#ci").val();
  let cedula2 = $("#MIDBcedula").val();
  let cedula = cedula1 == "" ? cedula2 : cedula1;

  $.ajax({
    type: "GET",
    url: "PHP/AJAX/serviciosContratados/listarServicios.php?cedula=" + cedula,
    dataType: "JSON",
    beforeSend: function () {
      $("#tbodyMSC tr").remove();
    },
    success: function (r) {
      if (r.error) {
        error("La cédula ingresada no pertenece a un socio actual de Vida.\nSe mostrarán los servicios contratados previos a la baja.");
        $.each(r, function (index, el) {
          if (el.nroServicio != null) {
            nuevoServicio =
              "<tr>" +
              '<td><input type="text" name="nroServicio' +
              index +
              '" value="' +
              el.nroServicio +
              '"	readonly class="form-control"></td>' +
              '<td><input type="text" name="servicio' +
              index +
              '" value="' +
              el.servicio +
              '"		readonly class="form-control"></td>' +
              '<td><input type="text" name="horas' +
              index +
              '" value="' +
              el.horas +
              '"			readonly class="form-control"></td>' +
              '<td><input type="text" name="importe' +
              index +
              '" value="$' +
              el.importe +
              '"		readonly class="form-control"></td>' +
              "</tr>";
            $(nuevoServicio).appendTo("#tbodyMSC");
          }
        });
        $("#modalServiciosContratados").modal("show");
      } else {
        $.each(r, function (index, el) {
          nuevoServicio =
            "<tr>" +
            '<td><input type="text" name="nroServicio' +
            index +
            '" value="' +
            el.nroServicio +
            '"	readonly class="form-control"></td>' +
            '<td><input type="text" name="servicio' +
            index +
            '" value="' +
            el.servicio +
            '"		readonly class="form-control"></td>' +
            '<td><input type="text" name="horas' +
            index +
            '" value="' +
            el.horas +
            '"			readonly class="form-control"></td>' +
            '<td><input type="text" name="importe' +
            index +
            '" value="$' +
            el.importe +
            '"		readonly class="form-control"></td>' +
            "</tr>";
          $(nuevoServicio).appendTo("#tbodyMSC");
        });
      }
      $("#modalServiciosContratados").modal("show");
    },
    error: function () {
      error("Ha ocurrido un error inesperado, por favor comuníquese con el administrador.");
    },
  });
});