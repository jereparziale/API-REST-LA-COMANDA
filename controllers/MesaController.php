<?php
require_once './models/Mesa.php';
// require_once './interfaces/IApiUsable.php';
require_once './models/Sector.php';

class MesaController extends Mesa{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id_mesa = $parametros['id_mesa'];
        $id_mozo = $parametros['id_mozo'];//debe cargarse solo por usuario de mozo
        $nombreCliente = $parametros['nombreCliente'];
        $cantidadComensales = $parametros['cantidadComensales'];

        // Creamos el Mesa
        $prod = new Mesa();
        $prod->id_mesa = $id_mesa;
        $prod->id_mozo = $id_mozo;
        $prod->nombreCliente = $nombreCliente;
        $prod->estado = Mesa::ESTADO_ESPERANDO;
        $prod->cantidadComensales = $cantidadComensales;
        $prod->importeTotal = 0;
        $fecha = new DateTime(date("d-m-Y H:i:s"));
        $prod->fechaApertura = $fecha->format("Y-m-d H:i:s");
        $prod->crearMesa();

        $payload = json_encode(array("mensaje" => "Mesa creado con exito"));

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
      $params = $request->getParsedBody();
      $id_mesa = $params["id_mesa"];
      $id_pedido = $params["id_pedido"];
      Mesa::CambiarEstadoAComiendo($id_mesa,$id_pedido);
      $payload = json_encode("En la mesa ".$id_mesa." están comiendo.");
      $response->getBody()->write($payload);
      return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function PasarAPagando($request, $response, $args)
    {
      $params = $request->getParsedBody();
      $id_mesa = $params["id_mesa"];
      Mesa::CambiarEstadoAPagando($id_mesa);
      $payload = json_encode("En la mesa ".$id_mesa." están pagando.");
      $response->getBody()->write($payload);
      return $response
          ->withHeader('Content-Type', 'application/json');
    }
    public function PasarACerrada($request, $response, $args)
    {
      $params = $request->getParsedBody();
      $id_mesa = $params["id_mesa"];
      Mesa::CambiarEstadoACerrada($id_mesa);
      $payload = json_encode("La mesa ".$id_mesa." esta cerrada.");
      $response->getBody()->write($payload);
      return $response
          ->withHeader('Content-Type', 'application/json');
    }
 
}