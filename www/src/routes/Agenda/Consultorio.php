<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 28/08/16
 * Time: 14:11
 */

namespace App\Route\Agenda;


use App\Util\Handle;
use App\Util\Handle\GET;
use DateTime;
use Slim\Http\Request;
use Slim\Http\Response;


class Consultorio extends Handle
{
    use GET;


    function get(Request $request, Response $response)
    {
        /** @var \Twig_Environment $twig */
        /** @var \PDO $conn */


        $coID = $request->getAttribute('co_id');
        $data = $data = $request->getAttribute('data');


        $sql = "
            SELECT
              c.id AS id
              ,c.nome AS nome
            FROM tb_consultorio c
            WHERE
              c.id = :id
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('id', $coID);
        $stmt->execute();


        $row = $stmt->fetch();
        if (!$row)
        {
            return $response->withRedirect('/agenda');
        }
        $context['consultorio'] = $row;
        $context['data'] = new DateTime($data);


        $sql = "
            SELECT
              to_char(h.horas, 'HH24:MI') AS hr_horas
              ,rc.nome AS rc_nome
              ,p.nome AS p_nome
            FROM tb_consultorio c
            JOIN tb_horario h ON (h.id_consultorio = c.id)
            LEFT JOIN tb_consulta ct ON (ct.id_horario = h.id AND ct.dt_consulta = :dt_consulta)
            LEFT JOIN tb_resp_consulta rc ON (rc.id = ct.id_resp_consulta)
            LEFT JOIN tb_paciente p ON (p.id = ct.id_paciente)
            WHERE
              c.id = :co_id
            ORDER BY
              h.horas
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('dt_consulta', $data);
        $stmt->bindValue('co_id', $coID);
        $stmt->execute();


        while ($row = $stmt->fetch())
        {
            $hr = [
                'horas' => $row['hr_horas'],
                'consulta_responsavel' => $row['rc_nome'],
                'paciente_nome' => $row['p_nome'],
            ];

            $context['horarios'][] = $hr;
        }


        $twig = $this->ci->get('twig_template');
        $view = $twig->render('agenda/consultorio/consultorio.twig', $context);
        $response->write($view);


        return $response;
    }
}