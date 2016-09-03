<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 30/08/16
 * Time: 23:33
 */

namespace App\Route\API;


use App\Util\Handle;
use App\Util\Handle\GET;
use App\Util\Pagination;
use Slim\Http\Request;
use Slim\Http\Response;


class Pacientes extends Handle {

    use GET;


    public function get(Request $request, Response $response) {

        /** @var \Slim\Http\Response $response */

        $response = $response->withHeader('X-Total-Pages', 0);

        $pNome = $request->getParam('nome');

        if (!$pNome) return $response;


        $page = (int)$request->getAttribute('page', 1);
        $json = $this->getPorNome($pNome, $page);

        if (!$json) return $response;


        $totalPg = $this->getTotalPages();
        $response = $response->withHeader('X-Total-Pages', $totalPg)->withJson($json);

        return $response;
    }


    private function getPorNome($nome, $page) {

        /** @var \PDO $conn */

        $limit = Pagination::LIMIT_DEFAULT;
        $start = Pagination::startIndex($page);
        $sql = "
            SELECT
                p.ID
                ,p.NOME
                ,p.UF
                ,p.CIDADE
                ,p.BAIRRO
                ,p.LOGRADOURO
                ,p.NUMERO
                ,p.TELEFONE
            FROM PACIENTE p
            WHERE
                p.NOME LIKE :NOME
            ORDER BY
                p.NOME
            LIMIT {$start}, {$limit}
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('NOME', "%{$nome}%");
        $stmt->execute();


        $ret = [];
        while ($row = $stmt->fetch()) {

            $pac = [
                'id' => (int)$row['ID'],
                'nome' => $row['NOME'],
                'endereco' => [
                    'logradouro' => $row['LOGRADOURO'],
                    'numero' => (int)$row['NUMERO'],
                    'bairro' => $row['BAIRRO'],
                    'cidade' => $row['CIDADE'],
                    'uf' => $row['UF'],
                ],
                'contato' => [
                    'telefone' => $row['TELEFONE'],
                ]
            ];

            $ret[] = $pac;
        }

        return $ret;
    }


    private function getTotalPages() {

        /** @var \PDO $conn */

        $sql = '
            SELECT COUNT(ID) AS TOTAL FROM PACIENTE
        ';
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->execute();

        $total = Pagination::totalPages($stmt->fetch()['TOTAL']);

        return $total;
    }
}