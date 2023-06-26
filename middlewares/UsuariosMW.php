<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;

require_once './models/Usuario.php';
require_once './models/Puesto.php';


class UsuariosMW
{

    public static function ValidarToken($request, $handler)
    {
        $header = $request->getHeaderLine('Authorization');
        $response = new Response();

        if(!empty($header))
        {
            $token = trim(explode("Bearer", $header)[1]);
            AutentificadorJWT::VerificarToken($token);
            $response = $handler->handle($request);
        }
        else
        {
            $response->getBody()->write(json_encode(array("Token error" => "No hay token.")));
            $response = $response->withStatus(401);
        }
        return  $response->withHeader('Content-Type', 'application/json');
    }

    public function ValidarSocio($request, $handler)
    {
        try 
        {
            $header = $request->getHeaderLine('Authorization');
            if(!empty($header))
            {
                $token = trim(explode("Bearer", $header)[1]);
                $data = AutentificadorJWT::VerificarToken($token);
                if($data->data->puesto == Puesto::PUESTO_SOCIO)
                {
                    return $handler->handle($request);
                }
                throw new Exception("Usuario no autorizado");
            }
            else
            {  
                throw new Exception("Token vacío");
            }
        } 
        catch (Exception $e) 
        {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "ERROR, ".$e->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');;
        }
    }
    public function ValidarMozo($request, $handler)
    {
        try 
        {
            $header = $request->getHeaderLine('Authorization');
            if(!empty($header))
            {
                $token = trim(explode("Bearer", $header)[1]);
                $data = AutentificadorJWT::VerificarToken($token);
                if($data->data->puesto == Puesto::PUESTO_MOZO || $data->data->puesto == Puesto::PUESTO_SOCIO)
                {
                    $request = $request->withAttribute('usuario', $data->data);
                    return $handler->handle($request);
                }
                throw new Exception("Usuario no autorizado");
            }
            else
            {                                                                                              
                throw new Exception("Token vacío");
            }
        } 
        catch (Exception $e) 
        {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "ERROR, ".$e->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');;
        }
    }
    public function ValidarEmpleadoDePreparacion($request, $handler)
    {
        try 
        {
            $header = $request->getHeaderLine('Authorization');
            if(!empty($header))
            {
                $token = trim(explode("Bearer", $header)[1]);
                $data = AutentificadorJWT::VerificarToken($token);
                if($data->data->puesto == Puesto::PUESTO_BARTENDER || $data->data->puesto == Puesto::PUESTO_CERVECERO || $data->data->puesto == Puesto::PUESTO_COCINERO || $data->data->puesto == Puesto::PUESTO_PASTELERO || $data->data->puesto == Puesto::PUESTO_SOCIO)
                {
                    $request = $request->withAttribute('usuario', $data->data);
                    return $handler->handle($request);
                }
                throw new Exception("Usuario no autorizado");
            }
            else
            {                                                                                              
                throw new Exception("Token vacío");
            }
        } 
        catch (Exception $e) 
        {
            $response = new Response();
            $payload = json_encode(array("mensaje" => "ERROR, ".$e->getMessage()));
            $response->getBody()->write($payload);
            return $response->withHeader('Content-Type', 'application/json');;
        }
    }
    

    
}
