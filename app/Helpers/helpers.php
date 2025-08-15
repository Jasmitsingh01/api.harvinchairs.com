<?php

if (! function_exists('gateway_path')) {
    /**
     * Get the path to the base of the install.
     *
     * @param  string  $path
     * @return string
     */
    function gateway_path($path = '')
    {
        return __DIR__. '/';
    }
}


if (! function_exists('currency_with_format')) {
    function currency_with_format($amount): string
    {
        return  '₹' . number_format($amount, 2);
    }
}

if (! function_exists('getFormatedDateTime')) {
    function getFormatedDateTime($date_time) {
        return date('d M Y h:i A',strtotime($date_time));
    }
}

if (! function_exists('getFormatedDate')) {
    function getFormatedDate($date_time) {
        return date('d M Y',strtotime($date_time));
    }
}

if (! function_exists('numberToWords')) {
    function numberToWords($number)
    {
        $hyphen      = '-';
        $conjunction = ' and ';
        $separator   = ' ';
        $negative    = 'negative ';
        $decimal     = ' point ';
        $dictionary  = [
            0                   => 'zero',
            1                   => 'one',
            2                   => 'two',
            3                   => 'three',
            4                   => 'four',
            5                   => 'five',
            6                   => 'six',
            7                   => 'seven',
            8                   => 'eight',
            9                   => 'nine',
            10                  => 'ten',
            11                  => 'eleven',
            12                  => 'twelve',
            13                  => 'thirteen',
            14                  => 'fourteen',
            15                  => 'fifteen',
            16                  => 'sixteen',
            17                  => 'seventeen',
            18                  => 'eighteen',
            19                  => 'nineteen',
            20                  => 'twenty',
            30                  => 'thirty',
            40                  => 'forty',
            50                  => 'fifty',
            60                  => 'sixty',
            70                  => 'seventy',
            80                  => 'eighty',
            90                  => 'ninety',
            100                 => 'hundred',
            1000                => 'thousand',
            100000              => 'lakh',
            10000000            => 'crore'
        ];

        if (!is_numeric($number)) {
            return false;
        }

        if (($number >= 0 && (int) $number < 0) || (int) $number < 0 - PHP_INT_MAX) {
            // overflow
            trigger_error(
                'numberToWords only accepts numbers between -' . PHP_INT_MAX . ' and ' . PHP_INT_MAX,
                E_USER_WARNING
            );
            return false;
        }

        if ($number < 0) {
            return $negative . numberToWords(abs($number));
        }

        $string = $fraction = null;

        if (strpos($number, '.') !== false) {
            list($number, $fraction) = explode('.', $number);
        }

        switch (true) {
            case $number < 21:
                $string = $dictionary[$number];
                break;
            case $number < 100:
                $tens   = ((int) ($number / 10)) * 10;
                $units  = $number % 10;
                $string = $dictionary[$tens];
                if ($units) {
                    $string .= $hyphen . $dictionary[$units];
                }
                break;
            case $number < 1000:
                $hundreds  = $number / 100;
                $remainder = $number % 100;
                $string = $dictionary[$hundreds] . ' ' . $dictionary[100];
                if ($remainder) {
                    $string .= $conjunction . numberToWords($remainder);
                }
                break;
            case $number < 100000:
                $thousands  = $number / 1000;
                $remainder = $number % 1000;
                $string = numberToWords((int) $thousands) . ' ' . $dictionary[1000];
                if ($remainder) {
                    $string .= $separator . numberToWords($remainder);
                }
                break;
            case $number < 10000000:
                $lakhs  = $number / 100000;
                $remainder = $number % 100000;
                $string = numberToWords((int) $lakhs) . ' ' . $dictionary[100000];
                if ($remainder) {
                    $string .= $separator . numberToWords($remainder);
                }
                break;
            default:
                $crores  = $number / 10000000;
                $remainder = $number % 10000000;
                $string = numberToWords((int) $crores) . ' ' . $dictionary[10000000];
                if ($remainder) {
                    $string .= $separator . numberToWords($remainder);
                }
                break;
        }

        if (null !== $fraction && is_numeric($fraction)) {
            $string .= $decimal;
            $words = [];
            foreach (str_split((string) $fraction) as $number) {
                $words[] = $dictionary[$number];
            }
            $string .= implode(' ', $words);
        }

        return ucwords($string);
    }
}
