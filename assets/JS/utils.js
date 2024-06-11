/*    SWEET ALERT FUNCIONES */
function correcto(mensaje = false, titulo = false) {
  Swal.fire(
    titulo === false ? 'Correcto' : titulo,
    !mensaje ? 'La operación se realizó correctamente' : mensaje,
    'success'
  );
}
function success(mensaje = false, titulo = false) {
  correcto(mensaje, titulo);
}
function error(
  mensaje = 'Ha ocurrido un error, contacte al administrador',
  titulo = 'Error!'
) {
  Swal.fire(titulo, mensaje, 'error');
}

function warning(mensaje = false) {
  Swal.fire(
    '',
    !mensaje ? 'Ha ocurrido un error, contacte al administrador' : mensaje,
    'warning'
  );
}

function cargando(opcion = 'M', mensaje = null) {
  let titulo = 'Cargando ...';
  if (mensaje != null) titulo = mensaje;
  else titulo = 'Cargando... ';
  if (opcion === 'M') {
    $loader = Swal.fire({
      title: titulo,
      allowEscapeKey: false,
      allowOutsideClick: false,
    });
    Swal.showLoading();
  } else {
    Swal.hideLoading();
    Swal.close();
  }
}

function showLoading(title = 'Cargando...') {
  Swal.fire({
    title,
    allowEscapeKey: false,
    allowOutsideClick: false,
    didOpen: () => Swal.showLoading(),
  });
}

function hideLoading() {
  Swal.close();
}

function confirmar(mensaje) {
  let conf = Swal.fire({
    title: mensaje,
    showDenyButton: true,
    confirmButtonText: 'Aceptar',
    denyButtonText: `Cancelar`,
  });
  return conf;
}

let table = null;

const uid = () =>
  String(Date.now().toString(32) + Math.random().toString(16)).replace(
    /\./g,
    ''
  );
/*  FUNCIONES TABLA  */
/* VER MAS TABLA */
function verMasTabla(event, descripcion_ver_mas) {
  event.preventDefault();
  $('#descripcion_ver_mas').html(descripcion_ver_mas.replace(/\n/g, '<br />'));
  $('#modalVerMas').modal('show');
}
/* VER MAS TABLA */

/*  FUNCIONES TABLA  */
function crearTabla(idTabla, ajaxUrl, columns, reload = false) {
  if ($.fn.DataTable.isDataTable(`#${idTabla}`) && reload) {
    table.ajax.reload();
  } else {
    table = $(`#${idTabla}`).DataTable({
      processing: true,
      serverMethod: 'post',
      searching: true,
      ajax: ajaxUrl,
      columns,
      language: { url: `${url}assets/js/lenguage.json` },
      order: [[0, 'desc']],
      responsive: true,
      autoWidth: false,
      pageLength: 10,
      detroy: true,
      stateSave: true,
    });
    $('[type="search"]').addClass('form-control-static');
    $('[type="search"]').css({ borderRadius: '5px' });
  }
}

/*  FUNCION TABLA  */

function tabla(idTabla, url, datos, columnsRec, recargar = false) {
  return new Promise((resolve, reject) => {
    let columns = columnsRec.map((column) => {
      return { data: column };
    });

    const tabla_html = $(`#${idTabla}`).DataTable({
      processing: true,
      rowReorder: {
        selector: 'td:nth-child(2)',
      },
      responsive: true,
      serverMethod: 'GET',
      searching: true,
      ajax: {
        url: url,
        data: datos,
      },
      columns: columns,
      language: { url: `${url}assets/js/lenguage.json` },
      retrieve: true,
      order: [[0, 'desc']],
      autoWidth: false,
      pageLength: 10,
      dom: 'Bfrtip',
      buttons: ['excel'],
    });
    if (recargar !== false) {
      tabla_html.ajax.reload();
    }

    resolve(true);
  });
}

/*  FUNCION TABLA  */

/*  OBETENER DATOS POR URL */
function getUrl(sParam) {
  let sPageURL = window.location.search.substring(1),
    sURLVariables = sPageURL.split('&'),
    sParameterName,
    i;

  for (i = 0; i < sURLVariables.length; i++) {
    sParameterName = sURLVariables[i].split('=');

    if (sParameterName[0] === sParam) {
      return sParameterName[1] === undefined
        ? true
        : decodeURIComponent(sParameterName[1]);
    }
  }
  return false;
}
/*  OBETENER DATOS POR URL */

/*   MODAL */
function cerrarModal(div) {
  $(`#${div}`).modal('hide');
}

function abrirModal(div) {
  $(`#${div}`).modal('show');
}

function abrirModalStatic(div) {
  $(`#${div}`).modal({ backdrop: 'static', keyboard: false }).modal('show');
}

/*   MODAL */

/* PARTIAL/VIEWS*/
function cargarPartial(partial) {
  $.get(`${url_app}views/partials/${partial}.html`, function (data) {
    $(`#${partial}`).html(data);
  });
}

function cargarPartialAsync(partial, container = '') {
  return new Promise(function (resolve, reject) {
    $.get(`${url_app}views/partials/${partial}.html`, function (data) {
      container = container ? container : partial;
      $(`#${container}`).html(data);
      resolve();
    });
  });
}

/* PARTIAL/VIEWS*/

/* PRIMERA LETRA A MAYUSCULAS */
function primeraLetraAMayusculas(cadena) {
  return cadena
    .charAt(0)
    .toUpperCase()
    .concat(cadena.substring(1, cadena.length));
}
/* PRIMERA LETRA A MAYUSCULAS */

/* IMAGENES */
function quitarImagenes(imagen, event) {
  event.preventDefault();
  $(`#${imagen}`).val('');
}
/* IMAGENES */

/* SCROLL */
function scroll(div) {
  $('html, body').animate({
    scrollTop: $(`#${div}`).offset().top,
  });
}
/* SCROLL */

/* INPUTS FUNCIONES */
function limpiarCampos(arrayInputs) {
  arrayInputs.forEach((input) => {
    $(`#${input}`).val('');
  });
}
/* INPUTS FUNCIONES */

/* FECHA ACTUAL */
function fechaActual() {
  const fecha = new Date();
  const anio = fecha.getFullYear();
  const dia = fecha.getDate();
  const mes = fecha.getMonth() + 1;

  return { dia: dia, mes: mes, anio: anio };
}

function setFechaActualMayor(input) {
  let fecha_actual = fechaActual();
  $(`${input}`).attr({
    max: `${fecha_actual.anio - 18}-${fecha_actual.mes}-${fecha_actual.dia}`,
    min: `${fecha_actual.anio - 120}-${fecha_actual.mes}-${fecha_actual.dia}`,
  });
}
/* FECHA ACTUAL */

/**
 * completarForm
 *
 * @param {String} prefix
 * @param {Object} datos
 */
function completarForm(prefix, datos) {
  Object.keys(datos).forEach((key) => {
    $(`#${prefix}${key}`).val(datos[key]);
  });
}
