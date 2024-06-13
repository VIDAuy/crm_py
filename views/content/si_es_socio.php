<!-- CONTAINER SI ES SOCIO -->
<div class="container" id="contenedor_si_es_socio" style="display: none;">

    <hr class="style5 container">

    <div class="alert alert-success border border-success" role="alert">
        <h3 class="text-center">
            <span class="text-decoration-underline d-block mb-2">Última Comunicación:</span>
            <span id="span_ultima_comunicacion_crm"></span>
        </h3>
    </div>

    <hr class="style5 container">

    <div class="alert alert-info border border-info" role="alert">
        <h3 class="text-center mb-4">
            <span class="text-decoration-underline d-block mb-2">Cédula consultada:</span>
            <span id="span_cedula_socio"></span>
        </h3>

        <div class="container text-center">
            <div class="row">
                <div class="col">
                    <div>Nombre Completo:</div>
                    <span class="fw-bolder" id="span_nombre"></span>
                </div>
                <div class="col">
                    <div>Teléfono:</div>
                    <span class="fw-bolder" id="span_telefono"></span>
                </div>
                <div class="col">
                    <div>Fecha de afiliación:</div>
                    <span class="fw-bolder" id="span_fecha_afiliacion"></span>
                </div>
                <div class="col">
                    <div>Radio:</div>
                    <span class="fw-bolder" id="span_radio"></span>
                </div>
                <div class="col">
                    <div>Sucursal:</div>
                    <span class="fw-bolder" id="span_sucursal"></span>
                </div>
                <div class="col">
                    <div>Inspira?:</div>
                    <span class="fw-bolder" id="span_inspira"></span>
                </div>
            </div>
        </div>

    </div>

    <hr class="style5 container">

    <div class="alert alert-warning border-warning" role="alert">
        <h3 class="text-center text-decoration-underline mb-4">Cargar registro de llamada</h3>
        <div class='row mb-3'>
            <div class='col-lg-4'>
                <div class='form-floating mb-3'>
                    <textarea class='form-control' placeholder='Observación' id='txt_observacion_si_es_socio'></textarea>
                    <label for='txt_observacion_si_es_socio'>Observación:</label>
                </div>
            </div>
            <div class='col-lg-4'>
                <div class='form-floating mb-3'>
                    <select class='form-select agregarFiliales' id='txt_avisar_a_si_es_socio' aria-label='Avisar a'>
                    </select>
                    <label for='txt_avisar_a_si_es_socio'>Avisar a:</label>
                </div>
            </div>
            <div class='col-lg-4 mb-3' style='margin-top: -1%;'>
                <label>Cargar Archivos (opcional):</label>
                <div class='d-flex justify-content-center'>
                    <input type='file' class='form-control mb-3' id='cargar_imagen_registro_3' accept='.jpg, .jpeg, .png, .pdf' multiple>
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-center">
            <input type="button" class="btn btn-success" value="Cargar" onClick="cargo(2, 1)">
        </div>
    </div>

</div>
<!-- END CONTAINER SI ES SOCIO -->