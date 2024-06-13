<?php

if (session_status() !== PHP_SESSION_ACTIVE)    session_start();

date_default_timezone_set('America/Montevideo');

define("PATH_APP", __DIR__);

const PRODUCCION     = false; // para definir si es test o producción la APP
const PROTOCOL       = PRODUCCION ? "http" : "http";
const HOST           = PRODUCCION ? "192.168.1.250:82" : "192.168.1.250:82";
const APP            = PRODUCCION ? "crm_py" : "crm_py_test";
const URL_APP        = PROTOCOL . '://' . HOST . '/' . APP;
const URL_DOCUMENTOS = PROTOCOL . '://' . HOST . '/' . APP . '/assets/documentos/registros';

error_reporting(PRODUCCION ? 0 : E_ALL);

//HEADERS
header('Cache-Control: no-cache, must-revalidate');
header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
header('Content-Type: application/json; charset=utf-8');
header('Content-Type: text/html; charset=UTF-8');
header('Access-Control-Allow-Methods: GET, POST');
header('Access-Control-Allow-Origin: *');


const PATH_FUNCIONEs = "modelos/";

//DB Conexiones
include_once PATH_APP . "/db.php";

//FUNCIONES


//LOGS
const LOGS_DIR = PATH_APP . "../logs";

//Utils /Functions
include_once "funciones.php";


// DB PRODUCCION
const DB_CRM_PY_PROD              = array("host" => "192.168.1.250", "user" => "root", "password" => "sist.2k8", "db" => "crm_py");
const DB_ABMMOD_PROD              = array("host" => "localhost", "user" => "root", "password" => "root", "db" => "abmmod");
const DB_COORDINA_PARAGUAY_PROD   = array("host" => "192.168.250.11", "user" => "root", "password" => "sist.2k8", "db" => "coordinaparaguay");
const DB_AFILIACION_PARAGUAY_PROD = array("host" => "192.168.13.10", "user" => "root", "password" => "sist.2k8", "db" => "afiliacionparaguay");
const DB_MOTOR_PRECIOS_PY_PROD    = array("host" => "192.168.13.10", "user" => "root", "password" => "sist.2k8", "db" => "motor_precios_py");



//DEV O DB TEST
const DB_CRM_PY_TEST              = array("host" => "192.168.1.250", "user" => "root", "password" => "sist.2k8", "db" => "crm_py");
const DB_ABMMOD_TEST              = array("host" => "localhost", "user" => "root", "password" => "root", "db" => "abmmod");
const DB_COORDINA_PARAGUAY_TEST   = array("host" => "192.168.250.11", "user" => "root", "password" => "sist.2k8", "db" => "coordinaparaguay");
const DB_AFILIACION_PARAGUAY_TEST = array("host" => "192.168.13.10", "user" => "root", "password" => "sist.2k8", "db" => "afiliacionparaguay");
const DB_MOTOR_PRECIOS_PY_TEST    = array("host" => "192.168.13.10", "user" => "root", "password" => "sist.2k8", "db" => "motor_precios_py");



//BD PROD O TEST
const DB                     = PRODUCCION ? DB_CRM_PY_PROD              : DB_CRM_PY_TEST;
const DB_ABMMOD              = PRODUCCION ? DB_ABMMOD_PROD              : DB_ABMMOD_TEST;
const DB_COORDINA_PARAGUAY   = PRODUCCION ? DB_COORDINA_PARAGUAY_PROD   : DB_COORDINA_PARAGUAY_TEST;
const DB_AFILIACION_PARAGUAY = PRODUCCION ? DB_AFILIACION_PARAGUAY_PROD : DB_AFILIACION_PARAGUAY_TEST;
const DB_MOTOR_PRECIOS_PY    = PRODUCCION ? DB_MOTOR_PRECIOS_PY_PROD    : DB_MOTOR_PRECIOS_PY_TEST;



//TABLAS BD

//SERVER - 250
const TABLA_PADRON_DATOS_SOCIO    = "padron_datos_socio";
const TABLA_PADRON_PRODUCTO_SOCIO = "padron_producto_socio"; //TAMBIÉN ESTA EN CALL
const TABLA_SERVICIOS_CODIGOS     = "servicios_codigos";
const TABLA_BAJAS                 = "bajas_PARAGUAY";
const TABLA_REGISTROS_PY          = "registros_PARAGUAY"; //También esta en vida te llama
const TABLA_USUARIOS              = "usuarios";
const TABLA_HISTORICO_ALERTA      = "historico_alerta_PARAGUAY";
const TABLA_IMAGENES_REGISTRO     = "imagenes_registro";
const TABLA_FILIALES_CODIGOS      = "filiales_codigos";
const TABLA_LOG_ERRORES           = "log_errores";


//SERVER - COORDINACIÓN
const TABLA_PEDIDO_ACOMP = "pedido_acomp";


//SERVER - 1310
const TABLA_COBRANZAS = "cobranzas";
const TABLA_SERVICIOS = "servicios";





//Emails
const EMAIL_PRUEBA     = "s.nunez@vida.com.uy";
const EMAIL_CALIDAD    = "calidad@vida.com.uy";
const EMAIL_BIENVENIDA = "administracion2@vidaparaguay.com.py";
const EMAIL_BAJAS      = "bajas@vida.com.uy";


//MENESAJES 
const ERROR_SESSION_USUARIO = "Error al verificar tu sesión , cierra la sesión y vuelve a ingresar";
const ERROR_GENERAL         = "Ha ocurrido un error, comuniquese con el administrador";
const ERROR_AL_MODIFICAR    = "Error al intentar modificar el registro";
const EXITO_AL_MODIFICAR    = "Se modifico el registro con éxito";
const EXITO_AL_REGISTRAR    = "Se ha registrado con éxito";
