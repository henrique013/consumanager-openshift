<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 23/08/16
 * Time: 20:41
 */

namespace App\Route\Cadastro\Consulta;


use App\Util\Handle;
use App\Util\Handle\GET;
use Slim\Http\Request;
use Slim\Http\Response;


class Pacientes extends Handle
{
    use GET;


    function get(Request $request, Response $response)
    {
        /** @var \GuzzleHttp\Client $api */


        $pNome = $request->getParam('nome');

        $api = $this->ci->get('API');


        $resp = $api->get('pacientes', ['query' => ['nome' => $pNome]]);

        if ($resp->getStatusCode() === 204) return $response;


        $pessoas = json_decode($resp->getBody(), true);

        $json = $this->geraJSON($pessoas);

        $response = $response->withJson($json);


        return $response;
    }


    private function geraJSON(array $pessoas)
    {
        $json = [];

        foreach ($pessoas as $pessoa)
        {
            $p = [
                'id' => json_encode($pessoa),
                'name' => $pessoa['nome'],
            ];

            $json[] = $p;
        }

        return $json;
    }
}