<?php
use Random\Engine\Secure;
date_default_timezone_set('America/Buenos_Aires');

class Pedido{

    public $id_pedido;
    public $id_producto;
    public $id_mesa;
    public $estado;
    public $cantidadProducto;
    public $tiempoEstimado;

    const ESTADO_PENDIENTE = "pendiente";
    const ESTADO_ENPREPARACION = "en preparacion";
    const ESTADO_LISTO = "listo para servir";
    const ESTADO_ENTREGADO = "entregado";
   

 
    public function crearPedido()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO pedidos (id_pedido,id_producto, id_mesa, estado,cantidadProducto) VALUES (:id_pedido,:id_producto,:id_mesa,:estado,:cantidadProducto)");
        $consulta->bindValue(':id_pedido', $this->id_pedido, PDO::PARAM_STR);
        $consulta->bindValue(':id_producto', $this->id_producto, PDO::PARAM_INT);
        $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':cantidadProducto', $this->cantidadProducto, PDO::PARAM_INT);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
    
    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_pedido,id_producto, id_mesa, estado,cantidadProducto,tiempoEstimado FROM pedidos");
        $consulta->execute();
    
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }

    public static function obtenerSegunEstado($estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_pedido,id_producto, id_mesa, estado,cantidadProducto,tiempoEstimado FROM pedidos WHERE estado = :estado");
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Pedido');
    }


    public static function obtenerPedido($id_pedido)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_pedido,id_producto,id_mesa, estado,cantidadProducto,tiempoEstimado FROM pedidos WHERE id_pedido = :id_pedido");
        $consulta->bindValue(':id_pedido', $id_pedido, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject('Pedido');
        
    }

    
    public function modificarPedido()
    {
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE pedidos SET estado = :estado,tiempoEstimado = :tiempoEstimado WHERE id_pedido = :id_pedido");
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':tiempoEstimado', $this->tiempoEstimado);
        $consulta->bindValue(':id_pedido', $this->id_pedido, PDO::PARAM_STR);
        $consulta->execute();
    }




    public static function CambiarEstado($estado, $id_Pedido)
    {
        $pedido = Pedido::obtenerPedido($id_Pedido);
        switch ($estado)
        {
            case Pedido::ESTADO_ENPREPARACION:
                if($pedido->estado==Pedido::ESTADO_ENPREPARACION){
                    return false;
                }
                $pedido = Pedido::CambiarAEnPreparacion($pedido);
                break;
            case Pedido::ESTADO_LISTO:
                if($pedido->estado==Pedido::ESTADO_LISTO){
                    return false;
                }
                $pedido = Pedido::CambiarAListo($pedido);
                break;
            case Pedido::ESTADO_ENTREGADO:
                if($pedido->estado==Pedido::ESTADO_ENTREGADO){
                    return false;
                }
                $pedido = Pedido::CambiarAEntregado($pedido);
                break;
        }
        $pedido->modificarPedido();
        return true;
    }

    public static function CambiarAEnPreparacion($pedido)
    {
        $pedido->estado = Pedido::ESTADO_ENPREPARACION;
        $tiempoEstimadoMin = Sector::CalcularTiempoDePreparacion($pedido);

        $fecha = new DateTime(date("d-m-Y H:i:s"));
        var_dump($fecha);
        $pedido->tiempoEstimado = $fecha->modify('+'.$tiempoEstimadoMin.' minutes');
        $pedido->tiempoEstimado = $pedido->tiempoEstimado->format("Y-m-d H:i:s");

        return $pedido;
    }
  
    public static function CambiarAListo($pedido)
    {
        $pedido->estado = Pedido::ESTADO_LISTO;
        return $pedido;
    }
    public static function CambiarAEntregado($pedido)
    {
        $pedido->estado = Pedido::ESTADO_ENTREGADO;
        return $pedido;
    }

    public static function ObtenerTiempoEsperaParaCliente($pedido)
    {
        $fechaActual = new DateTime(); 
        $tiempoEstimado = new DateTime($pedido->tiempoEstimado); 

        $intervalo = $fechaActual->diff($tiempoEstimado);
        $esperaEnMinutos = $intervalo->format('%i');
        return $esperaEnMinutos;
    }


}



