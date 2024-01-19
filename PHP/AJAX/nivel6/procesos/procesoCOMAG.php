<?php
include('../../../configuraciones.php');
include_once '../../../../assets/lib/PHPExcel.php';
include_once '../../../../assets/lib/PHPExcel/Writer/Excel2007.php';


$fecha_desde = isset($_POST['desde']) ? mysqli_real_escape_string($conexion, $_POST['desde']) : '';
$fecha_hasta = isset($_POST['hasta']) ? mysqli_real_escape_string($conexion, $_POST['hasta']) : '';


$data = array();

$registro_servicios = servicios_comag($fecha_desde, $fecha_hasta);


while ($row = mysqli_fetch_assoc($registro_servicios)) {

    $nombre_acompanante = $row['nombre_acompanante'];
    $cedula_acompanante = $row['cedula_acompanante'];
    $telefono_acompanante = $row['telefono'];
    $fecha_inicio = $row['fecha_servicio'];
    $fecha_fin = $row['fechafin'];
    $hora_inicio = $row['hora_inicio'];
    $hora_fin = $row['hora_fin'];
    $idinfo = $row['idinfo'];
    $lugar_servicio = $row['lugar'];
    $tipo_servicio = $row['tipo_servicio'];
    $aislamiento_reclamado = $row['asilamiento_reclamado'];
    $cedula_socio = $row['id_socio'];
    $nombre_socio = $row['nombre_socio'];
    $fecha_nacimiento = $row['nacimiento'];
    $filial_socio = $row['departamento'];
    $usuario = $row['usuario'];
    $registros_productos = producto_socio($cedula_socio);
    $cod_empresa = $registros_productos['cod_empresa'];
    $horas_contratadas = $registros_productos['horas_contratadas'];
    $comentario = $registros_productos['comentario'];
    $empresa_socio = empresa_socio($cod_empresa);


    $data[] = array(
        "nombre_acompanante" => $nombre_acompanante,
        "cedula_acompanante" => $cedula_acompanante,
        "telefono_acompanante" => $telefono_acompanante,
        "fecha_inicio" => $fecha_inicio,
        "fecha_fin" => $fecha_fin,
        "hora_inicio" => $hora_inicio,
        "hora_fin" => $hora_fin,
        "idinfo" => $idinfo,
        "lugar_servicio" => $lugar_servicio,
        "tipo_servicio" => $tipo_servicio,
        "aislamiento_reclamado" => $aislamiento_reclamado,
        "q" => "",
        "cedula_socio" => $cedula_socio,
        "nombre_socio" => $nombre_socio,
        "filial_socio" => $filial_socio,
        "empresa_socio" => $empresa_socio != "" ? $empresa_socio : "COMAG",
        "fecha_nacimiento" => $fecha_nacimiento,
        "count" => 0,
        "horas_contratadas" => $horas_contratadas != "" ? $horas_contratadas : 40,
        "comentario" => $comentario,
        "convenio" => "COMAG",
        "particulares" => "",
        "tipo_producto" => "",
        "usuario" => $usuario
    );
}




