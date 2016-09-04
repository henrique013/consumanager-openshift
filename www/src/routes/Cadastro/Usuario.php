<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 23/08/16
 * Time: 20:41
 */

namespace App\Route\Cadastro;


use App\Util\Handle;
use App\Util\Handle\GET;
use App\Util\Handle\POST;
use GuzzleHttp\Exception\TransferException;
use Slim\Http\Request;
use Slim\Http\Response;


class Usuario extends Handle
{
    use GET;
    use POST;


    function get(Request $request, Response $response)
    {
        /** @var \Twig_Environment $twig */
        /** @var \GuzzleHttp\Client $api */
        /** @var \Slim\Flash\Messages $flash */


        $usrID = $request->getAttribute('id');

        $flash = $this->ci->get('flash');
        $status = $flash->getMessage('cad_usuario_status');
        $context['status'] = is_array($status) ? array_shift($status) : false;


        if ($usrID)
        {
            $api = $this->ci->get('API');

            try
            {
                $resp = $api->get("usuarios/{$usrID}");

                if ($resp->getStatusCode() === 204) return $response->withRedirect('/cadastro/usuario');

                $context['usuario'] = json_decode($resp->getBody(), true);
            }
            catch (TransferException $e)
            {
                //TODO: implementar!
                throw $e;
            }
        }


        $twig = $this->ci->get('twig');

        $view = $twig->render('cadastro/usuario/usuario.twig', $context);

        $response->getBody()->write($view);


        return $response;
    }


    public function post(Request $request, Response $response)
    {
        /** @var \GuzzleHttp\Client $api */
        /** @var \Slim\Flash\Messages $flash */


        $usrID = $request->getAttribute('id');
        $json = $request->getParsedBody();

        $api = $this->ci->get('API');
        $flash = $this->ci->get('flash');

        try
        {
            if ($usrID)
            {
                $api->put("usuarios/{$usrID}", ['json' => $json]);
            }
            else
            {
                $api->post("usuarios", ['json' => $json]);
            }

            $status = 'success';
        }
        catch (TransferException $e)
        {
            $status = 'error';
        }


        $flash->addMessage('cad_usuario_status', $status);

        $response = $response->withRedirect('/cadastro/usuario');


        return $response;
    }
}