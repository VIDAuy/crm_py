<?php
include '../configuraciones.php';
$conexion = connection(DB_ABMMOD);


$q = "SELECT nro_filial, filial
            FROM filiales_codigos
            WHERE activo = 1
                AND pais = 'Uruguay'
            ORDER BY filial";
$r = mysqli_query($conexion, $q);
while ($row = mysqli_fetch_assoc($r)) {
    if ($row['filial'] != 'Acompañar' && $row['filial'] != 'Acompañar Colonia' && $row['filial'] != 'Inspira' && $row['filial'] != 'Núcleo') {
        $f[] = $row;
    }
}
echo json_encode($f);
