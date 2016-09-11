<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 23/08/16
 * Time: 20:41
 */

namespace App\Route\Cadastro;


use App\Route\Cadastro\Consulta\Pacientes;
use App\Util\Handle;
use App\Util\Handle\DELETE;
use App\Util\Handle\GET;
use App\Util\Handle\POST;
use App\Util\Handle\PUT;
use DateTime;
use Slim\Http\Request;
use Slim\Http\Response;


class Consulta extends Handle
{
    use GET;
    use POST;
    use PUT;
    use DELETE;


    function get(Request $request, Response $response)
    {
        /** @var \Twig_Environment $twig */
        /** @var \PDO $conn */


        $coID = $request->getAttribute('co_id');
        $data = $request->getAttribute('dt');
        $horario = str_replace('-', ':', $request->getAttribute('hr'));


        $sql = "SELECT ID AS id, NOME AS nome FROM TB_TIPO_CONSULTA ORDER BY NOME";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $context['tipos'] = $stmt->fetchAll();


        $sql = "SELECT ID AS id, NOME AS nome FROM TB_STATUS_CONSULTA ORDER BY NOME";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $context['status'] = $stmt->fetchAll();


        $sql = "SELECT ID AS id, NOME AS nome FROM TB_CONSULTORIO WHERE ID = :ID";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('ID', $coID);
        $stmt->execute();
        $context['consultorio'] = $stmt->fetch();


        $sql = "
            SELECT
              ct.ID AS CT_ID
              ,ct.ID_STATUS_CONSULTA AS CT_STATUS_ID
              ,ct.ID_TIPO_CONSULTA AS CT_TIPO_ID
              ,ct.RESPONSAVEL AS CT_RESPONSAVEL
              ,p.ID AS P_ID
              ,p.NOME AS P_NOME
              ,p.CIDADE AS P_CIDADE
              ,p.BAIRRO AS P_BAIRRO
              ,p.LOGRADOURO AS P_LOGRADOURO
              ,p.COMPLEMENTO AS P_COMPLEMENTO
              ,p.NUM_RESIDENCIA AS P_NUM_RESIDENCIA
              ,p.TELEFONE AS P_TELEFONE
              ,p.TELEFONE_2 AS P_TELEFONE_2
              ,uf.SIGLA AS P_UF_SIGLA
            FROM TB_CONSULTA ct
            JOIN TB_HORARIO h ON (h.ID = ct.ID_HORARIO)
            JOIN TB_PACIENTE p ON (p.ID = ct.ID_PACIENTE)
            JOIN TB_UF uf ON (uf.ID = p.ID_UF)
            WHERE
              h.HORAS = :HORAS
              AND h.ID_CONSULTORIO = :ID_CONSULTORIO
              AND ct.DT_CONSULTA = :DT_CONSULTA
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('HORAS', $horario);
        $stmt->bindValue('DT_CONSULTA', $data);
        $stmt->bindValue('ID_CONSULTORIO', $coID);
        $stmt->execute();


        if ($row = $stmt->fetch())
        {
            $c = [
                'id' => $row['CT_ID'],
                'status_id' => $row['CT_STATUS_ID'],
                'tipo_id' => $row['CT_TIPO_ID'],
                'responsavel' => $row['CT_RESPONSAVEL'],
                'paciente' => [
                    'id' => $row['P_ID'],
                    'nome' => $row['P_NOME'],
                    'endereco' => Pacientes::mascaraEndereco($row['P_LOGRADOURO'], $row['P_NUM_RESIDENCIA'], $row['P_BAIRRO'], $row['P_CIDADE'], $row['P_UF_SIGLA']),
                    'telefones' => Pacientes::mascaraTelefones($row['P_TELEFONE'], $row['P_TELEFONE_2']),
                ]
            ];

            $context['consulta'] = $c;
        }


        $context['data'] = new DateTime($data);
        $context['horario'] = $horario;


        $twig = $this->ci->get('twig');
        $view = $twig->render('cadastro/consulta/consulta.twig', $context);
        $response->getBody()->write($view);


        return $response;
    }


    public function delete(Request $request, Response $response)
    {
        return $response->withStatus(501);
    }


    public function post(Request $request, Response $response)
    {
        return $response->withStatus(501);
    }


    public function put(Request $request, Response $response)
    {
        return $response->withStatus(501);
    }
}