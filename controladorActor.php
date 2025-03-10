<?php

require_once './Actor.php';
session_start();
if (isset($_SESSION['usuario']) && $_SESSION['rol'] == "administrador") {
    echo nl2br("Bienvenido " . $_SESSION['usuario'] . ", " . $_SESSION['rol'] . ".\n");

    // Manejar solicitud AJAX para obtener supervisores disponibles
    if (isset($_POST['actorSeleccionado'])) {
        // Obtener y sanitizar el valor de actorSeleccionado
        $actorSeleccionado = filter_var($_POST['actorSeleccionado'], FILTER_SANITIZE_NUMBER_INT);

        // Verificar que el valor de actorSeleccionado sea un número válido
        if (!is_numeric($actorSeleccionado)) {
            echo "Error: El actor seleccionado no es válido.";
            exit; // Detener la ejecución si el valor no es válido
        }

        require_once './Conexion.php';
        $conexionBD = Conexion::conectarEspectaculosMySQLi();

        try {
            // Aquí obtenemos todos los actores excepto el seleccionado
            $consultaSupervisor = $conexionBD->query("SELECT cdactor, nombre FROM actor WHERE cdactor != $actorSeleccionado");

            // Verificar si la consulta devuelve resultados
            if ($consultaSupervisor->num_rows > 0) {
                echo "<option value='0'>Selecciona un Supervisor</option>";
                while ($supervisor = $consultaSupervisor->fetch_assoc()) {
                    // Mostrar supervisores, excluyendo al actor seleccionado
                    echo "<option value='" . $supervisor['cdactor'] . "'>" . $supervisor['nombre'] . "</option>";
                }
            } else {
                echo "<option value='0'>No hay supervisores disponibles</option>";
            }

            Conexion::desconectar();
        } catch (Exception $ex) {
            echo "ERROR: " . $ex->getMessage();
        }
        exit; // Termina la ejecución del script aquí
    }


    // Lógica para el resto de acciones: eliminar, buscar, asignar
    if (filter_has_var(INPUT_POST, "eliminar")) {
        $codigoActor = filter_input(INPUT_POST, "seleccionActor");
        if (Actor::eliminarActor($codigoActor)) {
            header("Location: muestraMensajes.php");
        } else {
            header("Location: muestraMensajesError.php");
        }
    }
    if (filter_has_var(INPUT_POST, "buscar")) {
        $codigoActor = filter_input(INPUT_POST, "seleccionActor");
        if (Actor::verActor($codigoActor)) {
            header("Location: muestraMensajes.php");
        } else {
            header("Location: muestraMensajesError.php");
        }
    }
    if (filter_has_var(INPUT_POST, "asignar")) {
        $codigoActor = filter_input(INPUT_POST, "seleccionActor");
        $codigoSupervisor = filter_input(INPUT_POST, "seleccionSupervisor");
        if ($codigoActor == $codigoSupervisor) {
            header("Location: muestraMensajesError.php");
        } else {
            $actorBD = Actor::verActor($codigoActor);
            $actorSeleccionadoBD = new Actor($actorBD['cdactor'], $actorBD['nombre'], $actorBD['sexo'], $actorBD['cdgrupo'], $actorBD['fecha_alta'], $actorBD['cache_base'] . $actorBD['cdSupervisa']);
            $actorSeleccionadoBD->setSupervisor($codigoSupervisor);
            if ($actorSeleccionadoBD->guardarActor()) {
                header("Location: muestraMensajes.php");
            }
        }
    }
}
?>
