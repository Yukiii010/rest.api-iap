<?php

// $mahasiswa = [
//     "nama" => "ilham surya ramadhan",
//     "nim" => "2217020051",
//     "email" => "ilhamsuryaramadhan@gmail.com"

// ]; 



$dbh = new PDO ('mysql:host=localhost; dbname=mahasiswa','root');
$db = $dbh -> prepare('SELECT * FROM tabel_mahasiswa');
$db -> execute();
$mahasiswa = $db->fetchAll(PDO::FETCH_ASSOC);


$data = json_encode($mahasiswa);
echo $data;

?>