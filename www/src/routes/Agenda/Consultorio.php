<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 28/08/16
 * Time: 14:11
 */

namespace App\Route\Agenda;


use App\Util\Handle;
use App\Util\Handle\GET;
use GuzzleHttp\Exception\TransferException;
use Slim\Http\Request;
use Slim\Http\Response;


class Consultorio extends Handle
{
    use GET;


    function get(Request $request, Response $response)
    {
        /** @var \Twig_Environment $twig */
        /** @var \GuzzleHttp\Client $api */


        $coID = $request->getAttribute('co_id');
        $data = $data = $request->getAttribute('data');

        $query = [
            'consultorios' => $coID
        ];

        $api = $this->ci->get('API');

        try
        {
            $resp = $api->get("agenda/resumos/{$data}", ['query' => $query]);

            $json = json_decode($resp->getBody(), true);

            $context['resumo'] = $json[0];
        }
        catch (TransferException $e)
        {
            //TODO: implementar!
            throw $e;
        }


        $context['data'] = $data;


        $twig = $this->ci->get('twig');

        $view = $twig->render('agenda/consultorio/consultorio.twig', $context);

        $response->getBody()->write($view);


        return $response;
    }
}