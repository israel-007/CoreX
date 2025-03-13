<?php 

include('PHP/DeviceFingerprint/DeviceFingerprint.php');
include('PHP/Zipper/Zipper.php');
include('PHP/NumberToWords/NumberToWords.php');

$zipper = new Zipper('new-zip');

// Examples
echo NumberToWords::convert(20000) . "\n";