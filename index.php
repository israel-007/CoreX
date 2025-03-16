<?php 

include('PHP/DeviceFingerprint/DeviceFingerprint.php');
include('PHP/Zipper/Zipper.php');
include('PHP/NumberToWords/NumberToWords.php');
include('PHP/MorseCode/MorseCode.php');
include('PHP/Uploader/Uploader.php');

include('idea.php');


// Example usage


    $files = Uploader::directory('uploads/')->exists('demo.jpg');

    echo json_encode($files);
