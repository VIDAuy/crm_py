<?php
include '../../configuraciones.php';

use PHPMailer\PHPMailer\PHPMailer;

require '../../../assets/lib/PHPMailer/src/PHPMailer.php';
require '../../../assets/lib/PHPMailer/src/SMTP.php';
require '../../../assets/lib/PHPMailer/src/Exception.php';


$conexion = connection(DB);
$fechaInicioGestion = date('Y-m-d');
$areaFinGestion = $_POST['usuario'];
$idrelacion = $_POST['id_relacion'];
$nombreFuncionarioFinal = mb_convert_case($_POST['nombre_funcionario'], MB_CASE_TITLE, 'UTF-8');
$estado = $_POST['estado'];
$motivoNoOtorgada = (isset($_POST['motivo'])) ? $_POST['motivo'] : null;
$observacionFinal = 'Baja ' .  $estado . ': ' . mysqli_real_escape_string($conexion, $_POST['observacion']);
$fechaFinGestion = date('Y-m-d');
$fecha_inicio_gestion = obtener_datos_inicio_gestion($idrelacion);
$enGestion = ($fecha_inicio_gestion['fecha_inicio_gestion'] != null) ? true : false;
$nombre = mb_convert_case($fecha_inicio_gestion['nombre_socio'], MB_CASE_TITLE, "UTF-8");
$cedula = $fecha_inicio_gestion['cedula_socio'];
$telefono = $fecha_inicio_gestion['telefono_contacto'] . ' ' . $fecha_inicio_gestion['celular_contacto'];
$fecha = date('Y-m-d H:i:s');


if (!$idrelacion || !$nombreFuncionarioFinal || !$estado || !$observacionFinal) {
	$respuesta['error'] = true;
	$respuesta['mensaje'] = 'Ha ocurrido un error, por favor dirígase al administrador.';
	die(json_encode($respuesta));
}


if (in_array($areaFinGestion, array('40479176', '48458544', '53220928', '63737983', '20053746', '49203790'))) {
	$sector = 'Coordinacion';
} else if (in_array($areaFinGestion, array('19585073', '50709395'))) {
	$sector = 'Bajas';
} else {
	$sector = $areaFinGestion;
}


if ($areaFinGestion == "Bienvenidapy") {

	$insert = ingresar_gestion_supervisor($idrelacion, $nombreFuncionarioFinal, $areaFinGestion, $estado, $motivoNoOtorgada, $observacionFinal);

	if ($insert === false) {
		$respuesta['error'] = true;
		$respuesta['mensaje'] = "Ocurrieron errores al insertar la gestión del supervisor";
		die(json_encode($respuesta));
	}

	$insert_registros = insert_registros($estado, $areaFinGestion, $cedula, $nombre, $telefono, $fecha, $sector, $observacionFinal);

	if ($insert_registros === false) {
		$respuesta['error'] = true;
		$respuesta['mensaje'] = "Ocurrieron errores al insertar en registros";
		die(json_encode($respuesta));
	}

	if ($estado == "En Gestión") {
		//Enviar Mail
		$texto = [
			"titulo" => "Nueva alerta",
			"cabecera" => "Tiene una nueva baja para gestionar.",
			"detalle1" => "Por favor corroboré los registros en su CRM,",
			"informacion" => "Muchas gracias!"
		];
		$bodyHtml = htmlBodyEmail($texto);
		$email = EnviarMail("Calidaduy", EMAIL_CALIDAD, $bodyHtml);

		if ($email === false) {
			$respuesta['error'] = true;
			$respuesta['mensaje'] = "Ocurrieron errores al enviar email de alerta";
			die(json_encode($respuesta));
		}

		//Enviar Mail
		$texto1 = [
			"titulo" => "Nueva alerta",
			"cabecera" => "Tiene una nueva baja para gestionar.",
			"detalle1" => "Por favor corroboré los registros en su CRM,",
			"informacion" => "Muchas gracias!"
		];
		$bodyHtml1 = htmlBodyEmail($texto1);
		$email1 = EnviarMail("Bajas", EMAIL_BAJAS, $bodyHtml1);

		if ($email1 === false) {
			$respuesta['error'] = true;
			$respuesta['mensaje'] = "Ocurrieron errores al enviar email de alerta";
			die(json_encode($respuesta));
		}
	}

	if ($estado == "Continua") {
		//Enviar Mail
		$texto = [
			"titulo" => "Nueva alerta",
			"cabecera" => "Tiene una nueva baja para darle continuidad.",
			"detalle1" => "Cédula: $cedula",
			"informacion" => "Muchas gracias!"
		];
		$bodyHtml = htmlBodyEmail($texto);
		$email = EnviarMail("Bajas", EMAIL_BAJAS, $bodyHtml);

		if ($email === false) {
			$respuesta['error'] = true;
			$respuesta['mensaje'] = "Ocurrieron errores al enviar email de alerta";
			die(json_encode($respuesta));
		}
	}
}



