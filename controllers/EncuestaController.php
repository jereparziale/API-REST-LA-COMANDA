<?php
require_once './models/Encuesta.php';
// require_once './interfaces/IApiUsable.php';
require_once './models/Encuesta.php';

class EncuestaController extends Encuesta{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $id_mesa = intval($parametros['id_mesa']);
        $puntuacionmozo = intval($parametros['puntuacionmozo']);
        $puntuacionRestaurante = intval($parametros['puntuacionRestaurante']);
        $puntuacionMesa = intval($parametros['puntuacionMesa']);
        $puntuacionCocinero = intval($parametros['puntuacionCocinero']);
        $comentario = $parametros['comentario'];


        // Creamos el Encuesta
        $enc = new Encuesta();
        $enc->id_mesa = $id_mesa;
        $enc->puntuacionmozo = $puntuacionmozo;
        $enc->puntuacionRestaurante = $puntuacionRestaurante;
        $enc->puntuacionMesa = $puntuacionMesa;
        $enc->puntuacionCocinero = $puntuacionCocinero;
        $enc->comentario = $comentario;
        $fecha = new DateTime(date("d-m-Y H:i:s"));
        $enc->fechaEncuesta = $fecha->format("Y-m-d H:i:s");
        $enc->crearEncuesta();
        $payload = json_encode(array("mensaje" => "Encuesta creada con exito"));

        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();
        $id_encuesta = $parametros['id_encuesta'];
        $encuesta = Encuesta::obtenerEncuesta($id_encuesta);
        $payload = json_encode($encuesta);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }
   
}