<?php

class Mesa{

    public $id_mesa;
    public $numeroMesa;
    public $usuario_mozo;
    public $nombreCliente;
    public $estado;
    public $cantidadComensales;
    public $ruta_foto;
    public $importeTotal;
    public $fechaApertura;
    public $fechaCierre;

    public const ESTADO_ESPERANDO = 'con cliente esperando pedido';
    public const ESTADO_COMIENDO = 'con cliente comiendo';
    public const ESTADO_PAGANDO = 'con cliente pagando';
    public const ESTADO_CERRADA = 'cerrada.';
 
    public function crearMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("INSERT INTO mesas (id_mesa,numeroMesa,usuario_mozo, nombreCliente, estado,cantidadComensales,importeTotal,fechaApertura,fechaCierre) VALUES (:id_mesa,:numeroMesa,:usuario_mozo, :nombreCliente, :estado,:cantidadComensales,:importeTotal,:fechaApertura,:fechaCierre)");
        $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_STR);
        $consulta->bindValue(':numeroMesa', $this->numeroMesa, PDO::PARAM_INT);
        $consulta->bindValue(':usuario_mozo', $this->usuario_mozo, PDO::PARAM_STR);
        $consulta->bindValue(':nombreCliente', $this->nombreCliente, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':cantidadComensales', $this->cantidadComensales, PDO::PARAM_INT);
        $consulta->bindValue(':importeTotal', $this->importeTotal);
        $consulta->bindValue(':fechaApertura', $this->fechaApertura);
        $consulta->bindValue(':fechaCierre', $this->fechaCierre);
        $consulta->execute();

        return $objAccesoDatos->obtenerUltimoId();
    }
    
    public static function obtenerTodos()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_mesa,numeroMesa,usuario_mozo, nombreCliente, estado,cantidadComensales,ruta_foto,importeTotal,fechaApertura,fechaCierre FROM mesas");
        $consulta->execute();
    
        return $consulta->fetchAll(PDO::FETCH_CLASS, 'Mesa');
    }

    public static function obtenerMesa($id_mesa)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_mesa,numeroMesa,usuario_mozo,nombreCliente, estado,cantidadComensales,ruta_foto,importeTotal,fechaApertura,fechaCierre  FROM mesas WHERE id_mesa = :id_mesa");
        $consulta->bindValue(':id_mesa', $id_mesa, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject('Mesa');
        
    }
    public function modificarMesa()
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("UPDATE mesas SET estado = :estado, importeTotal = :importeTotal,fechaCierre = :fechaCierre WHERE id_mesa = :id_mesa");
        $consulta->bindValue(':id_mesa',$this->id_mesa, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $this->estado, PDO::PARAM_STR);
        $consulta->bindValue(':importeTotal', $this->importeTotal);
        $consulta->bindValue(':fechaCierre', $this->fechaCierre);
        $consulta->execute();
        return $consulta->fetchObject('Mesa');
        
    }

    public function grabarFoto()
    {       
        $objAccesoDato = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDato->prepararConsulta("UPDATE mesas SET ruta_foto = :ruta_foto WHERE id_mesa = :id_mesa");
        $consulta->bindValue(':id_mesa', $this->id_mesa, PDO::PARAM_INT);
        $consulta->bindValue(':ruta_foto', $this->ruta_foto, PDO::PARAM_STR);
        $consulta->execute();
    }
    public static function obtenerMesaSegunEstado($id_mesa,$estado)
    {
        $objAccesoDatos = AccesoDatos::obtenerInstancia();
        $consulta = $objAccesoDatos->prepararConsulta("SELECT id_mesa,numeroMesa,usuario_mozo,nombreCliente, estado,cantidadComensales,ruta_foto,importeTotal,fechaApertura,fechaCierre  FROM mesas WHERE id_mesa = :id_mesa AND estado = :estado");
        $consulta->bindValue(':id_mesa', $id_mesa, PDO::PARAM_STR);
        $consulta->bindValue(':estado', $estado, PDO::PARAM_STR);
        $consulta->execute();
        return $consulta->fetchObject('Mesa');
        
    }

    public function GuardarImagen()
    {     
        $nombreFoto = "foto_mesa_".$this->id_mesa.".jpg";
        $destino = ".".DIRECTORY_SEPARATOR."fotos_mesas".DIRECTORY_SEPARATOR.DIRECTORY_SEPARATOR;

        if(!file_exists($destino))
        {
            mkdir($destino, 0777, true);
        }

        $dir = $destino.$nombreFoto;
        move_uploaded_file($this->ruta_foto, $dir);
        $this->ruta_foto = $dir;
        $this->grabarFoto();
        return $dir;
    }

    
    


    public static function CambiarEstadoAComiendo($id_mesa,$id_pedido){
        $mesaSeleccionada = Mesa::obtenerMesaSegunEstado($id_mesa,Mesa::ESTADO_ESPERANDO);
        if($mesaSeleccionada){
                $mesaSeleccionada->estado = Mesa::ESTADO_COMIENDO;
                $mesaSeleccionada->modificarMesa();
        }
        //validar
        Pedido::CambiarEstado(Pedido::ESTADO_ENTREGADO,$id_pedido);
    }


    public static function CambiarEstadoAPagando($id_mesa){
        $mesaSeleccionada = Mesa::obtenerMesaSegunEstado($id_mesa,Mesa::ESTADO_COMIENDO);
        if($mesaSeleccionada){
                $mesaSeleccionada->estado = Mesa::ESTADO_PAGANDO;
                $mesaSeleccionada->importeTotal = Mesa::CalcularImporteTotal($id_mesa);
                $mesaSeleccionada->modificarMesa();
                return $mesaSeleccionada;
        }
    }

    public static function CambiarEstadoACerrada($id_mesa){
        $mesaSeleccionada = Mesa::obtenerMesaSegunEstado($id_mesa,Mesa::ESTADO_PAGANDO);
        if($mesaSeleccionada){
                $mesaSeleccionada->estado = Mesa::ESTADO_CERRADA;
                $fecha = new DateTime(date("d-m-Y H:i:s"));
                $mesaSeleccionada->fechaCierre = $fecha->format("Y-m-d H:i:s");
                $mesaSeleccionada->modificarMesa();
        }
    }

    public static function CalcularImporteTotal($id_mesa){
        $pedidosArray = Pedido::obtenerTodos();
        $pedidosMesa=[];
        $total = 0;

        foreach($pedidosArray as $pedido){
            if($pedido->id_mesa==$id_mesa && $pedido-> estado == Pedido::ESTADO_ENTREGADO){
                $pedidosMesa []=$pedido;
            }
        }
        foreach ($pedidosMesa as $pedido) {
            $productoAPreparar =Producto::obtenerProducto($pedido->id_producto);
            $total += $productoAPreparar->precio * $pedido->cantidadProducto;
        }
        if($total>0){
            return $total;
        }
    }


}

 

