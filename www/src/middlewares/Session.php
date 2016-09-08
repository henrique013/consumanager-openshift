<?php

namespace App\Middleware;

use App\Util\Handle;
use App\Util\Handle\Middleware;
use Slim\Http\Request;
use Slim\Http\Response;


class Session extends Handle
{
    use Middleware;


    public function __invoke(Request $request, Response $response, callable $next)
    {
        /** @var \Slim\Http\Response $response */


        session_start();


        $path = $request->getUri()->getPath();

        if ($path === '/auth/login') // exceções
        {
            $response = $next($request, $response);
        }

        elseif (!isset($_SESSION['ultimo_acesso'])) // valida a sessão
        {
            $response = $response->withRedirect('/auth/login');
        }

        else // sessão válida
        {
            $t = time();

            if ($_SESSION['ultimo_acesso'] < $t - (60 * 5)) // atualiza o id da sessão de tempos em tempos
            {
                session_regenerate_id(true);
            }

            $_SESSION['ultimo_acesso'] = $t;

            $response = $next($request, $response);
        }


        return $response;
    }
}