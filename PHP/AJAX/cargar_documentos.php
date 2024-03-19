<?php
include '../configuraciones.php';


use PHPMailer\PHPMailer\PHPMailer;

require '../../assets/lib/PHPMailer/src/PHPMailer.php';
require '../../assets/lib/PHPMailer/src/SMTP.php';
require '../../assets/lib/PHPMailer/src/Exception.php';


$area_carga = $_SESSION['usuario_py'];
$tipo_documento = $_POST['tipo_documento'];
$texto_tipo_documento = $_POST['texto_tipo_documento'];
$area = $_POST['area'];
$documento = $_FILES['documento'];


$cargar_documento = subir_documento($tipo_documento, $area, $documento, $area_carga);


if ($cargar_documento === false) {
  $response['error'] = true;
  $response['mensaje'] = 'Ha ocurrido un error, contacte al administrador';
  die(json_encode($response));
}

if ($area != 'Sin avisar') {
  //Enviar Mail
  $texto = [
    "titulo" => "Nueva alerta",
    "cabecera" => "Se ha cargado un nuevo documento para registrar",
    "detalle1" => "El tipo de documento es: ",
    "detalle2" => $texto_tipo_documento,
    "informacion" => "Muchas gracias!"
  ];
  $bodyHtml = htmlBodyEmail($texto);
  $email = EnviarMail($area_carga, $bodyHtml);
  $response['email'] = $email == true ? true : $email;
}

$response['error'] = false;
$response['mensaje'] = 'Se ha cargado el documento con exito!';


//Devolver respuesta
echo json_encode($response);




function subir_documento($tipo_documento, $area, $documento, $area_carga)
{
  $conexion = connection(DB);

  $extension_archivo = strtolower(pathinfo(basename($documento["name"]), PATHINFO_EXTENSION));
  $nombre_archivo =  generarHash(20) . '.' . $extension_archivo;
  $ruta_origen = $documento["tmp_name"];

  $destino = "../../assets/documentos/" . $nombre_archivo;
  $destinoBD = URL_APP . "assets/documentos/" . $nombre_archivo;
  //die(var_dump($destino));

  if (move_uploaded_file($ruta_origen, $destino)) {
    $insert_carga = "INSERT INTO carga_documentos (tipo_documento, ruta_documento, avisar_a, carga, fecha_hora) VALUES ('$tipo_documento', '$destinoBD', '$area', '$area_carga', NOW())";
    $respuesta_carga = mysqli_query($conexion, $insert_carga) ? true : false;

    if ($respuesta_carga === true) {
      $consulta = mysqli_query($conexion, "SELECT id FROM carga_documentos WHERE ruta_documento = '$destinoBD' AND tipo_documento = '$tipo_documento'");
      $id = mysqli_fetch_array($consulta);
      $nro_carga = intval($id['id']);

      $insert_estado = "INSERT INTO respuesta_carga_documento (nro_carga, respuesta, fecha_hora, leido) VALUES ('$nro_carga', 1, NOW(), 2)";
      $respuesta_estado = mysqli_query($conexion, $insert_estado) ? true : false;

      $respuesta = $respuesta_carga == true && $respuesta_estado == true ? true : false;
    } else {
      $respuesta = false;
    }
  } else {
    $respuesta = false;
  }

  return $respuesta;
}

function htmlBodyEmail($texto)
{

  $html = '
    <!DOCTYPE html>
<html lang="es" xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office">
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

  $asunto = "Aviso Para Personal";

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
