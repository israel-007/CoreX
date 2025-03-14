<?php

class MorseCode
{
    private array $keys = [];
    private array $values = [];
    private array $morseMap = [];
    private array $reverseMap = [];

    public function __construct(string $encryptionKey = 'AES-258-ESPD')
    {
        $this->initializeKeysAndValues();
        $this->validateKeysAndValues();
        $this->generateDynamicMapping($encryptionKey);
    }

    /**
     * Declare all keys (characters) and all possible values separately
     */
    private function initializeKeysAndValues(): void
    {
        $this->keys = [
            'A',
            'B',
            'C',
            'D',
            'E',
            'F',
            'G',
            'H',
            'I',
            'J',
            'K',
            'L',
            'M',
            'N',
            'O',
            'P',
            'Q',
            'R',
            'S',
            'T',
            'U',
            'V',
            'W',
            'X',
            'Y',
            'Z',
            'a',
            'b',
            'c',
            'd',
            'e',
            'f',
            'g',
            'h',
            'i',
            'j',
            'k',
            'l',
            'm',
            'n',
            'o',
            'p',
            'q',
            'r',
            's',
            't',
            'u',
            'v',
            'w',
            'x',
            'y',
            'z',
            '0',
            '1',
            '2',
            '3',
            '4',
            '5',
            '6',
            '7',
            '8',
            '9',
            '.',
            ',',
            '?',
            '\'',
            '!',
            '/',
            '(',
            ')',
            '&',
            '*',
            ':',
            ';',
            '=',
            '+',
            '-',
            '—',
            '_',
            '"',
            '$',
            '@',
            '[',
            ']',
            '}',
            '{',
            '<',
            '>',
            ' '
        ];

        $this->values = [
            '.-',
            '~.-',
            '-...',
            '-.-..',
            '-..',
            '.',
            '..-.',
            '--.',
            '....',
            '..',
            '.---',
            '-.-~',
            '.-..',
            '--',
            '-.',
            '---',
            '.--.',
            '--.-',
            '.-.',
            '...',
            '-',
            '..-',
            '...-',
            '.--',
            '-..-',
            '-.--',
            '--.~.',
            '--..',
            '.~.-',
            '-.~..',
            '-.~.-',
            '--~',
            '..~',
            '.-~.-',
            '--~.~',
            '--~.',
            '....~',
            '..~.',
            '--.~',
            '--.~~',
            '.~---',
            '-.~.--',
            '..-~..',
            '-.-',
            '~--~',
            '~-.-~',
            '-~-',
            '-..~',
            '.--..',
            '---.-',
            '..-.~-',
            '-.-.',
            '..-~-',
            '..--',
            '...-.',
            '.---.',
            '-..--',
            '-.--.',
            '--..-',
            '-----',
            '.----',
            '..---',
            '...--',
            '....-',
            '.....',
            '..-.-..-',
            '-....',
            '--...',
            '---..',
            '----.',
            '.-.-.-',
            '--..--',
            '..--..',
            '.----.',
            '-.-.--',
            '-..-.',
            '~.--.-',
            '-.--.-',
            '.-...',
            '---...',
            '-.-.-.',
            '-...-',
            '.-.-.',
            '-....-',
            '..--.-',
            '.-..-.',
            '...-..-',
            '.--.-.',
            '~~--'
        ];
    }

    /**
     * Validate keys and values to ensure uniqueness before mapping
     */
    private function validateKeysAndValues(): void
    {
        if (count($this->keys) !== count(array_unique($this->keys))) {
            die("Error: Duplicate keys detected! Execution stopped.");
        }

        if (count($this->values) !== count(array_unique($this->values))) {
            die("Error: Duplicate values detected! Execution stopped.");
        }

        if (count($this->values) < count($this->keys)) {
            die("Error: Not enough values for the number of keys! Execution stopped.");
        }

        if (count($this->values) > count($this->keys)) {
            die("Error: Number of values is higher than number of keys! Execution stopped.");
        }
    }

    /**
     * Generate a dynamic Morse map based on the encryption key
     */
    private function generateDynamicMapping(string $key): void
    {
        $availableValues = $this->values;

        // Shuffle the values using the encryption key as seed
        srand(crc32($key));
        shuffle($availableValues);
        srand(); // Reset random seed

        // Assign each key a unique value from the shuffled pool
        foreach ($this->keys as $index => $char) {
            $this->morseMap[$char] = array_shift($availableValues);
        }

        $this->reverseMap = array_flip($this->morseMap);
    }

    /**
     * Encrypt a given text into Morse code
     */
    public function encrypt(string $text): string
    {
        $morseCode = array_map(function ($char) {
            return $this->morseMap[$char] ?? ''; // Convert each letter
        }, str_split($text));

        return implode(' ', $morseCode); // Separate Morse characters by space
        // die(json_encode($this->morseMap));
    }

    /**
     * Decrypt a Morse code string back into text
     */
    public function decrypt(string $morse): string
    {
        $words = explode(' / ', $morse); // Morse words are separated by "/"
        $decodedWords = array_map(function ($word) {
            $letters = explode(' ', $word);
            return implode('', array_map(function ($char) {
                return $this->reverseMap[$char] ?? ''; // Convert each Morse to letter
            }, $letters));
        }, $words);

        return implode(' ', $decodedWords); // Join words with spaces
    }
}