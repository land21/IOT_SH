<?php

$servername = "localhost";
$username = "id20835214_iotsh";
$password = "Duasatu21#";
$dbname = "id20835214_iotsh";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if(!$conn){
    die ("koneksi ke database gagal: ".mysqli_connect_error());
}else {
    echo "Selamat koneksi berhasil";
}

$webhook = json_decode(file_get_contents ('php://input'),true);
$topic = $webhook['topic'];
$payload = $webhook ['payload'];
if ($topic == "iot/SH/bedroom/Suhu" || $topic == "iot/SH/bedroom/Kelembapan" || $topic == "iot/SH/bedroom/led" ){
    $sql = "INSERT INTO bedroom (topic, payload) VALUES ('$topic', '$payload')";
}else if ($topic == "iot/SH/livingroom/Suhu" || $topic == "iot/SH/livingroom/Kelembapan" || $topic == "iot/SH/livingroom/led" ){
    $sql = "INSERT INTO livingroom (topic, payload) VALUES ('$topic', '$payload')";
}else if ($topic == "iot/SH/kitchen/Suhu" || $topic == "iot/SH/kitchen/Kelembapan" || $topic == "iot/SH/kitchen/led" ){
    $sql = "INSERT INTO kitchen (topic, payload) VALUES ('$topic', '$payload')";
}else if ($topic == "iot/SH/bathroom/Suhu" || $topic == "iot/SH/bathroom/Kelembapan" || $topic == "iot/SH/bathroom/led" ){
    $sql = "INSERT INTO bathroom (topic, payload) VALUES ('$topic', '$payload')";
}

mysqli_query($conn, $sql);
?>