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


        $sql = "SELECT ID AS id, NOME AS nome FROM TB_TIPO_PACIENTE ORDER BY NOME";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $context['tipos'] = $stmt->fetchAll();


        $sql = "SELECT ID AS id, NOME AS nome FROM TB_UF ORDER BY NOME";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $context['estados'] = $stmt->fetchAll();


        if ($pacID)
        {
            $sql = "
                SELECT
                    p.ID AS id
                    ,p.NOME AS nome
                    ,DATE_FORMAT(p.DT_NASC, '%d/%m/%Y') AS dt_nascimento
                    ,p.RESPONSAVEL AS responsavel
                    ,p.MOTIVO AS motivo
                    ,p.ENCAMINHAMENTO AS encaminhamento
                    ,p.CIDADE AS cidade
                    ,p.BAIRRO AS bairro
                    ,p.LOGRADOURO AS logradouro
                    ,p.COMPLEMENTO AS complemento
                    ,p.NUM_RESIDENCIA AS num_residencia
                    ,p.TELEFONE AS telefone
                    ,p.TELEFONE_2 AS telefone_2
                    ,tp.ID AS tipo_id
                    ,uf.ID AS uf_id
                FROM TB_PACIENTE p
                JOIN TB_UF uf ON(uf.ID = p.ID_UF)
                JOIN TB_TIPO_PACIENTE tp ON(tp.ID = p.ID_TIPO_PACIENTE)
                WHERE
                    p.ID = :ID
                ORDER BY
                    p.NOME
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('ID', $pacID);
            $stmt->execute();


            $row = $stmt->fetch();
            if (!$row)
            {
                return $response->withRedirect('/cadastro/paciente');
            }
            $context['paciente'] = $row;
        }


        $twig = $this->ci->get('twig_template');
        $view = $twig->render('ajax/cadastro/paciente/paciente.twig', $context);
        $response->write($view);


        return $response;
    }


    public function delete(Request $request, Response $response)
    {
        /** @var \PDO $conn */

        $id = $request->getAttribute('id');


        $sql = "DELETE FROM TB_PACIENTE WHERE ID = :ID";
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
            INSERT INTO TB_PACIENTE
            SET
                ID_TIPO_PACIENTE = :ID_TIPO_PACIENTE
                ,NOME = :NOME
                ,DT_NASC = :DT_NASC
                ,MOTIVO = :MOTIVO
                ,RESPONSAVEL = :RESPONSAVEL
                ,TELEFONE = :TELEFONE
                ,TELEFONE_2 = :TELEFONE_2
                ,ID_UF = :ID_UF
                ,CIDADE = :CIDADE
                ,BAIRRO = :BAIRRO
                ,LOGRADOURO = :LOGRADOURO
                ,NUM_RESIDENCIA = :NUM_RESIDENCIA
                ,COMPLEMENTO = :COMPLEMENTO
                ,ENCAMINHAMENTO = :ENCAMINHAMENTO
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('ID_TIPO_PACIENTE', $p['tipo']);
        $stmt->bindValue('NOME', $p['nome']);
        $stmt->bindValue('DT_NASC', $p['dt_nascimento']);
        $stmt->bindValue('MOTIVO', $p['motivo']);
        $stmt->bindValue('RESPONSAVEL', $p['responsavel']);
        $stmt->bindValue('TELEFONE', $p['tel']);
        $stmt->bindValue('TELEFONE_2', $p['tel2']);
        $stmt->bindValue('ID_UF', $p['uf']);
        $stmt->bindValue('CIDADE', $p['cidade']);
        $stmt->bindValue('BAIRRO', $p['bairro']);
        $stmt->bindValue('LOGRADOURO', $p['logradouro']);
        $stmt->bindValue('NUM_RESIDENCIA', $p['numero']);
        $stmt->bindValue('COMPLEMENTO', $p['complemento']);
        $stmt->bindValue('ENCAMINHAMENTO', $p['encaminhamento']);
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
            UPDATE TB_PACIENTE
            SET
                ID_TIPO_PACIENTE = :ID_TIPO_PACIENTE
                ,NOME = :NOME
                ,DT_NASC = :DT_NASC
                ,MOTIVO = :MOTIVO
                ,RESPONSAVEL = :RESPONSAVEL
                ,TELEFONE = :TELEFONE
                ,TELEFONE_2 = :TELEFONE_2
                ,ID_UF = :ID_UF
                ,CIDADE = :CIDADE
                ,BAIRRO = :BAIRRO
                ,LOGRADOURO = :LOGRADOURO
                ,NUM_RESIDENCIA = :NUM_RESIDENCIA
                ,COMPLEMENTO = :COMPLEMENTO
                ,ENCAMINHAMENTO = :ENCAMINHAMENTO
            WHERE
                ID = :ID
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('ID_TIPO_PACIENTE', $p['tipo']);
        $stmt->bindValue('NOME', $p['nome']);
        $stmt->bindValue('DT_NASC', $p['dt_nascimento']);
        $stmt->bindValue('MOTIVO', $p['motivo']);
        $stmt->bindValue('RESPONSAVEL', $p['responsavel']);
        $stmt->bindValue('TELEFONE', $p['tel']);
        $stmt->bindValue('TELEFONE_2', $p['tel2']);
        $stmt->bindValue('ID_UF', $p['uf']);
        $stmt->bindValue('CIDADE', $p['cidade']);
        $stmt->bindValue('BAIRRO', $p['bairro']);
        $stmt->bindValue('LOGRADOURO', $p['logradouro']);
        $stmt->bindValue('NUM_RESIDENCIA', $p['numero']);
        $stmt->bindValue('COMPLEMENTO', $p['complemento']);
        $stmt->bindValue('ENCAMINHAMENTO', $p['encaminhamento']);
        $stmt->bindValue('ID', $id);
        $stmt->execute();


        return $response;
    }


    private function prepareParams(Request $request)
    {
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
        $tel2 = $request->getParsedBodyParam('tel2');
        $tipo = $request->getParsedBodyParam('tipo');
        $uf = $request->getParsedBodyParam('uf');

        $v[] = v::notEmpty()->validate($bairro);
        $v[] = v::notEmpty()->validate($cidade);
        $v[] = v::date('d/m/Y')->validate($dt_nascimento);
        $v[] = v::notEmpty()->validate($logradouro);
        $v[] = v::notEmpty()->validate($motivo);
        $v[] = v::notEmpty()->validate($nome);
        $v[] = v::intVal()->validate($numero);
        $v[] = (bool)preg_match("/^\(\d\d\) \d{4,5}-\d{4}$/", $tel);
        $v[] = ($tel2 === '' || preg_match("/^\(\d\d\) \d{4,5}-\d{4}$/", $tel2));
        $v[] = v::intVal()->validate($tipo);
        $v[] = v::intVal()->validate($uf);

        if (in_array(false, $v))
        {
            return false;
        }

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