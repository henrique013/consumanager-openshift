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


        $sql = "SELECT id, nome FROM tb_tipo_consulta ORDER BY nome";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $context['tipos'] = $stmt->fetchAll();


        $sql = "SELECT id, nome FROM tb_status_consulta ORDER BY nome";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $context['status'] = $stmt->fetchAll();


        $sql = "SELECT id, nome FROM tb_consultorio WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('id', $coID);
        $stmt->execute();
        $context['consultorio'] = $stmt->fetch();


        $sql = "
            SELECT
              ct.id AS ct_id
              ,ct.id_status_consulta AS ct_status_id
              ,ct.id_tipo_consulta AS ct_tipo_id
              ,rc.id AS rc_id
              ,rc.nome AS rc_nome
              ,rc.telefone AS rc_telefone
              ,p.id AS p_id
              ,p.nome AS p_nome
              ,p.cidade AS p_cidade
              ,p.bairro AS p_bairro
              ,p.logradouro AS p_logradouro
              ,p.num_residencia AS p_num_residencia
              ,p.telefone AS p_telefone
              ,p.telefone_2 AS p_telefone_2
              ,uf.sigla AS p_uf_sigla
            FROM tb_consulta ct
            JOIN tb_horario h ON (h.id = ct.id_horario)
            JOIN tb_resp_consulta rc ON (rc.id = ct.id_resp_consulta)
            JOIN tb_paciente p ON (p.id = ct.id_paciente)
            JOIN tb_uf uf ON (uf.id = p.id_uf)
            WHERE
              h.horas = :horas
              AND h.id_consultorio = :id_consultorio
              AND ct.dt_consulta = :dt_consulta
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('horas', $horario);
        $stmt->bindValue('dt_consulta', $data);
        $stmt->bindValue('id_consultorio', $coID);
        $stmt->execute();


        if ($row = $stmt->fetch())
        {
            $c = [
                'id' => $row['ct_id'],
                'status_id' => $row['ct_status_id'],
                'tipo_id' => $row['ct_tipo_id'],
                'responsavel' => [
                    'id' => $row['rc_id'],
                    'nome' => $row['rc_nome'],
                    'telefones' => $row['rc_telefone'],
                ],
                'paciente' => [
                    'id' => $row['p_id'],
                    'nome' => $row['p_nome'],
                    'endereco' => Pacientes::mascaraEndereco($row['p_logradouro'], $row['p_num_residencia'], $row['p_bairro'], $row['p_cidade'], $row['p_uf_sigla']),
                    'telefones' => Pacientes::mascaraTelefones($row['p_telefone'], $row['p_telefone_2']),
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


        $sql = "DELETE FROM tb_consulta WHERE id = :id";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('id', $id);
        $stmt->execute();


        return $response;
    }


    public function post(Request $request, Response $response)
    {
        /** @var \PDO $conn */

        $p = $this->prepareParams($request);
        if (!$p) return $response->withStatus(400);


        $sql = "
            INSERT INTO tb_consulta
            (
              id_status_consulta
              ,id_tipo_consulta
              ,id_paciente
              ,id_horario
              ,id_resp_consulta
              ,dt_consulta
            )
            VALUES
            (
              :id_status_consulta
              ,:id_tipo_consulta
              ,:id_paciente
              ,:id_horario
              ,:id_resp_consulta
              ,:dt_consulta
            )
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('id_status_consulta', $p['status']);
        $stmt->bindValue('id_tipo_consulta', $p['tipo']);
        $stmt->bindValue('id_paciente', $p['paciente']);
        $stmt->bindValue('id_horario', $p['horario']);
        $stmt->bindValue('id_resp_consulta', $p['responsavel']);
        $stmt->bindValue('dt_consulta', $p['data']);
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
            UPDATE tb_consulta
            SET
                id_status_consulta = :id_status_consulta
                ,id_tipo_consulta = :id_tipo_consulta
                ,id_paciente = :id_paciente
                ,id_horario = :id_horario
                ,id_resp_consulta = :id_resp_consulta
                ,dt_consulta = :dt_consulta
            WHERE
                id = :id
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('id_status_consulta', $p['status']);
        $stmt->bindValue('id_tipo_consulta', $p['tipo']);
        $stmt->bindValue('id_paciente', $p['paciente']);
        $stmt->bindValue('id_horario', $p['horario']);
        $stmt->bindValue('id_resp_consulta', $p['responsavel']);
        $stmt->bindValue('dt_consulta', $p['data']);
        $stmt->bindValue('id', $id);
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
        $v[] = v::intVal()->validate($responsavel);

        if (in_array(false, $v))
        {
            return false;
        }

        $sql = "SELECT id FROM tb_horario WHERE horas = :horas AND id_consultorio = :id_consultorio";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('horas', $horario . ':00');
        $stmt->bindValue('id_consultorio', $consultorio);
        $stmt->execute();

        $row = $stmt->fetch();
        if (!$row)
        {
            return false;
        }

        $params['consultorio'] = $consultorio;
        $params['horario'] = $row['id'];
        $params['data'] = $data;
        $params['paciente'] = $paciente;
        $params['tipo'] = $tipo;
        $params['status'] = $status;
        $params['responsavel'] = $responsavel;

        return $params;
    }
}