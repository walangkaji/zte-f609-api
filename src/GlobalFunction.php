<?php

namespace walangkaji\ZteF609;

/**
 * Fungsi global
 */
class GlobalFunction
{
    /**
     * Untuk cari string diantara string
     *
     * @param string $content contentnya
     * @param string $start   awalan
     * @param string $end     akhiran
     */
    public static function getBetween($content, $start, $end)
    {
        $r = explode($start, $content);
        if (isset($r[1])) {
            $r = explode($end, $r[1]);
            return $r[0];
        }

        return '';
    }

    public static function find($content, $start, $end)
    {
        if (preg_match('/' . $start . '(.*?)' . $end .'/', $content, $match) == 1) {
            return $match[1];
        }
        return '';
    }

    public static function toFixed($number, $decimals)
    {
        return number_format($number, $decimals, '.', '');
    }
}
