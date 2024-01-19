let table = null;
let tableInterval = null;
const INTERVAL = 600000; // 10 minutos

const crearTabla = () => {
  const fechas = $("#reportrange").val();
  const motivoBaja = $("#motivoBaja").val();

  table = new Tabulator("#main-table", {
    ajaxURL: `ajax/bajas.php?fechas=${fechas}&motivoBaja=${motivoBaja}`,
    ajaxResponse: (url, params, response) => {
      if (response.data.length == 0) {
        Swal.fire({
          icon: "info",
          title: "Sin resultados",
          text: "No hay registros para mostrar.",
        });
      }

      $("#numeroRegistros").html(
        `${response.data.length} <small>registros</small>`
      );

      return response.data;
    },
    tooltips: true,
    addRowPos: "top",
    history: true,
    pagination: "local",
    paginationSize: 7,
    movableColumns: false,
    resizableRows: false,
    resizableColumns: true,
    paginationSize: 15,
    layout: "fitDataFill",
    initialSort: [{column: "Id", dir: "asc"}],
    columns: [
      {
        title: "Fecha ingreso",
        field: "fechaIngresoBaja",
        width: 150,
      },
      {
        title: "documento",
        field: "cedula",
        width: 120,
        headerFilter: "input",
        headerFilterPlaceholder: "Buscar...",
        headerFilterParams: matchAny,
      },
      {
        title: "Nombre",
        field: "nombre",
        headerFilter: "input",
        width: 325,
        headerFilterPlaceholder: "Buscar...",
        headerFilterParams: matchAny,
      },
      {
        title: "Teléfono",
        field: "telefono",
        width: 150,
      },
      {
        title: "Motivo baja",
        field: "motivoBaja",
        width: 100,
      },
      {
        title: "Estado",
        field: "estado",
        width: 100,
      },
      {
        title: "Sector",
        field: "sector",
        width: 150,
      },
      {
        title: "Observación",
        field: "observacionFinal",
        width: 800,
        formatter: "textarea",
      },
    ],
  });

  table.on("dataLoading", function () {
    Swal.fire({
      title: "Cargando...",
      allowEscapeKey: false,
      allowOutsideClick: false,
      didOpen: () => {
        Swal.showLoading();
      },
    });
  });

  table.on("dataLoaded", function () {
    Swal.close();
  });
};

function matchAny(data, filterParams) {
  let match = false;
  for (let key in data) if (data[key] == filterParams.value) match = true;
  return match;
}

async function actualizarTablaHorasFictas() {
  Swal.fire({
    title: "Cargando...",
    allowEscapeKey: false,
    allowOutsideClick: false,
    didOpen: () => {
      Swal.showLoading();
    },
  });

  const fechas = $("#reportrange").val();
  const motivoBaja = $("#motivoBaja").val();
  const res = await fetch(
    `ajax/bajas.php?fechas=${fechas}&motivoBaja=${motivoBaja}`
  ).then((res) => res.json());

  Swal.close();

  if (res.success && res.data.length === 0) {
    Swal.fire({
      icon: "info",
      title: "Sin resultados",
      text: "No hay registros para mostrar.",
    });
  } else if (res.success) {
    $("#numeroRegistros").html(`${res.data.length} registros`);
    table.replaceData(res.data);
  } else {
    Swal.fire({
      icon: "info",
      title: "Sin resultados",
      text: "No hay registros para mostrar.",
    });
  }
}

document.addEventListener("DOMContentLoaded", () => {
  $("#btnFiltrar").click(actualizarTablaHorasFictas);

  const start = moment().subtract(29, "days");
  const end = moment();

  function cb(start, end) {
    $("#reportrange span").html(
      start.format("MMMM D, YYYY") + " - " + end.format("MMMM D, YYYY")
    );
  }

  $("#reportrange").daterangepicker(
    {
      locale: {
        format: "DD-MM-YYYY",
        separator: " / ",
        applyLabel: "Aceptar",
        cancelLabel: "Cancelar",
        fromLabel: "Desde",
        toLabel: "Hasta",
        customRangeLabel: "Personalizar",
        weekLabel: "S",
        daysOfWeek: ["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
        monthNames: [
          "Enero",
          "Febrero",
          "Marzo",
          "Abril",
          "Mayo",
          "Junio",
          "Julio",
          "Agosto",
          "Septimbre",
          "Octubre",
          "Noviembre",
          "Deciembre",
        ],
        firstDay: 1,
      },
      startDate: start,
      endDate: end,
      ranges: {
        Hoy: [moment(), moment()],
        Ayer: [moment().subtract(1, "days"), moment().subtract(1, "days")],
        "Ultimos 7 días": [moment().subtract(6, "days"), moment()],
        "Ultimos 30 días": [moment().subtract(29, "days"), moment()],
        "Este mes": [moment().startOf("month"), moment().endOf("month")],
        "Ultimo mes": [
          moment().subtract(1, "month").startOf("month"),
          moment().subtract(1, "month").endOf("month"),
        ],
      },
    },
    cb
  );

  cb(start, end);

  crearTabla();

  document
    .getElementById("download-xlsx")
    .addEventListener("click", function () {
      const date = new Date();
      const currentDate = `${date.getDate()}-${
        date.getMonth() + 1
      }-${date.getFullYear()}`;

      table.download("xlsx", `Reporte ${currentDate}.xlsx`, {
        sheetName: `Reporte ${currentDate}`,
        documentProcessing: function (workbook) {
          workbook.Props = {
            Title: `Reporte ${currentDate}`,
            Subject: `Reporte ${currentDate}`,
            CreatedDate: currentDate,
          };

          return workbook;
        },
      });
    });
});
