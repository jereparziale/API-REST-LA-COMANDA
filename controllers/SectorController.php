<?php
require_once './models/Pedido.php';
require_once './models/Sector.php';

class SectorController extends Pedido{
    public function VerPedidosPendientes($request, $response, $args)
    {
        $usuario = $request->getAttribute('usuario');
        $usuarioSeleccionado = Usuario::obtenerUsuario($usuario->usuario);
        if($usuarioSeleccionado){
            $sectorUsuario = $usuarioSeleccionado->sector;
            $pedidosPendientes = Pedido::obtenerTodos();
            $pedidosPendientesPorSector = [];

            if($sectorUsuario!="socio"){

                foreach($pedidosPendientes as $pedido){
                    if($pedido->estado==Pedido::ESTADO_PENDIENTE){
                        $productoAPreparar =Producto::obtenerProducto($pedido->id_producto);
                        if($productoAPreparar->sectorDePreparacion==$sectorUsuario){
                            $pedidosPendientesPorSector[] = $pedido;
                        }
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
    public function VerPedidosPreparacion($request, $response, $args)
    {
        $usuario = $request->getAttribute('usuario');
        $usuarioSeleccionado = Usuario::obtenerUsuario($usuario->usuario);

        if($usuarioSeleccionado){
            $sectorUsuario = $usuarioSeleccionado->sector;
            $pedidosEnPreparacion = Pedido::obtenerTodos();
            $pedidosEnPreparacionPorSector = [];

            if($sectorUsuario!="socio"){

                foreach($pedidosEnPreparacion as $pedido){
                    if($pedido->estado==Pedido::ESTADO_ENPREPARACION){
                        $productoAPreparar =Producto::obtenerProducto($pedido->id_producto);
                        if($productoAPreparar->sectorDePreparacion==$sectorUsuario){
                            $pedidosEnPreparacionPorSector[] = $pedido;
                        }
                    }
                    
                }
                if(count($pedidosEnPreparacionPorSector)>0){
                    $payload = json_encode(array(("En Preparacion en ".$sectorUsuario) => $pedidosEnPreparacionPorSector));
                }else{
                    $payload = json_encode(array("No hay pedidos en preparacion en ".$sectorUsuario));
                }
            }else{
                if(count($pedidosEnPreparacion)>0){
                    $payload = json_encode(array(("Pedidos")=>$pedidosEnPreparacion));
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
            $id_pedido = $request->getQueryParams()['id_pedido'];
            $usuario = $request->getAttribute('usuario');
            $usuarioSeleccionado = Usuario::obtenerUsuario($usuario->usuario);
            if($usuarioSeleccionado){
                $retorno= Pedido::CambiarEstado(Pedido::ESTADO_ENPREPARACION, $id_pedido);
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
            $id_pedido = $request->getQueryParams()['id_pedido'];
            $usuario = $request->getAttribute('usuario');
            $usuarioSeleccionado = Usuario::obtenerUsuario($usuario->usuario);
            if($usuarioSeleccionado){
                $retorno= Pedido::CambiarEstado(Pedido::ESTADO_LISTO, $id_pedido);
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