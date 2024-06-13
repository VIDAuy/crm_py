const produccion = true;
const protocol = produccion ? "http" : "http";
const host = produccion ? "192.168.1.250:82" : "192.168.1.250:82";
const app = produccion ? "crm_py" : "crm_py_test";
const url_app = `${protocol}://${host}/${app}/`;
const url_ajax = `${protocol}://${host}/${app}/php/ajax/`;



$(document).ready(function () {

  $('#txt_cedula_buscar').keypress(function (e) {
    if (e.which == 13) buscarSocio();
  });

});



/** Funciones complementarias **/
function ocultarContenido() {
  if ($("#txt_cedula_buscar").val() != $("#span_cedula_socio").text()) {
    $("#contenedor_si_es_socio").css("display", "none");
    $("#contenedor_no_es_socio").css("display", "none");
    $("#contenedor_no_es_socio_registros").css("display", "none");
    $("#contenedor_registros_del_socio").css("display", "none");
    $("#contenedor_seccion_mas_datos").css("display", "none");
  }
}


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


function verMasTabla(observacion) {
  $("#todo_comentario_funcionarios").val(observacion);
  $("#modalVerMasFuncionarios").modal("show");
}


function modal_ver_imagen_registro(ruta, id) {
  document.getElementById('mostrar_imagenes_relamos').innerHTML = '';

  $.ajax({
    type: 'GET',
    url: `${url_ajax}imagenes_de_registros.php`,
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