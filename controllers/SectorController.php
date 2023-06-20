<?php
require_once './models/Pedido.php';
// require_once './interfaces/IApiUsable.php';
require_once './models/Sector.php';

class SectorController extends Pedido{
    public function VerPedidosPendientes($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuario = $parametros['usuario'];
        $usuarioSeleccionado = Usuario::obtenerUsuario($usuario);
        if($usuarioSeleccionado){
            $sectorUsuario = $usuarioSeleccionado->sector;
            $pedidosPendientes = Pedido::obtenerTodos();
            $pedidosPendientesPorSector = [];

            if($sectorUsuario!="socio"){

                foreach($pedidosPendientes as $pedido){
                    $productoAPreparar =Producto::obtenerProducto($pedido->id_producto);
                    if($productoAPreparar->sectorDePreparacion==$sectorUsuario){
                        $pedidosPendientesPorSector[] = $pedido;
                    }
                }
                if(count($pedidosPendientesPorSector)>0){
                    $payload = json_encode(array(("Pendientes en ".$sectorUsuario) => $pedidosPendientesPorSector));
                }else{
                    $payload = json_encode(array("No hay pedidos pendientes en ".$sectorUsuario));
                }
            }else{
                if(count($pedidosPendientes)>0){
                    $payload = json_encode(array(("Pedidos")=>$pedidosPendientes));
                }else{
                    $payload = json_encode(array("No hay pedidos en sistema"));
                }
            }

            


        }else{
            $payload = json_encode(array("mensaje" => "Usuario inexistente"));
        }
          $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');

    }


    public function PedidoEnPreparacion($request, $response, $args)
    {
            $params = $request->getParsedBody();
            $idPedido = $params["id_pedido"];
            $idUsuario = $params["usuario"];
            $usuarioSeleccionado = Usuario::obtenerUsuario($idUsuario);
            if($usuarioSeleccionado){
                $retorno= Pedido::CambiarEstado(Pedido::ESTADO_ENPREPARACION, $idPedido, $idUsuario);
                if($retorno){
                    $payload = json_encode("Pedido en preparación.");
                }else{
                    $payload = json_encode("No es posible cambiar a ese estado ya que se encuentra en el mismo.");
                }
            }else{
                $payload = json_encode(array("mensaje" => "Usuario inexistente"));
            }
            $response->getBody()->write($payload);
            return $response
            ->withHeader('Content-Type', 'application/json');
    }
    public function PedidoListo($request, $response, $args)
    {
            $params = $request->getParsedBody();
            $idPedido = $params["id_pedido"];
            $idUsuario = $params["usuario"];
            $usuarioSeleccionado = Usuario::obtenerUsuario($idUsuario);
            if($usuarioSeleccionado){
                $retorno= Pedido::CambiarEstado(Pedido::ESTADO_LISTO, $idPedido, $idUsuario);
                if($retorno){
                    $payload = json_encode("Pedido Listo para servir.");
                }else{
                    $payload = json_encode("No es posible cambiar a ese estado ya que se encuentra en el mismo.");
                }
            }else{
                $payload = json_encode(array("mensaje" => "Usuario inexistente"));
            }
            $response->getBody()->write($payload);
            return $response
            ->withHeader('Content-Type', 'application/json');
    }
}