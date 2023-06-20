<?php
//PARZIALR JEREMIAS DNI 42839805

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;



require __DIR__ . '/./vendor/autoload.php';
require_once './controllers/ProductoController.php';
require_once './controllers/UsuarioController.php';
require_once './controllers/MesaController.php';
require_once './controllers/PedidoController.php';
require_once './controllers/SectorController.php';
require_once './controllers/EncuestaController.php';
require_once './db/AccesoDatos.php';

require_once 'middlewares/UsuariosMW.php';


$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$app = AppFactory::create();
$app->setBasePath('/app');
$app->addRoutingMiddleware();


$errorMiddleware = $app->addErrorMiddleware(true, true, true);

$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("hola soy jeremias probando el tp la comanda");
    return $response;
});

//Genera el Token
$app->post('/empleados/login[/]', \UsuarioController::class . ':Login');

$app->group('/usuarios', function (RouteCollectorProxy $group) {
  //socio
  $group->post('[/]', \UsuarioController::class . ':CargarUno');
  $group->get('/', \UsuarioController::class . ':TraerTodos');
  $group->delete('/', \UsuarioController::class . ':BorrarUno');
})
->add(UsuariosMW::class. ':ValidarToken')
->add(UsuariosMW::class. ':ValidarSocio');

// peticiones
$app->group('/productos', function (RouteCollectorProxy $group) {
  //socio
  $group->post('[/]', \ProductoController::class . ':CargarUno');
  $group->get('/', \ProductoController::class . ':TraerTodos');
})
->add(UsuariosMW::class. ':ValidarToken')
->add(UsuariosMW::class. ':ValidarSocio');




////////////////////MESAS////////////////////////
$app->group('/mesas', function (RouteCollectorProxy $group) {
  //mozo
  $group->post('[/]', \MesaController::class . ':CargarUno');
   //Subir Foto
  $group->post('/subirfoto[/]', \MesaController::class . ':SubirFoto');
  $group->post('/comiendo[/]', \MesaController::class . ':PasarAComiendo'); 
  $group->post('/pagando[/]', \MesaController::class . ':PasarAPagando'); 

  //socio
  $group->get('/', \MesaController::class . ':TraerTodos')
  ->add(UsuariosMW::class. ':ValidarToken')
  ->add(UsuariosMW::class. ':ValidarSocio');
  $group->post('/cerrada[/]', \MesaController::class . ':PasarACerrada')
  ->add(UsuariosMW::class. ':ValidarToken')
  ->add(UsuariosMW::class. ':ValidarSocio');
});

////////////////////PEDIDOS////////////////////////
$app->group('/pedidos', function (RouteCollectorProxy $group) {
  //mozo
  $group->post('[/]', \PedidoController::class . ':CargarUno');
  $group->post('/paraservir[/]', \PedidoController::class . ':TraerListosParaServir'); 

  //socio
  $group->get('/', \PedidoController::class . ':TraerTodos')
  ->add(UsuariosMW::class. ':ValidarToken')
  ->add(UsuariosMW::class. ':ValidarSocio');

  //cliente
  $group->post('/pedido', \PedidoController::class . ':MostrarEstadoACliente');
  //empleados
  $group->post('/pendientes', \SectorController::class . ':VerPedidosPendientes');
  $group->post('/enpreparacion[/]', \SectorController::class . ':PedidoEnPreparacion');
  $group->post('/listo[/]', \SectorController::class . ':PedidoListo');
});

$app->group('/encuesta', function (RouteCollectorProxy $group) {
  //cliente
  $group->post('[/]', \EncuestaController::class . ':CargarUno');
});


// Run app
$app->run();