if ($areaFinGestion == "Calidaduy") {

	$insert_registros = insert_registros($estado, $areaFinGestion, $cedula, $nombre, $telefono, $fecha, $sector, $observacionFinal);

	if ($insert_registros === false) {
		$respuesta['error'] = true;
		$respuesta['mensaje'] = "Ocurrieron errores al insertar en registros";
		die(json_encode($respuesta));
	}

	$insert_bajas = insert_bajas($enGestion, $estado, $motivoNoOtorgada, $nombreFuncionarioFinal, $observacionFinal, $sector, $fechaInicioGestion, $fechaFinGestion, $idrelacion);

	if ($insert_bajas === false) {
		$respuesta['error'] = true;
		$respuesta['mensaje'] = "Ocurrieron errores al insertar en bajas";
		die(json_encode($respuesta));
	}

	if ($estado == 'Otorgada') {
		$modificar_padron = update_padron($cedula, $observacionFinal);

		if ($modificar_padron === false) {
			$respuesta['error'] = true;
			$respuesta['mensaje'] = "Ocurrieron errores al modificar en padrón";
			die(json_encode($respuesta));
		}
	}

	/*
	//Enviar Mail
	$texto = [
		"titulo" => "Nueva alerta",
		"cabecera" => "Calidad gestionó el estado de la baja de la cédula: $cedula y le asignó el estado: $estado",
		"detalle1" => "Por favor corroboré los registros en su CRM,",
		"informacion" => "Muchas gracias!"
	];
	$bodyHtml = htmlBodyEmail($texto);
	$email = EnviarMail("Bienvenidauy", EMAIL_BIENVENIDA, $bodyHtml);

	if ($email === false) {
		$respuesta['error'] = true;
		$respuesta['mensaje'] = "Ocurrieron errores al enviar email de alerta";
		die(json_encode($respuesta));
	}
	*/
}





$respuesta['error'] = false;
$respuesta['mensaje'] = "El registro se ha actualizado de forma exitosa.";


echo json_encode($respuesta);





function ingresar_gestion_supervisor($idrelacion, $nombreSupervisor, $area, $estado, $motivoNoOtorgada, $observacionFinal)
{
	$conexion = connection(DB);
	$tabla = TABLA_BAJAS;

	$sql = "UPDATE
		{$tabla}
	SET
		usuario_funcionario_supervisor = '{$nombreSupervisor}',
		area_funcionario_supervisor = '{$area}',
		estado_supervisor = '{$estado}',
		motivo_no_otorgada_supervisor = '{$motivoNoOtorgada}',
		observacion_supervisor = '{$observacionFinal}',
		fecha_gestion_supervisor = NOW()
	WHERE
		idrelacion = '{$idrelacion}'";
	$consulta = mysqli_query($conexion, $sql);

	return mysqli_fetch_assoc($consulta)['id'] != "" ? false : true;
}

function obtener_datos_inicio_gestion($idrelacion)
{
	$conexion = connection(DB);
	$tabla = TABLA_BAJAS;

	$sql = "SELECT
				cedula_socio,
				nombre_socio,
				telefono_contacto,
				celular_contacto,
				fecha_inicio_gestion
			FROM
				{$tabla}
			WHERE
				idrelacion = '$idrelacion'
			ORDER BY id DESC
			LIMIT 1";
	$consulta = mysqli_query($conexion, $sql);

	return mysqli_fetch_assoc($consulta);
}

function insert_registros($estado, $area, $cedula, $nombre, $telefono, $fecha, $sector, $observacionFinal)
{
	$conexion = connection(DB);
	$tabla = TABLA_REGISTROS_PY;

	if ($area == "Calidaduy") {
		if ($estado != 'Otorgada') {
			$sql = "INSERT INTO {$tabla} SET cedula = '$cedula', nombre = '$nombre', telefono = '$telefono', fecha_registro = '$fecha', sector	= '$sector', observaciones = '$observacionFinal', activo = 0, socio = 1, baja = 1";
		} else {
			$sql = "INSERT INTO {$tabla} SET cedula = '$cedula', nombre = '$nombre', telefono = '$telefono', fecha_registro = '$fecha', sector	= '$sector', observaciones = '$observacionFinal', activo = 0, socio = 0, baja = 1";
		}
	} else {
		$sql = "INSERT INTO {$tabla} SET cedula = '$cedula', nombre = '$nombre', telefono = '$telefono', fecha_registro = '$fecha', sector	= '$sector', observaciones = '$observacionFinal', activo = 1, socio = 1, baja = 1";
	}

	return mysqli_query($conexion, $sql);
}

