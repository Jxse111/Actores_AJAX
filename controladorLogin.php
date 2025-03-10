<?php
require_once './Conexion.php';
require_once './funcionesValidacion.php';
require_once './funcionesBaseDeDatos.php';
$mensajeError = "Mensajes de error : ";
$mensajeExito = "Mensajes de éxito: ";

if (filter_has_var(INPUT_POST, "Registrarse")) {
    header("Location: registro.html");
    die();
} elseif (filter_has_var(INPUT_POST, "Entrar")) {
    // Comprobamos si ya existe una sesión activa
    session_start();
    if (isset($_SESSION['usuario'])) {
        $mensajeSesion = "El usuario registrado tiene una sesión activa.";
    } else {
        // Creación de la conexión
        $conexionBD = Conexion::conectarEspectaculosMySQLi();

        // Validación del usuario y la contraseña
        $usuarioLogin = validarUsuarioExistenteMySQLi(filter_input(INPUT_POST, "usuarioExistente"), $conexionBD);
        if ($usuarioLogin) {
            // Comprobamos la contraseña
            $conexionBD->autocommit(false);
            $consultaSesiones = $conexionBD->query("SELECT contraseña FROM usuarios WHERE login='$usuarioLogin'");
            if ($consultaSesiones->num_rows > 0) {
                $contraseña = $consultaSesiones->fetch_assoc();
                $contraseñaEncriptada = hash("sha512", filter_input(INPUT_POST, "contraseñaExistente"));

                // Verificamos que las contraseñas coincidan
                if ($contraseñaEncriptada === $contraseña['contraseña']) {
                    $_SESSION['usuario'] = $usuarioLogin;
                    $mensajeExito .= "Inicio de sesión realizado con éxito. \n";

                    // Recuperamos el rol del usuario
                    $buscarRolUsuarioRegistrado = $conexionBD->query("SELECT id_rol FROM usuarios WHERE login='$usuarioLogin'");
                    if ($buscarRolUsuarioRegistrado->num_rows > 0) {
                        $rolUsuarioRegistrado = $buscarRolUsuarioRegistrado->fetch_assoc()['id_rol'];
                        $buscarTipoRolUsuarioRegistrado = $conexionBD->query("SELECT tipo FROM roles WHERE id_rol='$rolUsuarioRegistrado'");
                        if ($buscarTipoRolUsuarioRegistrado->num_rows > 0) {
                            $rol = $buscarTipoRolUsuarioRegistrado->fetch_assoc()['tipo'];
                            $_SESSION['rol'] = $rol;

                            // Redirección según el rol del usuario
                            switch ($_SESSION['rol']) {
                                case "administrador":
                                    header("Location: areaAdmin.php");
                                    exit();
                                case "usuario":
                                    header("Location: reservar_espectaculo.php");
                                    exit();
                                case "invitado":
                                    header("Location: ver_espectaculos.php");
                                    exit();
                            }
                        } else {
                            $mensajeError .= "Tipo de rol no encontrado.\n";
                        }
                    } else {
                        $mensajeError .= "No se ha podido recuperar el rol.\n";
                    }
                } else {
                    $mensajeError .= "Contraseña incorrecta.\n";
                }
            } else {
                $mensajeError .= "Usuario no encontrado.\n";
            }
            Conexion::desconectar();
        } else {
            $mensajeError .= "Datos inválidos o incorrectos.\n";
        }
    }
}
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Iniciar sesión</title>
    </head>
    <body>
        <h2>LISTA DE MENSAJES: </h2>
        <?php if ($mensajeError != "Mensajes de error : ") { ?>
            <h2>Mensajes de error: </h2>
            <ul>
                <li><?php echo nl2br($mensajeError); ?></li>
            </ul>
        <?php } ?>
        <?php if ($mensajeExito != "Mensajes de éxito: ") { ?>
            <h2>Mensajes de éxito: </h2>
            <ul>
                <li><?php echo nl2br($mensajeExito); ?></li>
            </ul>
        <?php } ?>
    </body>
</html>
