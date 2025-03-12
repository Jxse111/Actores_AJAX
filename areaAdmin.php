<?php
session_start();
if (!isset($_SESSION['usuario']) || $_SESSION['rol'] == "usuario") {
    session_destroy();
    header("location:login.html");
} else {
    echo nl2br("Bienvenido " . $_SESSION['usuario'] . ", " . $_SESSION['rol'] . ".\n");
    if (filter_has_var(INPUT_POST, "eliminar") || filter_has_var(INPUT_POST, "buscar") || filter_has_var(INPUT_POST, "asignar")) {
        header("Location: controladorActor.php");
    }
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>areaAdmin</title>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> <!-- Implementamos la libreria de  JQuery para poder usar sus funciones -->
            <script>
                $(document).ready(function () {  //Nos aseguramos de que el código se ejecute solo después de que el DOM haya cargado completamente
                    $("select[name='seleccionActor']").change(function () {
                        var actorSeleccionado = $(this).val(); // Obtenemos el valor del actor seleccionado

                        // Realizamos la solicitud AJAX para actualizar los supervisores
                        $.ajax({
                            url: "controladorActor.php", //Url a la se le hace la solicitud AJAX
                            method: "POST", //Tipo de solicitud HTTP que en este caso será POST
                            data: {actorSeleccionado: actorSeleccionado}, //Los datos enviados al servidor, en este caso el actor seleccionado
                            success: function (response) { // Si todo funciona  se ejecutará esta función
                                // Llenamos el desplegable de supervisores con las nuevas opciones
                                $("select[name='seleccionSupervisor']").html(response); // Actualiza el contenido del desplegable de supervisores con la respuesta del servidor

                                // Remover la opción del actor seleccionado del desplegable de supervisores
                                $("select[name='seleccionSupervisor'] option").each(function () {
                                    if ($(this).val() == actorSeleccionado) { // Si el valor de la opción es igual al actor seleccionado
                                        $(this).remove(); // Eliminamos la opción que coincide con el actor seleccionado
                                    }
                                });
                            },
                            error: function (xhr, status, error) { //Si falla la solicitud de AJAX se ejecuta esta funcion
                                console.log("Error: " + error); //Mensaje de error en caso de fallo con la solicitud de AJAX
                            }
                        });
                    });
                });

            </script>
        </head>
        <body>
            <?php
            require_once './funcionesValidacion.php';
            require_once './Conexion.php';
            $conexionBD = Conexion::conectarEspectaculosMySQLi();
            try {
                $consultaActor = $conexionBD->query("SELECT cdactor,nombre FROM actor");
                while ($actores = $consultaActor->fetch_assoc()) {
                    $tablaActor[] = $actores;
                }
                Conexion::desconectar();
            } catch (Exception $ex) {
                $mensajeError .= "ERROR: " . $ex->getMessage();
            }
            ?>
            <form action="controladorActor.php" method="post">
                <label>Selecciona el nombre del Actor: </label>
                <select name="seleccionActor">
                    <option value="0">Selecciona un Actor</option>
                    <?php
                    if ($tablaActor) {
                        foreach ($tablaActor as $actoresExistentes) {
                            ?>
                            <option value="<?php echo $actoresExistentes['cdactor'] ?>"><?php echo $actoresExistentes['nombre'] ?></option>
                            <?php
                        }
                    }
                    ?>
                </select><br><br>
                <label>Selecciona el Supervisor: </label>
                <select name="seleccionSupervisor">
                    <option value="0">Selecciona un Supervisor</option>
                    <?php
                    if ($tablaActor) {
                        foreach ($tablaActor as $actoresExistentes) {
                            ?>
                            <option value="<?php echo $actoresExistentes['cdactor'] ?>"><?php echo $actoresExistentes['nombre'] ?></option>
                            <?php
                        }
                    }
                    ?>
                </select><br><br>
                <button type="submit" name="eliminar">Eliminar</button>
                <button type="submit" name="buscar">Buscar</button>
                <button type="submit" name="asignar">Asignar</button>
            </form><br>
            <?php
            include_once './cerrarSesion.html';
        }
        ?>
    </body>
</html>