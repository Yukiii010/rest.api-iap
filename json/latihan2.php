<?php

$data = file_get_contents('Coba.json');
$mahasiswa = json_decode($data, true);

var_dump($mahasiswa);
?>