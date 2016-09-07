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
        /** @var \Twig_Environment $twig */
        /** @var \GuzzleHttp\Client $api */


        $pacID = $request->getAttribute('id');
        $api = $this->ci->get('API');
        $context = [];


        $resp = $api->get("pacientes/tipos");
        $context['tipos'] = ($resp->getStatusCode() === 204) ? [] : json_decode($resp->getBody(), true);


        $resp = $api->get("uf");
        $context['estados'] = ($resp->getStatusCode() === 204) ? [] : json_decode($resp->getBody(), true);


        if ($pacID)
        {
            $resp = $api->get("pacientes/{$pacID}");
            if ($resp->getStatusCode() === 204) return $response->withRedirect('/cadastro/pacientes');
            $context['paciente'] = json_decode($resp->getBody(), true);
        }


        $twig = $this->ci->get('twig');
        $view = $twig->render('cadastro/paciente/paciente.twig', $context);
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