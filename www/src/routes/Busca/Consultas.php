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
use Slim\Http\Request;
use Slim\Http\Response;


class Consultas extends Handle
{
    use GET;


    const TP_BUSCA_ALUNO = 1;
    const TP_BUSCA_PACIENTE = 2;


    private $tp_buscas = [
        self::TP_BUSCA_ALUNO => 'Aluno',
        self::TP_BUSCA_PACIENTE => 'Paciente',
    ];


    public function get(Request $request, Response $response)
    {
        /** @var \Twig_Environment $twig */


        $tpBusca = (int)$request->getParam('tp_busca', self::TP_BUSCA_PACIENTE);
        $pNome = $request->getParam('nome');


        if ($pNome)
        {
            switch ($tpBusca)
            {
                case self::TP_BUSCA_ALUNO:
                    $consultas = $this->getByAluno($pNome);
                    break;

                case self::TP_BUSCA_PACIENTE:
                    $consultas = $this->getByPaciente($pNome);
                    break;

                default:
                    $consultas = [];
                    break;
            }
        }
        else
        {
            $consultas = $this->getAll();
        }


        $context['consultas'] = $consultas;
        $context['tp_buscas'] = $this->tp_buscas;
        $context['tp_busca'] = $tpBusca;
        $context['busca'] = $pNome;


        $twig = $this->ci->get('twig_template');
        $view = $twig->render('busca/consultas/consultas.twig', $context);
        $response->write($view);


        return $response;
    }


    private function getByAluno($nome)
    {
        /** @var \PDO $conn */


        $sql = "
            SELECT
                ct.dt_consulta AS data
                ,to_char(h.horas, 'HH24:MI') AS horas
                ,co.id AS co_id
                ,co.nome AS co_nome
                ,rc.nome AS resp_nome
                ,p.nome AS pac_nome
            FROM tb_consulta ct
            JOIN tb_resp_consulta rc ON (rc.id = ct.id_resp_consulta)
            JOIN tb_paciente p ON (p.id = ct.id_paciente)
            JOIN tb_horario h ON (h.id = ct.id_horario)
            JOIN tb_consultorio co ON (co.id = h.id_consultorio)
            WHERE
                rc.nome ILIKE :nome
            ORDER BY
                ct.dt_consulta DESC
                ,h.horas
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('nome', "%{$nome}%");
        $stmt->execute();
        $ret = $stmt->fetchAll();


        return $ret;
    }


    private function getByPaciente($nome)
    {
        /** @var \PDO $conn */


        $sql = "
            SELECT
                ct.dt_consulta AS data
                ,to_char(h.horas, 'HH24:MI') AS horas
                ,co.id AS co_id
                ,co.nome AS co_nome
                ,rc.nome AS resp_nome
                ,p.nome AS pac_nome
            FROM tb_consulta ct
            JOIN tb_resp_consulta rc ON (rc.id = ct.id_resp_consulta)
            JOIN tb_paciente p ON (p.id = ct.id_paciente)
            JOIN tb_horario h ON (h.id = ct.id_horario)
            JOIN tb_consultorio co ON (co.id = h.id_consultorio)
            WHERE
                p.nome ILIKE :nome
            ORDER BY
                ct.dt_consulta DESC
                ,h.horas
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('nome', "%{$nome}%");
        $stmt->execute();
        $ret = $stmt->fetchAll();


        return $ret;
    }


    private function getAll()
    {
        /** @var \PDO $conn */


        $sql = "
            SELECT
                ct.dt_consulta AS data
                ,to_char(h.horas, 'HH24:MI') AS horas
                ,co.id AS co_id
                ,co.nome AS co_nome
                ,rc.nome AS resp_nome
                ,p.nome AS pac_nome
            FROM tb_consulta ct
            JOIN tb_resp_consulta rc ON (rc.id = ct.id_resp_consulta)
            JOIN tb_paciente p ON (p.id = ct.id_paciente)
            JOIN tb_horario h ON (h.id = ct.id_horario)
            JOIN tb_consultorio co ON (co.id = h.id_consultorio)
            ORDER BY
                ct.dt_consulta DESC
                ,h.horas
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $ret = $stmt->fetchAll();


        return $ret;
    }
}