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
                  u.id
                  ,u.nome
                  ,u.email
                FROM tb_usuario u
                WHERE
                  u.id = :id
            ";
            $conn = $this->ci->get('PDO');
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $usrID);
            $stmt->execute();


            $row = $stmt->fetch();
            if (!$row)
            {
                return $response->withRedirect('/cadastro/usuario');
            }
            $context['usuario'] = $row;
        }


        $twig = $this->ci->get('twig');
        $view = $twig->render('templates/ajax/cadastro/usuario/usuario.twig', $context);
        $response->write($view);


        return $response;
    }


    public function post(Request $request, Response $response)
    {
        /** @var \PDO $conn */


        $p = $this->prepareParams($request);
        if (!$p) return $response->withStatus(400);


        $sql = "
            INSERT INTO tb_usuario
            (
              nome
              ,email
              ,hash_senha
            )
            VALUES
            (
              :nome
              ,:email
              ,:hash_senha
            )
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('nome', $p['nome']);
        $stmt->bindValue('email', $p['email']);
        $stmt->bindValue('hash_senha', $p['senha']);
        $stmt->execute();


        return $response->withStatus(201);
    }


    public function delete(Request $request, Response $response)
    {
        /** @var \PDO $conn */


        $uID = $request->getAttribute('id');


        $sql = "
            DELETE FROM tb_usuario WHERE id = :id
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('id', $uID);
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
            UPDATE tb_usuario
            SET
                nome = :nome
                ,email = :email
                ,HASH_SENHA = :HASH_SENHA
            WHERE
                id = :id
        ';
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('nome', $p['nome']);
        $stmt->bindValue('email', $p['email']);
        $stmt->bindValue('HASH_SENHA', $p['senha']);
        $stmt->bindValue('id', $id);
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