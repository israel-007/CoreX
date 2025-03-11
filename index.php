<?php 

include('PHP/DeviceFingerprint/DeviceFingerprint.php');

$fingerprint = new DeviceFingerprint();

echo $fingerprint->generate();