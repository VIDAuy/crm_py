<?php
include '../../configuraciones.php';


$tabla["data"] = [];

$consulta = todas_alertas_respuestas();


while ($resultado = mysqli_fetch_array($consulta)) {

    $respuesta_estado = $resultado["respuesta"];

    if ($respuesta_estado == "Pendiente") {
        $estado = URL_APP . '/assets/img/icono_respuesta_pendiente.png';
    } else if ($respuesta_estado == "Cargado") {
        $estado = URL_APP . '/assets/img/icono_respuesta_cargada.png';
    } else {
        $estado = URL_APP . '/assets/img/icono_respuesta_devuelta.png';
    }

    $fila = "<div class='media text-muted pt-3'>
                
                <img data-src='" . $estado . "' alt='32x32' class='mr-2 rounded' src='" . $estado . "' data-holder-rendered='true' style='width: 32px; height: 32px' />
                
                <div class='media-body pb-3 mb-0 small lh-125 border-bottom border-gray'>
                    
                    <div class='d-flex justify-content-between align-items-center w-100'>
            
                        <strong class='text-gray-dark'>
                
                            <font style='vertical-align: inherit'>
                    
                                <font style='vertical-align: inherit'>Área: " . $resultado['area'] . "</font>
                
                            </font>
                        
                        </strong>
            
                        <a href='#'>
                            
                            <font style='vertical-align: inherit'>
                                
                                <font style='vertical-align: inherit'>Respuesta: " . $resultado['respuesta'] . "</font>
                            
                            </font>
                        
                        </a>
                    
                    </div>

                    <span class='d-block'>
        
                        <font style='vertical-align: inherit'>
                            
                            <font style='vertical-align: inherit'>Nro. Carga: " . $resultado['nro_carga'] . "</font>
            
                        </font>

                    </span>

                </div>

            </div>";



    $tabla["data"][] = [
        "fila" => $fila,
    ];
}



echo json_encode($tabla);









function todas_alertas_respuestas()
{
    $conexion = connection(DB);

    $consulta = mysqli_query($conexion, "SELECT
	c.id,
	c.avisar_a AS 'area',
	c.id AS 'nro_carga',
	e.estado AS 'respuesta' 
    FROM
	carga_documentos AS c,
	respuesta_carga_documento AS r,
	estado_documento AS e 
    WHERE
	c.id = r.nro_carga AND
	r.respuesta = e.id
	ORDER BY c.id DESC");

    return $consulta;
}
