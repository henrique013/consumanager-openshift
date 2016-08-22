<?php

namespace App\Route\API\Auth;

use App\Util\Handle;
use App\Util\Handle\POST;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Signer\Hmac\Sha256;
use Slim\Http\Request;
use Slim\Http\Response;


class Login extends Handle {

    use POST;


    public function post(Request $request, Response $response) {

        // valida o usuário

        /** @var \PDO $conn */

        $p = $request->getParsedBody();
        $user = $p['user'];
        $pass = md5($p['password']);

        $sql = "
            SELECT
                u.ID
            FROM USUARIO u
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

        if (!$row) {
            return $response->withStatus(401, 'Invalid Credentials');
        }


        // gera o JWT

        $secret = $this->ci->get('settings')['JWT']['secret'];
        $sgner = new Sha256();

        $jwtBuilder = new Builder(); //TODO:implementar outros claims
        $jwtBuilder
            ->set('uid', (int)$row['ID'])
            ->sign($sgner, $secret);

        $jwt = $jwtBuilder->getToken();

        setcookie('token', $jwt, null, '/', null, null, true);


        return $response;
    }
}