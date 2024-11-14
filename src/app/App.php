
<?php
use DI\Container;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Routing\RouteCollectorProxy;
use Slim\Routing\RouteContext;
use Slim\Factory\AppFactory;
use Tuupola\Middleware\CorsMiddleware;
use Tuupola\Middleware\JwtAuthentication;

require __DIR__ . '/../../vendor/autoload.php';

$container = new Container();
AppFactory::setContainer($container);
$app = AppFactory::create();
$container = $app->getContainer();
$app->setBasePath("/backend/public");

$app->addBodyParsingMiddleware();

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Authorization, X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__FILE__,3)); //Para leer las variables (3: para retroceder niveles del path).
$dotenv->safeLoad();

$app->add(new JwtAuthentication([
    "ignore" => ["/backend/public/api/seguridad/*"],
    "secret" => $_ENV['JWT_SECRET']
]));

$app->addRoutingMiddleware(); // The RoutingMiddleware should be added after our CORS middleware so routing is performed first.

require __DIR__ ."/Routes.php";
require __DIR__ . '/Config.php';
require __DIR__ . '/../services/SeguridadService.php';

$app->run();