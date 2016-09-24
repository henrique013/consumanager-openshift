<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 23/08/16
 * Time: 20:41
 */

namespace App\Route\Ajax\Cadastro;


use App\Route\Ajax\Cadastro\Consulta\Pacientes;
use App\Util\Handle;
use App\Util\Handle\DELETE;
use App\Util\Handle\GET;
use App\Util\Handle\POST;
use App\Util\Handle\PUT;
use DateTime;
use Respect\Validation\Validator as v;
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


        $twig = $this->ci->get('twig_template');
        $view = $twig->render('ajax/cadastro/consulta/consulta.twig', $context);
        $response->write($view);


        return $response;
    }


    public function delete(Request $request, Response $response)
    {
        /** @var \PDO $conn */

        $id = $request->getAttribute('id');


        $sql = "DELETE FROM TB_CONSULTA WHERE ID = :ID";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('ID', $id);
        $stmt->execute();


        return $response;
    }


    public function post(Request $request, Response $response)
    {
        /** @var \PDO $conn */

        $p = $this->prepareParams($request);
        if (!$p) return $response->withStatus(400);


        $sql = "
            INSERT INTO TB_CONSULTA
            SET
                ID_STATUS_CONSULTA = :ID_STATUS_CONSULTA
                ,ID_TIPO_CONSULTA = :ID_TIPO_CONSULTA
                ,ID_PACIENTE = :ID_PACIENTE
                ,ID_HORARIO = :ID_HORARIO
                ,DT_CONSULTA = :DT_CONSULTA
                ,RESPONSAVEL = :RESPONSAVEL
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('ID_STATUS_CONSULTA', $p['status']);
        $stmt->bindValue('ID_TIPO_CONSULTA', $p['tipo']);
        $stmt->bindValue('ID_PACIENTE', $p['paciente']);
        $stmt->bindValue('ID_HORARIO', $p['horario']);
        $stmt->bindValue('DT_CONSULTA', $p['data']);
        $stmt->bindValue('RESPONSAVEL', $p['responsavel']);
        $stmt->execute();


        return $response->withStatus(201);
    }


    public function put(Request $request, Response $response)
    {
        /** @var \PDO $conn */

        $id = $request->getAttribute('id');
        $p = $this->prepareParams($request);
        if (!$p) return $response->withStatus(400);


        $sql = "
            UPDATE TB_CONSULTA
            SET
                ID_STATUS_CONSULTA = :ID_STATUS_CONSULTA
                ,ID_TIPO_CONSULTA = :ID_TIPO_CONSULTA
                ,ID_PACIENTE = :ID_PACIENTE
                ,ID_HORARIO = :ID_HORARIO
                ,DT_CONSULTA = :DT_CONSULTA
                ,RESPONSAVEL = :RESPONSAVEL
            WHERE
                ID = :ID
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('ID_STATUS_CONSULTA', $p['status']);
        $stmt->bindValue('ID_TIPO_CONSULTA', $p['tipo']);
        $stmt->bindValue('ID_PACIENTE', $p['paciente']);
        $stmt->bindValue('ID_HORARIO', $p['horario']);
        $stmt->bindValue('DT_CONSULTA', $p['data']);
        $stmt->bindValue('RESPONSAVEL', $p['responsavel']);
        $stmt->bindValue('ID', $id);
        $stmt->execute();


        return $response;
    }


    private function prepareParams(Request $request)
    {
        /** @var \PDO $conn */

        $consultorio = $request->getParsedBodyParam('consultorio');
        $horario = $request->getParsedBodyParam('horario');
        $data = $request->getParsedBodyParam('data');
        $paciente = $request->getParsedBodyParam('paciente');
        $tipo = $request->getParsedBodyParam('tipo');
        $status = $request->getParsedBodyParam('status');
        $responsavel = $request->getParsedBodyParam('responsavel');

        $v[] = v::intVal()->validate($consultorio);
        $v[] = (bool)preg_match("/^\d\d:\d\d$/", $horario);
        $v[] = v::date('Y-m-d')->validate($data);
        $v[] = v::intVal()->validate($paciente);
        $v[] = v::intVal()->validate($tipo);
        $v[] = v::intVal()->validate($status);
        $v[] = v::notEmpty()->validate($responsavel);

        if (in_array(false, $v))
        {
            return false;
        }

        $sql = "SELECT ID FROM TB_HORARIO WHERE HORAS = :HORAS AND ID_CONSULTORIO = :ID_CONSULTORIO";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('HORAS', $horario . ':00');
        $stmt->bindValue('ID_CONSULTORIO', $consultorio);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!$row)
        {
            return false;
        }

        $params['consultorio'] = $consultorio;
        $params['horario'] = $row['ID'];
        $params['data'] = $data;
        $params['paciente'] = $paciente;
        $params['tipo'] = $tipo;
        $params['status'] = $status;
        $params['responsavel'] = $responsavel;

        return $params;
    }
}