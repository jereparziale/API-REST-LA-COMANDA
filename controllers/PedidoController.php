<?php
require_once './models/Pedido.php';
// require_once './interfaces/IApiUsable.php';
require_once './models/Sector.php';

class PedidoController extends Pedido{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id_producto = intval($parametros['id_producto']);
        $id_mesa = $parametros['id_mesa'];
        $cantidadProducto = intval($parametros['cantidadProducto']);


        // Creamos el Pedido
        $ped = new Pedido();
        $ped->id_pedido = PedidoController::generarIdPedido();
        $ped->id_producto = $id_producto;
        $ped->id_mesa = $id_mesa;
        $ped->estado = Pedido::ESTADO_PENDIENTE;
        $ped->cantidadProducto = $cantidadProducto;
        $ped->crearPedido();
        // Restaurante::AsignarPedido($ped);



        $payload = json_encode(array("mensaje" => "Pedido creado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos Pedido por nombre
        $parametros = $request->getParsedBody();
        $pedido = $parametros['id_pedido'];
        $Pedido = Pedido::obtenerPedido($pedido);
        $payload = json_encode($Pedido);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function MostrarEstadoACliente($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $pedido = $parametros['id_pedido'];
        $pedidoSeleccionado = Pedido::obtenerPedido($pedido);
        switch ($pedidoSeleccionado->estado)
        {
            case Pedido::ESTADO_ENPREPARACION:
              if($pedidoSeleccionado->estado== Pedido::ESTADO_ENPREPARACION){
                $tiempoEstimado=Pedido::ObtenerTiempoEsperaParaCliente($pedidoSeleccionado);
                $payload =  json_encode(array("El pedido estara listo en: ".$tiempoEstimado." minutos"));
                }
                break;
            case Pedido::ESTADO_LISTO:
              $payload =  json_encode(array("El pedido se encuentra listo para servir"));
                break;
            case Pedido::ESTADO_PENDIENTE:
              $payload =  json_encode(array("El pedido se pendiente a ser tomado por el empleado"));
                break;
        }
       
        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Pedido::obtenerTodos();
        if(count($lista)>0){
            $payload = json_encode(array("listaPedido" => $lista));
          }else{
            $payload = json_encode(array("No hay pedidos en sistema."));
          }
        

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
    
    public function TraerListosParaServir($request, $response, $args)
    {
        $lista = Pedido::obtenerSegunEstado(Pedido::ESTADO_LISTO);
        if(count($lista)>0){
            $payload = json_encode(array("Lista pedidos para servir" => $lista));
          }else{
            $payload = json_encode(array("No hay pedidos para servir."));
          }
        

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

   

    
      
    // public function ModificarUno($request, $response, $args)
    // {
    //     $parametros = $request->getParsedBody();

    //     $nombre = $parametros['nombre'];
    //     Usuario::modificarUsuario($nombre);

    //     $payload = json_encode(array("mensaje" => "Usuario modificado con exito"));

    //     $response->getBody()->write($payload);
    //     return $response
    //       ->withHeader('Content-Type', 'application/json');
    // }

    // public function BorrarUno($request, $response, $args)
    // {
    //     $parametros = $request->getParsedBody();

    //     $usuarioId = $parametros['usuarioId'];
    //     Usuario::borrarUsuario($usuarioId);

    //     $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

    //     $response->getBody()->write($payload);
    //     return $response
    //       ->withHeader('Content-Type', 'application/json');
    // }
   
    public static function generarIdPedido() {
      $caracteres = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
      $id = '';
      $longitud=5;
  
      for ($i = 0; $i < $longitud; $i++) {
          $indice = mt_rand(0, strlen($caracteres) - 1);
          $id .= $caracteres[$indice];
      }
  
      return $id;
  }

 
}