<?php
include('../../../configuraciones.php');
include '../../../../assets/lib/PHPExcel.php';
include '../../../../assets/lib/PHPExcel/Writer/Excel2007.php';


$fecha_desde = isset($_POST['desde']) ? mysqli_real_escape_string($conexion, $_POST['desde']) : '';
$fecha_hasta = isset($_POST['hasta']) ? mysqli_real_escape_string($conexion, $_POST['hasta']) : '';
$en_bruto = isset($_POST['enBruto']) ? (int)mysqli_real_escape_string($conexion, $_POST['enBruto']) : '';


$data = array();
$horas_fictas = array();
if ($fecha_desde != '' && $fecha_hasta != '') {
  mysqli_query($conexion, 'TRUNCATE TABLE cierrehr3');

  // recupero los servicios
  $resultado_servicios = consulta_servicios($en_bruto, $fecha_desde, $fecha_hasta);


  if (mysqli_num_rows($resultado_servicios) > 0) {
    // recorro los servicios
    while ($row = mysqli_fetch_assoc($resultado_servicios)) {
      $id_servicio = $row['id_servicio'];
      $idinfo = $row['idinfo'];
      $fecha_servicio = $row['fecha_servicio'];
      $fecha_carga = $row['fecha_carga'];
      $fecha_fin = $row['fechafin'] == '' ? 'ACTUALMENTE ACTIVO' : $row['fechafin'];
      $lugar = $row['lugar'];
      $tipo_servicio = $row['tipo_servicio'];
      $telefono_socio = $row['telefono'];
      $usuario = $row['usuario'];
      $cedula_socio = $row['id_socio'];
      $nombre_socio = $row['nombre_socio'];
      $hora_inicio = $row['hora_inicio'] == '23:59' ? '00:00' : $row['hora_inicio'];
      $hora_fin = $row['hora_fin'] == '23:59' ? '00:00' : $row['hora_fin'];
      $nombre_acompanante = $row['nombre_acompanante'];
      $cedula_acompanante = $row['cedula_acompanante'];
      $horario_cortado = $row['horario_cortado'];
      $aislamientoReclamado = $row['asilamiento_reclamado'] == 1 ? 'SI' : 'NO';

      if (!$en_bruto) {
        $_fecha = $fecha_servicio;
        // recupero la hora fin del horario cortado
        if ($horario_cortado == 1) {
          $_fecha = date('Y-m-d', strtotime($fecha_servicio . '+1 day'));

          //recupero las horas fin
          $hora_fin = consulta_hora_fin($_fecha, $idinfo);
        }

        if ($row['hora_inicio'] == '23:59') {
          $fecha_servicio = $_fecha;
        }
      }


      $respuesta_padron_socios = consulta_padron_socios($cedula_socio);

      $cod_filial = $respuesta_padron_socios['cod_filial'];
      $cod_empresa = $respuesta_padron_socios['cod_empresa'];
      $cod_servicio = $respuesta_padron_socios['cod_servicio'];
      $fecha_nacimiento = $respuesta_padron_socios['fecha_nacimiento'];
      $count = $respuesta_padron_socios['count'];
      $horas_contratadas = $respuesta_padron_socios['horas_contratadas'];
      $comentario = $respuesta_padron_socios['comentario'];
      $filial_socio = consulta_filial($cod_filial);
      $empresa_socio = consulta_empresa($cod_empresa);
      $tipo_producto = consulta_tipo_producto($cod_servicio);



      if ($horario_cortado == 0 || ($horario_cortado == 1 && $hora_inicio != '00:00') || ($horario_cortado == 1 && $hora_inicio == '00:00' && $fecha_servicio != $fecha_desde)) {
        $data[] = array(
          'nombre_acompanante' => $nombre_acompanante,
          'fecha_servicio' => $fecha_servicio,
          'hora_inicio' => $hora_inicio,
          'hora_fin' => $hora_fin,
          'idinfo' => $idinfo,
          'lugar' => $lugar,
          'tipo_servicio' => $tipo_servicio,
          'cedula_acompanante' => $cedula_acompanante,
          'aislamientoReclamado' => $aislamientoReclamado,
          'cedula_socio' => $cedula_socio,
          'telefono_socio' => $telefono_socio,
          'fecha_fin' => $fecha_fin,
          'usuario' => $usuario,
          'nombre_socio' => $nombre_socio,
          'fecha_carga' => $fecha_carga,
          'turno' => '',
          'q' => '',
          'filial_socio' => $filial_socio,
          'empresa_socio' => $empresa_socio,
          'fecha_nacimiento' => $fecha_nacimiento,
          'count' => $count,
          'horas_contratadas' => $horas_contratadas,
          'comentario' => $comentario,
          'tipo_producto' => $tipo_producto != "" ? $tipo_producto : "#N/D"
        );
      }

      if ($en_bruto && $horario_cortado == 1 && $hora_inicio != '00:00' && $fecha_servicio == $fecha_hasta) {
        $_fecha = date('Y-m-d', strtotime($fecha_servicio . '+1 day'));

        $_row = consulta_3($_fecha, $idinfo);

        $data[] = array(
          'nombre_acompanante' => $nombre_acompanante,
          'fecha_servicio' => $_row['fecha'],
          'hora_inicio' => $_row['hora_inicio'],
          'hora_fin' => $_row['hora_fin'],
          'idinfo' => $idinfo,
          'lugar' => $lugar,
          'tipo_servicio' => $tipo_servicio,
          'cedula_acompanante' => $cedula_acompanante,
          'aislamientoReclamado' => $aislamientoReclamado,
          'cedula_socio' => $cedula_socio,
          'telefono_socio' => $telefono_socio,
          'fecha_fin' => $fecha_fin,
          'usuario' => $usuario,
          'nombre_socio' => $nombre_socio,
          'fecha_carga' => $fecha_carga,
          'turno' => '',
          'q' => '',
          'filial_socio' => $filial_socio,
          'empresa_socio' => $empresa_socio,
          'fecha_nacimiento' => $fecha_nacimiento,
          'count' => $count,
          'horas_contratadas' => $horas_contratadas,
          'comentario' => $comentario,
          'tipo_producto' => $tipo_producto != "" ? $tipo_producto : "#N/D"
        );
      }
    }


    $insertar_cierre_horas_3 =  insert_cierrehr3($nombre_acompanante, $fecha_servicio, $hora_inicio, $hora_fin, $idinfo, $lugar, $tipo_servicio, $cedula_acompanante, $cedula_socio, $telefono_socio, $fecha_fin, $usuario, $nombre_socio, $fecha_carga);
  }



  // horas fictas
  $resultado_horas_fictas = consulta_horas_fictas($fecha_desde, $fecha_hasta);

  if (mysqli_num_rows($resultado_horas_fictas) > 0) {
    while ($row = mysqli_fetch_assoc($resultado_horas_fictas)) {
      $horas_fictas[] = array(
        'id' => $row['id_pedido_acomp'],
        'horas_fictas' => $row['horas_fictas'],
        'cedula_acompanante' => $row['ci_acompanante'],
        'nombre_acompanante' => $row['nombre_acompanante'],
        'fecha_turno' => date('d/m/Y', strtotime($row['fecha_turno'])),
        'hora_inicio_original' => $row['hora_inicio_original'],
        'hora_fin_original' => $row['hora_fin_original'],
        'fecha_cancelacion' => date('d/m/Y', strtotime($row['fecha_cancelacion'])),
        'observacion' => $row['observacion']
      );
    }
  }

  // genero el archivo Excel
  if (count($data) > 1) {
    $objPHPExcel = new PHPExcel();
    $objPHPExcel->setActiveSheetIndex(0);
    $objPHPExcel->getActiveSheet()->SetCellValue('A1', "NOMBRE");
    $objPHPExcel->getActiveSheet()->SetCellValue('B1', "FECHA");
    $objPHPExcel->getActiveSheet()->SetCellValue('C1', "TI");
    $objPHPExcel->getActiveSheet()->SetCellValue('D1', "TF");
    $objPHPExcel->getActiveSheet()->SetCellValue('E1', "IDINIFO");
    $objPHPExcel->getActiveSheet()->SetCellValue('F1', "LUGAR");
    $objPHPExcel->getActiveSheet()->SetCellValue('G1', "TIPOS");
    $objPHPExcel->getActiveSheet()->SetCellValue('H1', "CEDULA");
    $objPHPExcel->getActiveSheet()->SetCellValue('I1', "TURNOS");
    $objPHPExcel->getActiveSheet()->SetCellValue('J1', "FECHA BD");
    $objPHPExcel->getActiveSheet()->SetCellValue('J1', "AISLAMIENTO RECLAMADO");

    $objPHPExcel->getActiveSheet()->SetCellValue('K1', "-");

    $objPHPExcel->getActiveSheet()->SetCellValue('L1', "CEDULA SOCIO");
    $objPHPExcel->getActiveSheet()->SetCellValue('M1', "TELEFONO");
    $objPHPExcel->getActiveSheet()->SetCellValue('N1', "FECHA FIN");
    $objPHPExcel->getActiveSheet()->SetCellValue('O1', "USUARIO");
    $objPHPExcel->getActiveSheet()->SetCellValue('P1', "NOMBRE SOCIO");
    $objPHPExcel->getActiveSheet()->SetCellValue('Q1', "FILIAL SOCIO");
    $objPHPExcel->getActiveSheet()->SetCellValue('R1', "EMPRESA SOCIO");
    $objPHPExcel->getActiveSheet()->SetCellValue('S1', "FECHA DE NACIMIENTO");
    $objPHPExcel->getActiveSheet()->SetCellValue('T1', "COUNT");
    $objPHPExcel->getActiveSheet()->SetCellValue('U1', "HORAS CONTRATADAS");
    $objPHPExcel->getActiveSheet()->SetCellValue('V1', "COMENTARIO");
    $objPHPExcel->getActiveSheet()->SetCellValue('W1', "TIPO DE PRODUCTO");




    $index = 2;
    for ($i = 0; $i < count($data); $i++) {

      $objPHPExcel->getActiveSheet()->SetCellValue('A' . $index, $data[$i]['nombre_acompanante']);
      $objPHPExcel->getActiveSheet()->SetCellValue('B' . $index, $data[$i]['fecha_servicio']);
      $objPHPExcel->getActiveSheet()->SetCellValue('C' . $index, $data[$i]['hora_inicio']);
      $objPHPExcel->getActiveSheet()->SetCellValue('D' . $index, $data[$i]['hora_fin']);
      $objPHPExcel->getActiveSheet()->SetCellValue('E' . $index, $data[$i]['idinfo']);
      $objPHPExcel->getActiveSheet()->SetCellValue('F' . $index, $data[$i]['lugar']);
      $objPHPExcel->getActiveSheet()->SetCellValue('G' . $index, $data[$i]['tipo_servicio']);
      $objPHPExcel->getActiveSheet()->SetCellValue('H' . $index, $data[$i]['cedula_acompanante']);
      $objPHPExcel->getActiveSheet()->SetCellValue('I' . $index, $data[$i]['turno']);
      $objPHPExcel->getActiveSheet()->SetCellValue('J' . $index, $data[$i]['fecha_carga']);
      $objPHPExcel->getActiveSheet()->SetCellValue('J' . $index, $data[$i]['aislamientoReclamado']);

      $objPHPExcel->getActiveSheet()->SetCellValue('K' . $index, $data[$i]['q']);

      $objPHPExcel->getActiveSheet()->SetCellValue('L' . $index, $data[$i]['cedula_socio']);
      $objPHPExcel->getActiveSheet()->SetCellValue('M' . $index, $data[$i]['telefono_socio']);
      $objPHPExcel->getActiveSheet()->SetCellValue('N' . $index, $data[$i]['fecha_fin']);
      $objPHPExcel->getActiveSheet()->SetCellValue('O' . $index, $data[$i]['usuario']);
      $objPHPExcel->getActiveSheet()->SetCellValue('P' . $index, $data[$i]['nombre_socio']);
      $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $index, $data[$i]['filial_socio']);
      $objPHPExcel->getActiveSheet()->SetCellValue('R' . $index, $data[$i]['empresa_socio']);
      $objPHPExcel->getActiveSheet()->SetCellValue('S' . $index, $data[$i]['fecha_nacimiento']);
      $objPHPExcel->getActiveSheet()->SetCellValue('T' . $index, $data[$i]['count']);
      $objPHPExcel->getActiveSheet()->SetCellValue('U' . $index, $data[$i]['horas_contratadas']);
      $objPHPExcel->getActiveSheet()->SetCellValue('V' . $index, $data[$i]['comentario']);
      $objPHPExcel->getActiveSheet()->SetCellValue('W' . $index, $data[$i]['tipo_producto']);


      $index++;
    }

    $objPHPExcel->getActiveSheet()->getStyle('A1:W1')->getFont()->setBold(true);
    $objPHPExcel->getActiveSheet()->getStyle('A1:W1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
    $objPHPExcel->getActiveSheet()->getStyle('A1:W1')->getFont()->setSize(13);
    $objPHPExcel->getActiveSheet()->getStyle('A1:W1')->applyFromArray(
      array(
        'fill' => array(
          'type' => PHPExcel_Style_Fill::FILL_SOLID,
          'color' => array('rgb' => 'FEFEB5')
        )
      )
    );
    $objPHPExcel->getActiveSheet()->getStyle("A1:W1")->applyFromArray(array(
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

    $objPHPExcel->getActiveSheet()->setTitle("Listado Bruto");

    # horas fictas
    if (count($horas_fictas) > 0) {
      $objPHPExcel->createSheet(1);
      $current_sheet = $objPHPExcel->setActiveSheetIndex(1);
      $current_sheet->setTitle("Horas Fictas");
      $current_sheet->SetCellValue('A1', "IDINFO");
      $current_sheet->SetCellValue('B1', "HORAS FICTAS");
      $current_sheet->SetCellValue('C1', "CÉDULA");
      $current_sheet->SetCellValue('D1', "NOMBRE");
      $current_sheet->SetCellValue('E1', "FECHA TURNO");
      $current_sheet->SetCellValue('F1', "HORA INICIO ORIGINAL");
      $current_sheet->SetCellValue('G1', "HORA FIN ORIGINAL");
      $current_sheet->SetCellValue('H1', "FECHA CANCELACIÓN");
      $current_sheet->SetCellValue('I1', "OBSERVACIÓN COORDINADORA");

      $index = 2;
      for ($i = 0; $i < count($horas_fictas); $i++) {
        $current_sheet->SetCellValue('A' . $index, $horas_fictas[$i]['id']);
        $current_sheet->SetCellValue('B' . $index, $horas_fictas[$i]['horas_fictas']);
        $current_sheet->SetCellValue('C' . $index, $horas_fictas[$i]['cedula_acompanante']);
        $current_sheet->SetCellValue('D' . $index, $horas_fictas[$i]['nombre_acompanante']);
        $current_sheet->SetCellValue('E' . $index, $horas_fictas[$i]['fecha_turno']);
        $current_sheet->SetCellValue('F' . $index, $horas_fictas[$i]['hora_inicio_original']);
        $current_sheet->SetCellValue('G' . $index, $horas_fictas[$i]['hora_fin_original']);
        $current_sheet->SetCellValue('H' . $index, $horas_fictas[$i]['fecha_cancelacion']);
        $current_sheet->SetCellValue('I' . $index, $horas_fictas[$i]['observacion']);
        $index++;
      }

      $current_sheet->getStyle('A1:I1')->getFont()->setBold(true);
      $current_sheet->getStyle('A1:I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
      $current_sheet->getStyle('A1:I1')->getFont()->setSize(13);
      $objPHPExcel->getActiveSheet()->getStyle('A1:W1')->applyFromArray(
        array(
          'fill' => array(
            'type' => PHPExcel_Style_Fill::FILL_SOLID,
            'color' => array('rgb' => 'FEFEB5')
          )
        )
      );
      $objPHPExcel->getActiveSheet()->getStyle("A1:W1")->applyFromArray(array(
        'borders' => array(
          'allborders' => array(
            'style' => PHPExcel_Style_Border::BORDER_THIN
          )
        )
      ));
      $current_sheet->getColumnDimension('A')->setAutoSize(true);
      $current_sheet->getColumnDimension('B')->setAutoSize(true);
      $current_sheet->getColumnDimension('C')->setAutoSize(true);
      $current_sheet->getColumnDimension('D')->setAutoSize(true);
      $current_sheet->getColumnDimension('E')->setAutoSize(true);
      $current_sheet->getColumnDimension('F')->setAutoSize(true);
      $current_sheet->getColumnDimension('G')->setAutoSize(true);
      $current_sheet->getColumnDimension('H')->setAutoSize(true);
      $current_sheet->getColumnDimension('I')->setAutoSize(true);
    }

    $objPHPExcel->setActiveSheetIndex(0);
    $filename = 'Cierre_Vida_' . $fecha_desde . '_' . $fecha_hasta . ($en_bruto ? '_Bruto' : '') . '.xlsx';
    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    header('Content-Disposition: attachment;filename=' . $filename);
    header('Cache-Control: max-age=0');

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    $objWriter->save('php://output');
    mysqli_close($conexion);
    die();
  }
}

mysqli_close($conexion);







function consulta_servicios($en_bruto, $fecha_desde, $fecha_hasta)
{
  $conexion = connection(DB_COORDINACION);

  $where = $en_bruto ? '' : 'AND (h.horario_cortado = 0 OR (h.horario_cortado = 1 AND h.hora_fin = "23:59"))';

  $query = "SELECT 
  s.id AS id_servicio, 
  s.idinfo, 
  s.fecha AS fecha_servicio, 
  REPLACE(p.lugar, '_', ' ') AS lugar, 
  p.tipo AS tipo_servicio, 
  p.fechafin, 
  p.fecha_carga,
  p.telefono, 
  p.usuario1 AS usuario, 
  p.id_socio, 
  p.nombre_socio, 
  h.hora_inicio, 
  h.hora_fin, 
  h.nombre_acompanante, 
  h.cedula_acompanante, 
  h.horario_cortado, 
  h.total_horas,
  h.asilamiento_reclamado
  FROM 
  servicios_new s 
  INNER JOIN pedido_acomp p ON p.id = s.idinfo 
  INNER JOIN horarios h ON h.id_servicio = s.id
  WHERE 
  s.activo = 1 AND 
  h.activo = 1 AND 
  h.sin_acompanante = 0 AND 
  s.fecha BETWEEN '$fecha_desde' AND '$fecha_hasta' 
  $where
  ORDER BY 
  h.cedula_acompanante";

  return mysqli_query($conexion, $query);
  mysqli_close($conexion);
}


function consulta_hora_fin($_fecha, $idinfo)
{
  $conexion = connection(DB_COORDINACION);

  $query = "SELECT 
  h.hora_fin 
  FROM 
  servicios_new s 
  INNER JOIN horarios h ON s.id = h.id_servicio 
  WHERE 
  h.activo = 1 AND 
  h.hora_inicio = '00:00' AND 
  h.horario_cortado = 1 AND 
  s.activo = 1 AND 
  s.fecha = '$_fecha' AND 
  s.idinfo=$idinfo";

  return mysqli_fetch_assoc(mysqli_query($conexion, $query))['hora_fin'];
  mysqli_close($conexion);
}


function insert_cierrehr3($nombre_acompanante, $fecha_servicio, $hora_inicio, $hora_fin, $idinfo, $lugar, $tipo_servicio, $cedula_acompanante, $cedula_socio, $telefono_socio, $fecha_fin, $usuario, $nombre_socio, $fecha_carga)
{
  $conexion = connection(DB_COORDINACION);

  $query = "INSERT INTO 
  cierrehr3(nombre, fecha, ti, tf, idinfo, lugar, tipos, cedula, cedula_socios, tel_socios, fechafin, usuario, nombre_socio, fechabd)
  VALUES
  ('$nombre_acompanante', '$fecha_servicio', '$hora_inicio', '$hora_fin', $idinfo, '$lugar', '$tipo_servicio', '$cedula_acompanante', '$cedula_socio', 
  '$telefono_socio', '$fecha_fin', '$usuario', '$nombre_socio', '$fecha_carga')";

  return mysqli_query($conexion, $query);

  mysqli_close($conexion);
}


function consulta_3($_fecha, $idinfo)
{
  $conexion = connection(DB_COORDINACION);

  $query = "SELECT 
  s.fecha, 
  h.hora_inicio, 
  h.hora_fin
  FROM 
  servicios_new s 
  INNER JOIN horarios h ON s.id = h.id_servicio 
  WHERE 
  h.activo = 1 AND 
  h.hora_inicio = '00:00' AND 
  h.horario_cortado = 1 AND 
  s.activo = 1 AND 
  s.fecha = '$_fecha' AND 
  s.idinfo=$idinfo";

  return mysqli_fetch_assoc(mysqli_query($conexion, $query));

  mysqli_close($conexion);
}


function consulta_horas_fictas($fecha_desde, $fecha_hasta)
{
  $conexion = connection(DB_COORDINACION);

  $query = "SELECT 
  id_pedido_acomp, 
  horas_fictas, 
  ci_acompanante, 
  nombre_acompanante, 
  fecha_turno, 
  hora_inicio_original, 
  hora_fin_original, 
  fecha_cancelacion, 
  observacion
  FROM 
  horas_fictas 
  WHERE 
  CAST(fecha_turno AS DATE) BETWEEN '$fecha_desde' AND '$fecha_hasta' AND 
  aprobadas=1";

  return mysqli_query($conexion, $query);

  mysqli_close($conexion);
}


function consulta_padron_socios($cedula_socio)
{
  $conexion = connection(DB_COORDINACION);

  $consulta = mysqli_query($conexion, "SELECT
	sucursal AS 'cod_filial',
	empresa_rut AS 'cod_empresa',
	fecha_nacimiento AS 'fecha_nacimiento',
	count AS 'count',
	hora AS 'horas_contratadas',
	observaciones AS 'comentario',
  servicio AS 'cod_servicio' 
  FROM
  padron_datos_socio_temporal
  WHERE
  cedula = '$cedula_socio'
  ");

  return mysqli_fetch_assoc($consulta);

  mysqli_close($conexion);
}


function consulta_filial($cod_filial)
{
  $conexion = connection(DB_COORDINACION);

  $consulta = mysqli_query($conexion, "SELECT 
  filial AS 'filial_socio'
  FROM 
  filiales_codigos_temporal 
  WHERE 
  nro_filial = '$cod_filial'
  ");

  return mysqli_fetch_assoc($consulta)['filial_socio'];

  mysqli_close($conexion);
}


function consulta_empresa($cod_empresa)
{
  $conexion = connection(DB_COORDINACION);

  $consulta = mysqli_query($conexion, "SELECT 
  nombre AS 'empresa_socio'
  FROM 
  empresas_temporal 
  WHERE 
  codigo = '$cod_empresa'
  ");

  return mysqli_fetch_assoc($consulta)['empresa_socio'];

  mysqli_close($conexion);
}


function consulta_tipo_producto($cod_servicio)
{
  $conexion = connection(DB_COORDINACION);

  $consulta = mysqli_query($conexion, "SELECT 
  tipo AS 'tipo_producto'
  FROM 
  tipo_productos_temporal 
  WHERE 
  cod_servicio = '$cod_servicio'
  ");

  return mysqli_fetch_assoc($consulta)['tipo_producto'];

  mysqli_close($conexion);
}
