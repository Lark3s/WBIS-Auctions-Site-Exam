<?php
    require_once 'vendor/autoload.php';
    require_once 'Configuration.php';

    use App\Controllers\MainController;
    use App\Core\ApiController;
    use App\Core\DatabaseConnection;
    use App\Core\DatabaseConfig;
    use App\Core\Fingerprint\BasicFingerprintProviderFactory;
    use App\Core\Router;
    use App\Core\Session\Session;
    use App\Models\UserModel;
    use App\Models\CategoryModel;
    use Twig\Environment;
    use Twig\Loader\FilesystemLoader;

    ob_start();

    $databaseConfig = new DatabaseConfig(Configuration::DATABASE_HOST,
                                        Configuration::DATABASE_USER,
                                    Configuration::DATABASE_PASSWORD,
                                        Configuration::DATABASE_NAME);
    $databaseConnection = new DatabaseConnection($databaseConfig);

    $url = strval(filter_input(INPUT_GET, 'URL'));
    $httpMethod = filter_input(INPUT_SERVER, 'REQUEST_METHOD');

    $router = new Router();
    $routes = require_once 'Routes.php';
    foreach ($routes as $route) {
        $router->add($route);
    }

    $route = $router->find($httpMethod, $url);
    $arguments = $route->extractArguments($url);

    $fullControllerName = '\\App\\Controllers\\' . $route->getControllerName() . 'Controller';
    $controller = new $fullControllerName($databaseConnection);

    $fingerprintProviderFactoryClass = Configuration::FINGERPRINT_PROVIDER_FACTORY;
    $fingerprintProviderFactoryMethod = Configuration::FINGERPRINT_PROVIDER_METHOD;
    $fingerprintProviderFactoryArgs = Configuration::FINGERPRINT_PROVIDER_ARGS;
    $fingerprintProviderFactory = new $fingerprintProviderFactoryClass;
    $fingerprintProvider = $fingerprintProviderFactory->$fingerprintProviderFactoryMethod(...$fingerprintProviderFactoryArgs);

    $sessionStorageClassName = Configuration::SESSION_STORAGE;
    $sessionStorageConstructArgs = Configuration::SESSION_STORAGE_DATA;
    $sessionStorage = new $sessionStorageClassName(...$sessionStorageConstructArgs); // TODO: Read about ... in PHP

    $session = new Session($sessionStorage, Configuration::SESSION_LIFETIME);
    $session->setFingerprintProvider($fingerprintProvider);

    $controller->setSession($session);
    $controller->getSession()->reload();
    $controller->__pre();
    call_user_func_array([$controller, $route->getMethodName()], $arguments);
    $controller->getSession()->save();

    $data = $controller->getData();

    if ( $controller instanceof ApiController) {
        ob_clean();
        header('Content-type: application/json; charset=utf-8');
        header('Access-Control-Allow-Origin: *');
        echo json_encode($data);
        exit;
    }

    $loader = new FilesystemLoader("./views");
    $twig = new Environment($loader, [
        "cache" => "./twig-cache",
        "auto_reload" => true
    ]);

    $data['BASE'] = Configuration::BASE;

    echo $twig->render($route->getControllerName() . '/' . $route->getMethodName() . '.html', $data);