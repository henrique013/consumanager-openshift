<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 23/08/16
 * Time: 20:41
 */

namespace App\Route\Busca;


use App\Util\Handle;
use App\Util\Handle\GET;
use Respect\Validation\Validator as v;
use Slim\Http\Request;
use Slim\Http\Response;


class Pacientes extends Handle
{
    use GET;


    const TP_BUSCA_NOME = 1;
    const TP_BUSCA_PRONTUARIO = 2;


    private $tp_buscas = [
        self::TP_BUSCA_NOME => 'Nome',
        self::TP_BUSCA_PRONTUARIO => 'Prontuário',
    ];


    function get(Request $request, Response $response)
    {
        /** @var \Twig_Environment $twig */
        /** @var \PDO $conn */


        $tpBusca = (int)$request->getParam('tp_busca', self::TP_BUSCA_NOME);
        $pValor = $request->getParam('valor');
        $pacientes = [];


        if (is_string($pValor))
        {
            switch ($tpBusca)
            {
                case self::TP_BUSCA_NOME:
                    $pacientes = $this->getByNome($pValor);
                    break;

                case self::TP_BUSCA_PRONTUARIO:
                    $pacientes = $this->getByProntuario($pValor);
                    break;
            }
        }


        $context['pacientes'] = $pacientes;
        $context['busca'] = $pValor;
        $context['tp_busca'] = $tpBusca;
        $context['tp_buscas'] = $this->tp_buscas;


        $twig = $this->ci->get('twig_template');
        $view = $twig->render('busca/pacientes/pacientes.twig', $context);
        $response->write($view);


        return $response;
    }


    private function getByNome($nome)
    {
        /** @var \PDO $conn */


        $sql = "
            SELECT
                p.id
                ,p.num_prontuario
                ,p.nome
                ,p.telefone
                ,p.telefone_2
            FROM tb_paciente p
            WHERE
                p.nome ILIKE :nome
            ORDER BY
                p.nome
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('nome', "%{$nome}%");
        $stmt->execute();
        $ret = $stmt->fetchAll();


        return $ret;
    }


    private function getByProntuario($num_prontuario)
    {
        /** @var \PDO $conn */


        if (v::intVal()->validate($num_prontuario) === false)
        {
            return [];
        }


        $sql = "
            SELECT
                p.id
                ,p.num_prontuario
                ,p.nome
                ,p.telefone
                ,p.telefone_2
            FROM tb_paciente p
            WHERE
                cast(p.num_prontuario AS TEXT) LIKE :num_prontuario
            ORDER BY
                p.nome
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('num_prontuario', "%{$num_prontuario}%");
        $stmt->execute();
        $ret = $stmt->fetchAll();


        return $ret;
    }
}