<?php
require_once './models/Mesa.php';
require_once './models/Sector.php';
require_once 'middlewares/UsuariosMW.php';

class MesaController extends Mesa{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $usuario = $request->getAttribute('usuario');
        $usuario_mozo = $usuario->usuario; 
        $nombreCliente = $parametros['nombreCliente'];
        $cantidadComensales = $parametros['cantidadComensales'];
        $numeroMesa = $parametros['numeroMesa'];

        if(MesaController::ValidacionesMesaPost($request)){
            // Creamos el Mesa
            $mes = new Mesa();
            $mes->numeroMesa = $numeroMesa;
            $mes->usuario_mozo = $usuario_mozo;
            $mes->nombreCliente = $nombreCliente;
            $mes->estado = Mesa::ESTADO_ESPERANDO;
            $mes->cantidadComensales = $cantidadComensales;
            $mes->importeTotal = 0;
            $fecha = new DateTime(date("d-m-Y H:i:s"));
            $mes->fechaApertura = $fecha->format("Y-m-d H:i:s");
            $mes->crearMesa();

            $payload = json_encode(array("mensaje" => "Mesa creado con exito"));
        }else{
          $payload = json_encode(array("mensaje" => "Mesa no creada, error en los datos"));
        }
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos Mesa por nombre
        $mesa = $args['id_mesa'];
        $Mesa = Mesa::obtenerMesa($mesa);
        var_dump($Mesa);
        $payload = json_encode($Mesa);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Mesa::obtenerTodos();
        if(count($lista)>0){
            $payload = json_encode(array("listaMesa" => $lista));
          }else{
            $payload = json_encode(array("No hay mesas en sistema."));
          }
        

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    
    public function SubirFoto($request, $response, $args)
    {
            $params = $request->getParsedBody();
            $mesa = new Mesa();
            $mesa->id_mesa = $params["id_mesa"];
            $archivo = ($_FILES["archivo"]);
            $mesa->ruta_foto = ($archivo["tmp_name"]);
            $mesa->GuardarImagen();
            $payload = json_encode("Carga exitosa.");
            $response->getBody()->write($payload);
            return $response
            ->withHeader('Content-Type', 'application/json');
    }

    public function PasarAComiendo($request, $response, $args)
    {
      $id_mesa = $request->getQueryParams()['id_mesa'];
      $id_pedido = $request->getQueryParams()['id_pedido'];
      Mesa::CambiarEstadoAComiendo($id_mesa,$id_pedido);
      $payload = json_encode("En la mesa ".$id_mesa." están comiendo.");
      $response->getBody()->write($payload);
      return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function PasarAPagando($request, $response, $args)
    {
      $id_mesa = $request->getQueryParams()['id_mesa'];
      $mesa = Mesa::CambiarEstadoAPagando($id_mesa);
      if($mesa !=null){
        $payload = json_encode("En la mesa ".$mesa->id_mesa." están pagando. El importe total es: $".$mesa->importeTotal);
      }else{
        $payload = json_encode("No se encontro la mesa o la misma no se encuentra comiendo para cerrar la cuenta");
      }
      $response->getBody()->write($payload);
      return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function PasarACerrada($request, $response, $args)
    {
      $id_mesa = $request->getQueryParams()['id_mesa'];
      Mesa::CambiarEstadoACerrada($id_mesa);
      $payload = json_encode("La mesa ".$id_mesa." esta cerrada.");
      $response->getBody()->write($payload);
      return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public static function ValidacionesMesaPost($request){
      $parametros = $request->getParsedBody();
      $nombreCliente = $parametros['nombreCliente'];
      $cantidadComensales = $parametros['cantidadComensales'];
      $numeroMesa = $parametros['numeroMesa'];
      if (!is_string($nombreCliente) || empty($nombreCliente)) {
        return false;
      }

      if (!is_numeric($cantidadComensales) || $cantidadComensales <= 0) {
        return false;
      }
      if (!is_numeric($numeroMesa) || ($numeroMesa <= 0 || $numeroMesa>20)) {
        return false;
      }
      return true;

    }
 
}