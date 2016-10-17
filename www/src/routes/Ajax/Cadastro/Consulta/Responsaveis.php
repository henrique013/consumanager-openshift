<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 23/08/16
 * Time: 20:41
 */

namespace App\Route\Ajax\Cadastro\Consulta;


use App\Util\Handle;
use App\Util\Handle\GET;
use Slim\Http\Request;
use Slim\Http\Response;


class Responsaveis extends Handle
{
    use GET;


    function get(Request $request, Response $response)
    {
        /** @var \PDO $conn */


        $pNome = $request->getParam('nome');


        $sql = "
            SELECT
                rc.id
                ,rc.nome
                ,rc.telefone
            FROM tb_resp_consulta rc
            WHERE
                rc.nome ILIKE :nome
            ORDER BY
                rc.nome
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('nome', "%{$pNome}%");
        $stmt->execute();


        $json = [];
        while ($row = $stmt->fetch())
        {
            $dados = [
                'id' => (int)$row['id'],
                'nome' => $row['nome'],
                'telefones' => $row['telefone'],
            ];

            $p = [
                'id' => json_encode($dados),
                'name' => $row['nome']
            ];

            $json[] = $p;
        }


        if (!$json) return $response->withStatus(204);


        return $response->withJson($json);
    }
}