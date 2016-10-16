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


    function get(Request $request, Response $response)
    {
        /** @var \Twig_Environment $twig */
        /** @var \PDO $conn */


        $pNome = $request->getParam('nome');
        $conn = $this->ci->get('PDO');


        if ($pNome)
        {
            $sql = "
                SELECT
                    ct.dt_consulta AS data
                    ,to_char(h.horas, 'HH24:MI') AS horas
                    ,co.id AS co_id
                    ,co.nome AS co_nome
                    ,p.nome AS pac_nome
                FROM tb_consulta ct
                JOIN tb_paciente p ON (p.id = ct.id_paciente)
                JOIN tb_horario h ON (h.id = ct.id_horario)
                JOIN tb_consultorio co ON (co.id = h.id_consultorio)
                WHERE
                    p.nome ILIKE :nome
                ORDER BY
                    ct.dt_consulta DESC
                    ,h.horas
            ";
            $stmt = $conn->prepare($sql);
            $stmt->bindValue('nome', "%{$pNome}%");
        }
        else
        {
            $sql = "
                SELECT
                    ct.dt_consulta AS data
                    ,to_char(h.horas, 'HH24:MI') AS horas
                    ,co.id AS co_id
                    ,co.nome AS co_nome
                    ,p.nome AS pac_nome
                FROM tb_consulta ct
                JOIN tb_paciente p ON (p.id = ct.id_paciente)
                JOIN tb_horario h ON (h.id = ct.id_horario)
                JOIN tb_consultorio co ON (co.id = h.id_consultorio)
                ORDER BY
                    ct.dt_consulta DESC
                    ,h.horas
            ";
            $stmt = $conn->prepare($sql);
        }


        $stmt->execute();
        $context['consultas'] = $stmt->fetchAll();


        $twig = $this->ci->get('twig_template');
        $view = $twig->render('busca/consultas/consultas.twig', $context);
        $response->write($view);


        return $response;
    }
}