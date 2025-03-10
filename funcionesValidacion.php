<?php

require_once './patrones.php';
require_once './funcionesBaseDeDatos.php';

// Funcion de validacion de nombre
function validarCadena($cadena) {
    $cadena = trim($cadena);
    $cadena = stripcslashes($cadena);
    $cadena = strip_tags($cadena);
    $cadena = htmlspecialchars($cadena);
    return $cadena;
}

function validarDni($dni) {
    global $patronDni;
    $dni = validarCadena($dni);
    $esValido = preg_match($patronDni, $dni);
    return $esValido ? $dni : false;
}

function validarNombre($nombre) {
    global $patronNombreYApellidos;
    if (validarCadena($nombre)) {
        if (preg_match($patronNombreYApellidos, $nombre)) {
            $resultado = $nombre;
        } else {
            $resultado = false;
        }
    } else {
        $resultado = false;
    }
    return $resultado;
}

function validarEmail($email) {
    global $patronEmail;
    $email = validarCadena($email);
    $esValido = preg_match($patronEmail, $email);
    return $esValido ? $email : false;
}

function validarUrl($url) {
    global $patronUrl;
    $url = validarCadena($url);
    $esValido = preg_match($patronUrl, $url);
    return $esValido ? $url : false;
}

// Funcion de validacion de edad
function validarEdad($numero) {
    $opciones = array('options' => array('min_range' => 18, 'max_range' => 100));
    return filter_has_var($numero, FILTER_VALIDATE_INT, $opciones) ? $numero : false;
}

// Funcion de validacion de sexo
function validarSexo($campo) {
    $sexo = ["H", "M"];
    return in_array($campo, $sexo) ? $campo : false;
}

// Funcion de validacion de aficiones
function validarAficiones($campo) {
    $aficionValidada = ["deportes", "musica", "alimentacion", "moda"];
    foreach ($campo as $aficion) {
        if (!in_array($aficion, $aficionValidada)) {
            $campo = false;
        }
    }
    return $campo;
}

// Funcion de validacion de módulos
function validarMódulos($modulos) {
    $modulosValidos = ["DWES", "DWEC", "DIWEB", "EIE", "DESAW", "HLC"];
    if (is_array($modulos)) {
        $resultado = array_intersect($modulos, $modulosValidos);
        return $resultado ? $resultado : false; // Retorna los módulos válidos o false si no hay
    }
}

// Funcion de validacion de categorías
function validarCategorías($categorias) {
    $categoriasValidas = ["Sprint", "Olímpica", "Ironman"];
    return in_array($categorias, $categoriasValidas) ? $categorias : false;
}

// Función de validación de año
function validarAño($anio) {
    global $patronAnio;
    $anio = filter_var($anio, FILTER_VALIDATE_INT); // Validamos que sea un entero
    $esValido = ($anio !== false && preg_match($patronAnio, $anio)); // Verificamos la validez
    return $esValido ? $anio : false; // Retorna el año si es válido, o false
}

// Funcion de validacion de provincias
function validarProvincias($campo) {
    $provincia = ["Almería", "Granada", "Córdoba", "Jaen", "Sevilla", "Huelva", "Málaga"];
    return in_array($campo, $provincia) ? $campo : false;
}

// Función de validación de numero
function validarNumero($numero) {
    $numero = validarCadena($numero); // Eliminamos etiquetas HTML
    if (is_numeric($numero)) {
        $esValido = $numero;
    } else {
        $esValido = false;
    }
    return $esValido;
}

// Función de validación de precio
function validarPrecio($numero) {
    $numero = strip_tags(htmlspecialchars($numero)); // Eliminamos etiquetas HTML
    $esValido = filter_var($numero, FILTER_VALIDATE_FLOAT);
    return $esValido ? $numero : false; // Retorna el nombre si es válido, o false
}

//Función de validacion del iva
function validarIVA($iva) {
    $valoresPermitidos = [0.21, 0.18];
    return in_array((float) $iva, $valoresPermitidos, true) ? (float) $iva : null;
}

//Funcion para validar matriculas Españolas
function validarMatricula($matricula) {
    global $patronMatricula;
    $matricula = validarCadena($matricula);
    $esValido = preg_match($patronMatricula, $matricula);
    return $esValido ? $matricula : false;
}

//Funcion que valida las marcas
function validarMarcas($marca) {
    $marcasValidas = ["Chrysler", "BMW", "Audi", "Otro"];
    return in_array($marca, $marcasValidas) ? $marca : false;
}

