<?php
/**
 * Created by PhpStorm.
 * User: henrique
 * Date: 31/08/16
 * Time: 21:05
 */

namespace App\Util;


class Pagination {

    const LIMIT_DEFAULT = 10;


    /**
     * @param int $numValues
     * @param int $limit
     * @return int
     */
    public static function totalPages($numValues, $limit = self::LIMIT_DEFAULT) {

        $total = ($numValues / $limit);
        $total = (int)ceil($total);

        return $total;
    }


    /**
     * @param int $page
     * @param int $limit
     * @return int
     */
    public static function startIndex($page, $limit = self::LIMIT_DEFAULT) {

        $start = ($page * $limit) - $limit;

        return $start;
    }
}