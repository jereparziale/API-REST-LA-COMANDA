<?php

class Producto{

    public $id_producto;
    public $nombre;
    public $tiempoPreparacion;
    public $sectorDePreparacion;
    public $precio;

    public function crearProducto()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO productos (nombre, tiempo_preparacion, sector_preparacion, precio) VALUES (:nombre, :tiempoPreparacion, :sectorPreparacion, :precio)");
        $consulta->bindValue(':nombre', $this->nombre, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoPreparacion', $this->tiempoPreparacion, PDO::PARAM_INT);
        $consulta->bindValue(':sectorPreparacion', $this->sectorDePreparacion, PDO::PARAM_STR);
        $consulta->bindValue(':precio', $this->precio, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
    
    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_producto, nombre, tiempo_preparacion as tiempoPreparacion, sector_preparacion as sectorDePreparacion, precio FROM productos");
        $consulta->execute();
    
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Producto');
    }

    public static function obtenerProducto($id_producto)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_producto, nombre, tiempo_preparacion as tiempoPreparacion, sector_preparacion as sectorDePreparacion, precio FROM productos WHERE id_producto = :id_producto");
        $consulta->bindValue(':id_producto', $id_producto);
        $consulta->execute();
        return $consulta->fetchObject('Producto');
    }




    //MOD Y BORRAR

}



