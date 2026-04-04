<?php
require './vendor/autoload.php';

use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
use Illuminate\Routing\Contracts\CallableDispatcher;
use Illuminate\Routing\CallableDispatcher as DefaultCallableDispatcher;
use Src\Infra\Http\Router\Routes;

$container = new Container();
$container->bind(CallableDispatcher::class, DefaultCallableDispatcher::class);

$events = new Dispatcher($container);
$router = new Router($events, $container);
Routes::register($router);
$request = Request::capture();
$response = $router->dispatch($request);
$response->send();