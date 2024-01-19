<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <link href="assets/img/icon.png" type="image/png" rel="icon">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta2/css/all.min.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu&family=Roboto&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tabulator/5.0.0/css/tabulator.min.css" integrity="sha512-icPIPd8ECoTVfmBnGAo0u87EV4CFh9hk3oef4x3pitYxXOZg4NAlPv/5rm/BzgI7eeazl795eeSYbCmGqhp6mA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <link href="css/styles.css" rel="stylesheet" />
    <title>Reportes</title>
</head>

<body>
    <div class="container-fluid pt-4">
        <div class="row">
            <div class="col-12">
                <h2 class="text-uppercase">Reporte</h2>
            </div>
            <div class="col-12">
                <div class="row mt-2 g-3">
                    <div class="col-md-6 col-lg-2">
                        <div class="form-group">
                            <input type="text" name="dates" id="reportrange" class="form-control">
                        </div>
                    </div>
                    <div class="col-12 col-md-6 col-lg-2">
                        <select class="form-control" name="motivo" id="motivoBaja">
                            <option value="" selected>Selecciona un motivo de baja</option>
                            <option value="Competencia">Competencia</option>
                            <option value="Desconformidad con el servicio">Desconformidad con el servicio</option>
                            <option value="Economicos">Economicos</option>
                            <option value="Fallecimiento">Fallecimiento</option>
                            <option value="Exepciones">Exepciones</option>
                            <option value="Personales">Personales</option>
                            <option value="Reclamo defensa al consumidor">Reclamo defensa al consumidor</option>
                            <option value="Viaje">Viaje</option>
                        </select>
                    </div>
                    <div class="col-12">
                        <button class="btn btn-primary" id="btnFiltrar">Filtrar</button>
                        <button id="download-xlsx" class="btn btn-success">Descargar Excel</button>
                    </div>
                    <div class="col-12">
                        <h3><span class="badge badge-pill bg-dark" id="numeroRegistros"></span></h3>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <p class="aviso-importante" id="emptyDataMessage"></p>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div id="main-table"></div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/jquery/latest/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tabulator/5.0.0/js/tabulator.min.js" integrity="sha512-I9rWvSxMHl/ArQ6i5kq3vOkLEWQgX2E0hQtxgLT+m8ZGdpN5PsBHbqXJ8f5919PVfXylLTsgvyNT9cmWtxhUCQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script type="text/javascript" src="https://oss.sheetjs.com/sheetjs/xlsx.full.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <!-- <script type="text/javascript" src="assets/js/utils.js"></script> -->
    <script type="text/javascript" src="assets/js/main.js"></script>
</body>