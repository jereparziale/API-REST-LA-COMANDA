<?php
require_once './models/Usuario.php';
require_once './middlewares/AutentificadorJWT.php';
// require_once './interfaces/IApiUsable.php';

class UsuarioController extends Usuario 
{
    public function CargarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $nombre = $parametros['nombre'];
        $apellido = $parametros['apellido'];
        $dni = $parametros['dni'];
        $fecha_nacimiento = $parametros['fecha_nacimiento'];
        $sector = $parametros['sector'];
        $puesto = $parametros['puesto'];
        $fecha_contratacion = $parametros['fecha_contratacion'];
        $usuario = $parametros['usuario'];
        $clave = $parametros['clave'];

        // Creamos el usuario
        $usr = new Usuario();
        $usr->nombre = $nombre;
        $usr->apellido = $apellido;
        $usr->dni = $dni;
        $usr->fechaNacimiento = $fecha_nacimiento;
        $usr->sector = $sector;
        $usr->puesto = $puesto;
        $usr->fechaContratacion = $fecha_contratacion;
        $usr->usuario = $usuario;
        $usr->clave = $clave;
        $usr->estado = Usuario::ESTADO_ACTIVO;
        $usr->crearUsuario();

        $payload = json_encode(array("mensaje" => "Usuario creado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerUno($request, $response, $args)
    {
        // Buscamos usuario por nombre
        $usr = $args['usuario'];
        $usuario = Usuario::obtenerUsuario($usr);
        $payload = json_encode($usuario);

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function TraerTodos($request, $response, $args)
    {
        $lista = Usuario::obtenerTodos();
        if(count($lista)>0){
          $payload = json_encode(array("listaUsuario" => $lista));
        }else{
          $payload = json_encode(array("No hay usuarios en sistema."));
        }

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }

    public function BorrarUno($request, $response, $args)
    {
        $parametros = $request->getParsedBody();

        $usuarioId = $parametros['usuarioId'];
        Usuario::borrarUsuario($usuarioId);

        $payload = json_encode(array("mensaje" => "Usuario borrado con exito"));

        $response->getBody()->write($payload);
        return $response
          ->withHeader('Content-Type', 'application/json');
    }


    public function Login($request, $response, $args)
    {
        try
        {
            $params = $request->getParsedBody();
            $usuario = $params["usuario"];
            $clave = $params["clave"];
            $usuarioLogueado = Usuario::ValidarExtraerUsuario($usuario, $clave);
            //var_dump($usuarioLogueado);
    
            if($usuarioLogueado != null)
            {
                $datos = array('usuario' => $usuarioLogueado->usuario, 'puesto' => $usuarioLogueado->puesto);
                $token = AutentificadorJWT::CrearToken($datos);
                $respuesta = $token;
            }
            else
            {
                $respuesta = "Usuario y/o contraseña incorrectos.";
            }
    
            $payload = json_encode($respuesta);
            $response->getBody()->write($payload);
            $newResponse = $response->withHeader('Content-Type', 'application/json');
        }
        catch(Throwable $mensaje)
        {
            printf("Error al loguearse: <br> $mensaje .<br>");
        }
        finally
        {
            return $newResponse;
        }
    }
    
}