<?php

namespace App\Helpers;

class NumberHelper
{
    public static function terbilang($number)
    {
        $number = abs($number);
        $words = array("", "Satu", "Dua", "Tiga", "Empat", "Lima", "Enam", "Tujuh", "Delapan", "Sembilan", "Sepuluh", "Sebelas");
        $temp = "";

        if ($number < 12) {
            $temp = " " . $words[$number];
        } else if ($number < 20) {
            $temp = self::terbilang($number - 10) . " Belas";
        } else if ($number < 100) {
            $temp = self::terbilang($number / 10) . " Puluh" . self::terbilang($number % 10);
        } else if ($number < 200) {
            $temp = " Seratus" . self::terbilang($number - 100);
        } else if ($number < 1000) {
            $temp = self::terbilang($number / 100) . " Ratus" . self::terbilang($number % 100);
        } else if ($number < 2000) {
            $temp = " Seribu" . self::terbilang($number - 1000);
        } else if ($number < 1000000) {
            $temp = self::terbilang($number / 1000) . " Ribu" . self::terbilang($number % 1000);
        } else if ($number < 1000000000) {
            $temp = self::terbilang($number / 1000000) . " Juta" . self::terbilang($number % 1000000);
        } else if ($number < 1000000000000) {
            $temp = self::terbilang($number / 1000000000) . " Milyar" . self::terbilang(fmod($number, 1000000000));
        } else if ($number < 1000000000000000) {
            $temp = self::terbilang($number / 1000000000000) . " Trilyun" . self::terbilang(fmod($number, 1000000000000));
        }
        return $temp;
    }
}
