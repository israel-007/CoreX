<?php 

include('PHP/DeviceFingerprint/DeviceFingerprint.php');
include('PHP/Zipper/Zipper.php');
include('PHP/NumberToWords/NumberToWords.php');
include('PHP/MorseCode/MorseCode.php');


// Example usage
$morse = new MorseCode();

$text = "The quick brown FOX jumps over 13 lazy dogs! It's 2025, and coding is fun-right?";
$encrypted = $morse->encrypt($text);
$decrypted = $morse->decrypt($encrypted);

echo "Encrypted: $encrypted\n";
echo "Decrypted: $decrypted\n";
