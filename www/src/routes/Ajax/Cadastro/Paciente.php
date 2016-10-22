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


class Paciente extends Handle
{
    use GET;
    use POST;
    use PUT;
    use DELETE;


    public function get(Request $request, Response $response)
    {
        /** @var \Twig_Environment $twig */
        /** @var \PDO $conn */


        $pacID = $request->getAttribute('id');
        $context = [];


        $sql = "SELECT id, nome FROM tb_tipo_paciente ORDER BY nome";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $context['tipos'] = $stmt->fetchAll();


        $sql = "SELECT id, nome FROM tb_uf ORDER BY nome";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $context['estados'] = $stmt->fetchAll();


        if ($pacID)
        {
            $sql = "
                SELECT
                    p.id
                    ,p.num_prontuario
                    ,p.nome
                    ,to_char(p.dt_nasc, 'DD/MM/YYYY') AS dt_nascimento
                    ,p.responsavel
                    ,p.motivo
                    ,p.encaminhamento
                    ,p.cidade
                    ,p.bairro
                    ,p.logradouro
                    ,p.complemento
                    ,p.num_residencia
                    ,p.telefone
                    ,p.telefone_2
                    ,tp.id AS tipo_id
                    ,uf.id AS uf_id
                FROM tb_paciente p
                JOIN tb_uf uf ON(uf.id = p.id_uf)
                JOIN tb_tipo_paciente tp ON(tp.id = p.id_tipo_paciente)
                WHERE
                    p.id = :id
                ORDER BY
                    p.nome
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('id', $pacID);
            $stmt->execute();
            $row = $stmt->fetch();
        }
        else
        {
            // sugestão de número de prontuário
            $sql = "
              SELECT
                np.numero AS num_prontuario
              FROM tb_num_prontuario np
              WHERE
                np.numero NOT IN(SELECT p.num_prontuario FROM tb_paciente p)
              ORDER BY
                np.numero
              LIMIT 1
            ";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $row = $stmt->fetch();
        }


        if (!$row)
        {
            return $response->withRedirect('/cadastro/paciente');
        }
        $context['paciente'] = $row;


        $twig = $this->ci->get('twig_template');
        $view = $twig->render('ajax/cadastro/paciente/paciente.twig', $context);
        $response->write($view);


        return $response;
    }


    public function delete(Request $request, Response $response)
    {
        /** @var \PDO $conn */

        $id = $request->getAttribute('id');


        $sql = "DELETE FROM tb_paciente WHERE id = :id";
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
            INSERT INTO tb_paciente
            (
                num_prontuario
                ,id_tipo_paciente
                ,nome
                ,dt_nasc
                ,motivo
                ,responsavel
                ,telefone
                ,telefone_2
                ,id_uf
                ,cidade
                ,bairro
                ,logradouro
                ,num_residencia
                ,complemento
                ,encaminhamento
            )
            VALUES
            (
                :num_prontuario
                ,:id_tipo_paciente
                ,:nome
                ,:dt_nasc
                ,:motivo
                ,:responsavel
                ,:telefone
                ,:telefone_2
                ,:id_uf
                ,:cidade
                ,:bairro
                ,:logradouro
                ,:num_residencia
                ,:complemento
                ,:encaminhamento
            )
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('num_prontuario', $p['prontuario']);
        $stmt->bindValue('id_tipo_paciente', $p['tipo']);
        $stmt->bindValue('nome', $p['nome']);
        $stmt->bindValue('dt_nasc', $p['dt_nascimento']);
        $stmt->bindValue('motivo', $p['motivo']);
        $stmt->bindValue('responsavel', $p['responsavel']);
        $stmt->bindValue('telefone', $p['tel']);
        $stmt->bindValue('telefone_2', $p['tel2']);
        $stmt->bindValue('id_uf', $p['uf']);
        $stmt->bindValue('cidade', $p['cidade']);
        $stmt->bindValue('bairro', $p['bairro']);
        $stmt->bindValue('logradouro', $p['logradouro']);
        $stmt->bindValue('num_residencia', $p['numero']);
        $stmt->bindValue('complemento', $p['complemento']);
        $stmt->bindValue('encaminhamento', $p['encaminhamento']);
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
            UPDATE tb_paciente
            SET
                id_tipo_paciente = :id_tipo_paciente
                ,num_prontuario = :num_prontuario
                ,nome = :nome
                ,dt_nasc = :dt_nasc
                ,motivo = :motivo
                ,responsavel = :responsavel
                ,telefone = :telefone
                ,telefone_2 = :telefone_2
                ,id_uf = :id_uf
                ,cidade = :cidade
                ,bairro = :bairro
                ,logradouro = :logradouro
                ,num_residencia = :num_residencia
                ,complemento = :complemento
                ,encaminhamento = :encaminhamento
            WHERE
                id = :id
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('num_prontuario', $p['prontuario']);
        $stmt->bindValue('id_tipo_paciente', $p['tipo']);
        $stmt->bindValue('nome', $p['nome']);
        $stmt->bindValue('dt_nasc', $p['dt_nascimento']);
        $stmt->bindValue('motivo', $p['motivo']);
        $stmt->bindValue('responsavel', $p['responsavel']);
        $stmt->bindValue('telefone', $p['tel']);
        $stmt->bindValue('telefone_2', $p['tel2']);
        $stmt->bindValue('id_uf', $p['uf']);
        $stmt->bindValue('cidade', $p['cidade']);
        $stmt->bindValue('bairro', $p['bairro']);
        $stmt->bindValue('logradouro', $p['logradouro']);
        $stmt->bindValue('num_residencia', $p['numero']);
        $stmt->bindValue('complemento', $p['complemento']);
        $stmt->bindValue('encaminhamento', $p['encaminhamento']);
        $stmt->bindValue('id', $id);
        $stmt->execute();


        return $response;
    }


