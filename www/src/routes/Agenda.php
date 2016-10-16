<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 23/08/16
 * Time: 20:41
 */

namespace App\Route;


use App\Util\Handle;
use App\Util\Handle\GET;
use DateTime;
use Slim\Http\Request;
use Slim\Http\Response;


class Agenda extends Handle
{
    use GET;


    function get(Request $request, Response $response)
    {
        /** @var \Twig_Environment $twig */
        /** @var \PDO $conn */


        $data = $request->getAttribute('data', date('Y-m-d'));


        $sql = "
            SELECT
               c.id AS co_id
              ,c.nome AS co_nome
              ,COUNT(h.id) AS hr_total
              ,SUM(CASE WHEN ct.id IS NULL THEN 1 ELSE 0 END) AS hr_livres
              ,SUM(CASE WHEN ct.id IS NOT NULL THEN 1 ELSE 0 END) AS hr_ocupados
            FROM tb_consultorio c
              JOIN tb_horario h ON (h.id_consultorio = c.id)
              LEFT JOIN tb_consulta ct ON (ct.id_horario = h.id AND ct.dt_consulta = :dt_consulta)
            GROUP BY
              c.id
        ";
        $conn = $this->ci->get('PDO');
        $stmt = $conn->prepare($sql);
        $stmt->bindValue('dt_consulta', $data);
        $stmt->execute();


        $context['data'] = new DateTime($data);
        while ($row = $stmt->fetch())
        {
            $resumo = [
                'consultorio' => [
                    'id' => $row['co_id'],
                    'nome' => $row['co_nome']
                ],
                'ocupacao_horarios' => [
                    'livres' => $row['hr_livres'],
                    'ocupados' => $row['hr_ocupados'],
                    'total' => $row['hr_total']
                ],
            ];

            $context['resumos'][] = $resumo;
        }


        $twig = $this->ci->get('twig_template');
        $view = $twig->render('agenda/agenda.twig', $context);
        $response->write($view);


        return $response;
    }
}