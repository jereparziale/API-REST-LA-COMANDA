<?php
require_once './models/Producto.php';
// require_once './interfaces/IApiUsable.php';
require_once './models/Sector.php';

class ProductoController extends Producto{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $tiempoPreparacion = $parametros['tiempoPreparacion'];
        $sectorDePreparacion = $parametros['sectorDePreparacion'];
        $precio = $parametros['precio'];

        // Creamos el Producto
        $prod = new Producto();
        $prod->nombre = $nombre;
        $prod->tiempoPreparacion = $tiempoPreparacion;
        $prod->sectorDePreparacion = Sector::getSector($sectorDePreparacion);
        $prod->precio = $precio;
        $prod->crearProducto();


        $payload = json_encode(array("mensaje" => "Producto creado con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos Producto por nombre
        $prod = $args['Producto'];
        $Producto = Producto::obtenerProducto($prod);
        var_dump($Producto);
        $payload = json_encode($Producto);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Producto::obtenerTodos();
        if(count($lista)>0){
            $payload = json_encode(array("listaProducto" => $lista));
          }else{
            $payload = json_encode(array("No hay productos en sistema."));
          }
        

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
   
}