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


class Main extends Handle
{
    use Middleware;


    public function __invoke(Request $request, Response $response, callable $next)
    {
        /* @var \Slim\Http\Response $response */

        $response = $response->withHeader('Access-Control-Allow-Headers', 'Content-Type');
        $response = $response->withHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, DELETE, PUT, PATCH');

        if ($request->isOptions())
        {
            return $response->withStatus(204);
        }

        $response = $next($request, $response);

        if (!$response->getBody()->getSize() && $response->isOk())
        {
            $response = $response->withStatus(204);
        }

        return $response;
    }
}