function insert_bajas($enGestion, $estado, $motivoNoOtorgada, $nombreFuncionarioFinal, $observacionFinal, $sector, $fechaInicioGestion, $fechaFinGestion, $idrelacion)
{
	$conexion = connection(DB);
	$tabla = TABLA_BAJAS;

	if ($enGestion) {
		$sql = ($estado == 'En Gestión')
			? "UPDATE {$tabla} SET estado = '$estado', motivo_no_otorgada = '$motivoNoOtorgada', nombre_funcionario_final = '$nombreFuncionarioFinal', observacion_final = '$observacionFinal', area_fin_gestion = '$sector', activo = 1 WHERE idrelacion = '$idrelacion'"
			:
			"UPDATE {$tabla} SET estado = '$estado', motivo_no_otorgada = '$motivoNoOtorgada', nombre_funcionario_final = '$nombreFuncionarioFinal', observacion_final = '$observacionFinal', area_fin_gestion = '$sector', fecha_fin_gestion = '$fechaFinGestion', activo = 0 WHERE idrelacion = '$idrelacion'";
	} else {
		$sql = ($estado == 'En Gestión')
			? "UPDATE {$tabla} SET fecha_inicio_gestion = '$fechaInicioGestion', estado = '$estado', motivo_no_otorgada = '$motivoNoOtorgada', nombre_funcionario_final = '$nombreFuncionarioFinal', observacion_final = '$observacionFinal', area_fin_gestion = '$sector', activo = 1 WHERE idrelacion = '$idrelacion'"
			:
			"UPDATE {$tabla} SET fecha_inicio_gestion = '$fechaInicioGestion', estado = '$estado', motivo_no_otorgada = '$motivoNoOtorgada', nombre_funcionario_final 	= '$nombreFuncionarioFinal', observacion_final = '$observacionFinal', area_fin_gestion = '$sector', fecha_fin_gestion = '$fechaFinGestion', activo = 0 WHERE idrelacion = '$idrelacion'";
	}

	return mysqli_query($conexion, $sql);
}

function update_padron($cedula, $observacionFinal)
{
	$conexion = connection(DB_AFILIACION_PARAGUAY);
	$tabla1 = TABLA_PADRON_DATOS_SOCIO;
	$tabla2 = TABLA_PADRON_PRODUCTO_SOCIO;

	$sql1 = "UPDATE {$tabla1} SET abmactual = 1, abm = 'baja', observaciones = '$observacionFinal' WHERE cedula = '$cedula'";
	$sql2 = "UPDATE {$tabla2} SET abmactual = 1, abm = 'baja' WHERE cedula = '$cedula'";
	$consulta1 = mysqli_query($conexion, $sql1);
	$consulta2 = mysqli_query($conexion, $sql2);

	return $consulta1 == true && $consulta2 == true ? true : false;
}








function htmlBodyEmail($texto)
{
	$html = '
    <!DOCTYPE html>
	<html lang="es">
	<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="x-apple-disable-message-reformatting">
	<style>
		table, td, div, h1, p {font-family: Arial, sans-serif;}
	</style>
	</head>
	<body style="margin:0;padding:0;">
	<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
		<tr>
		<td align="center" style="padding:0;">
			<table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
			<tr>
				<td align="center" style="padding:40px 0 30px 0;background:#304689;">
				<img src="https://i.ibb.co/WkqgSFv/111-fotor-bg-remover-2023051092030.png" alt="" width="300" style="height:auto;display:block;" />
				</td>
			</tr>
			<tr>
				<td style="padding:36px 30px 42px 30px;">
				<table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
					<tr>
					<td style="padding:0 0 36px 0;color:#153643;">
						<h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">' . @utf8_decode($texto["titulo"]) . '</h1>
						<p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">' . @utf8_decode($texto["cabecera"]) . '</p>
						<p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">' . @utf8_decode($texto["detalle1"]) . '</p>
						<p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">' . @utf8_decode($texto["informacion"]) . '</p>
					</td>
					</tr>
				</table>
				</td>
			</tr>
			<tr>
				<td style="padding:30px;background: #942f4a !important;">
				
				</td>
			</tr>
			</table>
		</td>
		</tr>
	</table>
	</body>
	</html>
    ';
	return $html;
}

function EnviarMail($sector, $email, $bodyHtml, $ccs = null)
{
	$configuracion = [
		"host" => "smtp.gmail.com",
		"port" => 587,
		"username" => "no-responder@vida.com.uy",
		"password" => "2k8.vida",
		"from" => "no-responder@vida.com.uy",
	];

	$datos = [
		"email" => $email,
		"nombre" => $sector
	];

	$asunto = "Usted tiene una nueva alerta en CRM";

	$mail = new PHPMailer();
	$mail->isSMTP();
	$mail->Host = $configuracion["host"];
	$mail->SMTPAuth = true;
	$mail->Username = $configuracion["username"];
	$mail->Password = $configuracion["password"];
	$mail->SMTPSecure = 'tls';
	$mail->Port = $configuracion["port"];
	$mail->Subject = $asunto;
	$mail->isHTML(true);
	$mail->setFrom($configuracion["from"], $configuracion["fromname"]);
	//$mail->addReplyTo($configuracion["from"], $configuracion["fromname"]);
	$mail->addAddress($datos["email"], @utf8_decode(ucfirst($datos["nombre"])));
	if ($ccs != null) {
		foreach ($ccs as $cc) {
			$mail->addCC($cc["email"], @utf8_decode(ucfirst($cc["nombre"])));
		}
	}
	$mail->Body = $bodyHtml;

	if ($mail->send()) {
		return true;
	} else {
		return $mail->ErrorInfo;
	}
}
