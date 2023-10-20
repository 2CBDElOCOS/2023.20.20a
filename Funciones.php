/**
 * Realiza una consulta a la base de datos y retorna los resultados de la tabla de usuarios como una cadena HTML.
 *
 * @param string $u El ID de usuario a autenticar.
 * @param string $s La clave del usuario a autenticar.
 * @param int $c Parámetro que determina el tipo de consulta. Si es 1, se obtienen datos de usuarios; si es diferente de 1, se obtiene el conteo.
 * @param int|null $limit Límite de resultados a obtener.
 * @param string|null $columnas Columnas a seleccionar en la consulta. Si no se especifican, se seleccionan todas las columnas.
 *
 * @return string Devuelve una cadena HTML con los resultados de la consulta.
 */
function autenticar($u = null, $s = null, $c = 1, $limit = null, $columnas = null) {
    // Conectar a la base de datos
    $conexion = mysqli_connect('localhost', 'root', 'root', 'practica_03');

    if (!$conexion) {
        return "Error al conectar a la base de datos";
    }

    if ($c != 1) {
        // Consulta para obtener el conteo de usuarios
        $sql = "SELECT COUNT(*) AS num_usuarios FROM tb_personas";
    } else {
        if ($u !== null && $s !== null) {
            $s = mysqli_real_escape_string($conexion, $s); // Evita SQL injection
            $columnas_select = $columnas ? $columnas : '*';
            // Consulta para obtener datos de usuarios con ID y clave específicos
            $sql = "SELECT $columnas_select FROM tb_personas WHERE id_usuario = '$u' AND BINARY Clave = '$s'";
        } elseif ($u !== null) {
            $columnas_select = $columnas ? $columnas : 'id_usuario, Nombre, Sitio, Invitacion';
            // Consulta para obtener datos de usuarios con ID específico
            $sql = "SELECT $columnas_select FROM tb_personas WHERE id_usuario = '$u'";
        } else {
            $columnas_select = $columnas ? $columnas : '*';
            // Consulta para obtener todos los datos de usuarios
            $sql = "SELECT $columnas_select FROM tb_personas";
        }

        // Aplicar el límite si se proporciona
        if ($limit !== null) {
            $sql .= " LIMIT $limit";
        }
    }

    // Ejecutar la consulta
    $resultado = $conexion->query($sql);

    if ($resultado) {
        if ($c != 1) {
            // Si se solicitó el conteo, mostrar el número de usuarios
            $row = $resultado->fetch_assoc();
            return 'El número de usuarios existentes son: ' . $row['num_usuarios'];
        } else {
            $salida = '';

            while ($fila = mysqli_fetch_assoc($resultado)) {
                // Mostrar los datos de los usuarios
                foreach ($fila as $clave => $valor) {
                    $salida .= "$clave: $valor <br>";
                }
                $salida .= '<br>';
            }

            return $salida;
        }
    } else {
        return "Error al ejecutar la consulta";
    }
}
