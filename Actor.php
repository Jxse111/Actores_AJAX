<?php

require_once './Conexion.php';
require_once './funcionesBaseDeDatos.php';
require_once './funcionesValidacion.php';

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of Actor
 *
 * @author 2DAW
 */
class Actor {

    private $cdActor;
    private $nombre;
    private $fecha_alta;
    private $cache_base;
    private $sexo;
    private $cdSupervisa;
    private $cdGrupo;

    public function __construct($cdActor, $nombre, $sexo, $cdGrupo, $fecha_alta = null, $cache_base = null, $cdSupervisa = null) {
        $this->cdActor = validarCadena($cdActor);
        $this->nombre = validarCadena($nombre);
        $this->fecha_alta = $fecha_alta;
        $this->cache_base = validarNumero($cache_base);
        $this->sexo = validarCadena($sexo);
        $this->cdSupervisa = validarCadena($cdSupervisa);
        $this->cdGrupo = validarCadena($cdGrupo);
    }

//Métodos Getter y Setter
    public function getCdActor() {
        return $this->cdActor;
    }

    public function getNombre() {
        return $this->nombre;
    }

    public function getFecha_alta() {
        return $this->fecha_alta;
    }

    public function getCache_base() {
        return $this->cache_base;
    }

    public function getSexo() {
        return $this->sexo;
    }

    public function getCdSupervisa() {
        return $this->cdSupervisa;
    }

    public function getCdGrupo() {
        return $this->cdGrupo;
    }