// genero el archivo Excel
if (count($data) > 1) {
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->SetCellValue('A1', "Nombre");
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', "Cedula");
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', "Telefono");
    $objPHPExcel->getActiveSheet()->SetCellValue('D1', "Fecha Inicio");
    $objPHPExcel->getActiveSheet()->SetCellValue('E1', "Fecha Fin");
    $objPHPExcel->getActiveSheet()->SetCellValue('F1', "TI");
    $objPHPExcel->getActiveSheet()->SetCellValue('G1', "TF");
    $objPHPExcel->getActiveSheet()->SetCellValue('H1', "IDINFO");
    $objPHPExcel->getActiveSheet()->SetCellValue('I1', "Lugar Servicio");
    $objPHPExcel->getActiveSheet()->SetCellValue('J1', "Tipo Servicio");
    $objPHPExcel->getActiveSheet()->SetCellValue('K1', "Aislamiento Reclamado");
    $objPHPExcel->getActiveSheet()->SetCellValue('L1', "-");
    $objPHPExcel->getActiveSheet()->SetCellValue('M1', "Cedula Socio");
    $objPHPExcel->getActiveSheet()->SetCellValue('N1', "Nombre Socio");
    $objPHPExcel->getActiveSheet()->SetCellValue('O1', "Filial Socio");
    $objPHPExcel->getActiveSheet()->SetCellValue('P1', "Empresa Socio");
    $objPHPExcel->getActiveSheet()->SetCellValue('Q1', "FECHA Nac");
    $objPHPExcel->getActiveSheet()->SetCellValue('R1', "Count");
    $objPHPExcel->getActiveSheet()->SetCellValue('S1', "Horas Contratadas");
    $objPHPExcel->getActiveSheet()->SetCellValue('T1', "Comentario");
    $objPHPExcel->getActiveSheet()->SetCellValue('U1', "Convenio");
    $objPHPExcel->getActiveSheet()->SetCellValue('V1', "Particulares");
    $objPHPExcel->getActiveSheet()->SetCellValue('W1', "Tipo Producto");
    $objPHPExcel->getActiveSheet()->SetCellValue('X1', "Usuario");



    $index = 2;
    for ($i = 0; $i < count($data); $i++) {

        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $index, $data[$i]['nombre_acompanante']);
        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $index, $data[$i]['cedula_acompanante']);
        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $index, $data[$i]['telefono_acompanante']);
        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $index, $data[$i]['fecha_inicio']);
        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $index, $data[$i]['fecha_fin']);
        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $index, $data[$i]['hora_inicio']);
        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $index, $data[$i]['hora_fin']);
        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $index, $data[$i]['idinfo']);
        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $index, $data[$i]['lugar_servicio']);
        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $index, $data[$i]['tipo_servicio']);
        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $index, $data[$i]['aislamiento_reclamado']);
        $objPHPExcel->getActiveSheet()->SetCellValue('L' . $index, $data[$i]['q']);
        $objPHPExcel->getActiveSheet()->SetCellValue('M' . $index, $data[$i]['cedula_socio']);
        $objPHPExcel->getActiveSheet()->SetCellValue('N' . $index, $data[$i]['nombre_socio']);
        $objPHPExcel->getActiveSheet()->SetCellValue('O' . $index, $data[$i]['filial_socio']);
        $objPHPExcel->getActiveSheet()->SetCellValue('P' . $index, $data[$i]['empresa_socio']);
        $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $index, $data[$i]['fecha_nacimiento']);
        $objPHPExcel->getActiveSheet()->SetCellValue('R' . $index, $data[$i]['count']);
        $objPHPExcel->getActiveSheet()->SetCellValue('S' . $index, $data[$i]['horas_contratadas']);
        $objPHPExcel->getActiveSheet()->SetCellValue('T' . $index, $data[$i]['comentario']);
        $objPHPExcel->getActiveSheet()->SetCellValue('U' . $index, $data[$i]['convenio']);
        $objPHPExcel->getActiveSheet()->SetCellValue('V' . $index, $data[$i]['particulares']);
        $objPHPExcel->getActiveSheet()->SetCellValue('W' . $index, $data[$i]['tipo_producto']);
        $objPHPExcel->getActiveSheet()->SetCellValue('X' . $index, $data[$i]['usuario']);


        $index++;
    }

    $objPHPExcel->getActiveSheet()->getStyle('A1:X1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A1:X1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1:X1')->getFont()->setSize(13);
    $objPHPExcel->getActiveSheet()->getStyle('A1:X1')->applyFromArray(
        array(
            'fill' => array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'color' => array('rgb' => 'FEFEB5')
            )
        )
    );
    $objPHPExcel->getActiveSheet()->getStyle("A1:X1")->applyFromArray(array(
        'borders' => array(
            'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
            )
        )
    ));
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('L')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('M')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('N')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('O')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('P')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('Q')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('R')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('S')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('T')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('U')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('V')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('W')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->getColumnDimension('X')->setAutoSize(true);
    $objPHPExcel->getActiveSheet()->setTitle("Listado");
    $objPHPExcel->setActiveSheetIndex(0);


    $filename = 'Cierre_COMAG_' . $fecha_desde . '_' . $fecha_hasta . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $filename);
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    mysqli_close($conexion);
    die();
}






mysqli_close($conexion);





function producto_socio($cedula_socio)
{
    $conexion = connection(DB_ABMMOD);

    $consulta = "SELECT
    empresa AS 'cod_empresa', 
    hora AS 'horas_contratadas', 
    observaciones AS 'comentario'
    FROM 
    padron_producto_socio 
    WHERE 
    cedula = '$cedula_socio'";

    $resultados = mysqli_query($conexion, $consulta);

    return mysqli_fetch_assoc($resultados);
    mysqli_close($conexion);
}


function empresa_socio($cod_empresa)
{
    $conexion = connection(DB_ABMMOD);

    $consulta = mysqli_query($conexion, "SELECT 
        nombre AS 'empresa_socio'
      FROM 
        empresas
      WHERE 
        codigo = '$cod_empresa'");

    return mysqli_fetch_assoc($consulta)['empresa_socio'];
    mysqli_close($conexion);
}


function servicios_comag($fecha_desde, $fecha_hasta)
{
    $conexion = connection(DB_COORDINACION);

    $consulta = "SELECT
	vs.id_servicio,
	vs.idinfo,
	vs.fecha_servicio,
	vs.lugar,
	vs.tipo_servicio,
	vs.fechafin,
	vs.fecha_carga,
	vs.telefono,
	vs.usuario,
	vs.id_socio,
	vs.nombre_socio,
	vs.hora_inicio,
	vs.hora_fin,
	vs.nombre_acompanante,
	vs.cedula_acompanante,
	vs.horario_cortado,
	vs.total_horas,
	vs.asilamiento_reclamado,
    psc.nacimiento,
    psc.departamento
FROM
	vista_servicios AS vs,
	padron_socios_comag AS psc
WHERE
	vs.id_socio = psc.cedula
	AND vs.fecha_servicio BETWEEN '$fecha_desde' AND '$fecha_hasta'
GROUP BY vs.id_servicio
ORDER BY
	vs.fecha_servicio DESC";

    $respuesta = mysqli_query($conexion, $consulta);

    return $respuesta;
    mysqli_close($conexion);
}
