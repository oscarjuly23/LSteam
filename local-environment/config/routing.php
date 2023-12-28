<?php
declare(strict_types=1);

use SallePW\SlimApp\Controller\FileController;
use SallePW\SlimApp\Controller\HomeController;
use SallePW\SlimApp\Controller\LoginController;
use SallePW\SlimApp\Controller\SimpleFormController;
use SallePW\SlimApp\Controller\VisitsController;
use SallePW\SlimApp\Middleware\StartSessionMiddleware;
use SallePW\SlimApp\Controller\CookieMonsterController;
use SallePW\SlimApp\Controller\FlashController;
use SallePW\SlimApp\Controller\CreateUserController;

$app->add(StartSessionMiddleware::class);

$app->get(
    '/',
    HomeController::class . ':apply')
    ->setName('home');

$app->get(
    '/visits',
    VisitsController::class . ":showVisits"
)->setName('visits');

$app->get(
    '/cookies',
    CookieMonsterController::class . ":showAdvice"
)->setName('cookies');

$app->get(
    '/flash',
    FlashController::class . ":addMessage"
)->setName('flash');

$app->post(
    '/user',
    CreateUserController::class . ":apply"
)->setName('create_user');

$app->get(
    '/simple-form',
    SimpleFormController::class . ":showForm"
);

$app->post(
    '/simple-form',
    SimpleFormController::class . ":handleFormSubmission"
)->setName('handle-form');

$app->get(
    '/files',
    FileController::class . ':showFileFormAction'
);

$app->post(
    '/files',
    FileController::class . ':uploadFileAction'
)->setName('upload');

$app->get(
    '/login',
    LoginController::class . ':showLoginFormAction'
);

$app->post('/login',
    LoginController::class . ':loginAction'
)->setName('login');