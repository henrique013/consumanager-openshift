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
use GuzzleHttp\Exception\TransferException;
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
            if ($resp->getStatusCode() === 204) return $response->withRedirect('/cadastro/paciente');
            $context['paciente'] = json_decode($resp->getBody(), true);
        }


        $twig = $this->ci->get('twig');
        $view = $twig->render('cadastro/paciente/paciente.twig', $context);
        $response->getBody()->write($view);


        return $response;
    }


    public function post(Request $request, Response $response)
    {
        /** @var \GuzzleHttp\Client $api */


        $json = $request->getParsedBody();
        $api = $this->ci->get('API');


        try
        {
            $api->post("pacientes", ['json' => $json]);
        }
        catch (TransferException $e)
        {
            return $response->withStatus($e->getCode());
        }


        return $response;
    }


    public function delete(Request $request, Response $response)
    {
        /** @var \GuzzleHttp\Client $api */


        $pacID = $request->getAttribute('id');
        $api = $this->ci->get('API');


        try
        {
            $api->delete("pacientes/{$pacID}");
        }
        catch (TransferException $e)
        {
            return $response->withStatus($e->getCode());
        }


        return $response;
    }


    public function put(Request $request, Response $response)
    {
        /** @var \GuzzleHttp\Client $api */


        $pacID = $request->getAttribute('id');
        $json = $request->getParsedBody();
        $api = $this->ci->get('API');


        try
        {
            $api->put("pacientes/{$pacID}", ['json' => $json]);
        }
        catch (TransferException $e)
        {
            return $response->withStatus($e->getCode());
        }


        return $response;
    }
}