<?php
include '../../configuraciones.php';
$conexion = connection_nodum();

$q =
	"SELECT
		t.nombre_completo, t.telefono, t.doc_persona, t.fingreso, t.fegreso, d.nom_dpto, t.estado_trab
	FROM
		v_RHTrabajador AS t
	INNER JOIN
		ct_dptos AS d
		ON d.cod_dpto = t.cod_dpto
	WHERE
		t.cod_cargo = 501";
if ($r = sqlsrv_query($conexion, $q)) {
	if (sqlsrv_has_rows($r)) {
		while ($f = sqlsrv_fetch_array($r, SQLSRV_FETCH_ASSOC)) {
			var_dump($f);
		}
	}
}
