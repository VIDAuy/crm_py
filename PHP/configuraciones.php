<?php

if (session_status() !== PHP_SESSION_ACTIVE)    session_start();

date_default_timezone_set('America/Montevideo');

define("PATH_APP", __DIR__);

const PRODUCCION = true; // para definir si es test o produccion la APP
const URL_APP        = PRODUCCION ? 'http://192.168.1.250:82/crm_py'                             : 'http://192.168.1.250:82/crm_py_test';
const URL_DOCUMENTOS = PRODUCCION ? 'http://192.168.1.250:82/crm_py/assets/documentos/registros' : 'http://192.168.1.250:82/crm_py_test/assets/documentos/registros';

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
include_once "utils.php";
include_once "funciones.php";


// DB PRODUCCION
const DB_CRM_PY_PROD              = array("host" => "192.168.1.250", "user" => "root", "password" => "sist.2k8", "db" => "crm_py");
const DB_ABMMOD_PROD              = array("host" => "localhost", "user" => "root", "password" => "root", "db" => "abmmod");
const DB_STATUS_PROD              = array("host" => "192.168.250.11", "user" => "root", "password" => "sist.2k8", "db" => "status");
const DB_COORDINA_PARAGUAY_PROD   = array("host" => "192.168.250.11", "user" => "root", "password" => "sist.2k8", "db" => "coordinaparaguay");
const DB_AFILIACION_PARAGUAY_PROD = array("host" => "192.168.13.10", "user" => "root", "password" => "sist.2k8", "db" => "afiliacionparaguay");
const DB_COMAG_PROD               = array("host" => "192.168.13.10", "user" => "root", "password" => "sist.2k8", "db" => "comag");
const DB_COORDINACION_1310_PROD   = array("host" => "192.168.13.10", "user" => "root", "password" => "sist.2k8", "db" => "coordinacion");
const DB_BRASIL_PROD              = array("host" => "192.168.13.10", "user" => "root", "password" => "sist.2k8", "db" => "brasil");
const DB_MOODLE_VIDA_PROD         = array("host" => "192.168.252.20", "user" => "consultas", "password" => "2k8.vida", "db" => "moodle_vida");
const DB_COORDINACION_PROD        = array("host" => "192.168.250.11", "user" => "root", "password" => "sist.2k8", "db" => "coordinacion");
const DB_COORDINACOMP_PROD        = array("host" => "192.168.1.250", "user" => "root", "password" => "sist.2k8", "db" => "coordinacomp");
const DB_CALL_PROD                = array("host" => "192.168.1.13", "user" => "root", "password" => "sist.2k8", "db" => "call");
const DB_CONTROL_UNIFORMES_PROD   = array("host" => "192.168.1.250", "user" => "root", "password" => "sist.2k8", "db" => "control_uniformes");
const DB_VIDA_TE_LLEVA_PROD       = array("host" => "192.168.13.10", "user" => "root", "password" => "sist.2k8", "db" => "vida_te_lleva");
const DB_COBRA_PROD               = array("host" => "192.168.250.11", "user" => "root", "password" => "sist.2k8", "db" => "cobra");
const DB_MOTOR_PRECIOS_PY_PROD    = array("host" => "192.168.13.10", "user" => "root", "password" => "sist.2k8", "db" => "motor_precios_py");



