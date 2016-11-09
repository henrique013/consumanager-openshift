<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 16/10/16
 * Time: 00:21
 */

namespace App\Route\Ajax\Cadastro\Consulta;


use App\Util\Handle;
use App\Util\Handle\DELETE;
use App\Util\Handle\GET;
use App\Util\Handle\POST;
use App\Util\Handle\PUT;
use Respect\Validation\Validator as v;
use Slim\Http\Request;
use Slim\Http\Response;


class Responsavel extends Handle
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
                  rc.id
                  ,rc.nome
                  ,rc.telefone
                  ,rc.supervisor
                FROM tb_resp_consulta rc
                WHERE
                  rc.id = :id
            ";
            $conn = $this->ci->get('PDO');
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $usrID);
            $stmt->execute();


            $row = $stmt->fetch();
            if (!$row)
            {
                //TODO: testar esse e os outros redirecionamentos
                return $response->withRedirect('/cadastro/consulta/responsavel');
            }
            $context['responsavel'] = $row;
        }


        $twig = $this->ci->get('twig');
        $view = $twig->render('templates/ajax/cadastro/consulta/responsavel/responsavel.twig', $context);
        $response->write($view);


        return $response;
    }


    public function post(Request $request, Response $response)
    {
        /** @var \PDO $conn */


        $p = $this->prepareParams($request);
        if (!$p) return $response->withStatus(400);


        $sql = "
            INSERT INTO tb_resp_consulta
            (
              nome
              ,telefone
              ,supervisor
            )
            VALUES
            (
              :nome
              ,:telefone
              ,:supervisor
            )
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('nome', $p['nome']);
        $stmt->bindValue('telefone', $p['telefone']);
        $stmt->bindValue('supervisor', $p['supervisor']);
        $stmt->execute();


        return $response->withStatus(201);
    }


    public function delete(Request $request, Response $response)
    {
        /** @var \PDO $conn */


        $respID = $request->getAttribute('id');


        $sql = "
            DELETE FROM tb_resp_consulta WHERE id = :id
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('id', $respID);
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
            UPDATE tb_resp_consulta
            SET
                nome = :nome
                ,telefone = :telefone
                ,supervisor = :supervisor
            WHERE
                id = :id
        ';
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('nome', $p['nome']);
        $stmt->bindValue('telefone', $p['telefone']);
        $stmt->bindValue('supervisor', $p['supervisor']);
        $stmt->bindValue('id', $id);
        $stmt->execute();


        return $response;
    }


    private function prepareParams(Request $request)
    {
        $nome = $request->getParsedBodyParam('nome');
        $tel = $request->getParsedBodyParam('tel');
        $supervisor = $request->getParsedBodyParam('supervisor') ?: null;

        $v[] = v::notEmpty()->validate($nome);
        $v[] = (bool)preg_match("/^(\(\d\d\)\s)?\d{4,5}-\d{4}$/", $tel);

        if (in_array(false, $v))
        {
            return false;
        }

        $params['nome'] = $nome;
        $params['telefone'] = $tel;
        $params['supervisor'] = $supervisor;

        return $params;
    }
}