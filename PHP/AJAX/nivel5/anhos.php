<?php
include '../../configuraciones.php';
$conexion = connection(DB);


$q = "SELECT DISTINCT anho_registro
            FROM pagos_rechazados
            ORDER BY anho_registro";
$r = mysqli_query($conexion, $q);
while ($row = $r->fetch_row()) {
    $respuesta[] = array(
        'anho' => $row[0]
    );
}
echo json_encode($respuesta);
