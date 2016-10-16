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


class Pacientes extends Handle
{
    use GET;


    function get(Request $request, Response $response)
    {
        /** @var \PDO $conn */


        $pNome = $request->getParam('nome');


        $sql = "
            SELECT
                p.id
                ,p.nome
                ,p.cidade
                ,p.bairro
                ,p.logradouro
                ,p.num_residencia
                ,p.telefone
                ,p.telefone_2
                ,uf.sigla AS uf_sigla
            FROM tb_paciente p
            JOIN tb_uf uf ON(uf.id = p.id_uf)
            WHERE
                p.nome ILIKE :nome
            ORDER BY
                p.nome
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
                'endereco' => self::mascaraEndereco($row['logradouro'], $row['num_residencia'], $row['bairro'], $row['cidade'], $row['uf_sigla']),
                'telefones' => self::mascaraTelefones($row['telefone'], $row['telefone_2']),
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


    public static function mascaraEndereco($logradouro, $num_residencia, $bairro, $cidade, $uf_sigla)
    {
        return "{$logradouro} n° {$num_residencia}, {$bairro}, {$cidade} - {$uf_sigla}";
    }


    public static function mascaraTelefones($telefone, $telefone2)
    {
        return ($telefone2) ? "{$telefone} | {$telefone2}" : $telefone;
    }
}