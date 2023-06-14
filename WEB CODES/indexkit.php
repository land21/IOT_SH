<?php
    require 'config.php';
    $result = mysqli_query($db,
            "SELECT * FROM kitchen ORDER BY time DESC LIMIT 5",);
    $suhu = mysqli_query($db,
    "SELECT * FROM kitchen WHERE topic LIKE 'iot/SH/kitchen/Suhu' ORDER BY time DESC LIMIT 1",);
    foreach($suhu as $suuhu)
    $hum = mysqli_query($db,
    "SELECT * FROM kitchen WHERE topic LIKE 'iot/SH/kitchen/Kelembapan' ORDER BY time DESC LIMIT 1",);
    foreach($hum as $huum)
    $led = mysqli_query($db,
    "SELECT * FROM kitchen WHERE topic LIKE 'iot/SH/kitchen/led' ORDER BY time DESC LIMIT 1",);
    foreach($led as $leed)
    if ($leed['payload'] == '1'){
        $nilai_led = "Nyala";
    } elseif ($leed['payload'] == '0'){
        $nilai_led = "Mati";
    }
    $kit = mysqli_query($db,"SELECT * FROM kitchen WHERE topic LIKE 'iot/SH/kitchen/Kelembapan' or topic LIKE 'iot/SH/kitchen/Suhu'",);
    $jumlahkit = mysqli_num_rows($kit);

    if ($jumlahkit > 115){
        $hapus = mysqli_query($db, "DELETE FROM kitchen WHERE topic LIKE 'iot/SH/kitchen/Kelembapan' or topic LIKE 'iot/SH/kitchen/Suhu' ORDER BY time ASC LIMIT 100");
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IOT</title>
    <link rel="shortcut icon" type="image/png" href="image/logo.png"/>
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet">
    <script type="text/javascript" src="index.js"></script>
    <script type="text/javascript">
        function table() {
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                document.getElementById("isi").innerHTML = this.responseText;
            }    
            xhttp.open("GET","kitchen.php")
            xhttp.send();
        }
        setInterval(function() {
            table();
            <?php $hapus ?>
        }, 1000);
    </script>
</head>
<body onload = "table();">
    <div class="container" id="isi">
        
    </div>
    
</body>
</html>