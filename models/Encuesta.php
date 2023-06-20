<?php
use Random\Engine\Secure;
date_default_timezone_set('America/Buenos_Aires');

class Encuesta{

    public $id_encuesta;
    public $id_mesa;
    public $puntuacionmozo;
    public $puntuacionRestaurante;
    public $puntuacionMesa;
    public $puntuacionCocinero;
    public $comentario;
    public $fechaEncuesta;

 
    public function crearEncuesta()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO encuestas (id_encuesta,id_mesa, puntuacionmozo, puntuacionRestaurante,puntuacionMesa,puntuacionCocinero,comentario,fechaEncuesta) VALUES (:id_encuesta,:id_mesa,:puntuacionmozo,:puntuacionRestaurante,:puntuacionMesa,:puntuacionCocinero,:comentario,:fechaEncuesta)");
        $consulta->bindValue(':id_encuesta', $this->id_encuesta, PDO::PARAM_INT);
        $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacionmozo', $this->puntuacionmozo, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacionRestaurante', $this->puntuacionRestaurante, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacionMesa', $this->puntuacionMesa, PDO::PARAM_INT);
        $consulta->bindValue(':puntuacionCocinero', $this->puntuacionCocinero, PDO::PARAM_INT);
        $consulta->bindValue(':comentario', $this->comentario, PDO::PARAM_STR);
        $consulta->bindValue(':fechaEncuesta', $this->fechaEncuesta);
        $consulta->execute();
        return $objAccesoDatos->obtenerUltimoId();
    }
    
    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_encuesta,id_mesa, puntuacionmozo, puntuacionRestaurante,puntuacionMesa,puntuacionCocinero,comentario,fechaEncuesta FROM encuestas");
        $consulta->execute();
    
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Encuesta');
    }

    public static function obtenerEncuesta($id_encuesta)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_encuesta,id_mesa, puntuacionmozo, puntuacionRestaurante,puntuacionMesa,puntuacionCocinero,comentario,fechaEncuesta FROM encuestas WHERE id_encuesta = :id_encuesta");
        $consulta->bindValue(':id_encuesta', $id_encuesta, PDO::PARAM_INT);
        $consulta->execute();
        return $consulta->fetchObject('Encuesta');
        
    }

    

}



