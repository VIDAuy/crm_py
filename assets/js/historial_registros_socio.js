function historiaComunicacionDeCedula() {
    let cedula = $("#txt_cedula_buscar").val();

    $("#tabla_registros_de_socios").DataTable({
        ajax: `${url_ajax}registros_de_socios.php?CI=${cedula}`,
        columns: [
            { data: "id" },
            { data: "fecha" },
            { data: "sector" },
            { data: "socio" },
            { data: "baja" },
            { data: "observacion" },
            { data: "avisar_a" },
            { data: "imagen" },
            { data: "mas_info" },
        ],
        order: [[0, "desc"]],
        bDestroy: true,
        pageLength: 5,
        searching: true,
        paging: true,
        lengthChange: false,
        info: true,
        language: {
            url: `${url_app}assets/js/lenguage.json`,
        },
    });
}


function modal_ver_imagen_registro(ruta_registros, string_imagenes) {
    let div = document.getElementById('mostrar_imagenes_relamos');
    div.innerHTML = '';

    let obtener_imagenes = string_imagenes.split(',');
    obtener_imagenes.map((val) => {
        let imagen = val.trim();
        let separar_nombre_archivo = imagen.split('.');
        let extencion_archivo = separar_nombre_archivo[1];
        div.innerHTML += extencion_archivo != 'pdf' ?
            `<img src="${ruta_registros}/${imagen}" style="width: 100%; height: auto"> <br> <br>` :
            `<iframe src="${ruta_registros}/${imagen}" width=100% height=600></iframe>`;
    });

    $('#modalVerImagenesRegistro').modal('show');
}


function abrir_modal_ver_mas_registro(id, cedula, nombre, telefono, fecha_registro, sector, observacion, socio, baja) {

    $("#MHCDCtitulo").text(`#${id}`);
    $("#MHCDCcedula").val(cedula);
    $("#MHCDCnombre").val(nombre);
    $("#MHCDCtelefono").val(telefono);
    $("#MHCDCfecha_registro").val(fecha_registro);
    $("#MHCDCsector").val(sector);
    $("#MHCDCobservaciones").val(observacion);
    socio == 0 ? $("#MHCDCsocio").css("color", "#DC3545") : $("#MHCDCsocio").css("color", "black");
    baja == 1 ? $("#MHCDCbaja").css("color", "#DC3545") : $("#MHCDCbaja").css("color", "black");
    $("#MHCDCsocio").val(socio == 1 ? 'Si' : "No");
    $("#MHCDCbaja").val(baja == 1 ? "Si" : "No");


    $("#modalHistoriaComunicacionDeCedula").modal("show");
}