//DEV O DB TEST
const DB_CRM_PY_TEST              = array("host" => "192.168.1.250", "user" => "root", "password" => "sist.2k8", "db" => "crm_py_test");
const DB_ABMMOD_TEST              = array("host" => "localhost", "user" => "root", "password" => "root", "db" => "abmmod");
const DB_STATUS_TEST              = array("host" => "192.168.250.11", "user" => "root", "password" => "sist.2k8", "db" => "status");
const DB_COORDINA_PARAGUAY_TEST   = array("host" => "192.168.250.11", "user" => "root", "password" => "sist.2k8", "db" => "coordinaparaguay");
const DB_AFILIACION_PARAGUAY_TEST = array("host" => "192.168.13.10", "user" => "root", "password" => "sist.2k8", "db" => "afiliacionparaguay");
const DB_COMAG_TEST               = array("host" => "192.168.13.10", "user" => "root", "password" => "sist.2k8", "db" => "comag");
const DB_COORDINACION_1310_TEST   = array("host" => "192.168.13.10", "user" => "root", "password" => "sist.2k8", "db" => "coordinacion");
const DB_BRASIL_TEST              = array("host" => "192.168.13.10", "user" => "root", "password" => "sist.2k8", "db" => "brasil");
const DB_MOODLE_VIDA_TEST         = array("host" => "192.168.252.20", "user" => "consultas", "password" => "2k8.vida", "db" => "moodle_vida");
const DB_COORDINACION_TEST        = array("host" => "192.168.250.11", "user" => "root", "password" => "sist.2k8", "db" => "coordinacion");
const DB_COORDINACOMP_TEST        = array("host" => "192.168.1.250", "user" => "root", "password" => "sist.2k8", "db" => "coordinacomp");
const DB_CALL_TEST                = array("host" => "192.168.1.13", "user" => "root", "password" => "sist.2k8", "db" => "call");
const DB_CONTROL_UNIFORMES_TEST   = array("host" => "192.168.1.250", "user" => "root", "password" => "sist.2k8", "db" => "control_uniformes_test");
const DB_VIDA_TE_LLEVA_TEST       = array("host" => "192.168.13.10", "user" => "root", "password" => "sist.2k8", "db" => "vida_te_lleva");
const DB_COBRA_TEST               = array("host" => "192.168.250.11", "user" => "root", "password" => "sist.2k8", "db" => "cobra");
const DB_MOTOR_PRECIOS_PY_TEST    = array("host" => "192.168.13.10", "user" => "root", "password" => "sist.2k8", "db" => "motor_precios_py");


//BD PROD O TEST
const DB                     = PRODUCCION ? DB_CRM_PY_PROD              : DB_CRM_PY_TEST;
const DB_ABMMOD              = PRODUCCION ? DB_ABMMOD_PROD              : DB_ABMMOD_TEST;
const DB_STATUS              = PRODUCCION ? DB_STATUS_PROD              : DB_STATUS_TEST;
const DB_COORDINA_PARAGUAY   = PRODUCCION ? DB_COORDINA_PARAGUAY_PROD   : DB_COORDINA_PARAGUAY_TEST;
const DB_AFILIACION_PARAGUAY = PRODUCCION ? DB_AFILIACION_PARAGUAY_PROD : DB_AFILIACION_PARAGUAY_TEST;
const DB_COMAG               = PRODUCCION ? DB_COMAG_PROD               : DB_COMAG_TEST;
const DB_COORDINACION_1310   = PRODUCCION ? DB_COORDINACION_1310_PROD   : DB_COORDINACION_1310_TEST;
const DB_BRASIL              = PRODUCCION ? DB_BRASIL_PROD              : DB_BRASIL_TEST;
const DB_MOODLE_VIDA         = PRODUCCION ? DB_MOODLE_VIDA_PROD         : DB_MOODLE_VIDA_TEST;
const DB_COORDINACION        = PRODUCCION ? DB_COORDINACION_PROD        : DB_COORDINACION_TEST;
const DB_COORDINACOMP        = PRODUCCION ? DB_COORDINACOMP_PROD        : DB_COORDINACOMP_TEST;
const DB_CALL                = PRODUCCION ? DB_CALL_PROD                : DB_CALL_TEST;
const DB_CONTROL_UNIFORMES   = PRODUCCION ? DB_CONTROL_UNIFORMES_PROD   : DB_CONTROL_UNIFORMES_TEST;
const DB_VIDA_TE_LLEVA       = PRODUCCION ? DB_VIDA_TE_LLEVA_PROD       : DB_VIDA_TE_LLEVA_TEST;
const DB_COBRA               = PRODUCCION ? DB_COBRA_PROD               : DB_COBRA_TEST;
const DB_MOTOR_PRECIOS_PY    = PRODUCCION ? DB_MOTOR_PRECIOS_PY_PROD    : DB_MOTOR_PRECIOS_PY_TEST;


