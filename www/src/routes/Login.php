<?php
/**
 * Created by PhpStorm.
 * User: Henrique
 * Date: 29/07/2016
 * Time: 14:01
 */

namespace App\Route;

use App\Util\Handle;
use App\Util\Handle\GET;
use Slim\Http\Request;
use Slim\Http\Response;


class Login extends Handle {

    use GET;


    public function get(Request $request, Response $response) {

        /** @var \Twig_Environment $twig */

        $twig = $this->ci->get('twig');

        $context['api_host'] = $this->ci->get('settings')['APP']['api']['host'];

        $view = $twig->render('login.twig', $context);

        $response->getBody()->write($view);

        return $response;
    }
}