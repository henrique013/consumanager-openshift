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
use DateTime;
use GuzzleHttp\Exception\TransferException;
use Slim\Http\Request;
use Slim\Http\Response;


class Agenda extends Handle
{

    use GET;


    function get(Request $request, Response $response)
    {
        /** @var \Twig_Environment $twig */
        /** @var \GuzzleHttp\Client $api */


        $data = $data = $request->getAttribute('data', date('Y-m-d'));

        $api = $this->ci->get('API');

        try
        {
            $resp = $api->get("consultas/resumos/{$data}");

            $context['resumos'] = json_decode($resp->getBody(), true);
        }
        catch (TransferException $e)
        {
            //TODO: implementar!
            throw $e;
        }


        $context['data'] = $data;


        $twig = $this->ci->get('twig');
        $view = $twig->render('agenda/agenda.twig', $context);
        $response->getBody()->write($view);


        return $response;
    }
}