//TABLAS BD

//SERVER - 250
const TABLA_SERVICIOS_PARAGUAY_CRM     = "servicios_paraguay";
const TABLA_RADIOS                     = "radios";
const TABLA_PADRON_ACTUAL              = "padron_actual";
const TABLA_PADRON_DATOS_SOCIO         = "padron_datos_socio";
const TABLA_PADRON_PRODUCTO_SOCIO      = "padron_producto_socio"; //TAMBIÉN ESTA EN CALL
const TABLA_FILIALES_CODIGOS           = "filiales_codigos";
const TABLA_EMPRESA                    = "empresa";
const TABLA_CARGA_DOCUMENTOS           = "carga_documentos";
const TABLA_RESPUESTA_CARGA_DOCUMENTO  = "respuesta_carga_documento";
const TABLA_ESTADO_DOCUMENTO           = "estado_documento";
const TABLA_TIPO_DOCUMENTO             = "tipo_documento";
const TABLA_SERVICIOS_CODIGOS          = "servicios_codigos";
const TABLA_BAJAS                      = "bajas_PARAGUAY";
const TABLA_REGISTROS_PY               = "registros_PARAGUAY"; //También esta en vida te llama
const TABLA_REGISTROS                  = "registros"; //También esta en vida te llama
const TABLA_USUARIOS                   = "usuarios";
const TABLA_REGISTROS_FUNCIONARIOS     = "registros_funcionarios";
const TABLA_PRECIO_UNIFORME            = "precio_uniforme";
const TABLA_UNI_COMENTARIO             = "uni_comentario";
const TABLA_UNI_NO_DEVUELTO            = "uni_no_devuelto";
const TABLA_HISTORICO_ALERTA           = "historico_alerta_PARAGUAY";
const TABLA_IMAGENES_REGISTRO          = "imagenes_registro";


//SERVER - COORDINACIÓN
const VISTA_SERVICIOS                   = "vista_servicios";
const TABLA_PEDIDO_DERECHOS_SERVICIOS   = "pedido_derechos_servicios";
const TABLA_PEDIDO_DERECHO              = "pedido_derecho";
const TABLA_PEDIDO_ACOMP                = "pedido_acomp";
const TABLA_SERVICIOS_NEW               = "servicios_new";
const TABLA_HORARIOS                    = "horarios";
const TABLA_PADRON_DATOS_SOCIO_TEMPORAL = "padron_datos_socio_temporal";
const TABLA_PADRON_SOCIOS_COMAG         = "padron_socios_comag";
const TABLA_ACOMPANANTES_NODUM          = "acompanantes_nodum";
const TABLA_COBRADO                     = "cobrado";
const TABLA_ACOMPANANTES                = "acompanantes"; //También esta en control de uniformes
const TABLA_ACOMPANANTES_DESCANSOS      = "acompanantes_descansos";
const TABLA_CIERRE_HR3                  = "cierrehr3";
const TABLA_HORAS_FICTAS                = "horas_fictas";
const TABLA_FILIALES_CODIGOS_TEMPORAL   = "filiales_codigos_temporal";
const TABLA_EMPRESAS_TEMPORAL           = "empresas_temporal";
const TABLA_TIPO_PRODUCTO_TEMPORAL      = "tipo_productos_temporal";


//SERVER - 1310
const TABLA_PADRON_SOCIOS_BRASIL = "padron_socios_brasil";
const TABLA_PADRON_COMAG         = "padron_comag";
const TABLA_COBRANZAS            = "cobranzas";
const TABLA_SERVICIOS            = "servicios";


