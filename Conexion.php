<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Clase que implementa métodos de conexión a la base de datos Espectaculos.
 *
 * @author José Martínez Estrada
 */
class Conexion {

    private static $conexionBD = null;

    /**
     * Método que establece  una conexión con la base de datos mediante MySQLi
     * @return devuelve el objeto con la conexión establecida
     */
    public static function conectarEspectaculosMySQLi() {
        $host = "localhost";
        $usuario = "root";
        $contrasena = "";
        $bd = "espectaculos";

        if (is_null(self::$conexionBD)) {
            self::$conexionBD = new mysqli($host, $usuario, $contrasena, $bd);

            // Verificar si la conexión falló
            if (self::$conexionBD->connect_errno) {
                header("Location:./errores/erroresTecnicos.html");
            }
        }
        return self::$conexionBD;
    }

    /**
     * Método que establece una coneión con la base de datos mediante PDO
     * @return devuelve el objeto con la conexión establecida
     */
    public static function conectarEspectaculosPDO() {
        $driver = "mysql";
        $host = "localhost";
        $usuario = "root";
        $contrasena = "";
        $bd = "espectaculos";
        if (is_null(Conexion::$conexionBD)) {
            try {
                Conexion::$conexionBD = new PDO("$driver:host=$host;dbname=$bd", $usuario, $contrasena);
                Conexion::$conexionBD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $ex) {
                self::$conexionBD = null;
                header("Location:./errores/erroresTecnicos.html");
            }
        }
        return Conexion::$conexionBD;
    }

    /**
     * Método que cierra la conexión con la base de datos mediante MySQLi
     */
    public static function desconectarEspectaculosMySQLi() {
        if (!is_null(self::$conexionBD)) {
            self::$conexionBD->close();
            self::$conexionBD = null;
        }
    }

    /**
     * Método que cierra la conexión con la base de datos mediante PDO
     */
    public static function desconectarEspectaculosPDO() {
        if (!is_null(Conexion::$conexionBD)) {
            Conexion::$conexionBD = null;
        }
    }

    /**
     * Método que cierra la conexión con la base de datos si está abierta
     */
    public static function desconectar() {
        self::desconectarEspectaculosMySQLi();
        self::desconectarEspectaculosPDO();
    }
}
