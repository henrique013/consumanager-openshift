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
use Slim\Http\Body;
use Slim\Http\Request;
use Slim\Http\Response;


class Layout extends Handle
{
    use Middleware;


    public function __invoke(Request $request, Response $response, callable $next)
    {
        /** @var \Slim\Http\Response $response */
        /** @var \Twig_Environment $twig */


        $response = $next($request, $response);


        if ($response->isRedirection())
        {
            return $response;
        }


        $twig = $this->ci->get('twig_layout');
        $path = $request->getUri()->getPath();
        if ($path !== '/auth/login' && !preg_match("/^\/ajax/",$path))
        {
            $context['template'] = (string)$response->getBody();
            $view = $twig->render('sistema/sistema.twig', $context);

            $stream = fopen('php://memory', 'w+');
            fwrite($stream, $view);

            $response = $response->withBody(new Body($stream));
        }


        return $response;
    }
}