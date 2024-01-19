<?php
include '../configuraciones.php';


$cedula = $_GET['CI'];
$tipo = $_GET['tipo'];


$res_general = consulta_general($cedula, $tipo);
if ($res_general === "") {
	$response['error'] = true;
	$response['mensaje'] = "Error en la consulta general";
	die(json_encode($response));
}


$cod_persona = $res_general['cod_persona'];
$res_telefono = consulta_telefono($cod_persona);
if ($res_telefono === "") {
	$response['error'] = true;
	$response['mensaje'] = "Error en la consulta telefono";
	die(json_encode($response));
}


$res_mail = consulta_mail($cod_persona);
if ($res_mail === "") {
	$response['error'] = true;
	$response['mensaje'] = "Error en la consulta mail";
	die(json_encode($response));
}


$cod_empresa = $res_general['empresa'];
$res_empresa = consulta_empresa($cod_empresa);
if ($res_empresa === "") {
	$response['error'] = true;
	$response['mensaje'] = "Error en la consulta empresa";
	die(json_encode($response));
}


$cod_cargo = $res_general['cargo'];
$res_cargo = consulta_cargo($cod_cargo);
if ($res_cargo === "") {
	$response['error'] = true;
	$response['mensaje'] = "Error en la consulta cargo";
	die(json_encode($response));
}


$cod_seccion = $res_general['seccion'];
$res_seccion = consulta_centro_costos($cod_seccion);
if ($res_seccion === "") {
	$response['error'] = true;
	$response['mensaje'] = "Error en la consulta seccion";
	die(json_encode($response));
}


$id_nodum = $res_general['nro_nodum'];
$cedula = $res_general['cedula'];
$res_plan = consulta_comisionamiento($cedula);
if ($res_plan === "") {
	$response['error'] = true;
	$response['mensaje'] = "Error en la consulta tipo comisionamiento";
	die(json_encode($response));
}


$cod_dpto = $res_general['filiales'];
$res_filiales = consulta_filiales($cod_dpto);
if ($res_filiales === "") {
	$response['error'] = true;
	$response['mensaje'] = "Error en la consulta filiales";
	die(json_encode($response));
}


$cod_tipo_trabajador = $res_general['tipo_trabajador'];
$res_tipo_trabajador = consulta_tipo_trabajador($cod_tipo_trabajador);
if ($res_tipo_trabajador === "") {
	$response['error'] = true;
	$response['mensaje'] = "Error en la consulta tipo trabajador";
	die(json_encode($response));
}


$cod_causal_baja = $res_general['causal_baja'];
$res_causal_baja = consulta_causal_baja($cod_causal_baja);
if ($res_causal_baja === "") {
	$response['error'] = true;
	$response['mensaje'] = "Error en la consulta causal baja";
	die(json_encode($response));
}


$cod_banco = $res_general['medio_pago'];
$res_medio_pago = consulta_medio_pago($cod_banco);
if ($res_medio_pago === "") {
	$response['error'] = true;
	$response['mensaje'] = "Error en la consulta causal baja";
	die(json_encode($response));
}


$nombre_1_verificar = TRIM($res_general['nombre1']);
$nombre1 = verificar_letras($nombre_1_verificar) == true ? $nombre_1_verificar : "";
$nombre_2_verificar = TRIM($res_general['nombre2']);
$nombre2 = verificar_letras($nombre_2_verificar) == true ? $nombre_2_verificar : "";
$apellido1_verificar = TRIM($res_general['apellido1']);
$apellido1 = verificar_letras($apellido1_verificar) == true ? $apellido1_verificar : "";
$apellido2_verificar = TRIM($res_general['apellido2']);
$apellido2 = verificar_letras($apellido2_verificar) == true ? $apellido2_verificar : "";
$nombre_completo = $nombre1 . " " . $nombre2 . " " . $apellido1 . " " . $apellido2;

