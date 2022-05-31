<?php

if (!function_exists('pdfLess')) {
    function pdfLess($less, $from)
    {
        return $from - $less;
    }
}

if (!function_exists('customPDF')) {
    function customPDF()
    {
        return new \App\Helpers\CustomPDF();
    }
}
