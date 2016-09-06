<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 23/08/16
 * Time: 20:41
 */

namespace App\Route\Cadastro;


use App\Util\Handle;
use App\Util\Handle\DELETE;
use App\Util\Handle\GET;
use App\Util\Handle\POST;
use App\Util\Handle\PUT;
use Slim\Http\Request;
use Slim\Http\Response;


class Paciente extends Handle
{
    use GET;
    use POST;
    use PUT;
    use DELETE;


    function get(Request $request, Response $response)
    {
        $pacID = $request->getAttribute('id');

        /** @var \Twig_Environment $twig */
        $twig = $this->ci->get('twig');

        $view = $twig->render('cadastro/paciente/paciente.twig');

        $response->getBody()->write($view);


        return $response;
    }


    public function delete(Request $request, Response $response)
    {
        return $response->withStatus(501);
    }


    public function post(Request $request, Response $response)
    {
        return $response->withStatus(501);
    }


    public function put(Request $request, Response $response)
    {
        return $response->withStatus(501);
    }
}