$telefono = $res_telefono['telefono'];
$fecha_ingreso = $res_general['fecha_ingreso']->FORMAT('d/m/Y');
$fecha_egreso = $res_general['fecha_egreso'] != "" ? $res_general['fecha_egreso']->FORMAT('d/m/Y') : "";
$empresa = $res_empresa['nom_emp'];
$estado = $res_general['estado'];
$cargo = $res_cargo['descrip'];
$seccion = $res_seccion['desc_seccion'];
$plan = $res_plan['plan'] != "" ? $res_plan['plan'] : "Ninguno";
$filial = $res_filiales[0];
$subfilial = $res_filiales[1];
$tipo_trabajador = $res_tipo_trabajador['descrip'];
$causa_baja = $cod_causal_baja == 0 ? "" : $res_causal_baja['desc_combo'];
$medio_pago = $res_medio_pago['nom_banco'];
$mail = $res_mail['email'] != "" ? $res_mail['email'] : "Sin Registro";





$datos = [
	"cedula" => $cedula,
	"id_nodum" => $id_nodum,
	"nombre" => $nombre_completo,
	"fecha_ingreso" => $fecha_ingreso,
	"fecha_egreso" => $fecha_egreso,
	"empresa" => $empresa,
	"estado" => $estado,
	"causa" => $causa_baja,
	"planes" => $plan,
	"filial" => $filial,
	"sub_filial" => $subfilial,
	"cargo" => $cargo,
	"seccion" => $seccion,
	"tipo_trabajador" => $tipo_trabajador,
	"banco" => $medio_pago,
	"telefono" => $telefono,
	"correo" => $mail,
];


$response['error'] = false;
$response['datos'] = $datos;


echo json_encode($response);









function consulta_general($cedula, $tipo)
{
	$conexion = connection_nodum();

	if ($tipo == 'cedula') {
		$consulta = sqlsrv_query($conexion, "SELECT TOP 1
	v.doc_persona_nro AS 'cedula', 
	v.cod_persona, 
	v.cod_trabajador AS 'nro_nodum', 
	v.nombre1, 
	v.nombre2, 
	v.apellido1, 
	v.apellido2, 
	v.causal_baja, 
	v.fingreso AS 'fecha_ingreso', 
	v.fegreso AS 'fecha_egreso',
	v.estado_actual_tr AS 'estado', 
	v.cod_dpto AS 'filiales', 
	v.cod_cargo AS 'cargo', 
	v.cod_seccion AS 'seccion', 
	v.cod_tipo_trab AS 'tipo_trabajador', 
	v.cod_emp AS 'empresa', 
	v.cod_banco_trab AS 'medio_pago' 
	FROM 
	ct_RHTrabajador AS t, 
	v_RHTrabajador AS v 
	WHERE 
	t.cod_persona = v.cod_persona 
	AND v.doc_persona_nro = '$cedula'
	ORDER BY fecha_ingreso DESC
	");
	} else {
		$consulta = sqlsrv_query($conexion, "SELECT TOP 1
	v.doc_persona_nro AS 'cedula', 
	v.cod_persona, 
	v.cod_trabajador AS 'nro_nodum', 
	v.nombre1, 
	v.nombre2, 
	v.apellido1, 
	v.apellido2, 
	v.causal_baja, 
	v.fingreso AS 'fecha_ingreso', 
	v.fegreso AS 'fecha_egreso',
	v.estado_actual_tr AS 'estado', 
	v.cod_dpto AS 'filiales', 
	v.cod_cargo AS 'cargo', 
	v.cod_seccion AS 'seccion', 
	v.cod_tipo_trab AS 'tipo_trabajador', 
	v.cod_emp AS 'empresa', 
	v.cod_banco_trab AS 'medio_pago' 
	FROM 
	ct_RHTrabajador AS t, 
	v_RHTrabajador AS v 
	WHERE 
	t.cod_persona = v.cod_persona 
	AND v.cod_tit = '$cedula'
	ORDER BY fecha_ingreso DESC
	");
	}

	$respuesta = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC);

	return $respuesta == null ? "" : $respuesta;
}


function consulta_telefono($cod_persona)
{
	$conexion = connection_nodum();

	$consulta = sqlsrv_query($conexion, "SELECT telefono FROM ct_RHNrosTelxPer WHERE cod_persona = '$cod_persona'");

	return sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC);
}


