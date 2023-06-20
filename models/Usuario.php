<?php
require_once './models/Personal.php';

class Usuario extends Personal
{
    public $id;
    public $usuario;
    public $clave; 
    public $estado;

    const ESTADO_ACTIVO=1;
    const ESTADO_BAJA=0;

   

    public function crearUsuario()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO usuarios (nombre, apellido, dni, fecha_nacimiento,sector, puesto, fecha_de_contratacion, clave, usuario,estado) VALUES (:nombre, :apellido, :dni, :fechaNacimiento, :sector, :puesto, :fecha_de_contratacion,:clave, :usuario, :estado)");
        
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':apellido', $this->apellido, PDO::PARAM_STR);
        $consulta->bindValue(':dni', $this->dni, PDO::PARAM_INT);
        $consulta->bindValue(':fechaNacimiento', $this->fechaNacimiento);
        $consulta->bindValue(':sector', $this->sector, PDO::PARAM_STR);
        $consulta->bindValue(':puesto', $this->puesto, PDO::PARAM_STR);
        $consulta->bindValue(':fecha_de_contratacion', $this->fechaContratacion);
        $consulta->bindValue(':usuario', $this->usuario);
        $consulta->bindValue(':clave', $this->clave);
        $consulta->bindValue(':estado', $this->estado);

        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }

    
    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_usuario as id, nombre, apellido, dni, fecha_nacimiento as fechaNacimiento, clave, usuario, sector, puesto, fecha_de_contratacion as fechaContratacion,fecha_de_egreso as fechaEgreso, estado FROM usuarios");
        $consulta->execute();
    
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Usuario');
    }

    public static function obtenerUsuario($usuario)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_usuario as id, nombre, apellido, dni, fecha_nacimiento as fechaNacimiento, clave, usuario, sector, puesto, fecha_de_contratacion as fechaContratacion, fecha_de_egreso as fechaEgreso, estado FROM usuarios WHERE usuario = :usuario");
        $consulta->bindValue(':usuario', $usuario);
        $consulta->execute();
        return $consulta->fetchObject('Usuario');
    }
    public static function borrarUsuario($usuario)
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE usuarios SET fecha_de_egreso = :fechaEgreso WHERE usuario = :usuario");
        $fecha = new DateTime(date("d-m-Y"));
        $consulta->bindValue(':usuario', $usuario);
        $consulta->bindValue(':fechaEgreso', date_format($fecha, 'Y-m-d'));
        $consulta->execute();
    }
    
    public static function ValidarExtraerUsuario($usuario, $clave)
    {
        $usuarioSeleccionado = Usuario::obtenerUsuario($usuario);
        if($usuarioSeleccionado != null)
        {
            if($usuarioSeleccionado->clave == $clave)
            {
                return $usuarioSeleccionado;
            }
        }
        return null;
    }

    //MOD 
    //BORRAR

}