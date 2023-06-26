<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './models/Usuario.php';
require_once './models/Puesto.php';
class VerificacionesMW
{
    public function ValidarPostEncuesta($request, $handler)
    {
        $parametros = $request->getParsedBody();

        $id_mesa = $parametros['id_mesa'] ?? null;
        $puntuacionmozo = $parametros['puntuacionmozo'] ?? null;
        $puntuacionRestaurante = $parametros['puntuacionRestaurante'] ?? null;
        $puntuacionMesa = $parametros['puntuacionMesa'] ?? null;
        $puntuacionCocinero = $parametros['puntuacionCocinero'] ?? null;
        $comentario = $parametros['comentario'] ?? null;

        if (empty($id_mesa) || empty($puntuacionmozo) || empty($puntuacionRestaurante)|| empty($puntuacionMesa)|| empty($puntuacionCocinero)|| empty($comentario)) {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "Faltan parámetros o están vacíos"));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        return $handler->handle($request);
    }
    public function ValidarPostPedido($request, $handler)
    {
        $parametros = $request->getParsedBody();

        $id_producto = $parametros['id_producto'] ?? null;
        $id_mesa = $parametros['id_mesa'] ?? null;
        $cantidadProducto = $parametros['cantidadProducto'] ?? null;

        if (empty($id_producto) || empty($id_mesa) || empty($cantidadProducto)) {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "Faltan parámetros o están vacíos"));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        return $handler->handle($request);
    }
    public function ValidarPostMesa($request, $handler)
    {
        $parametros = $request->getParsedBody();

        $nombreCliente = $parametros['nombreCliente'] ?? null;
        $cantidadComensales = $parametros['cantidadComensales'] ?? null;
        $numeroMesa = $parametros['numeroMesa']  ?? null;


        if (empty($nombreCliente) || empty($cantidadComensales) || empty($numeroMesa)) {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "Faltan parámetros o están vacíos"));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        return $handler->handle($request);
    }
    public function ValidarPostProducto($request, $handler)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'] ?? null;
        $tiempoPreparacion = $parametros['tiempoPreparacion'] ?? null;
        $sectorDePreparacion = $parametros['sectorDePreparacion'] ?? null;
        $precio = $parametros['precio'] ?? null;

        if (empty($nombre) || empty($tiempoPreparacion) || empty($sectorDePreparacion) || empty($precio)) {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "Faltan parámetros o están vacíos"));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json')->withStatus(400);
        }

        return $handler->handle($request);
    }

    public function ValidarPostUsuario($request, $handler)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'] ?? '';
        $apellido = $parametros['apellido'] ?? '';
        $dni = $parametros['dni'] ?? '';
        $fecha_nacimiento = $parametros['fecha_nacimiento'] ?? '';
        $sector = $parametros['sector'] ?? '';
        $puesto = $parametros['puesto'] ?? '';
        $fecha_contratacion = $parametros['fecha_contratacion'] ?? '';
        $usuario = $parametros['usuario'] ?? '';
        $clave = $parametros['clave'] ?? '';

        if (empty($nombre) || empty($apellido) || empty($dni) || empty($fecha_nacimiento) ||
            empty($sector) || empty($puesto) || empty($fecha_contratacion) || empty($usuario) || empty($clave)) {
            $response = new Response();
            $response->getBody()->write(json_encode(array("error" => "Faltan parámetros")));
            return $response->withStatus(400)->withHeader('Content-Type', 'application/json');
        }

        return $handler->handle($request);
    }
}
