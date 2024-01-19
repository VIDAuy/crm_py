<?php
include '../configuraciones.php';


$tabla["data"] = [];

$consulta = todos_documentos_cargados();


while ($resultado = mysqli_fetch_array($consulta)) {

    $nro_carga = $resultado['id'];
    $tipo_documento = $resultado['tipo'];
    $ruta_archivo = $resultado['ruta_documento'];
    $ruta_documento = "<button class='btn btn-link' onclick='mostrar_documento(`" . $ruta_archivo . "`)'>Ver documento ...</button>";
    $area = $resultado['avisar_a'];
    $fecha_hora = $resultado['fecha_hora'];

    $tabla["data"][] = [
        "nro_carga" => $nro_carga,
        "tipo_documento" => $tipo_documento,
        "documento" => $ruta_documento,
        "area" => $area,
        "fecha_hora" => $fecha_hora,
    ];
}



echo json_encode($tabla);









function todos_documentos_cargados()
{
    $conexion = connection(DB);

    $consulta = mysqli_query($conexion, "SELECT 
    c.id,
    t.tipo,
    c.ruta_documento,
    c.avisar_a,
    c.fecha_hora
    FROM
    carga_documentos AS c,
    tipo_documento AS t
    WHERE
    c.tipo_documento = t.id
    ORDER BY c.id DESC");

    return $consulta;
}
