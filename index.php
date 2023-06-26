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
require_once 'middlewares/VerificacionesMW.php';


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
$app->post('/empleados/login[/]', \UsuarioController::class . ':Login');//okv

$app->group('/usuarios', function (RouteCollectorProxy $group) {
  //socio
  $group->post('[/]', \UsuarioController::class . ':CargarUno') 
    ->add(VerificacionesMW::class. ':ValidarPostUsuario'); //ok//okv
  $group->get('/', \UsuarioController::class . ':TraerTodos');//okv
  $group->delete('/', \UsuarioController::class . ':BorrarUno');//okv
})
->add(UsuariosMW::class. ':ValidarSocio');

// peticiones
$app->group('/productos', function (RouteCollectorProxy $group) {
  //socio
  $group->post('/CargarCsv', \ProductoController::class . ':CargarDesdeCsv');//ok//okv
  $group->get('/DescargarCsv', \ProductoController::class . ':DescargarACsv');//ok//okv
  $group->get('/', \ProductoController::class . ':TraerTodos');//ok//okv
  $group->post('[/]', \ProductoController::class . ':CargarUno')
  ->add(VerificacionesMW::class. ':ValidarPostProducto');//ok//okv
})
->add(UsuariosMW::class. ':ValidarSocio');


////////////////////MESAS////////////////////////
$app->group('/mesas', function (RouteCollectorProxy $group) {
  //mozo
  $group->post('[/]', \MesaController::class . ':CargarUno') ///ok//okv
  ->add(UsuariosMW::class. ':ValidarMozo')
  ->add(VerificacionesMW::class. ':ValidarPostMesa');
  $group->post('/subirfoto[/]', \MesaController::class . ':SubirFoto')->add(UsuariosMW::class. ':ValidarMozo');//ok
  $group->put('/comiendo', \MesaController::class . ':PasarAComiendo')->add(UsuariosMW::class. ':ValidarMozo'); //ok
  $group->put('/pagando', \MesaController::class . ':PasarAPagando')->add(UsuariosMW::class. ':ValidarMozo');//ok

  //socio
  $group->get('/', \MesaController::class . ':TraerTodos')->add(UsuariosMW::class. ':ValidarSocio');//ok
  $group->put('/cerrada[/]', \MesaController::class . ':PasarACerrada')->add(UsuariosMW::class. ':ValidarSocio');//ok
  
})->add(UsuariosMW::class. ':ValidarToken');

////////////////////PEDIDOS////////////////////////
$app->group('/pedidos', function (RouteCollectorProxy $group) {
  //mozo
  $group->post('[/]', \PedidoController::class . ':CargarUno')//ok
  ->add(UsuariosMW::class. ':ValidarMozo')
  ->add(VerificacionesMW::class. ':ValidarPostPedido');
  $group->get('/paraservir[/]', \PedidoController::class . ':TraerListosParaServir')->add(UsuariosMW::class. ':ValidarMozo');//ok

  //socio
  $group->get('/', \PedidoController::class . ':TraerTodos') ->add(UsuariosMW::class. ':ValidarSocio');//ok

  //cliente
  $group->get('/pedido', \PedidoController::class . ':MostrarEstadoACliente');//ok
  //empleados
  $group->get('/pendientes', \SectorController::class . ':VerPedidosPendientes')->add(UsuariosMW::class. ':ValidarEmpleadoDePreparacion');//ok
  $group->put('/enpreparacion', \SectorController::class . ':PedidoEnPreparacion')->add(UsuariosMW::class. ':ValidarEmpleadoDePreparacion');//ok
  $group->get('/enpreparacion', \SectorController::class . ':VerPedidosPreparacion')->add(UsuariosMW::class. ':ValidarEmpleadoDePreparacion');//ok
  $group->put('/listo', \SectorController::class . ':PedidoListo')->add(UsuariosMW::class. ':ValidarEmpleadoDePreparacion');//ok
});

$app->group('/encuesta', function (RouteCollectorProxy $group) {
  //cliente
  $group->post('[/]', \EncuestaController::class . ':CargarUno');//ok
}) ->add(VerificacionesMW::class. ':ValidarPostEncuesta');


// Run app
$app->run();

