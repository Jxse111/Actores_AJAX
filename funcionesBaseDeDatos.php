<?php

require_once './patrones.php';

// Función con conexión MySQLi
function noExisteUsuarioMySQLi($usuario, $conexionBD) {
    $usuarioNoExiste = false;
    $consultaUsuariosExistentes = $conexionBD->query("SELECT login FROM usuarios");
    $usuarios = $consultaUsuariosExistentes->fetch_all(MYSQLI_ASSOC);
    foreach ($usuarios as $usuarioExistente) {
        if ($usuarioExistente['login'] != $usuario) {
            $usuarioNoExiste = true;
        }
    }
    return $usuarioNoExiste ? true : false;
}

function noExisteContraseñaMySQLi($contraseña, $conexionBD) {
    $contraseñaNoExiste = false;
    $consultaContraseñasExistentes = $conexionBD->query("SELECT contraseña FROM usuarios");
    $contraseñas = $consultaContraseñasExistentes->fetch_all(MYSQLI_ASSOC);
    foreach ($contraseñas as $constraseñasExistentes) {
        if ($constraseñasExistentes['contraseña'] != $contraseña) {
            $contraseñaNoExiste = true;
        }
    }
    return $contraseñaNoExiste ? true : false;
}

function existeUsuarioMySQLi($usuario, $conexionBD) {
    $usuarioNoExiste = false;
    $consultaUsuariosExistentes = $conexionBD->query("SELECT login FROM usuarios");
    $usuarios = $consultaUsuariosExistentes->fetch_all(MYSQLI_ASSOC);
    foreach ($usuarios as $usuarioExistente) {
        if ($usuarioExistente['login'] == $usuario) {
            $usuarioNoExiste = true;
        }
    }
    return $usuarioNoExiste ? true : false;
}

function existeContraseñaMySQLi($contraseña, $conexionBD) {
    $contraseñaNoExiste = false;
    $consultaContraseñasExistentes = $conexionBD->query("SELECT contraseña FROM usuarios");
    $contraseñas = $consultaContraseñasExistentes->fetch_all(MYSQLI_ASSOC);
    foreach ($contraseñas as $constraseñasExistentes) {
        if ($constraseñasExistentes['contraseña'] == $contraseña) {
            $contraseñaNoExiste = true;
        }
    }
    return $contraseñaNoExiste ? true : false;
}

// Función con conexión PDO
function noExisteUsuarioPDO($usuario, $conexionBD) {
    $usuarioNoExiste = false;
    $consultaUsuariosExistentes = $conexionBD->query("SELECT login FROM usuarios");
    $usuarios = $consultaUsuariosExistentes->fetchAll(PDO::FETCH_ASSOC);
    foreach ($usuarios as $usuarioExistente) {
        if ($usuarioExistente['login'] != $usuario) {
            $usuarioNoExiste = true;
        }
    }
    return $usuarioNoExiste ? true : false;
}

function noExisteContraseñaPDO($contraseña, $conexionBD) {
    $contraseñaNoExiste = false;
    $consultaContraseñasExistentes = $conexionBD->query("SELECT contraseña FROM usuarios");
    $contraseñas = $consultaContraseñasExistentes->fetchAll(PDO::FETCH_ASSOC);
    foreach ($contraseñas as $constraseñasExistentes) {
        if ($constraseñasExistentes['contraseña'] != $contraseña) {
            $contraseñaNoExiste = true;
        }
    }
    return $contraseñaNoExiste ? true : false;
}

function existeUsuarioPDO($usuario, $conexionBD) {
    $usuarioNoExiste = false;
    $consultaUsuariosExistentes = $conexionBD->query("SELECT login FROM usuarios");
    $usuarios = $consultaUsuariosExistentes->fetchAll(PDO::FETCH_ASSOC);
    foreach ($usuarios as $usuarioExistente) {
        if ($usuarioExistente['login'] == $usuario) {
            $usuarioNoExiste = true;
        }
    }
    return $usuarioNoExiste ? true : false;
}

function existeContraseñaPDO($contraseña, $conexionBD) {
    $contraseñaNoExiste = false;
    $consultaContraseñasExistentes = $conexionBD->query("SELECT contraseña FROM usuarios");
    $contraseñas = $consultaContraseñasExistentes->fetchAll(PDO::FETCH_ASSOC);
    foreach ($contraseñas as $constraseñasExistentes) {
        if ($constraseñasExistentes['contraseña'] == $contraseña) {
            $contraseñaNoExiste = true;
        }
    }
    return $contraseñaNoExiste ? true : false;
}

// Modificación de la existencia del codigo del espectaculo
function noExisteCodigoEspectaculo($codigoEspectaculo, $conexionBD) {
    // Consulta SQL que verifica si el código del espectáculo existe
    $consultaCodigoEspectaculo = $conexionBD->prepare("SELECT COUNT(*) FROM espectaculo WHERE cdespec = ?");
    $consultaCodigoEspectaculo->bind_param("s", $codigoEspectaculo);
    $consultaCodigoEspectaculo->execute();
    $consultaCodigoEspectaculo->bind_result($count);
    $consultaCodigoEspectaculo->fetch();
    $consultaCodigoEspectaculo->close();
    // Si el count es mayor que 0, el código existe, de lo contrario no
    return $count == 0;
}

function noExisteNombreEspectaculo($nombreEspectaculo, $conexionBD) {
    $nombreValido = false;
    $consultaNombreEspectaculo = $conexionEspectaculos->query("SELECT nombre FROM espectaculo");
    $espectaculos = $consultaNombreEspectaculo->fetch_all(MYSQLI_ASSOC);
    foreach ($espectaculos as $registro) {
        if ($registro['nombre'] != $nombreEspectaculo) {
            $nombreValido = true;
        }
    }
    return $nombreValido ? true : false;
}

function noExisteCodigoActor($codigoActor, $conexionBD) {
    // Consulta SQL que verifica si el código del espectáculo existe
    $consultaCodigoEspectaculo = $conexionBD->prepare("SELECT COUNT(*) FROM actor WHERE cdactor = ?");
    $consultaCodigoEspectaculo->bind_param("s", $codigoActor);
    $consultaCodigoEspectaculo->execute();
    $consultaCodigoEspectaculo->bind_result($count);
    $consultaCodigoEspectaculo->fetch();
    $consultaCodigoEspectaculo->close();

    // Si el count es mayor que 0, el código existe, de lo contrario no
    return $count == 0;
}

?>