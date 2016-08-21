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
        if ($request->isOptions())
        {
            return $response->withStatus(204);
        }


        /* @var \Slim\Http\Response $response */
        $response = $next($request, $response);


        if (!$response->getBody()->getSize())
        {
            $response = $response->withStatus(204);
        }


        // CORS
        $response = $response->withHeader('Access-Control-Allow-Origin', '*');
        $response = $response->withHeader('Access-Control-Allow-Headers', 'X-Api-Token, Content-Type');
        $response = $response->withHeader('Access-Control-Allow-Methods', 'POST, GET, OPTIONS, DELETE, PUT, PATCH');


        return $response;
    }
}