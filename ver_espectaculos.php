<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "invitado") {
    session_destroy();
    header("location:login.html");
} else {
    echo nl2br("Bienvenido " . $_SESSION['usuario'] . ", " . $_SESSION['rol']);
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Indice</title>
        </head>
        <body>
            <?php
            require_once './Conexion.php';
            $conexionBD = Conexion::conectarEspectaculosMySQLi();
            $espectaculos = [];
            try {
                $consultaPreparadaTablaEspectaculosEstrellas = $conexionBD->stmt_init();
                $consultaPreparadaTablaEspectaculosEstrellas->prepare('SELECT nombre, estrellas FROM espectaculo ORDER BY estrellas DESC;');
                $consultaPreparadaTablaEspectaculosEstrellas->execute();
                $resultado = $consultaPreparadaTablaEspectaculosEstrellas->get_result();

                // Guardamos los resultados en el array de espectaculos
                while ($datosConsultaEspectaculosOrdenadosPorEstrellas = $resultado->fetch_assoc()) {
                    $espectaculosOrdenadosPorEstrellas[] = $datosConsultaEspectaculosOrdenadosPorEstrellas;
                }
                try {
                    $consultaPreparadaTablaEspectaculosTipo = $conexionBD->stmt_init();
                    $consultaPreparadaTablaEspectaculosTipo->prepare('SELECT nombre, tipo FROM espectaculo ORDER BY tipo DESC;');
                    $consultaPreparadaTablaEspectaculosTipo->execute();
                    $resultadoTipo = $consultaPreparadaTablaEspectaculosTipo->get_result();

                    // Guardamos los resultados en el array de espectaculos
                    while ($datosConsultaEspectaculosOrdenadosPorTIpo = $resultadoTipo->fetch_assoc()) {
                        $espectaculosOrdeandosPorTIpo[] = $datosConsultaEspectaculosOrdenadosPorTIpo;
                    }

                    if ($espectaculosOrdenadosPorEstrellas) {  // Verificamos que el array tenga contenido
                        ?>
                        <h1>Espectáculos ordenados por estrellas</h1>
                        <table border="1">
                            <?php
                            $contador = 0;
                            // Imprimimos los encabezados de la tabla solo una vez
                            if ($contador == 0) {
                                ?>
                                <tr>
                                    <?php
                                    // El ciclo foreach recorre el primer elemento del array para obtener las claves (encabezados)
                                    foreach ($espectaculosOrdenadosPorEstrellas[0] as $claveEspectaculos => $valorEspectaculos) {
                                        ?>
                                        <th><?php echo ($claveEspectaculos); ?></th>
                                    <?php } ?>
                                </tr>
                                <?php
                            }
                            // Imprimimos las filas de la tabla
                            foreach ($espectaculosOrdenadosPorEstrellas as $espectaculo) {
                                ?>
                                <tr>
                                    <?php foreach ($espectaculo as $valorEspectaculos) { ?>
                                        <td><?php echo ($valorEspectaculos); ?></td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                            <?php $contador++; ?>
                        </table><br><br>
                        <?php
                    } else {
                        $mensajeError .= "No existen espectáculos con esas características.";
                    }
                    if ($espectaculosOrdeandosPorTIpo) {
                        ?>
                        <h1>Espectáculos ordenados por tipo</h1>
                        <table border="1">
                            <?php
                            $contador = 0;
                            // Imprimimos los encabezados de la tabla solo una vez
                            if ($contador == 0) {
                                ?>
                                <tr>
                                    <?php
                                    // El ciclo foreach recorre el primer elemento del array para obtener las claves (encabezados)
                                    foreach ($espectaculosOrdeandosPorTIpo[0] as $claveEspectaculoTipo => $valorEspectaculosTipo) {
                                        ?>
                                        <th><?php echo ($claveEspectaculoTipo); ?></th>
                                    <?php } ?>
                                </tr>
                                <?php
                            }
                            // Imprimimos las filas de la tabla
                            foreach ($espectaculosOrdeandosPorTIpo as $espectaculoTipo) {
                                ?>
                                <tr>
                                    <?php foreach ($espectaculoTipo as $valorEspectaculosTipo) { ?>
                                        <td><?php echo ($valorEspectaculosTipo); ?></td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                            <?php $contador++; ?>
                        </table><br><br>
                        <?php
                    } else {
                        $mensajeError .= "No existen espectáculos con esas características.";
                    }
                    $consultaPreparadaTablaEspectaculosEstrellas->close();
                    $consultaPreparadaTablaEspectaculosTipo->close();
                    Conexion::desconectar();
                } catch (Exception $ex) {
                    echo "ERROR: " . $ex->getMessage();
                    $mensajeError .= "ERROR: " . $ex->getMessage();
                }
            } catch (Exception $ex) {
                echo "ERROR: " . $ex->getMessage();
                $mensajeError .= "ERROR: " . $ex->getMessage();
            }
            include_once './cerrarSesion.html';
        }
        ?>
        <h2>Lista de mensajes:</h2>
        <ul>
            <li><?php
                if (isset($mensajeError)) {
                    echo $mensajeError;
                }
                ?></li>
        </ul>

    </body>
</html>