function consulta_empresa($cod_empresa)
{
	$conexion = connection_nodum();

	$consulta = sqlsrv_query($conexion, "SELECT nom_emp FROM ct_empresas WHERE cod_emp = '$cod_empresa'");

	return sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC);
}


function consulta_cargo($cod_cargo)
{
	$conexion = connection_nodum();

	$consulta = sqlsrv_query($conexion, "SELECT TOP 1 descrip FROM ct_RHCargos WHERE cod_cargo = '$cod_cargo'");

	return sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC);
}


function consulta_centro_costos($cod_seccion)
{
	$conexion = connection_nodum();

	$consulta = sqlsrv_query($conexion, "SELECT TOP 1 desc_seccion FROM ct_RHSecciones WHERE cod_seccion = '$cod_seccion'");

	return sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC);
}


function consulta_comisionamiento($cedula)
{
	$conexion = connection_nodum();

	$consulta = sqlsrv_query($conexion, "SELECT
	tabla_plan_comision.descrip AS 'plan' 
	  FROM ct_rhPlanComXTr AS tabla_planes, 
	  ct_rhPlanComis AS tabla_plan_comision,
	  v_RHTrabajador AS vista_trabajadores
	  WHERE tabla_plan_comision.cod_PlanComis=tabla_planes.cod_PlanComis AND
	  tabla_planes.cod_trabajador = vista_trabajadores.cod_trabajador AND
	  vista_trabajadores.doc_persona_nro = '$cedula'
	  ORDER BY tabla_planes.fec_vigencia DESC;
	");

	return sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC);
}


function consulta_filiales($cod_dpto)
{
	$conexion = connection_nodum();

	$consulta = sqlsrv_query($conexion, "SELECT nom_dpto FROM ct_dptos WHERE cod_dpto = '$cod_dpto'");
	$respuesta = sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC);

	return explode('-', $respuesta['nom_dpto']);
}


function consulta_tipo_trabajador($cod_tipo_trabajador)
{
	$conexion = connection_nodum();

	$consulta = sqlsrv_query($conexion, "Select descrip from ct_RHTiposTrab WHERE cod_tipo_trab = '$cod_tipo_trabajador'");

	return sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC);
}


function consulta_causal_baja($cod_causal_baja)
{
	$conexion = connection_nodum();

	$consulta = sqlsrv_query($conexion, "SELECT desc_combo FROM ct_RHCombos WHERE cod_tipo_combo = 6 AND cod_combo = '$cod_causal_baja'");

	return sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC);
}


function consulta_medio_pago($cod_banco)
{
	$conexion = connection_nodum();

	$consulta = sqlsrv_query($conexion, "SELECT nom_banco FROM ct_bancos WHERE cod_banco = '$cod_banco'");

	return sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC);
}


function consulta_mail($cod_persona)
{
	$conexion = connection_nodum();

	$consulta = sqlsrv_query($conexion, "SELECT email FROM ct_RHEmailsxPers WHERE cod_persona = '$cod_persona'");

	return sqlsrv_fetch_array($consulta, SQLSRV_FETCH_ASSOC);
}





function verificar_letras($cadena)
{
	if (preg_match("/^(?=.{3,18}$)[a-zñA-ZÑ](\s?[a-zñA-ZÑ])*$/", $cadena)) {
		$respuesta = true;
	} else {
		$respuesta = false;
	}

	return $respuesta;
}
