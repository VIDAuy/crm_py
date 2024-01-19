<?php
    include '../../configuraciones.php';
    $conexion = connection(DB);
    $tabla = TABLA_REGISTROS_PY;

    
    $mysqli = new mysqli($dbhost, $dbusuario, $dbpassword, $db);
    if (isset($_POST['ci']))
    {
        $cedula = $_POST['ci'];
        $q = "SELECT id, fecha_registro, sector, observaciones, socio
                FROM {$tabla}
                WHERE cedula = $cedula";
        $r = $mysqli->query($q);
        if ($r->num_rows != 0)
        {
            $respuesta = array(
                'correcto'  => true,
                'registros' => true
            );
            while ($row = $r->fetch_assoc())
            {
                $row['fecha_registro']  = new DateTime($row['fecha_registro']);
                $row['fecha_registro']  = $row['fecha_registro']->format('d/m/Y H:i');
                if (mb_strlen($row['observaciones'], 'UTF-8') > 25) $row['observaciones']   = mb_substr($row['observaciones'], 0, 25, 'UTF-8') . ' ' . '(...)';
                $row['socio'] = ($row['socio'] == 1)
                    ? 'Sí'
                    : 'No';
                $respuesta['f'][] = $row;
            }
        }
        else
        {
            $respuesta = array(
                'error'         => true,
                'sinRegistros'  => true,
                'mensaje'       => 'El usuario no tiene datos en el CRM'
            );
        }
    }
    else if(isset($_POST['id']))
    {
        if ($_POST['menosInfo'] == 1)
        {
            $id = $_POST['id'];
            $q = "SELECT observaciones
                    FROM {$tabla}
                    WHERE id = $id";
            if($r = $mysqli->query($q))
            {
                $f = $r->fetch_assoc();
                $respuesta['correcto'] = true;
                if(mb_strlen($f['observaciones'], 'UTF-8') > 25) $respuesta['observacion'] = mb_substr($f['observaciones'], 0, 25, 'UTF-8') . ' ' . '(...)';
                else $respuesta['observacion'] = $f['observaciones'];
            }
            else
            {
                $respuesta = array(
                    'error' => true,
                    'mensaje' => 'Ha ocurrido un error, por favor contactese con el administrador'
                );
            }
        }
        else
        {
            $id = $_POST['id'];
            $q = "SELECT observaciones
                    FROM {$tabla}
                    WHERE id = $id";
            if($r = $mysqli->query($q))
            {
                $f = $r->fetch_assoc();
                $respuesta['correcto'] = true;
                $respuesta['observacion'] = $f['observaciones'];
            }
            else
            {
                $respuesta = array(
                    'error' => true,
                    'mensaje' => 'Ha ocurrido un error, por favor contactese con el administrador'
                );
            }
        }
    }

    $mysqli->close();
    echo json_encode($respuesta);