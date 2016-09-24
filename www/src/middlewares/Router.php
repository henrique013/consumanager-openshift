<?php
/**
 * Created by PhpStorm.
 * User: Henrique
 * Date: 29/07/2016
 * Time: 14:19
 */

namespace App\Middleware;

use App\Util\Handle;
use App\Util\Handle\Middleware;
use Slim\Http\Request;
use Slim\Http\Response;


class Router extends Handle
{
    use Middleware;


    public function __invoke(Request $request, Response $response, callable $next)
    {
        /* @var \Slim\Http\Response $response */


        $path = $request->getUri()->getPath();


        if (in_array($path, ['', '/']))
        {
            return $response->withRedirect('/auth/login');
        }


        return $next($request, $response);
    }
}