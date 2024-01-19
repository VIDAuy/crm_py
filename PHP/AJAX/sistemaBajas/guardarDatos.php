<?php
include '../../configuraciones.php';

use PHPMailer\PHPMailer\PHPMailer;

require '../../../assets/lib/PHPMailer/src/PHPMailer.php';
require '../../../assets/lib/PHPMailer/src/SMTP.php';
require '../../../assets/lib/PHPMailer/src/Exception.php';


$conexion = connection(DB);
$tabla = TABLA_BAJAS;
$tabla1 = TABLA_REGISTROS_PY;

$sector = $_GET['sector'];


$data 	  = array_map('stripslashes', $_POST);
$response = array('result' => false, 'message' => 'error');

if ($data) {
	$fechaIngresoBaja   = date('Y-m-d');
	$idrelacion 	    = $data['idrelacion'];
	$nombreFuncionario  = mb_convert_case(mysqli_real_escape_string($conexion, $data['nombre_funcionario']), MB_CASE_TITLE, 'UTF-8');
	$filialSolicitud    = $data['filial_solicitud'];
	$estado 	        = 'Pendiente';
	$observaciones 	    = ucfirst(mb_strtolower(mysqli_real_escape_string($conexion, $data['observaciones'])));
	$nombreSocio 	    = $data['nombre_socio'];
	$cedulaSocio 	    = $data['cedula_socio'];
	$filialSocio 	    = $data['filial_socio'];
	$servicioContratado	= $data['nroServicio0'];
	$horasContratadas 	= $data['horas0'];
	$importe 			= $data['importe0'];

	$i = 1;
	while (isset($data['horas' . $i])) {
		$servicioContratado = $servicioContratado . ', ' . $data['nroServicio' . $i];
		$horasContratadas 	= $horasContratadas . ', ' . $data['horas' . $i];
		$importe 			= $importe . ', ' . $data['importe' . $i];
		$i++;
	}
	$motivoBaja 	  = $data['motivo_baja'];
	$nombreContacto   = mb_convert_case(mysqli_real_escape_string($conexion, $data['nombre_contacto']), MB_CASE_TITLE, 'UTF-8');
	$apellidoContacto = mb_convert_case(mysqli_real_escape_string($conexion, $data['apellido_contacto']), MB_CASE_TITLE, 'UTF-8');
	$telefonoContacto = (isset($data['telefono_contacto']) && strlen($data['telefono_contacto']) === 8) ? $data['telefono_contacto'] : null;
	$celularContacto  = (isset($data['celular_contacto']) && strlen($data['celular_contacto']) === 9) ? $data['celular_contacto'] : null;


	if (in_array($_GET['sector'], array('19585073', '50709395'))) {
		$sector = 'Bajas';
	}

	$q = "SELECT activo FROM {$tabla} WHERE idrelacion = '$idrelacion' AND activo = 1";
	$r = mysqli_query($conexion, $q);

	if (mysqli_num_rows($r) != 0) $response = array('registroActivo' => true, 'message' => 'Ya se está gestionando una baja para esa persona.');
	else {
		$qSelect = "SELECT COUNT(`telefono_contacto`) AS `cantidad` FROM {$tabla} WHERE `telefono_contacto` = '{$telefonoContacto}' GROUP BY `telefono_contacto`";
		$select = mysqli_query($conexion, $q);
		$cantidadDeUsosTelefono = mysqli_fetch_assoc($select)['cantidad'];
		$qSelect = "SELECT COUNT(`celular_contacto`) AS `cantidad` FROM {$tabla} WHERE `celular_contacto` = '{$celularContacto}' GROUP BY `celular_contacto`";
		$select = mysqli_query($conexion, $q);
		$cantidadDeUsosCelular = mysqli_fetch_assoc($select)['cantidad'];

		if ($cantidadDeUsosTelefono > 0)
			$observaciones .= "\nADVERTENCIA, EL TELÉFONO DE CONTACTO {$telefonoContacto} SE HA UTILIZADO {$cantidadDeUsosTelefono} VECES.";
		if ($cantidadDeUsosCelular > 0)
			$observaciones .= "\nADVERTENCIA, EL CELULAR DE CONTACTO {$celularContacto} SE HA UTILIZADO {$cantidadDeUsosCelular} VECES.";




		/** Datos genéricos de aprovación por supervisor **/
		$estado_supervisor = "En Gestión";
		$usuario_funcionario_supervisor = "Sistema";
		$observacion_supervisor = "Baja En Gestión: Baja";
		$area_funcionario_supervisor = "Sistema";
		$fecha_gestion_supervisor = $fechaIngresoBaja;
		/** End datos genéricos de aprovación por supervisor **/



		$q = "INSERT INTO {$tabla} (fecha_ingreso_baja, idrelacion, nombre_funcionario, filial_solicitud, estado, observaciones, nombre_socio, cedula_socio, filial_socio, servicio_contratado, horas_contratadas, importe, motivo_baja, nombre_contacto, apellido_contacto, telefono_contacto, celular_contacto, estado_supervisor, usuario_funcionario_supervisor, observacion_supervisor, area_funcionario_supervisor, fecha_gestion_supervisor)
		VALUES ('$fechaIngresoBaja', '$idrelacion', '$nombreFuncionario', $filialSolicitud, '$estado', '$observaciones', '$nombreSocio', '$cedulaSocio', $filialSocio,
		'$servicioContratado', '$horasContratadas', '$importe', '$motivoBaja', '$nombreContacto', '$apellidoContacto', '$telefonoContacto', '$celularContacto', '$estado_supervisor', '$usuario_funcionario_supervisor', '$observacion_supervisor', '$area_funcionario_supervisor', '$fecha_gestion_supervisor')";

		$r = mysqli_query($conexion, $q);
		$fechaIngresoBaja = date('Y-m-d H:i:s');
		$observaciones 	  = 'Solicitud de baja: ' . $observaciones;

		$q = "INSERT INTO {$tabla1} (cedula, nombre, telefono, fecha_registro, sector, observaciones, socio, baja) VALUES ('$cedulaSocio', '$nombreSocio', '$telefonoContacto', '$fechaIngresoBaja', '$sector', '$observaciones', 1, 1)";
		$r = mysqli_query($conexion, $q);

		if ($r) {
			$mensaje = 'Los registros se han ingresado de forma exitosa.';
			if ($cantidadDeUsosTelefono > 0)
				$mensaje .= "\nADVERTENCIA, EL TELÉFONO DE CONTACTO {$telefonoContacto} SE HA UTILIZADO {$cantidadDeUsosTelefono} VECES.";
			if ($cantidadDeUsosCelular > 0)
				$mensaje .= "\nADVERTENCIA, EL CELULAR DE CONTACTO {$celularContacto} SE HA UTILIZADO {$cantidadDeUsosCelular} VECES.";


			/*
			//if ($sector != "Calidaduy") {
			//Enviar Mail
			$texto = [
				"titulo" => "Nueva alerta",
				"cabecera" => "Tiene una nueva baja para gestionar.",
				"detalle1" => "Por favor corroboré los registros en su CRM,",
				"informacion" => "Muchas gracias!"
			];
			$bodyHtml = htmlBodyEmail($texto);
			$email = EnviarMail("Bienvenidapy", EMAIL_BIENVENIDA, $bodyHtml);
			//}
			*/

			//Enviar Mail
			$texto = [
				"titulo" => "Nueva alerta",
				"cabecera" => "Tiene una nueva baja para gestionar.",
				"detalle1" => "Por favor corroboré los registros en su CRM,",
				"informacion" => "Muchas gracias!"
			];
			$bodyHtml = htmlBodyEmail($texto);
			$email = EnviarMail("Calidaduy", EMAIL_CALIDAD, $bodyHtml);


			$response 	= array(
				'email'                  => $email ? true : false,
				'result'                 => true,
				'message'                => $mensaje,
				'telefono'               => $telefonoContacto,
				'reiteraciones_telefono' => $cantidadDeUsosTelefono,
				'celular'                => $celularContacto,
				'reiteraciones_celular'  => $cantidadDeUsosCelular
			);
		} else {
			$response = array('result' => false, 'message' => 'Ha ocurrido un error al ingresar los registros.');
		}
	}

	/*
	if ($sector == "Calidaduy") {
		$agregar_supervisor = "UPDATE {$tabla} SET estado_supervisor = 'Otorgada', usuario_funcionario_supervisor = '$nombreFuncionario', observacion_supervisor = '$observaciones', area_funcionario_supervisor = '$sector', fecha_gestion_supervisor = '$fechaIngresoBaja' WHERE idrelacion = '$idrelacion'";
		$r = mysqli_query($conexion, $agregar_supervisor);

		if ($r) {

			//Enviar Mail
			$texto = [
				"titulo" => "Nueva alerta",
				"cabecera" => "Tiene una nueva baja para gestionar.",
				"detalle1" => "Por favor corroboré los registros en su CRM,",
				"informacion" => "Muchas gracias!"
			];
			$bodyHtml = htmlBodyEmail($texto);
			$email = EnviarMail("Calidaduy", EMAIL_CALIDAD, $bodyHtml);


			$response 	= array(
				'email'                  => $email ? true : false,
				'result'                 => true,
				'message'                => 'Los registros se han ingresado de forma exitosa.',
				'telefono'               => $telefonoContacto,
				'reiteraciones_telefono' => $cantidadDeUsosTelefono,
				'celular'                => $celularContacto,
				'reiteraciones_celular'  => $cantidadDeUsosCelular
			);
		} else {
			$response = array('result' => false, 'message' => 'Ha ocurrido un error al registrar con supervisión.');
		}
	}
	*/
}
echo json_encode($response);








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
