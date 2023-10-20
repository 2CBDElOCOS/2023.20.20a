<?php

function consulta(){
    $Salida = ""; // Se inicializa la variable como una cadena vacía
    $conexion = mysqli_connect('localhost', 'root', 'root', 'practica_03'); // Se establece la conexión a la base de datos

    $sql = "SELECT * FROM tb_Personas;"; // Consulta SQL
    $resultado = $conexion->query($sql); // Ejecuta la consulta

    // Recorre el recordset
    while ($fila = mysqli_fetch_array($resultado)) {
        $Salida .= $fila[0] .'<br>'; // Concatena el valor de la columna 0 a $Salida
        $Salida .= $fila[1] .'<br>'; // Concatena el valor de la columna 1 a $Salida
        $Salida .= $fila[2] .'<br>'; // Concatena el valor de la columna 2 a $Salida
        $Salida .= $fila[3] .'<br>'; // Concatena el valor de la columna 3 a $Salida
        $Salida .= '<br>'; // Agrega un salto de línea adicional
    }
    $conexion->close(); // Se finaliza la conexión

    return $Salida; // Retorna la salida con los resultados
}
