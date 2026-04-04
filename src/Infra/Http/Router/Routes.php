<?php

namespace Src\Infra\Http\Router;

use Illuminate\Routing\Router;
use Src\Infra\Http\Router\Response\Response;

class Routes
{
    public static function register(Router $router)
    { 
        $router->get('/', fn() => Response::OK(message: "Server is On",));
        UserRouter::register($router);
    }
}
