<?php

namespace App\Helpers;

class NumberToWordsHelper
{
    public static function convertToWords($number)
    {
        // Array of words
        $words = [
            'zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine', 'ten',
            'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'
        ];

        $tens = ['', '', 'twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];

        // Convert number to words
        if ($number < 20) {
            return $words[$number];
        } elseif ($number < 100) {
            return $tens[floor($number / 10)] . ($number % 10 !== 0 ? '-' . $words[$number % 10] : '');
        } elseif ($number < 1000) {
            return $words[floor($number / 100)] . ' hundred ' . ($number % 100 !== 0 ? 'and ' . self::convertToWords($number % 100) : '');
        } elseif ($number < 1000000) {
            return self::convertToWords(floor($number / 1000)) . ' thousand ' . ($number % 1000 !== 0 ? self::convertToWords($number % 1000) : '');
        } else {
            return 'Number is too large to convert';
        }
    }
}
