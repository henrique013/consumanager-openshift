<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 23/08/16
 * Time: 20:41
 */

namespace App\Route\Ajax\Cadastro;


use App\Util\Handle;
use App\Util\Handle\DELETE;
use App\Util\Handle\GET;
use App\Util\Handle\POST;
use App\Util\Handle\PUT;
use Respect\Validation\Validator as v;
use Slim\Http\Request;
use Slim\Http\Response;


class Usuario extends Handle
{
    use GET;
    use POST;
    use PUT;
    use DELETE;


    public function get(Request $request, Response $response)
    {
        /** @var \Twig_Environment $twig */
        /** @var \PDO $conn */


        $usrID = $request->getAttribute('id');
        $context = [];


        if ($usrID)
        {
            $sql = "
                SELECT
                  u.ID AS id
                  ,u.NOME AS nome
                  ,u.EMAIL AS email
                FROM TB_USUARIO u
                WHERE
                  u.ID = :ID
            ";
            $conn = $this->ci->get('PDO');
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('ID', $usrID);
            $stmt->execute();


            $row = $stmt->fetch();
            if (!$row)
            {
                return $response->withRedirect('/cadastro/usuario');
            }
            $context['usuario'] = $row;
        }


        $twig = $this->ci->get('twig_template');
        $view = $twig->render('ajax/cadastro/usuario/usuario.twig', $context);
        $response->write($view);


        return $response;
    }


    public function post(Request $request, Response $response)
    {
        /** @var \PDO $conn */


        $p = $this->prepareParams($request);
        if (!$p) return $response->withStatus(400);


        $sql = "
            INSERT INTO TB_USUARIO
            SET
                NOME = :NOME
                ,EMAIL = :EMAIL
                ,HASH_SENHA = :HASH_SENHA
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('NOME', $p['nome']);
        $stmt->bindValue('EMAIL', $p['email']);
        $stmt->bindValue('HASH_SENHA', $p['senha']);
        $stmt->execute();


        return $response->withStatus(201);
    }


    public function delete(Request $request, Response $response)
    {
        /** @var \PDO $conn */


        $uID = $request->getAttribute('id');


        $sql = "
            DELETE FROM TB_USUARIO WHERE ID = :ID
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('ID', $uID);
        $stmt->execute();


        return $response;
    }


    public function put(Request $request, Response $response)
    {
        /** @var \PDO $conn */


        $id = $request->getAttribute('id');
        $p = $this->prepareParams($request);
        if (!$p) return $response->withStatus(400);


        $sql = '
            UPDATE TB_USUARIO
            SET
                NOME = :NOME
                ,EMAIL = :EMAIL
                ,HASH_SENHA = :HASH_SENHA
            WHERE
                ID = :ID
        ';
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('NOME', $p['nome']);
        $stmt->bindValue('EMAIL', $p['email']);
        $stmt->bindValue('HASH_SENHA', $p['senha']);
        $stmt->bindValue('ID', $id);
        $stmt->execute();


        return $response;
    }


    private function prepareParams(Request $request)
    {
        $nome = $request->getParsedBodyParam('nome');
        $email = $request->getParsedBodyParam('email');
        $senha = $request->getParsedBodyParam('senha');
        $senha2 = $request->getParsedBodyParam('senha2');

        $v[] = v::notEmpty()->validate($nome);
        $v[] = v::email()->validate($email);
        $v[] = v::notEmpty()->noWhitespace()->equals($senha2)->validate($senha);

        if (in_array(false, $v))
        {
            return false;
        }

        $params['nome'] = $nome;
        $params['email'] = $email;
        $params['senha'] = md5($senha);

        return $params;
    }
}