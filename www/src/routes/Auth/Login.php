<?php
/**
 * Created by PhpStorm.
 * User: Henrique
 * Date: 29/07/2016
 * Time: 14:01
 */

namespace App\Route\Auth;

use App\Util\Handle;
use App\Util\Handle\GET;
use App\Util\Handle\POST;
use GuzzleHttp\Exception\TransferException;
use Slim\Http\Request;
use Slim\Http\Response;


class Login extends Handle
{
    use GET;
    use POST;


    public function get(Request $request, Response $response)
    {
        /** @var \Twig_Environment $twig */

        $twig = $this->ci->get('twig');

        $view = $twig->render('login/login.twig');

        $response->getBody()->write($view);

        return $response;
    }


    public function post(Request $request, Response $response)
    {
        /** @var \Twig_Environment $twig */
        /** @var \GuzzleHttp\Client $api */


        $json = [
            'user' => $request->getParsedBodyParam('user'),
            'password' => $request->getParsedBodyParam('password'),
        ];

        $context = [];
        $api = $this->ci->get('API');

        try
        {
            $resp = $api->post('auth/login', ['json' => $json]);

            $json = json_decode($resp->getBody(), true);

            $this->startSession($json);

            $response = $response->withRedirect('/agenda');
        }
        catch (TransferException $e)
        {
            $context['login_fail'] = true;

            $twig = $this->ci->get('twig');

            $view = $twig->render('login/login.twig', $context);

            $response->getBody()->write($view);
        }


        return $response;
    }


    private function startSession(array $json)
    {
        session_regenerate_id(true);

        $_SESSION = [
            'ultimo_acesso' => time(),
            'usuario' => [
                'id' => $json['id']
            ],
        ];
    }
}