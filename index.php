<?php 

include('PHP/DeviceFingerprint/DeviceFingerprint.php');
include('PHP/Zipper/Zipper.php');
include('PHP/NumberToWords/NumberToWords.php');
include('PHP/MorseCode/MorseCode.php');
include('PHP/Uploader/Uploader.php');

include('idea.php');


$backup = new DatabaseBackup('localhost', 'cart', 'root', '');
echo $backup->dir(__DIR__ . '/uploads')->format('sql')->backup();