//SERVER - MOODLE
const TABLA_MDL_USER          = "mdl_user";
const TABLA_MDL_QUIZ_ATTEMPTS = "mdl_quiz_attempts";


//SERVER - NODUM
const VISTA_TRABAJADOR        = "v_RHTrabajador"; //TAMBIÉN ESTA EN NODUM/MODELO_RSE
const VISTA_SECCIONES         = "v_RHSecciones";
const TABLA_TRABAJADOR        = "ct_RHTrabajador";
const TABLA_EMPRESAS          = "ct_empresas";
const TABLA_DEPARTAMENTOS     = "ct_dptos";
const TABLA_SECCIONES         = "ct_RHSecciones";
const TABLA_PLAN_COMIS_X_TRAB = "ct_rhPlanComXTr";
const TABLA_PLAN_COMIS        = "ct_rhPlanComis";
const TABLA_SUPER_TRAB        = "ct_rhsupervxtrab";
const TABLA_TELEFONOS         = "ct_RHNrosTelxPer";
const TABLA_CARGOS            = "ct_RHCargos";
const TABLA_COMBOS            = "ct_RHCombos";
const TABLA_BANCOS            = "ct_bancos";
const TABLA_EMAIL             = "ct_RHEmailsxPers";
const TABLA_CPT_LICENCIAS     = "cpt_RHLicencias";
const TABLA_TIPO_TRAB         = "ct_RHTiposTrab";


//SERVER - NODUM/MODELO_RSE
const TABLA_CPP_LICENCIAS = "cpp_RHLicencias";




//HASH o TOKEN
include_once "token.php";


//Emails
const EMAIL_PRUEBA     = "s.nunez@vida.com.uy";
const EMAIL_CALIDAD    = "calidad@vida.com.uy";
const EMAIL_BIENVENIDA = "administracion2@vidaparaguay.com.py";
const EMAIL_BAJAS      = "bajas@vida.com.uy";


//MENESAJES 
const ERROR_PERMISOS        =  ["success" => false, "mensaje" => "Usted no cuenta con dichos permisos para ejecutar la operación", "permisos" => false];
const ERROR_LOGIN           = "Error de usuario o contraseña";
const ERROR_SESSION_USUARIO = "Error al verificar tu sesión , cierra la sesión y vuelve a ingresar";
const ERROR_GENERAL         = "Ha ocurrido un error, comuniquese con el administrador";
const ERROR_AL_MODIFICAR    = "Error al intentar modificar el registro";
const EXITO_AL_MODIFICAR    = "Se modifico el registro con éxito";
const EXITO_AL_REGISTRAR    = "Se ha registrado con éxito";
const SIN_REGISTROS         = "No se han encontrado registros";
const ERROR_CREAR_TABLA     = "Error al crear la tabla";
const ERROR_VACIAR_TABLA    = "Error al vaciar la tabla";


//ERRORES
const ERROR_CONSULTA_GENERAL     = "Error en la consulta general";
const ERROR_CONSULTA_TELEFONO    = "Error en la consulta telefono";
const ERROR_CONSULTA_MAIL        = "Error en la consulta mail";
const ERROR_CONSULTA_EMPRESA     = "Error en la consulta empresa";
const ERROR_CONSULTA_CARGO       = "Error en la consulta cargo";
const ERROR_CONSULTA_SECCION     = "Error en la consulta seccion";
const ERROR_CONSULTA_TIPO_COMIS  = "Error en la consulta tipo comisionamiento";
const ERROR_CONSULTA_FILIAL      = "Error en la consulta filiales";
const ERROR_CONSULTA_TIPO_TRAB   = "Error en la consulta tipo trabajador";
const ERROR_CONSULTA_CAUSAL_BAJA = "Error en la consulta causal baja";
const ERROR_CONSULTA_MEDIO_PAGO  = "Error en la consulta medio de pago";
