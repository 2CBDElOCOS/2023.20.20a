<?php
/**
 * Realiza una consulta a la base de datos y retorna los resultados de la tabla de usuarios como una cadena HTML.
 * @param  $U (Opcional) El ID del usuario para filtrar los resultados. Evita problemas de SQL injection al usar este parámetro.
 * @return Texto Devuelve una cadena HTML con los resultados de la consulta.
 */
function consulta($U = null)
{
    $Salida = ""; // Se inicializa la variable como una cadena vacía
    $conexion = mysqli_connect('localhost', 'root', 'root', 'practica_03'); // Se establece la conexión a la base de datos

    $sql = "SELECT id_usuario, Nombre, Sitio, Invitacion FROM tb_personas"; // Consulta SQL

    if ($U != null) {
        $U = mysqli_real_escape_string($conexion, $U); // Evita problemas de SQL injection
        $sql .= " WHERE id_usuario = '$U'";
    }

    $resultado = $conexion->query($sql); // Ejecuta la consulta

    // Recorre el recordset
    while ($fila = mysqli_fetch_assoc($resultado)) {
        $Salida .= $fila['id_usuario'] . '<br>'; // Concatena el valor de la columna id_usuario a $Salida
        $Salida .= $fila['Nombre'] . '<br>'; // Concatena el valor de la columna Nombre a $Salida
        $Salida .= $fila['Sitio'] . '<br>'; // Concatena el valor de la columna Sitio a $Salida
        $Salida .= $fila['Invitacion'] . '<br>'; // Concatena el valor de la columna Invitacion a $Salida
        $Salida .= '<br>'; // Agrega un salto de línea adicional
    }
    $conexion->close(); // Se finaliza la conexión

    return $Salida; // Retorna la salida con los resultados
}
/**
 * Realiza una consulta a la base de datos y retorna los resultados de la tabla de usuarios como una cadena HTML.
 *
 * @param  $u El ID de usuario a autenticar.
 * @param  $s La clave del usuario a autenticar.
 * @return Texto Devuelve una cadena HTML con los resultados de la consulta.
 */

 function autenticar($u = null, $s = null, $c = 1, $limit = null, $columnas = null) {
    $conexion = mysqli_connect('localhost', 'root', 'root', 'practica_03');

    if (!$conexion) {
        return "Error al conectar a la base de datos";
    }

    if ($c != 1) {
        $sql = "SELECT COUNT(*) AS num_usuarios FROM tb_personas";
    } else {
        if ($u !== null && $s !== null) {
            $s = mysqli_real_escape_string($conexion, $s); // Evita SQL injection
            $columnas_select = $columnas ? $columnas : '*';
            $sql = "SELECT $columnas_select FROM tb_personas WHERE id_usuario = '$u' AND BINARY Clave = '$s'";
        } elseif ($u !== null) {
            $columnas_select = $columnas ? $columnas : 'id_usuario, Nombre, Sitio, Invitacion';
            $sql = "SELECT $columnas_select FROM tb_personas WHERE id_usuario = '$u'";
        } else {
            $columnas_select = $columnas ? $columnas : '*';
            $sql = "SELECT $columnas_select FROM tb_personas";
        }

        // Aplicar el límite si se proporciona
        if ($limit !== null) {
            $sql .= " LIMIT $limit";
        }
    }

    $resultado = $conexion->query($sql);

    if ($resultado) {
        if ($c != 1) {
            $row = $resultado->fetch_assoc();
            return 'El número de usuarios existentes son: ' . $row['num_usuarios'];
        } else {
            $salida = '';

            if ($columnas) {
                $columnas_array = explode(',', $columnas);
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    foreach ($columnas_array as $columna) {
                        $salida .= "$columna: " . $fila[trim($columna)] . "<br>";
                    }
                    $salida .= '<br>';
                }
            } else {
                while ($fila = mysqli_fetch_assoc($resultado)) {
                    foreach ($fila as $clave => $valor) {
                        $salida .= "$clave: $valor <br>";
                    }
                    $salida .= '<br>';
                }
            }

            return $salida;
        }
    } else {
        return "Error al ejecutar la consulta";
    }
}
