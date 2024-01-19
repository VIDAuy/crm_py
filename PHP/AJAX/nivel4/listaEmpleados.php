<?php
include '../../configuraciones.php';


$mysqli = connection(DB_COORDINACION);
$conexion = connection_nodum();

$ci = $_GET['CI'];
//Apellido	Nombre	Cedula	Localidad	Telefonos	Estado	Servicio	Horario	Ultimo descanso	Prox descanso	Observaciones	Perfil de Acte.	Disp. Horaria	Disp. Para viajar	Fecha de nac.	Fecha de ingreso
$q =
	"SELECT
		t.nombre_completo, t.telefono, t.doc_persona, t.fingreso, t.fegreso, d.nom_dpto, t.estado_trab, h.desc_horario
	FROM
		v_RHTrabajador AS t
	INNER JOIN
		ct_dptos AS d
		ON d.cod_dpto = t.cod_dpto
	INNER JOIN
		ct_RHHorarios AS h
		ON h.cod_horario = t.cod_horario
	WHERE
		t.cod_cargo = 501";
if ($r = sqlsrv_query($conexion, $q)) {
	if (sqlsrv_has_rows($r)) {
		$f = sqlsrv_fetch_array($r, SQLSRV_FETCH_ASSOC);
		$f['nombre_completo']	= str_replace('  ', '', mb_convert_case($f['nombre_completo'], MB_CASE_TITLE, "UTF-8"));
		$f['nom_dpto']			= str_replace('  ', '', mb_convert_case($f['nom_dpto'], MB_CASE_TITLE, "UTF-8"));
		$f['nom_dpto']			= substr($f['nom_dpto'], 0, -1);
		$f['telefono']			= ($f['telefono'] === null)
			? 'Sin teléfono'
			: str_replace('  ', '', $f['telefono']);
		if ($f['telefono'][0] == 9)
			$f['telefono']		= '0' . $f['telefono'];
		$nomDpto				= explode('-', $f['nom_dpto']);
		if ($nomDpto[0]			== $nomDpto[1])
			$f['nom_dpto']		= $nomDpto[0];
		$f['doc_persona']		= str_replace(' ', '', mb_convert_case($f['doc_persona'], MB_CASE_TITLE, "UTF-8"));
		$f['fingreso']			= $f['fingreso']->format('d/m/Y');
		$f['fegreso']			= ($f['fegreso'] != null)
			? $f['fegreso']->format('d/m/Y')
			: '--/--/----';
		$q =
			"SELECT
				`hit1`, `hft1`, `diat1`
			FROM
				`servicios`
			WHERE
				`ciacompt1` = $ci
			ORDER BY
				`diat1` DESC
			LIMIT 1";
		$r			= $mysqli->query($q);
		$row		= $r->fetch_row();
		$f['hit1']	= $row[0];
		$f['hft1']	= $row[1];
		$f['diat1']	= $row[2];
		$fecha1 = ($row[2] < date('Y-m-d'))
			? true
			: false;
		$q =
			"SELECT
				`hit2`, `hft2`, `diat1`
			FROM
				`servicios`
			WHERE
				`ciacompt2` = $ci
			ORDER BY
				`diat1` DESC
			LIMIT 1";
		$r			= $mysqli->query($q);
		$row		= $r->fetch_row();
		$f['hit2']	= $row[0];
		$f['hft2']	= $row[1];
		$f['diat2']	= $row[2];
		$fecha2 = ($row[2] < date('Y-m-d'))
			? true
			: false;
		$q =
			"SELECT
				`hit3`, `hft3`, `diat1`
			FROM
				`servicios`
			WHERE
				`ciacompt3` = $ci
			ORDER BY
				`diat1` DESC
			LIMIT 1";
		$r			= $mysqli->query($q);
		$row		= $r->fetch_row();
		$f['hit3']	= $row[0];
		$f['hft3']	= $row[1];
		$f['diat3']	= $row[2];
		$fecha3 = ($row[2] < date('Y-m-d'))
			? true
			: false;

		if ($fecha1 && $fecha1 && $fecha2) {
			$f['ultimoServicio'] = ($f['diat1'] > $f['diat2'])
				? $f['diat1']
				: $f['diat2'];

			if ($f['ultimoServicio'] > $f['diat2'])
				$f['ultimoServicio'] = $f['diat2'];
			else if ($f['ultimoServicio'] > $f['diat3'])
				$f['ultimoServicio'] = $f['diat3'];
		} else if ($fecha1 && $fecha1)
			$f['ultimoServicio'] = ($f['diat1'] > $f['diat2'])
				? $f['diat1']
				: $f['diat2'];
		else if ($fecha1 && $fecha2)
			$f['ultimoServicio'] = ($f['diat1'] > $f['diat3'])
				? $f['diat1']
				: $f['diat3'];
		else if ($fecha1 && $fecha2)
			$f['ultimoServicio'] = ($f['diat2'] > $f['diat3'])
				? $f['diat2']
				: $f['diat3'];


		if (!$fecha1 && !$fecha1 && !$fecha2) {
			$f['proximoServicio'] = ($f['diat1'] < $f['diat2'] && $f['diat1'] > date('Y-m-d') && $f['diat2'] > date('Y-m-d'))
				? $f['diat1']
				: $f['diat2'];

			if ($f['proximoServicio'] > $f['diat2'] && $f['diat2'] < date('Y-m-d'))
				$f['proximoServicio'] = $f['diat2'];
			else if ($f['proximoServicio'] > $f['diat3'] && $f['diat3'] < date('Y-m-d'))
				$f['proximoServicio'] = $f['diat3'];
		} else if (!$fecha1 && !$fecha1)
			$f['proximoServicio'] = ($f['diat1'] < $f['diat2'] && $f['diat1'] > date('Y-m-d') && $f['diat2'] > date('Y-m-d'))
				? $f['diat1']
				: $f['diat2'];
		else if (!$fecha1 && !$fecha2)
			$f['proximoServicio'] = ($f['diat1'] < $f['diat3'] && $f['diat1'] > date('Y-m-d') && $f['diat3'] > date('Y-m-d'))
				? $f['diat1']
				: $f['diat3'];
		else if (!$fecha1 && !$fecha2)
			$f['proximoServicio'] = ($f['diat2'] < $f['diat3'] && $f['diat2'] > date('Y-m-d') && $f['diat3'] > date('Y-m-d'))
				? $f['diat2']
				: $f['diat3'];
	} else
		$f = ['sinRegistros' => true];
} else
	$f['asd'] = false;

$f['a'] = date('Y-m-d');
echo json_encode($f);