    /**
     * Método que devuelve el numero de actores registrados en la base de datos.
     * @return type devuelve en caso de que sea valido el numero de actores registrados, por lo contrario devuelve false.
     */
    public static function getNumActores() {
        $conexionBD = Conexion::conectarEspectaculosMySQLi();
        $esValido = false;
        try {
            $consultaNumActores = "SELECT COUNT * FROM actor";
            $resultadoConsultaMostrarEspectaculos = $conexionBD->query($consultaNumActores);
            if ($resultadoConsultaMostrarEspectaculos->num_rows > 0) {
                $esValido = true;
                $numActores .= $resultadoConsultaMostrarEspectaculos->fetch_column();
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
        Conexion::desconectar();
        return $esValido ? $numActores : false;
    }

    /**
     * Método que permite asignar valor a cache_base.
     * @param type $cache_base la cache del actor.
     */
    public function setCache($cache_base) {
        $this->cache_base = $cache_base;
    }

    /**
     * Método que permite asignar un codigo de supervisor.
     * @param type $cdSupervisa codigo de supervisor.
     */
    public function setSupervisor($cdSupervisa) {
        $this->cdSupervisa = $cdSupervisa;
    }

    /**
     * Método que muestra el contenido de un  actor referenciado por su codigo.
     * @param type $codigoActor codigo por el que se hace referencia a los datos que contiene.
     * @return type devuelve los datos del actor en un array.
     */
    public static function verActor($codigoActor) {
        $conexionBD = Conexion::conectarEspectaculosMySQLi();
        $esValido = false;
        // Verificar si el actor existe
        if (!noExisteCodigoActor($codigoActor, $conexionBD)) {
            try {
                $consultaExisteActor = $conexionBD->prepare("SELECT * FROM actor WHERE cdactor = ?");
                $consultaExisteActor->bind_param("s", $codigoActor);
                if ($consultaExisteActor->execute()) {
                    $resultado = $consultaExisteActor->get_result();
                    // Obtener los datos
                    //Agregar comprobacion de que los datos no estan vacíos
                    $datosActor = $resultado->fetch_assoc();
                    if ($datosActor) {
                        $esValido = true;
                    }
                }
            } catch (Exception $ex) {
                echo "ERROR: " . $ex->getMessage();
            }
        }
        $consultaExisteActor->close();
        Conexion::desconectar();
        return $esValido ? $datosActor : false;
    }

    /**
     * Método que devuelve todos los actores registrados en la base de datos.
     * @return string devuelve la cadena compuesta por todos los actores y sus datos correspondientes.
     */
    public static function listarActores() {
        // Conectar a la base de datos
        $conexionBD = Conexion::conectarEspectaculosMySQLi();
        $mensajeResultado = "";

        // Consulta para obtener todos los actores
        try {
            $consultaListarActores = "SELECT * FROM actor";
            $resultadoConsultaListarActores = $conexionBD->query($consultaListarActores);

            // Verificar que la consulta se haya ejecutado y que existan registros
            if ($resultadoConsultaListarActores->num_rows > 0) {
                $actoresExistentes = $resultadoConsultaListarActores->fetch_all();
            }
        } catch (Exception $ex) {
            $mensajeResultado = "ERROR: " . $ex->getMessage();
        }
        Conexion::desconectar();
        return $actoresExistentes;
    }

    /**
     * Método que elimina un actor por su código de referencia.
     * @param type $codigoActor código del actor.
     * @return type devuelve si se ha podido o no eliminar dicho actor.
     */
    public static function eliminarActor($codigoActor) {
        $conexionBD = Conexion::conectarEspectaculosMySQLi();
        $esValido = false;

        // Verificamos si el código del actor existe en la base de datos
        if (!noExisteCodigoActor($codigoActor, $conexionBD)) {
            try {
                $consultaEliminarActor = $conexionBD->stmt_init();

                // Preparamos la consulta SQL para eliminar el actor
                $consultaEliminarActor->prepare("DELETE FROM actor WHERE cdactor = ?");

                // Asociamos el parámetro a la consulta
                $consultaEliminarActor->bind_param("s", $codigoActor);

                // Ejecutamos la consulta
                if ($consultaEliminarActor->execute()) {
                    $esValido = true;
                }
                // Cerramos la consulta
                $consultaEliminarActor->close();
            } catch (Exception $ex) {
                echo "ERROR: " . $ex->getMessage();
            }
        }
        Conexion::desconectar();
        return $esValido ? true : false;
    }

    /**
     * Método que registra un actor o actualiza dicho actor de manera directa, en la base de datos
     * @return type devuelve si se ha podido realizar o no la operación.
     */
    public function guardarActor() {
        $conexionBD = Conexion::conectarEspectaculosMySQLi();
        $mensajeExito = "";
        $esValido = false;
        $codigoActor = $this->getCdActor();
        $nombreActor = $this->getNombre();
        $fechaAlta = $this->getFecha_alta();
        $cacheBase = $this->getCache_base();
        $sexo = $this->getSexo();
        $cdSupervisa = $this->getCdSupervisa();
        $cdGrupo = $this->getCdGrupo();
        if (noExisteCodigoActor($codigoActor, $conexionBD)) {
            try {
                // Inserción del nuevo actor
                $consultaActor = "INSERT INTO actor (cdactor, nombre, fecha_alta, cache_base, sexo, cdsupervisa, cdgrupo) VALUES (?, ?, ?, ?, ?,?,?)";
                $consultaPreparada = $conexionBD->prepare($consultaActor);
                $consultaPreparada->bind_param("sssdsss", $codigoActor, $nombreActor, $fechaAlta, $cacheBase, $sexo, $cdSupervisa, $cdGrupo);
                if ($consultaPreparada->execute()) {
                    $esValido = true;
                }
            } catch (Exception $ex) {
                echo "ERROR: " . $ex->getMessage();
            }
        } else {
            try {
                // Si el código existe, procedemos  a actualizar
                $consultaActualizacion = "UPDATE actor SET nombre = ?, fecha_alta = ?, cache_base = ?, sexo = ?, cdsupervisa = ?, cdgrupo = ?  WHERE cdactor = ?";
                $consultaPreparada = $conexionBD->prepare($consultaActualizacion);

                $consultaPreparada->bind_param("ssdssss", $nombreActor, $fechaAlta, $cacheBase, $sexo, $cdSupervisa, $cdGrupo, $codigoActor);
                if ($consultaPreparada->execute()) {
                    $esValido = true;
                }
            } catch (Exception $ex) {
                echo "ERROR: " . $ex->getMessage();
            }
        }
        $consultaPreparada->close();
        Conexion::desconectar();
        return $esValido ? true : false;
    }
}
