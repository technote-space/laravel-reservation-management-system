<?php
declare(strict_types=1);

namespace App\Helpers\Traits;

use DateTime;
use Exception;
use Illuminate\Support\Arr;

/**
 * Trait ValueProcessHelper
 * @package App\Helpers\Traits
 */
trait ValueHelper
{
    /**
     * @param  string  $time
     *
     * @return string
     * @throws Exception
     */
    protected function getWeek(string $time): string
    {
        $date = new DateTime($time);
        $week = (int) $date->format('w');
        $week = Arr::get(['日', '月', '火', '水', '木', '金', '土'], $week);

        return "({$week})";
    }

    /**
     * @param  string  $time
     *
     * @return string
     * @throws Exception
     */
    protected function getDate(string $time): string
    {
        $year = '';
        if (date('Y') !== date('Y', strtotime($time))) {
            $year = date('Y', strtotime($time)).'年';
        }

        return $year.date('n月j日', strtotime($time)).$this->getWeek($time);
    }

    /**
     * @param  string  $string
     *
     * @return string
     */
    protected function toHalf(string $string): string
    {
        return mb_convert_kana($string, 'a', 'utf-8');
    }

    /**
     * @param  string  $string
     *
     * @return string
     */
    protected function toFull(string $string): string
    {
        return mb_convert_kana($string, 'A', 'utf-8');
    }

    /**
     * @param  string  $string
     *
     * @return string
     */
    protected function toHalfSpace(string $string): string
    {
        return mb_convert_kana($string, 's', 'utf-8');
    }

    /**
     * @param  string  $string
     *
     * @return string
     */
    protected function toFullSpace(string $string): string
    {
        return mb_convert_kana($string, 'S', 'utf-8');
    }

    /**
     * @param  string  $string
     *
     * @return string
     */
    protected function toHalfDash(string $string): string
    {
        return str_replace(['―', 'ー', '－', '‐'], '-', $string);
    }

    /**
     * @param  string  $string
     *
     * @return string
     */
    protected function toFullDash(string $string): string
    {
        return str_replace('-', '－', $string);
    }

    /**
     * @param  string  $string
     *
     * @return string
     */
    protected function escapeText(string $string): string
    {
        $string = preg_replace("/\r\n|\r|\n/", '', $string);
        $string = preg_replace('/,/', ' ', $string);

        return $string;
    }
}
