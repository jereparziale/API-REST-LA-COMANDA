<?php
require_once './models/Producto.php';
require_once './models/Sector.php';

class ProductoController extends Producto{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $tiempoPreparacion = $parametros['tiempoPreparacion'];
        $sectorDePreparacion = $parametros['sectorDePreparacion'];
        $precio = $parametros['precio'];
        if(ProductoController::ValidacionesProductoPost($request) !=false){
           // Creamos el Producto
          $prod = new Producto();
          $prod->nombre = $nombre;
          $prod->tiempoPreparacion = $tiempoPreparacion;
          $prod->sectorDePreparacion = Sector::getSector($sectorDePreparacion);
          $prod->precio = $precio;
          $prod->crearProducto();
          $payload = json_encode(array("mensaje" => "Producto creado con exito"));
        }else{
          $payload = json_encode(array("mensaje" => "Producto no creado, error en los datos"));
        }
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

    public function CargarDesdeCsv($request, $response, $args)
    {
      $archivos = $request->getUploadedFiles();
      $archivo = $archivos["archivo"];
      $contadorproductosCorrectos = 0;
      $delimitadorCSV = ",";

      $csvData = $archivo->getStream()->getContents();
      $lineas = explode("\n",$csvData);

      // El metodo explode si no encuentra datos, la siguiente linea la determina en blanco por lo que debemos filtrar el listado
      $lineas = array_filter($lineas,function($valor){return trim($valor) !=="";});

      if($lineas && count($lineas)>0)
      {
        $headers = $lineas[0];
        $camposHeader = str_getcsv($headers,$delimitadorCSV);
        if($camposHeader[0]=="nombre" && $camposHeader[1]=="tiempoPreparacion" && $camposHeader[2]=="sectorDePreparacion" && $camposHeader[3]=="precio")
        {
            array_splice($lineas,0,1);

            foreach($lineas as $index => $linea)
            {
                $campos = str_getcsv($linea,$delimitadorCSV);

                $nombre = $campos[0];
                $tiempoPreparacion = $campos[1];
                $sectorDePreparacion = $campos[2];
                $precio = $campos[3];

        
                if(isset($nombre) && !empty($nombre) && isset($tiempoPreparacion) && !empty($tiempoPreparacion) && isset($sectorDePreparacion) && !empty($sectorDePreparacion) && isset($precio) && !empty($precio))
                {
                  echo("paseeee");

                    $sectorDePreparacion = strtolower($sectorDePreparacion);
                    if($sectorDePreparacion == Sector::SECTOR_BARRA_TRAGOS_VINOS  || $sectorDePreparacion == Sector::SECTOR_COCINA || $sectorDePreparacion == Sector::SECTOR_BARRA_CERVEZA || $sectorDePreparacion == Sector::SECTOR_CANDYBAR)
                    {
                    $producto = new producto();
                    $producto->nombre = $nombre;
                    $producto->tiempoPreparacion = $tiempoPreparacion;
                    $producto->sectorDePreparacion = $sectorDePreparacion;
                    $producto->precio = $precio;
                    $producto->crearproducto();
                    $contadorproductosCorrectos ++;
                    }
                }
            $payload = json_encode(array("mensaje" => "Se procesaron los productos: Correctos ".$contadorproductosCorrectos));
            }
    }else{
          $payload = json_encode(array("mensaje" => "El archivo no tiene el formato correcto. Debe ser: nombre,tiempoPreparacion,sectorDePreparacion,precio"));
        }
      }
      else{
        $payload = json_encode(array("mensaje" => "El archivo no tiene contenido"));
      }
      $response->getBody()->write($payload);
      return $response
        ->withHeader('Content-Type', 'application/json');
    }

    function DescargarACsv($request, $response, $args)
        {
          $listaProductos = Producto::obtenerTodos();
          $dataProductos = array();
          $delimitadorCSV = ",";
          array_push($dataProductos, "id_producto".$delimitadorCSV."nombre".$delimitadorCSV."tiempoPreparacion".$delimitadorCSV."sectorDePreparacion".$delimitadorCSV."precio");
          foreach($listaProductos as $producto)
          {
            array_push($dataProductos,$producto->id_producto.$delimitadorCSV.$producto->nombre.$delimitadorCSV.$producto->tiempoPreparacion.$delimitadorCSV.$producto->sectorDePreparacion.$delimitadorCSV.$producto->precio.$delimitadorCSV);
          }
          $dataCsv = implode("\n",$dataProductos);
          // Establece los encabezados de respuesta para indicar que se devuelve un archivo CSV
          $response = $response->withHeader('Content-Type','text/csv');
          $response = $response->withHeader('Content-Disposition', 'attachment; filename="productos.csv"');
          $response->getBody()->write($dataCsv);
          return $response;
        }




        public static function ValidacionesProductoPost($request){
          $parametros = $request->getParsedBody();
          $nombre = $parametros['nombre'];
          $tiempoPreparacion = $parametros['tiempoPreparacion'];
          $sectorDePreparacion = $parametros['sectorDePreparacion'];
          $precio = $parametros['precio'];

          if (!is_string($nombre) || empty($nombre)) {
            return false;
          }
          if (!is_numeric($tiempoPreparacion) || $tiempoPreparacion <= 0 || !is_int($tiempoPreparacion + 0)) {
              return false;
          }

          $sectoresValidos = [
              'barra_tragos_vinos',
              'cocina',
              'barra_cerveza',
              'candybar'
          ];
          if (!in_array($sectorDePreparacion, $sectoresValidos)) {
            return false;
          }

          if (!is_numeric($precio) || $precio <= 0) {
            return false;
          }
        }





}
   