//Funcion para validar reparaciones
function validarReparacion($reparacion) {
    $reparacionesValidas = ["Cambio de aceite", "Cambio de filtros", "Correa de distribución", "Cambio de 2 neumáticos"];
    $resultadosValidos = [];
    if (is_array($reparacion)) {
        foreach ($reparacion as $valor) {
            if (in_array($valor, $reparacionesValidas)) {
                $resultadosValidos[] = $valor;
            }
        }
    }
    return $resultadosValidos; // Retorna solo las reparaciones válidas
}

//Funcion que valida el color
function validarColor($color) {
    $coloresPermitidos = ["Blanco", "Negro", "Rojo", "Otro"];
    return in_array($color, $coloresPermitidos) ? $color : false;
}

// Funcion de validacion de Modalidades
function validarModalidades($modalidades) {
    $modalidadesValidas = ["Individual", "Grupal", "Por club", "Federado/a"];
    if (is_array($modalidades)) {
        $resultado = array_intersect($modalidades, $modalidadesValidas);
        return $resultado ? $resultado : false; // Retorna las categorías válidas o false si no hay
    }
}

// Validación del codigo del grupo
function validarGrupo($grupo) {
    $grupoValido = false;
    $conexionEspectaculos = new mysqli("localhost", "root", "", "espectaculos");
    if ($conexionEspectaculos->connect_error) {
        echo "ERROR: " . $conexionEspectaculos->connect_error;
        return false;
    }
    $consultaCodigoGrupo = $conexionEspectaculos->query("SELECT cdgrupo FROM grupo");
    $grupos = $consultaCodigoGrupo->fetch_all(MYSQLI_ASSOC);
    foreach ($grupos as $registro) {
        if ($registro['cdgrupo'] === $grupo) {
            $grupoValido = true;
        }
    }
    return $grupoValido ? $grupo : false;
}

// Validación del codigo del actor
function validarCodigoActor($cdactor) {
    global $patronCodigoActor;
    $actorValido = false;
    $conexionEspectaculos = new mysqli();
    try {
        $conexionEspectaculos->connect("localhost", "root", "", "espectaculos");
    } catch (Exception $ex) {
        echo "ERROR: " . $ex->getMessage();
        $conexionEspectaculos->close();
    }
    $consultaCodigoActor = $conexionEspectaculos->query("SELECT cdactor FROM actor");
    $actores = $consultaCodigoActor->fetch_all(MYSQLI_ASSOC);

    foreach ($actores as $registro) {
        if ($registro['cdactor'] != $cdactor) {
            $actorValido = true;
        }
    }
    if (!$actorValido) {
        $cdactor = validarCadena($cdactor);
        $actorValido = preg_match($patronCodigoActor, $cdactor);
    }
    return $actorValido ? $cdactor : false;
}

// Validación del codigo del espectaculo
function validarCodigoEspectaculo($codigoEspectaculo, $conexionBD) {
    $espectaculoValido = false;
    $consultaCodigoEspectaculo = $conexionEspectaculos->query("SELECT cdespec FROM espectaculo");
    $espectaculos = $consultaCodigoEspectaculo->fetch_all(MYSQLI_ASSOC);
    foreach ($espectaculos as $registro) {
        if ($registro['cdespec'] != $codigoEspectaculo) {
            $espectaculoValido = true;
        }
    }
    return $espectaculoValido ? $codigoEspectaculo : false;
}

// Validación nombre del espectaculo
function validarNombreEspectaculo($nombreEspectaculo) {
    $nombreValido = false;
    $conexionEspectaculos = new mysqli();
    try {
        $conexionEspectaculos->connect("localhost", "root", "", "espectaculos");
    } catch (Exception $ex) {
        echo "ERROR: " . $ex->getMessage();
        $conexionEspectaculos->close();
    }
    $consultaNombreEspectaculo = $conexionEspectaculos->query("SELECT nombre FROM espectaculo");
    $espectaculos = $consultaNombreEspectaculo->fetch_all(MYSQLI_ASSOC);
    foreach ($espectaculos as $registro) {
        if ($registro['nombre'] != $nombreEspectaculo) {
            $nombreValido = true;
        }
    }
    if (!$nombreValido) {

        $nombreEspectaculo = validarCadena($nombreEspectaculo);
    }
    return $nombreValido ? $nombreEspectaculo : false;
}

