<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>



<input type="hidden" id="sector_py" value="<?= ucfirst($_SESSION['avisar_a_py']) ?>">
<input type="hidden" id="nivel_py" value="<?= $_SESSION['nivel_py'] ?>">
<input type="hidden" id="idrelacion">

<nav class="navbar navbar-dark bg-dark text-white mb-4">
    <a class="navbar-brand">Menú</a>
    <a class="navbar-brand"><i class="bi bi-person-circle"></i> <strong> <?= ucfirst($_SESSION['avisar_a_py']) ?> </strong></a>
    <form class="form-inline">
        <a class="btn btn-outline-danger" style="float: right" href="http://192.168.1.250:82/crm_py/PHP/cerrarSesion.php">Cerrar sesión</a>
    </form>
</nav>
<div class="container" align="center">
    <h2 style="color:#FB0B0F">CRM</h2>
</div>



<!-- Contenedor General -->
<div class="container mt-5">


    <div class="alert alert-info" role="alert">
        <div class="table-responsive">
            <h3 class="text-center mb-3"><u>Registros:</u></h3>
            <table id="tabla_historial_registros" class="hover cell-border" style="width:100%">
                <thead class="table-dark table-sm table-bordered text-white">
                    <tr>
                        <th>Fecha/Hora</th>
                        <th>Cédula</th>
                        <th>Sector</th>
                        <th>Socio</th>
                        <th>Baja</th>
                        <th>Comentario</th>
                        <th>Más info</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>


</div>