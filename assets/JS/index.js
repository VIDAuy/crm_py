function getAfiliacionesCompetencia() {
    $.ajax({
        url: 'PHP/AJAX/afiliaciones_competencia.php',
        dataType: 'json',
        success: (res) => {
            if (res.success) {
                $('#badgeAfiliacionesCompetencia').html(res.cantidad);
            }
        }
    });
}

$(document).ready(function() {
    getAfiliacionesCompetencia();
    let interval = window.setInterval(getAfiliacionesCompetencia, 5000);
});