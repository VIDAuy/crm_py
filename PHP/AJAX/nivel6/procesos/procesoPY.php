<?php
include('../../../configuraciones.php');
include '../../../../assets/lib/PHPExcel.php';
include '../../../../assets/lib/PHPExcel/Writer/Excel2007.php';


$conexion = connection(DB_COORDINA_PARAGUAY);
$desde = $_POST['desde'];
$hasta = $_POST['hasta'];


$q = "delete from cierrehr3";
$result = mysqli_query($conexion, $q);
if ($result) {
    $q2 = "INSERT INTO cierrehr3 (cierrehr3.cedula,cierrehr3.fecha,cierrehr3.idinfo,cierrehr3.nombre,cierrehr3.ti,cierrehr3.tf) ";
    $q2 .= "SELECT servicios.ciacompt1,servicios.diat1,servicios.idinfo,servicios.nombreacomt1,servicios.hit1,servicios.hft1 ";
    $q2 .= "FROM servicios where borrado=0 and diat1 between '" . $desde . "' and '" . $hasta . "'";
    $result2 = mysqli_query($conexion, $q2);


    $q3 = "INSERT INTO cierrehr3 (cierrehr3.cedula,cierrehr3.fecha,cierrehr3.idinfo,cierrehr3.nombre,cierrehr3.ti,cierrehr3.tf) ";
    $q3 .= "SELECT servicios.ciacompt2,servicios.diat1,servicios.idinfo,servicios.nombreacomt2,servicios.hit2,servicios.hft2 ";
    $q3 .= "FROM servicios where borrado=0 and diat1 between '" . $desde . "' and '" . $hasta . "'";
    $result3 = mysqli_query($conexion, $q3);


    $q4 = "INSERT INTO cierrehr3 (cierrehr3.cedula,cierrehr3.fecha,cierrehr3.idinfo,cierrehr3.nombre,cierrehr3.ti,cierrehr3.tf) ";
    $q4 .= "SELECT servicios.ciacompt3,servicios.diat1,servicios.idinfo,servicios.nombreacomt3,servicios.hit3,servicios.hft3 ";
    $q4 .= "FROM servicios where borrado=0 and diat1 between '" . $desde . "' and '" . $hasta . "'";
    $result4 = mysqli_query($conexion, $q4);

    if ($result2 && $result3 && $result4) {
        $q5 = "update cierrehr3 set lugar=(select lugar from pedido_acomp where pedido_acomp.id=cierrehr3.idinfo)";
        $result5 = mysqli_query($conexion, $q5);

        $q6 = "update cierrehr3 set tipos=(select tipo from pedido_acomp where pedido_acomp.id=cierrehr3.idinfo)";
        $result6 = mysqli_query($conexion, $q6);

        $q7 = "update cierrehr3 set cedula_socios=(select id_socio from pedido_acomp where pedido_acomp.id=cierrehr3.idinfo)";
        $result7 = mysqli_query($conexion, $q7);

        $q8 = "update cierrehr3 set tel_socios=(select telefono from pedido_acomp where pedido_acomp.id=cierrehr3.idinfo)";
        $result8 = mysqli_query($conexion, $q8);

        $q9 = "update cierrehr3 set fechafin=(select fechafin from pedido_acomp where pedido_acomp.id=cierrehr3.idinfo)";
        $result9 = mysqli_query($conexion, $q9);

        $q10 = "update cierrehr3 set usuario=(select usuario1 from pedido_acomp where pedido_acomp.id=cierrehr3.idinfo)";
        $result10 = mysqli_query($conexion, $q10);

        $q11 = "update cierrehr3 set nombre_socio=(select nombre_socio from pedido_acomp where pedido_acomp.id=cierrehr3.idinfo)";
        $result11 = mysqli_query($conexion, $q11);

        if ($result5 && $result6 && $result7 && $result8 && $result9 && $result10 && $result11) {
            $qBruto = "select * from cierrehr3";
            $rBruto = mysqli_query($conexion, $qBruto);
            $datos_bruto = array();
            while ($row = mysqli_fetch_array($rBruto)) {
                $nombre = $row['nombre'];
                $fecha = $row['fecha'];
                $ti = $row['ti'];
                $tf = $row['tf'];
                $idinfo = $row['idinfo'];
                $lugar = $row['lugar'];
                $tipos = $row['tipos'];
                $cedula = $row['cedula'];
                $turnos = $row['turnos'];
                $q = $row['q'];
                $cedula_socios = $row['cedula_socios'];
                $tel_socios = $row['tel_socios'];
                $fechafin = $row['fechafin'];
                $usuario = $row['usuario'];
                $nombre_socio = $row['nombre_socio'];

                $datos_socios = datos_socio($cedula_socios);
                $filial_socio = $datos_socios['filial'];
                $empresa_socio = $datos_socios['empresa'];
                $fecha_nacimiento = $datos_socios['fecha_nacimiento'];
                $horas_contratadas = $datos_socios['horas_contratadas'];
                $comentario = $datos_socios['comentario'];
                $convenio = $datos_socios['convenio'];
                $fecha_afiliacion = $datos_socios['fecha_creado'];
                $count = calcular_count($fecha_afiliacion);

                $fechabd = $row['fechabd'];


                $datos_bruto[] = array(
                    'nombre' => $nombre,
                    'fecha' => $fecha,
                    'ti' => $ti,
                    'tf' => $tf,
                    'idinfo' => $idinfo,
                    'lugar' => $lugar,
                    'tipos' => $tipos,
                    'cedula' => $cedula,
                    'turnos' => $turnos,
                    'q' => $q,
                    'cedula_socios' => $cedula_socios,
                    'tel_socios' => $tel_socios,
                    'fechafin' => $fechafin,
                    'usuario' => $usuario,
                    'nombre_socio' => $nombre_socio,
                    //
                    //
                    'filial_socio' => $filial_socio,
                    'empresa_socio' => $empresa_socio,
                    'fecha_nacimiento' => $fecha_nacimiento,
                    'count' => $count == 0 ? 1 : $count,
                    'horas_contratadas' => $horas_contratadas,
                    'comentario' => $comentario,
                    'convenio' => $convenio == "(Ninguno)" ? "NINGUNO" : $convenio,
                    //
                    //
                    'fechabd' => $fechabd
                );
            }

            if ($rBruto) {
                $objPHPExcel = new PHPExcel();

                $objPHPExcel->setActiveSheetIndex(0);
                $objPHPExcel->getActiveSheet()->SetCellValue('A1', "Nombre");
                $objPHPExcel->getActiveSheet()->SetCellValue('B1', "Fecha");
                $objPHPExcel->getActiveSheet()->SetCellValue('C1', "Ti");
                $objPHPExcel->getActiveSheet()->SetCellValue('D1', "Tf");
                $objPHPExcel->getActiveSheet()->SetCellValue('E1', "Idinfo");
                $objPHPExcel->getActiveSheet()->SetCellValue('F1', "Lugar");
                $objPHPExcel->getActiveSheet()->SetCellValue('G1', "Tipos");
                $objPHPExcel->getActiveSheet()->SetCellValue('H1', "Cedula");
                $objPHPExcel->getActiveSheet()->SetCellValue('I1', "Turnos");
                $objPHPExcel->getActiveSheet()->SetCellValue('J1', "Q");
                $objPHPExcel->getActiveSheet()->SetCellValue('K1', "Cedula Socio");
                $objPHPExcel->getActiveSheet()->SetCellValue('L1', "Tel Socio");
                $objPHPExcel->getActiveSheet()->SetCellValue('M1', "Fecha Fin");
                $objPHPExcel->getActiveSheet()->SetCellValue('N1', "Usuario");
                $objPHPExcel->getActiveSheet()->SetCellValue('O1', "Nombre Socio");

                $objPHPExcel->getActiveSheet()->SetCellValue('P1', "Filial Socio");
                $objPHPExcel->getActiveSheet()->SetCellValue('Q1', "Empresa Socio");
                $objPHPExcel->getActiveSheet()->SetCellValue('R1', "Fecha Nacimiento");
                $objPHPExcel->getActiveSheet()->SetCellValue('S1', "Count");
                $objPHPExcel->getActiveSheet()->SetCellValue('T1', "Horas Contratadas");
                $objPHPExcel->getActiveSheet()->SetCellValue('U1', "Comentario");
                $objPHPExcel->getActiveSheet()->SetCellValue('V1', "Convenio");

                $objPHPExcel->getActiveSheet()->SetCellValue('W1', "Fecha BD");

                $cont = 2;

                for ($i = 0; $i < count($datos_bruto); $i++) {
                    $objPHPExcel->getActiveSheet()->SetCellValue('A' . $cont, $datos_bruto[$i]['nombre']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('B' . $cont, $datos_bruto[$i]['fecha']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('C' . $cont, $datos_bruto[$i]['ti']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('D' . $cont, $datos_bruto[$i]['tf']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('E' . $cont, $datos_bruto[$i]['idinfo']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('F' . $cont, $datos_bruto[$i]['lugar']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('G' . $cont, $datos_bruto[$i]['tipos']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('H' . $cont, $datos_bruto[$i]['cedula']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('I' . $cont, $datos_bruto[$i]['turnos']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('J' . $cont, $datos_bruto[$i]['q']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('K' . $cont, $datos_bruto[$i]['cedula_socios']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('L' . $cont, $datos_bruto[$i]['tel_socios']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('M' . $cont, $datos_bruto[$i]['fechafin']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('N' . $cont, $datos_bruto[$i]['usuario']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('O' . $cont, $datos_bruto[$i]['nombre_socio']);

                    $objPHPExcel->getActiveSheet()->SetCellValue('P' . $cont, $datos_bruto[$i]['filial_socio']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $cont, $datos_bruto[$i]['empresa_socio']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('R' . $cont, $datos_bruto[$i]['fecha_nacimiento']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('S' . $cont, $datos_bruto[$i]['count']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('T' . $cont, $datos_bruto[$i]['horas_contratadas']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('U' . $cont, $datos_bruto[$i]['comentario']);
                    $objPHPExcel->getActiveSheet()->SetCellValue('V' . $cont, $datos_bruto[$i]['convenio']);

                    $objPHPExcel->getActiveSheet()->SetCellValue('W' . $cont, $datos_bruto[$i]['fechabd']);

                    $cont++;
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

                // Rename sheet
                $objPHPExcel->getActiveSheet()->setTitle("Listado Bruto");

                $q12 = "delete from cierrehr3 where ti=''";
                $result12 = mysqli_query($conexion, $q12);

                $q13 = "delete from cierrehr3 where tf=''";
                $result13 = mysqli_query($conexion, $q13);

                $q14 = "delete from cierrehr3 where fechafin < fecha";
                $result14 = mysqli_query($conexion, $q14);

                $q15 = "delete from  cierrehr3 where cedula=666";
                $result15 = mysqli_query($conexion, $q15);

                $q16 = "alter table cierrehr3 order by cedula,fecha asc";
                $result16 = mysqli_query($conexion, $q16);

                if ($result12 && $result13 && $result14 && $result15 && $result16) {
                    $qLimpio = "select * from cierrehr3";
                    $rLimpio = mysqli_query($conexion, $qLimpio);
                    $datos_limpio = array();

                    while ($row = mysqli_fetch_array($rLimpio)) {
                        $nombre = $row['nombre'];
                        $fecha = $row['fecha'];
                        $ti = $row['ti'];
                        $tf = $row['tf'];
                        $idinfo = $row['idinfo'];
                        $lugar = $row['lugar'];
                        $tipos = $row['tipos'];
                        $cedula = $row['cedula'];
                        $turnos = $row['turnos'];
                        $q = $row['q'];
                        $cedula_socios = $row['cedula_socios'];
                        $tel_socios = $row['tel_socios'];
                        $fechafin = $row['fechafin'];
                        $usuario = $row['usuario'];
                        $nombre_socio = $row['nombre_socio'];

                        $datos_socios = datos_socio($cedula_socios);
                        $filial_socio = $datos_socios['filial'];
                        $empresa_socio = $datos_socios['empresa'];
                        $fecha_nacimiento = $datos_socios['fecha_nacimiento'];
                        $horas_contratadas = $datos_socios['horas_contratadas'];
                        $comentario = $datos_socios['comentario'];
                        $convenio = $datos_socios['convenio'];
                        $fecha_afiliacion = $datos_socios['fecha_creado'];
                        $count = calcular_count($fecha_afiliacion);
                        $fechabd = $row['fechabd'];


                        $datos_limpio[] = array(
                            'nombre' => $nombre,
                            'fecha' => $fecha,
                            'ti' => $ti,
                            'tf' => $tf,
                            'idinfo' => $idinfo,
                            'lugar' => $lugar,
                            'tipos' => $tipos,
                            'cedula' => $cedula,
                            'turnos' => $turnos,
                            'q' => $q,
                            'cedula_socios' => $cedula_socios,
                            'tel_socios' => $tel_socios,
                            'fechafin' => $fechafin,
                            'usuario' => $usuario,
                            'nombre_socio' => $nombre_socio,
                            //
                            //
                            'filial_socio' => $filial_socio,
                            'empresa_socio' => $empresa_socio,
                            'fecha_nacimiento' => $fecha_nacimiento,
                            'count' => $count == 0 ? 1 : $count,
                            'horas_contratadas' => $horas_contratadas,
                            'comentario' => $comentario,
                            'convenio' => $convenio == "(Ninguno)" ? "NINGUNO" : $convenio,
                            //
                            //
                            'fechabd' => $fechabd
                        );
                    }

                    $sheetId = 1;
                    $objPHPExcel->createSheet(NULL, $sheetId);
                    $objPHPExcel->setActiveSheetIndex(1);
                    $objPHPExcel->getActiveSheet()->setTitle("Listado Limpio");

                    $objPHPExcel->getActiveSheet()->SetCellValue('A1', "Nombre");
                    $objPHPExcel->getActiveSheet()->SetCellValue('B1', "Fecha");
                    $objPHPExcel->getActiveSheet()->SetCellValue('C1', "Ti");
                    $objPHPExcel->getActiveSheet()->SetCellValue('D1', "Tf");
                    $objPHPExcel->getActiveSheet()->SetCellValue('E1', "Idinfo");
                    $objPHPExcel->getActiveSheet()->SetCellValue('F1', "Lugar");
                    $objPHPExcel->getActiveSheet()->SetCellValue('G1', "Tipos");
                    $objPHPExcel->getActiveSheet()->SetCellValue('H1', "Cedula");
                    $objPHPExcel->getActiveSheet()->SetCellValue('I1', "Turnos");
                    $objPHPExcel->getActiveSheet()->SetCellValue('J1', "Q");
                    $objPHPExcel->getActiveSheet()->SetCellValue('K1', "Cedula Socio");
                    $objPHPExcel->getActiveSheet()->SetCellValue('L1', "Tel Socio");
                    $objPHPExcel->getActiveSheet()->SetCellValue('M1', "Fecha Fin");
                    $objPHPExcel->getActiveSheet()->SetCellValue('N1', "Usuario");
                    $objPHPExcel->getActiveSheet()->SetCellValue('O1', "Nombre Socio");

                    $objPHPExcel->getActiveSheet()->SetCellValue('P1', "Filial Socio");
                    $objPHPExcel->getActiveSheet()->SetCellValue('Q1', "Empresa Socio");
                    $objPHPExcel->getActiveSheet()->SetCellValue('R1', "Fecha Nacimiento");
                    $objPHPExcel->getActiveSheet()->SetCellValue('S1', "Count");
                    $objPHPExcel->getActiveSheet()->SetCellValue('T1', "Horas Contratadas");
                    $objPHPExcel->getActiveSheet()->SetCellValue('U1', "Comentario");
                    $objPHPExcel->getActiveSheet()->SetCellValue('V1', "Convenio");

                    $objPHPExcel->getActiveSheet()->SetCellValue('W1', "Fecha BD");

                    $cont = 2;

                    for ($i = 0; $i < count($datos_limpio); $i++) {
                        $objPHPExcel->getActiveSheet()->SetCellValue('A' . $cont, $datos_limpio[$i]['nombre']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('B' . $cont, $datos_limpio[$i]['fecha']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('C' . $cont, $datos_limpio[$i]['ti']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('D' . $cont, $datos_limpio[$i]['tf']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('E' . $cont, $datos_limpio[$i]['idinfo']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('F' . $cont, $datos_limpio[$i]['lugar']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('G' . $cont, $datos_limpio[$i]['tipos']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('H' . $cont, $datos_limpio[$i]['cedula']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('I' . $cont, $datos_limpio[$i]['turnos']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('J' . $cont, $datos_limpio[$i]['q']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('K' . $cont, $datos_limpio[$i]['cedula_socios']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('L' . $cont, $datos_limpio[$i]['tel_socios']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('M' . $cont, $datos_limpio[$i]['fechafin']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('N' . $cont, $datos_limpio[$i]['usuario']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('O' . $cont, $datos_limpio[$i]['nombre_socio']);

                        $objPHPExcel->getActiveSheet()->SetCellValue('P' . $cont, $datos_bruto[$i]['filial_socio']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('Q' . $cont, $datos_bruto[$i]['empresa_socio']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('R' . $cont, $datos_bruto[$i]['fecha_nacimiento']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('S' . $cont, $datos_bruto[$i]['count']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('T' . $cont, $datos_bruto[$i]['horas_contratadas']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('U' . $cont, $datos_bruto[$i]['comentario']);
                        $objPHPExcel->getActiveSheet()->SetCellValue('V' . $cont, $datos_bruto[$i]['convenio']);

                        $objPHPExcel->getActiveSheet()->SetCellValue('W' . $cont, $datos_bruto[$i]['fechabd']);

                        $cont++;
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


                    $objPHPExcel->setActiveSheetIndex(0);

                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
                    header('Content-Disposition: attachment;filename="Cierre_Paraguay_' . $desde . '_' . $hasta . '.xlsx"');
                    header('Cache-Control: max-age=0');

                    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
                    $objWriter->save('php://output');
                    exit;
                }
            }
        }
    }
}


mysqli_close($conexion);




function datos_socio($cedula_socio)
{
    $conexion = connection(DB_AFILIACION_PARAGUAY);

    $consulta = mysqli_query($conexion, "SELECT 
    'ASUNCION' AS 'filial',
    'DAVI' AS 'empresa',
    u.fecha_nac AS 'fecha_nacimiento',
    p.cant_horas AS 'horas_contratadas',
    u.observaciones AS 'comentario',
    u.convenio,
    u.fecha_creado
    FROM 
    usuarios AS u 
    INNER JOIN productos AS p ON u.idproducto = p.id
    WHERE 
    u.cinPac = '$cedula_socio'");

    return mysqli_fetch_assoc($consulta);
    mysqli_close($conexion);
}


function calcular_count($fecha_afiliacion)
{
    $fecha_actual = date("Y-m-d");
    $fechainicial = new DateTime($fecha_afiliacion);
    $fechafinal = new DateTime($fecha_actual);
    $diferencia = $fechainicial->diff($fechafinal);
    $meses = ($diferencia->y * 12) + $diferencia->m;
    return $meses;
}
