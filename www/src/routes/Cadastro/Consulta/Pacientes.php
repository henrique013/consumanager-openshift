<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 23/08/16
 * Time: 20:41
 */

namespace App\Route\Cadastro\Consulta;


use App\Util\Handle;
use App\Util\Handle\GET;
use Slim\Http\Request;
use Slim\Http\Response;


class Pacientes extends Handle
{
    use GET;


    function get(Request $request, Response $response)
    {
        /** @var \PDO $conn */


        $pNome = $request->getParam('nome');


        $sql = "
            SELECT
                p.ID
                ,p.NOME
                ,p.CIDADE
                ,p.BAIRRO
                ,p.LOGRADOURO
                ,p.COMPLEMENTO
                ,p.NUM_RESIDENCIA
                ,p.TELEFONE
                ,p.TELEFONE_2
                ,uf.SIGLA AS UF_SIGLA
            FROM TB_PACIENTE p
            JOIN TB_UF uf ON(uf.ID = p.ID_UF)
            WHERE
                p.NOME LIKE :NOME
            ORDER BY
                p.NOME
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('NOME', "%{$pNome}%");
        $stmt->execute();


        $json = [];
        while ($row = $stmt->fetch())
        {
            $dados = [
                'id' => (int)$row['ID'],
                'nome' => $row['NOME'],
                'endereco' => self::mascaraEndereco($row['LOGRADOURO'], $row['NUM_RESIDENCIA'], $row['BAIRRO'], $row['CIDADE'], $row['UF_SIGLA']),
                'telefones' => self::mascaraTelefones($row['TELEFONE'], $row['TELEFONE_2']),
            ];

            $p = [
                'id' => json_encode($dados),
                'name' => $row['NOME']
            ];

            $json[] = $p;
        }


        if (!$json) return $response->withStatus(204);


        return $response->withJson($json);
    }


    public static function mascaraEndereco($logradouro, $num_residencia, $bairro, $cidade, $uf_sigla)
    {
        return "{$logradouro} n° {$num_residencia}, {$bairro}, {$cidade} - {$uf_sigla}";
    }


    public static function mascaraTelefones($telefone, $telefone2)
    {
        return ($telefone2) ? "{$telefone} | {$telefone2}" : $telefone;
    }
}