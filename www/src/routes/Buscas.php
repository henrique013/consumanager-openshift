<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 23/08/16
 * Time: 20:41
 */

namespace App\Route;


use App\Util\Handle;
use App\Util\Handle\GET;
use Slim\Http\Request;
use Slim\Http\Response;


class Buscas extends Handle {

    use GET;


    function get(Request $request, Response $response) {

        /** @var \Twig_Environment $twig */
        $twig = $this->ci->get('twig');

        $view = $twig->render('buscas/buscas.twig');

        $response->getBody()->write($view);


        return $response;
    }
}