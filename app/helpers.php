<?php

if (!function_exists('numberToWords')) {
    function numberToWords($number)
    {
        $ones = array(
            0 => 'zero', 1 => 'one', 2 => 'two', 3 => 'three', 4 => 'four', 5 => 'five',
            6 => 'six', 7 => 'seven', 8 => 'eight', 9 => 'nine', 10 => 'ten',
            11 => 'eleven', 12 => 'twelve', 13 => 'thirteen', 14 => 'fourteen',
            15 => 'fifteen', 16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty'
        );

        $tens = array(
            0 => 'zero', 1 => 'ten', 2 => 'twenty', 3 => 'thirty', 4 => 'forty', 5 => 'fifty',
            6 => 'sixty', 7 => 'seventy', 8 => 'eighty', 9 => 'ninety'
        );

        $hundreds = array(
            'hundred', 'thousand', 'million', 'billion', 'trillion',
            'quadrillion', 'quintillion', 'sextillion', 'septillion', 'octillion',
            'nonillion', 'decillion', 'undecillion', 'duodecillion', 'tredecillion',
            'quattuordecillion', 'quindecillion', 'sexdecillion', 'septendecillion',
            'octodecillion', 'novemdecillion', 'vigintillion'
        );

        $words = [];

        if ($number < 21) {
            $words[] = $ones[$number];
        } elseif ($number < 100) {
            $words[] = $tens[floor($number / 10)];
            $remainder = $number % 10;
            if ($remainder) {
                $words[] = numberToWords($remainder);
            }
        } elseif ($number < 1000) {
            $words[] = $ones[floor($number / 100)] . ' ' . $hundreds[0];
            $remainder = $number % 100;
            if ($remainder) {
                $words[] = numberToWords($remainder);
            }
        } else {
            foreach (array_reverse($hundreds) as $index => $unit) {
                $unitValue = pow(1000, $index);
                if ($unitValue <= $number) {
                    $chunk = floor($number / $unitValue);
                    $number -= $chunk * $unitValue;
                    if ($chunk) {
                        $words[] = numberToWords($chunk) . ' ' . $unit;
                    }
                }
            }
        }

        return implode(' ', $words);
    }
}
