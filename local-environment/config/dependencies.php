<?php
declare(strict_types=1);

use DI\Container;
use SallePW\SlimApp\Controller\FileController;
use SallePW\SlimApp\Controller\LoginController;
use SallePW\SlimApp\Controller\SimpleFormController;
use Slim\Views\Twig;
use SallePW\SlimApp\Controller\HomeController;
use Psr\Container\ContainerInterface;
use SallePW\SlimApp\Controller\VisitsController;
use SallePW\SlimApp\Controller\CookieMonsterController;
use Slim\Flash\Messages;
use SallePW\SlimApp\Controller\FlashController;
use SallePW\SlimApp\Model\Repository\MysqlUserRepository;
use SallePW\SlimApp\Controller\CreateUserController;
use SallePW\SlimApp\Model\UserRepository;
use SallePW\SlimApp\Model\Repository\PDOSingleton;

$container = new Container();

$container->set(
    'view',
    function () {
        return Twig::create(__DIR__ . '/../templates', ['cache' => false]);
    }
);

$container->set(
    'flash',
    function () {
        return new Messages();
    }
);

$container->set(
    HomeController::class,
    function (ContainerInterface $c) {
        $controller = new HomeController($c->get("view"), $c->get("flash"));
        return $controller;
    }
);

$container->set(
    VisitsController::class,
    function (ContainerInterface $c) {
        $controller = new VisitsController($c->get("view"));
        return $controller;
    }
);

$container->set(
    CookieMonsterController::class,
    function (ContainerInterface $c) {
        $controller = new CookieMonsterController($c->get("view"));
        return $controller;
    }
);

$container->set(
    FlashController::class,
    function (Container $c) {
        $controller = new FlashController($c->get("view"), $c->get("flash"));
        return $controller;
    }
);

$container->set(
    CreateUserController::class,
    function (Container $c) {
        $controller = new CreateUserController($c->get("view"), $c->get(UserRepository::class));
        return $controller;
    }
);

$container->set('db', function () {
    return PDOSingleton::getInstance(
        $_ENV['MYSQL_USER'],
        $_ENV['MYSQL_ROOT_PASSWORD'],
        $_ENV['MYSQL_HOST'],
        $_ENV['MYSQL_PORT'],
        $_ENV['MYSQL_DATABASE']
    );
});

$container->set(UserRepository::class, function (ContainerInterface $container) {
    return new MySQLUserRepository($container->get('db'));
});

$container->set(
    SimpleFormController::class,
    function (ContainerInterface $c) {
        $controller = new SimpleFormController($c->get("view"));
        return $controller;
    }
);

$container->set(
    FileController::class,
    function (ContainerInterface $c) {
        $controller = new FileController($c->get("view"));
        return $controller;
    }
);

$container->set(
    LoginController::class,
    function (ContainerInterface $c) {
        $controller = new LoginController($c->get("view"));
        return $controller;
    }
);
;