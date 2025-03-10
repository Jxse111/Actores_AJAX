<?php
session_start();
$mensajeExito = "Listado mensajes de exito: ";
$mensajeError = "Listado de mensajes de error: ";
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] != "usuario") {
    session_destroy();
    header("location:login.html");
} else {
    echo nl2br("Bienvenido " . $_SESSION['usuario'] . ", " . $_SESSION['rol'] . ".\n");
    if (filter_has_var(INPUT_POST, "reservar")) {
        $mensajeExito .= nl2br("Espectaculo reservado.\n\n");
    }
    ?>
    <!DOCTYPE html>
    <!--
    Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
    Click nbfs://nbhost/SystemFileSystem/Templates/Project/PHP/PHPProject.php to edit this template
    -->
    <html>
        <head>
            <meta charset="UTF-8">
            <title></title>
        </head>
        <body>
            <?php
            require_once './Conexion.php';
            require_once './funcionesValidacion.php';
            $conexionBD = Conexion::conectarEspectaculosMySQLi();
            try {
                $consultaReserva = $conexionBD->query("SELECT nombre FROM espectaculo");
                while ($espectaculos = $consultaReserva->fetch_assoc()) {
                    $tablaReserva[] = $espectaculos;
                }
            } catch (Exception $ex) {
                $mensajeError .= "ERROR: " . $ex->getMessage();
            }
            ?>
            <form action="<?php echo $_SERVER["PHP_SELF"] ?>" method="post">
                <label>Selecciona el nombre del espectáculo que quieres reservar: </label>
                <select name="reserva">
                    <option value="0">Selecciona un espectáculo</option>
                    <?php
                    if ($tablaReserva) {
                        foreach ($tablaReserva as $reservaEspectaculos) {
                            foreach ($reservaEspectaculos as $espectaculo) {
                                ?>
                                <option value = "<?php echo $espectaculo ?>"><?php echo $espectaculo ?></option>
                                <?php
                            }
                        }
                    }
                    ?>
                </select><br><br>
                <button type="submit" name="reservar">Reservar</button>
            </form><br>
            <?php
            include_once './cerrarSesion.html';
        }
        ?>
        <h2>Lista de mensajes:</h2>
        <ul>
            <li><?php echo $mensajeExito; ?></li>
            <li><?php echo $mensajeError; ?></li>
        </ul>

    </body>
</html>
