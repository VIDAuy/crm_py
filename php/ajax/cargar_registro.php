<?php
include '../configuraciones.php';

use PHPMailer\PHPMailer\PHPMailer;

require '../../assets/lib/PHPMailer/src/PHPMailer.php';
require '../../assets/lib/PHPMailer/src/SMTP.php';
require '../../assets/lib/PHPMailer/src/Exception.php';

$conexion = connection(DB);
$area        = $_SESSION['usuario_py'];
$nombre      = mb_convert_case($_REQUEST['nombre'], MB_CASE_UPPER, "UTF-8");
$tel         = $_REQUEST['telefono'];
$observacion = mysqli_real_escape_string($conexion, $_REQUEST['observacion']);
$cedula      = $_REQUEST['cedulas'];
$envioSector = $_REQUEST['ensec'];
$socio       = $_REQUEST['socio'];
$sector      = $_REQUEST['sector'];


if ($area == "" || $nombre == "" || $tel == "" || $observacion == "" || $cedula == "" || $socio == "" || $sector == "")
	devolver_error(ERROR_GENERAL);


if (in_array($_POST['sector'], ['19585073', '50709395'])) $sector = 'Bajas';


if (count($_FILES) > 0) {
	$imagen = $_FILES['imagen'];

	if (controlarExtension($imagen, ["png", "jpeg", "jpg", "pdf"]) <= 0)
		devolver_error("Los archivos cargados solo pueden ser de tipo .jpg, .jpeg, .png o .pdf");

	$insert = insert_registro_con_imagen($imagen, $cedula, $nombre, $tel, $sector, $observacion, $envioSector, $socio);
	if ($insert === false) devolver_error("Error al cargar el registro");
} else {
	$insert = insert_registro($cedula, $nombre, $tel, $sector, $observacion, $envioSector, $socio);
	if ($insert === false) devolver_error("Error al cargar el registro");
}


if ($envioSector != "") {
	$datos_sector = obtener_email_envioSector($envioSector);

	//Enviar Mail
	$texto = [
		"titulo" => "Nueva alerta",
		"cabecera" => ucfirst($sector) . " ha cargado una nueva alerta.",
		"detalle1" => "Por favor corroboré los registros en su CRM,",
		"informacion" => "Muchas gracias!",
	];
	$bodyHtml = htmlBodyEmail($texto);
	$email = EnviarMail($sector, $datos_sector, $bodyHtml);
}


if ($envioSector != "") {
	$respuesta['error'] = false;
	$respuesta['email'] = $email == true ? true : $email;
	$respuesta['mensaje'] = 'Se ha cargado el registro para la cédula <span class="text-danger"><strong>' . $cedula . '</strong></span>';
	$respuesta['area'] = $area;
} else {
	$respuesta['error'] = false;
	$respuesta['mensaje'] = 'Se ha cargado el registro para la cédula <span class="text-danger"><strong>' . $cedula . '</strong></span>';
	$respuesta['area'] = $area;
}



echo json_encode($respuesta);




function insert_registro_con_imagen($documento, $cedula, $nombre, $telefono, $sector, $observacion, $envioSector, $socio)
{
	$conexion = connection(DB);
	$tabla = TABLA_IMAGENES_REGISTRO;
	$errores = 0;

	$id_insert = insert_registro($cedula, $nombre, $telefono, $sector, $observacion, $envioSector, $socio);
	if ($id_insert === false) devolver_error("Error al cargar el registro");

	for ($i = 0; $i < count($documento["name"]); $i++) {
		$extension_archivo = strtolower(pathinfo(basename($documento["name"][$i]), PATHINFO_EXTENSION));
		$nombre_archivo =  generarHash(50) . '.' . $extension_archivo;
		$ruta_origen = $documento["tmp_name"][$i];
		$destino = "../../assets/documentos/registros/" . $nombre_archivo;

		if (move_uploaded_file($ruta_origen, $destino)) {
			$insert_imagenes = "INSERT INTO {$tabla} (id_registro, nombre_imagen) VALUES ('$id_insert', '$nombre_archivo')";
			$respuesta = mysqli_query($conexion, $insert_imagenes) == true ? $errores : $errores++;
		} else {
			devolver_error("Error al cargar el archivo");
		}
	}

	return $errores > 0 ? false : true;
}


function insert_registro($cedula, $nombre, $telefono, $sector, $observacion, $envioSector, $socio)
{
	$conexion = connection(DB);
	$tabla = TABLA_REGISTROS_PY;

	$sql = "INSERT INTO {$tabla}(cedula, nombre, telefono, fecha_registro, sector, observaciones, envioSector, activo, socio)
	VALUES('{$cedula}', '{$nombre}', '{$telefono}', NOW(), '{$sector}', '{$observacion}', '{$envioSector}', 1, {$socio})";
	$consulta = mysqli_query($conexion, $sql);

	return $consulta == true ? mysqli_insert_id($conexion) : false;
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


function EnviarMail($sector, $datos_sector, $bodyHtml, $ccs = null)
{
	$configuracion = [
		"host" => "smtp.gmail.com",
		"port" => 587,
		"username" => "no-responder@vida.com.uy",
		"password" => "2k8.vida",
		"from" => "no-responder@vida.com.uy",
		"fromname" => @utf8_decode(ucfirst($sector)),
	];

	$datos = [
		"email" => $datos_sector['email'],
		"nombre" => @utf8_decode(ucfirst($datos_sector['avisar_a']))
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

	return $mail->send() ? true : $mail->ErrorInfo;
}
