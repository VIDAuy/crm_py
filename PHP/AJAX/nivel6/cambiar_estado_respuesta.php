<?php
include '../../configuraciones.php';


use PHPMailer\PHPMailer\PHPMailer;

require '../../../assets/lib/PHPMailer/src/PHPMailer.php';
require '../../../assets/lib/PHPMailer/src/SMTP.php';
require '../../../assets/lib/PHPMailer/src/Exception.php';


$area_carga = $_SESSION['usuario_py'];
$nro_carga = $_POST['nro_carga'];
$estado = $_POST['estado'];


$respuesta = $estado == 'cargado' ? 2 : 3;

$conexion = connection(DB);
$update = "UPDATE respuesta_carga_documento SET respuesta='$respuesta', fecha_hora = NOW(), leido = 1 WHERE nro_carga = '$nro_carga'";
$respuesta = mysqli_query($conexion, $update) ? false : true;

if ($respuesta === true) {
  $response['error'] = true;
  $response['mensaje'] = 'Ha ocurrido un error, contacte con el administrador';
  die(json_encode($response));
}


//Enviar Mail
$texto = [
  "titulo" => "Nueva alerta",
  "cabecera" => "Se ha cambiado el estado del documento cargado Nro. " . $nro_carga,
  "detalle1" => "El nuevo estado es: ",
  "detalle2" => $estado,
  "informacion" => "Muchas gracias!"
];
$bodyHtml = htmlBodyEmail($texto);
$email = EnviarMail($area_carga, $bodyHtml);


$response['email'] = $email == true ? true : $email;
$response['error'] = false;
$response['mensaje'] = 'Se ha respondido con exito';



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
                    <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">' . $texto["titulo"] . '</h1>
                    <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">' . $texto["cabecera"] . '</p>
                    <p style="margin:0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">' . $texto["detalle1"] . '<a href="#" style="color: #942f4a;text-decoration:underline;">' . $texto["detalle2"] . '</a></p>
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



function EnviarMail($area_carga, $bodyHtml, $ccs = null)
{
  $configuracion = [
    "host" => "smtp.gmail.com",
    "port" => 587,
    "username" => "no-responder@vida.com.uy",
    "password" => "2k8.vida",
    "from" => "no-responder@vida.com.uy",
    "fromname" => $area_carga,
  ];

  $datos = [
    "email" => "s.nunez@vida.com.uy",
    "nombre" => "Desarrollo"
  ];

  $asunto = "Aviso Para Calidad Interna";

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
  $mail->addReplyTo($configuracion["from"], $configuracion["fromname"]);
  $mail->addAddress($datos["email"], $datos["nombre"]);
  if ($ccs != null) {
    foreach ($ccs as $cc) {
      $mail->addCC($cc["email"], $cc["nombre"]);
    }
  }
  $mail->Body = $bodyHtml;

  if ($mail->send()) {
    return true;
  } else {
    return $mail->ErrorInfo;
  }
}