    private function prepareParams(Request $request)
    {
        $prontuario = $request->getParsedBodyParam('num_prontuario');
        $bairro = $request->getParsedBodyParam('bairro');
        $cidade = $request->getParsedBodyParam('cidade');
        $complemento = $request->getParsedBodyParam('complemento');
        $dt_nascimento = $request->getParsedBodyParam('dt_nascimento');
        $encaminhamento = $request->getParsedBodyParam('encaminhamento');
        $logradouro = $request->getParsedBodyParam('logradouro');
        $motivo = $request->getParsedBodyParam('motivo');
        $nome = $request->getParsedBodyParam('nome');
        $numero = $request->getParsedBodyParam('num_residencia');
        $responsavel = $request->getParsedBodyParam('responsavel');
        $tel = $request->getParsedBodyParam('tel');
        $tel2 = $request->getParsedBodyParam('tel2') ?: null;
        $tipo = $request->getParsedBodyParam('tipo');
        $uf = $request->getParsedBodyParam('uf');

        $v[] = v::intVal()->validate($prontuario);
        $v[] = v::notEmpty()->validate($bairro);
        $v[] = v::notEmpty()->validate($cidade);
        $v[] = v::date('d/m/Y')->validate($dt_nascimento);
        $v[] = v::notEmpty()->validate($logradouro);
        $v[] = v::notEmpty()->validate($motivo);
        $v[] = v::notEmpty()->validate($nome);
        $v[] = v::intVal()->validate($numero);
        $v[] = (bool)preg_match("/^(\(\d\d\)\s)?\d{4,5}-\d{4}$/", $tel);
        $v[] = (is_null($tel2) || preg_match("/^(\(\d\d\)\s)?\d{4,5}-\d{4}$/", $tel2));
        $v[] = v::intVal()->validate($tipo);
        $v[] = v::intVal()->validate($uf);

        if (in_array(false, $v))
        {
            return false;
        }

        $params['prontuario'] = $prontuario;
        $params['bairro'] = $bairro;
        $params['cidade'] = $cidade;
        $params['complemento'] = $complemento;
        $params['dt_nascimento'] = implode('-', array_reverse(explode('/', $dt_nascimento)));
        $params['encaminhamento'] = $encaminhamento;
        $params['logradouro'] = $logradouro;
        $params['motivo'] = $motivo;
        $params['numero'] = $numero;
        $params['nome'] = $nome;
        $params['responsavel'] = $responsavel;
        $params['tel'] = $tel;
        $params['tel2'] = $tel2;
        $params['tipo'] = $tipo;
        $params['uf'] = $uf;

        return $params;
    }
}