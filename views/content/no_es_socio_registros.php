<!-- CONTAINER NO ES SOCIO PERO TIENE REGISTROS -->
<div class="container" id="contenedor_no_es_socio_registros" style="display: none;">

    <hr class="style5 container">

    <div class="alert alert-info border border-info" role="alert">
        <h3 class="text-center mb-4">
            <span class="text-decoration-underline d-block mb-2">Cédula consultada:</span>
            <span id="span_cedula_no_socio_registros"></span>
        </h3>

        <div class="row">
            <div class="col-6">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="txt_nombre_no_socio_registros" placeholder="Nombre completo" disabled>
                    <label for="txt_nombre_no_socio_registros">Nombre Completo:</label>
                </div>
            </div>
            <div class="col-6">
                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="txt_telefono_no_socio_registros" placeholder="Telefono" disabled>
                    <label for="txt_telefono_no_socio_registros">Teléfono:</label>
                </div>
            </div>
        </div>
    </div>

    <hr class="style5 container">

    <div class="alert alert-warning border-warning" role="alert">
        <div class='row mb-3'>
            <div class='col-lg-4'>
                <div class='form-floating mb-3'>
                    <textarea class='form-control' placeholder='Observación' id='txt_observacion_no_socio_registros'></textarea>
                    <label for='txt_observacion_no_socio_registros'>Observación:</label>
                </div>
            </div>
            <div class='col-lg-4'>
                <div class='form-floating mb-3'>
                    <select class='form-select agregarFiliales' id='txt_avisar_a_no_socio_registros' aria-label='Avisar a'>
                    </select>
                    <label for='txt_avisar_a_no_socio_registros'>Avisar a:</label>
                </div>
            </div>
            <div class='col-lg-4 mb-3' style='margin-top: -1%;'>
                <label>Cargar Archivos (opcional):</label>
                <div class='d-flex justify-content-center'>
                    <input type='file' class='form-control mb-3' id='cargar_imagen_registro_1' accept='.jpg, .jpeg, .png, .pdf' multiple>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-center">
            <button class="btn btn-success center-block" onClick="cargo(0, 0)" style="display: block;"> Cargar
            </button>
        </div>
    </div>

</div>
<!-- END CONTAINER NO ES SOCIO PERO TIENE REGISTROS -->