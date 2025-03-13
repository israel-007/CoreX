<?php
class NumberToWords
{
    private static $ones = [
        0 => 'Zero',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen'
    ];

    private static $tens = [
        2 => 'Twenty',
        3 => 'Thirty',
        4 => 'Forty',
        5 => 'Fifty',
        6 => 'Sixty',
        7 => 'Seventy',
        8 => 'Eighty',
        9 => 'Ninety'
    ];

    private static $thousands = [
        '',
        'Thousand',
        'Million',
        'Billion',
        'Trillion'
    ];

    private static $romanNumerals = [
        1000 => 'M',
        900 => 'CM',
        500 => 'D',
        400 => 'CD',
        100 => 'C',
        90 => 'XC',
        50 => 'L',
        40 => 'XL',
        10 => 'X',
        9 => 'IX',
        5 => 'V',
        4 => 'IV',
        1 => 'I'
    ];

    /**
     * Convert a number to words.
     */
    public static function convert($number)
    {
        if (!is_numeric($number)) {
            return "Invalid input";
        }

        if ($number < 0) {
            return "Negative " . self::convert(abs($number));
        }

        if (strpos($number, '.') !== false) {
            return self::convertDecimal($number);
        }

        return self::convertInteger($number);
    }

    /**
     * Convert an integer part of a number to words.
     */
    private static function convertInteger($number)
    {
        if ($number == 0) {
            return self::$ones[0];
        }

        $words = '';
        $count = 0;

        while ($number > 0) {
            $threeDigits = $number % 1000;
            if ($threeDigits != 0) {
                $words = self::convertThreeDigits($threeDigits) . ' ' . self::$thousands[$count] . ' ' . $words;
            }
            $count++;
            $number = floor($number / 1000);
        }

        return trim($words);
    }

    /**
     * Convert a three-digit number to words.
     */
    private static function convertThreeDigits($number)
    {
        $words = '';

        $hundreds = floor($number / 100);
        $tensAndOnes = $number % 100;

        if ($hundreds > 0) {
            $words .= self::$ones[$hundreds] . ' Hundred';
            if ($tensAndOnes > 0) {
                $words .= ' and ';
            }
        }

        if ($tensAndOnes < 20) {
            $words .= self::$ones[$tensAndOnes];
        } else {
            $words .= self::$tens[floor($tensAndOnes / 10)];
            $onesDigit = $tensAndOnes % 10;
            if ($onesDigit > 0) {
                $words .= '-' . self::$ones[$onesDigit];
            }
        }

        return trim($words);
    }

    /**
     * Convert a decimal number to words.
     */
    private static function convertDecimal($number)
    {
        list($integerPart, $decimalPart) = explode('.', $number);

        $integerWords = self::convertInteger($integerPart);
        $decimalWords = self::convertDecimalPart($decimalPart);

        return $integerWords . " Point " . $decimalWords;
    }

    /**
     * Convert the decimal part to words.
     */
    private static function convertDecimalPart($decimalPart)
    {
        $words = '';
        for ($i = 0; $i < strlen($decimalPart); $i++) {
            $words .= self::$ones[$decimalPart[$i]] . ' ';
        }
        return trim($words);
    }

    /**
     * Convert a number to a compact format.
     */
    public static function convertCompact($number)
    {
        if ($number < 1_000) {
            return $number;
        }

        $units = ['K', 'M', 'B', 'T'];
        $unitIndex = 0;

        while ($number >= 1_000 && $unitIndex < count($units)) {
            $number /= 1_000;
            $unitIndex++;
        }

        return round($number, 1) . $units[$unitIndex - 1];
    }

    /**
     * Convert a number to Roman Numerals.
     */
    public static function toRoman($number)
    {
        if ($number < 1 || $number > 3999) {
            return "Out of Range";
        }

        $result = '';
        foreach (self::$romanNumerals as $value => $symbol) {
            while ($number >= $value) {
                $result .= $symbol;
                $number -= $value;
            }
        }
        return $result;
    }

    /**
     * Convert a number to its ordinal form (full words).
     */
    public static function toOrdinalWords($number)
    {
        $base = self::convertInteger($number);

        if ($number % 10 == 1 && $number % 100 != 11)
            return $base . ' First';
        if ($number % 10 == 2 && $number % 100 != 12)
            return $base . ' Second';
        if ($number % 10 == 3 && $number % 100 != 13)
            return $base . ' Third';

        return $base . 'th';
    }

    /**
     * Convert a number to ordinal numeric suffix (1st, 2nd, 3rd, etc.).
     */
    public static function toOrdinalSuffix($number)
    {
        if ($number % 10 == 1 && $number % 100 != 11)
            return $number . 'st';
        if ($number % 10 == 2 && $number % 100 != 12)
            return $number . 'nd';
        if ($number % 10 == 3 && $number % 100 != 13)
            return $number . 'rd';

        return $number . 'th';
    }
}