// Validacion de los tipos de espectaculos
function validarTipoEspectaculo($tipoEspectaculo) {
    $tipoValido = false;
    $conexionEspectaculos = new mysqli();
    try {
        $conexionEspectaculos->connect("localhost", "root", "", "espectaculos");
    } catch (Exception $ex) {
        echo "ERROR: " . $ex->getMessage();
        $conexionEspectaculos->close();
    }
    $consultaTipoEspectaculo = $conexionEspectaculos->query("SELECT DISTINCT tipo FROM espectaculo");
    $tipos = $consultaTipoEspectaculo->fetch_all(MYSQLI_ASSOC);
    foreach ($tipos as $registro) {
        if ($registro['tipo'] === $tipoEspectaculo) {
            $tipoValido = true;
        }
    }
    return $tipoValido ? $tipoEspectaculo : false;
}

// Validacion de las estrellas
function validarEstrellasEspectaculo($estrellasEspectaculo) {
    $estrellasValido = false;
    $conexionEspectaculos = new mysqli();
    try {
        $conexionEspectaculos->connect("localhost", "root", "", "espectaculos");
    } catch (Exception $ex) {
        echo "ERROR: " . $ex->getMessage();
        $conexionEspectaculos->close();
    }

    $consultaEstrellasEspectaculo = $conexionEspectaculos->query("SELECT DISTINCT estrellas FROM espectaculo");
    $estrellas = $consultaEstrellasEspectaculo->fetch_all(MYSQLI_ASSOC);
    foreach ($estrellas as $registro) {
        if ($registro['estrellas'] === $estrellasEspectaculo) {
            $estrellasValido = true;
        }
    }
    if (!$estrellasValido) {
        $estrellasEspectaculo = intval($estrellasEspectaculo);
        $estrellasValido = ($estrellasEspectaculo >= 1 && $estrellasEspectaculo <= 5);
    }
    return $estrellasValido ? $estrellasEspectaculo : false;
}

// Función con conexión MySQLi
function validarUsuarioMySQLi($usuario, $conexionBD) {
    $esValido = false;
    $usuario = validarCadena($usuario);
    if (noExisteUsuarioMySQLi($usuario, $conexionBD)) {
        $esValido = true;
    }
    return $esValido ? $usuario : false;
}

function validarContraseñaMySQLi($contraseña, $conexionBD) {
    $esValido = false;
    $contraseña = validarCadena($contraseña);
    if (noExisteContraseñaMySQLi($contraseña, $conexionBD)) {
        $esValido = true;
    }
    return $esValido ? $contraseña : false;
}

function validarUsuarioExistenteMySQLi($usuario, $conexionBD) {
    $esValido = false;
    $usuario = validarCadena($usuario);
    if (existeUsuarioMySQLi($usuario, $conexionBD)) {
        $esValido = true;
    }
    return $esValido ? $usuario : false;
}

function validarContraseñaExistenteMySQLi($contraseña, $conexionBD) {
    $esValido = false;
    $contraseña = validarCadena($contraseña);
    if (existeContraseñaMySQLi($contraseña, $conexionBD)) {
        $esValido = true;
    }
    return $esValido ? $contraseña : false;
}

// Función con conexión PDO
function validarUsuarioPDO($usuario, $conexionBD) {
    $esValido = false;
    $usuario = validarCadena($usuario);
    if (noExisteUsuarioPDO($usuario, $conexionBD)) {
        $esValido = true;
    }
    return $esValido ? $usuario : false;
}

function validarContraseñaPDO($contraseña, $conexionBD) {
    $esValido = false;
    $contraseña = validarCadena($contraseña);
    if (noExisteContraseñaPDO($contraseña, $conexionBD)) {
        $esValido = true;
    }
    return $esValido ? $contraseña : false;
}

function validarUsuarioExistentePDO($usuario, $conexionBD) {
    $esValido = false;
    $usuario = validarCadena($usuario);
    if (existeUsuarioPDO($usuario, $conexionBD)) {
        $esValido = true;
    }
    return $esValido ? $usuario : false;
}

function validarContraseñaExistentePDO($contraseña, $conexionBD) {
    $esValido = false;
    $contraseña = validarCadena($contraseña);
    if (existeContraseñaPDO($contraseña, $conexionBD)) {
        $esValido = true;
    }
    return $esValido ? $contraseña : false;
}
