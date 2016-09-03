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


class Consulta extends Handle {

    use GET;


    function get(Request $request, Response $response) {

        $conID = $request->getAttribute('id');

        /** @var \Twig_Environment $twig */
        $twig = $this->ci->get('twig');

        $context = $this->ci->get('settings')['Twig']['context']['sistema'];

        $view = $twig->render('consulta/consulta.twig', $context);

        $response->getBody()->write($view);


        return $response;
    }
}