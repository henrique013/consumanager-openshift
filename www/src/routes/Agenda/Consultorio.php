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
              c.ID AS id
              ,c.NOME AS nome
            FROM TB_CONSULTORIO c
            WHERE
              c.ID = :ID
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('ID', $coID);
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
              time_format(h.HORAS, '%H:%i') AS HR_HORAS
              ,ct.RESPONSAVEL AS CT_RESPONSAVEL
              ,p.NOME AS P_NOME
            FROM TB_CONSULTORIO c
            JOIN TB_HORARIO h ON (h.ID_CONSULTORIO = c.ID)
            LEFT JOIN TB_CONSULTA ct ON (ct.ID_HORARIO = h.ID AND ct.DT_CONSULTA = :DT_CONSULTA)
            LEFT JOIN TB_PACIENTE p ON (p.ID = ct.ID_PACIENTE)
            WHERE
              c.ID = :CO_ID
            ORDER BY
              h.HORAS
        ";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('DT_CONSULTA', $data);
        $stmt->bindValue('CO_ID', $coID);
        $stmt->execute();


        while ($row = $stmt->fetch())
        {
            $hr = [
                'horas' => $row['HR_HORAS'],
                'consulta_responsavel' => $row['CT_RESPONSAVEL'],
                'paciente_nome' => $row['P_NOME'],
            ];

            $context['horarios'][] = $hr;
        }


        $twig = $this->ci->get('twig_template');
        $view = $twig->render('agenda/consultorio/consultorio.twig', $context);
        $response->write($view);


        return $response;
    }
}