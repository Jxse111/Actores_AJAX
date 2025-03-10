<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        require_once './funcionesValidacion.php';
        require_once './funcionesBaseDeDatos.php';
        require_once './Conexion.php';
        $mensajeError = "";
        $mensajeExito = "";
        $conexionBD = Conexion::conectarEspectaculosMySQLi();
        if (filter_has_var(INPUT_POST, "enviar")) {
            try {
                //Validación de los datos recogidos
                $usuarioValidado = validarUsuarioMySQLi(filter_input(INPUT_POST, "usuario"), $conexionBD);
                $contraseñaValidada = validarContraseñaMySQLi(filter_input(INPUT_POST, "contraseña"), $conexionBD);
                $camposValidados = $usuarioValidado && $contraseñaValidada;
                echo var_dump($usuarioValidado, $contraseñaValidada);
                if ($camposValidados) {
                    $mensajeExito .= "Datos recibidos y validados correctamente. ";
                    $consultaInsercionUsuarios = $conexionBD->stmt_init();
                    $consultaInsercionUsuarios->prepare("INSERT INTO usuarios (login,contraseña) VALUES (?,?)");
                    $contraseñaEncriptada = hash("sha512", $contraseñaValidada);
                    $consultaInsercionUsuarios->bind_param("ss", $usuarioValidado, $contraseñaEncriptada);
                    if ($consultaInsercionUsuarios->execute()) {
                        $mensajeExito .= "Registro insertado correctamente. ";
                        Conexion::desconectar();
                    } else {
                        $mensajeError .= "La inserción no se ha podido realizar. ";
                    }
                } else {
                    $mensajeError .= "Los datos son inválidos o incorrectos. ";
                }
            } catch (Exception $ex) {
                $mensajeError .= "ERROR: " . $ex->getMessage();
                Conexion::desconectar();
            }
            ?>
            <h2>LISTA DE MENSAJES: </h2>
            <h2>Mensajes de error: </h2>
            <ul>
                <li><?php echo $mensajeError ?></li>
            </ul><br>
            <h2>Mensajes de exito: </h2>
            <ul>
                <li><?php echo $mensajeExito ?></li>
            </ul>
        <?php } ?>
    </body>
</html>