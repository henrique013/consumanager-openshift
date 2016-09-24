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
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Slim\Http\Request;
use Slim\Http\Response;


class Login extends Handle
{
    use GET;
    use POST;


    public function get(Request $request, Response $response)
    {
        /** @var \Twig_Environment $twig */


        $twig = $this->ci->get('twig_template');
        $view = $twig->render('auth/login/login.twig');
        $response->write($view);

        return $response;
    }


    public function post(Request $request, Response $response)
    {
        /** @var \Twig_Environment $twig */
        /** @var \PDO $conn */


        $user = $request->getParsedBodyParam('user');
        $pass = md5($request->getParsedBodyParam('password'));


        $sql = "
            SELECT
                u.ID
            FROM TB_USUARIO u
            WHERE
                u.EMAIL = :EMAIL
                AND u.HASH_SENHA = :HASH_SENHA
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('EMAIL', $user);
        $stmt->bindValue('HASH_SENHA', $pass);
        $stmt->execute();
        $row = $stmt->fetch();


        if ($row)
        {
            $settings = $this->ci->get('settings');
            $signer = new Sha256();
            $token = (new Builder())
                ->setIssuedAt(time())
                ->set('uid', (int)$row['ID'])
                ->sign($signer, $settings['JWT']['secret'])
                ->getToken();

            setcookie('AUTH_TOKEN', (string)$token, null, '/', null, false, true);
        }
        else
        {
            $response = $response->withStatus(401);
        }


        return $response;
    }
}