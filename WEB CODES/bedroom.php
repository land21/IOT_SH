<?php
    require 'config.php';
    $result = mysqli_query($db,
            "SELECT * FROM bedroom ORDER BY time DESC LIMIT 5",);
    $suhu = mysqli_query($db,
    "SELECT * FROM bedroom WHERE topic LIKE 'iot/SH/bedroom/Suhu' ORDER BY time DESC LIMIT 1",);
    foreach($suhu as $suuhu)
    $hum = mysqli_query($db,
    "SELECT * FROM bedroom WHERE topic LIKE 'iot/SH/bedroom/Kelembapan' ORDER BY time DESC LIMIT 1",);
    foreach($hum as $huum)
    $led = mysqli_query($db,
    "SELECT * FROM bedroom WHERE topic LIKE 'iot/SH/bedroom/led' ORDER BY time DESC LIMIT 1",);
    foreach($led as $leed)
    if ($leed['payload'] == '1'){
        $nilai_led = "Nyala";
    } elseif ($leed['payload'] == '0'){
        $nilai_led = "Mati";
    }
    $bed = mysqli_query($db,"SELECT * FROM bedroom WHERE topic LIKE 'iot/SH/bedroom/Kelembapan' or topic LIKE 'iot/SH/bedroom/Suhu'",);
    $jumlahbed = mysqli_num_rows($bed);

    if ($jumlahbed > 115){
        $hapus = mysqli_query($db, "DELETE FROM bedroom WHERE topic LIKE 'iot/SH/bedroom/Kelembapan' or topic LIKE 'iot/SH/bedroom/Suhu' ORDER BY time ASC LIMIT 100");
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
</head>
<body>
        <!-- =================== ASIDE ================= -->
        <aside>
            <div class="top">
                <div class="logo">
                    <img src="image/logo.png" alt="IOT">
                    <h2>My<span class="danger">IOT</span></h2>
                </div>
                <div class="close" id="close-btn">                    
                    <span class="material-icons-sharp">
                        close
                    </span>
                </div>
            </div>
            <div class="sidebar">
                <a href="index.php" class="active">
                    <span class="material-icons-sharp">
                        king_bed
                    </span>
                    <h3>Bedroom</h3>
                </a>
                <a href="indexliving.php">
                    <span class="material-icons-sharp">
                        chair
                    </span>
                    <h3>Living Room</h3>
                </a>
                <a href="indexbath.php">
                    <span class="material-icons-sharp">
                        bathtub
                    </span>
                    <h3>Bathroom</h3>
                </a>
                <a href="indexkit.php">
                    <span class="material-icons-sharp">
                        countertops
                    </span>
                    <h3>Kitchen</h3>
                </a>
            </div>
        </aside>
        <!-- ================= MAIN ==================  -->
        <main>
            <!-- ================= INSIGHTS ==================  -->
            <div class="navigasi">
                <div class="active">
                    <a href="index.php" class="room">
                        <h3>BEDROOM</h3>
                    </a>
                </div>
                <div class="liv">
                    <a href="indexliving.php" class="room">
                        <h3>LIVING ROOM</h3>
                    </a>
                </div>
                <div class="bath">
                    <a href="indexbath.php" class="room">
                        <h3>BATHROOM</h3>
                    </a>
                </div>
                <div class="kit">
                    <a href="indexkit.php" class="room">
                        <h3>KITCHEN</h3>
                    </a>
                </div>
            </div>
            <h1 class="room">Bedroom</h1>
            <div class="insights">
                <!-- --------------- SUHU ---------------- -->
                <div class="Suhu">
                    <span class="material-icons-sharp">
                        thermostat
                    </span>
                    <div class="middle">
                        <div class="left">
                            <h3>Suhu</h3>
                            <h1><?=$suuhu['payload']?>° Celcius</h1>
                        </div>
                        <div class="progress">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="number">
                                <p><?=$suuhu['payload']?>°C</p>
                            </div>
                        </div>
                    </div>
                    <small class="text-muted">Suhu pada kamar tidur</small>
                </div>
                <!-- --------------- KELEMBAPAN ---------------- -->
                <div class="Kelembapan">
                    <span class="material-icons-sharp">
                        ac_unit
                    </span>
                    <div class="middle">
                        <div class="left">
                            <h3>Kelembapan</h3>
                            <h1><?=$huum['payload']?>% RH</h1>
                        </div>
                        <div class="progress">
                            <svg>
                                <circle cx="38" cy="38" r="36"></circle>
                            </svg>
                            <div class="number">
                                <p><?=$huum['payload']?>%</p>
                            </div>
                        </div>
                    </div>
                    <small class="text-muted">Kelembapan pada kamar tidur</small>
                </div>
                <!-- --------------- LED ---------------- -->
                <div class="led">
                    <span class="material-icons-sharp">
                        lightbulb
                    </span>
                    <div class="middle">
                        <div class="left">
                            <h3>LED</h3>
                            <h1><?php echo $nilai_led?></h1>
                        </div>
                    </div>
                    <small class="text-muted">LED pada kamar tidur</small>
                </div>
            </div>
            <!-- ================= TABLE ==================  -->
            <div class="data-topic">
                <h2>Data masuk dari broker</h2>
                <table>
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Topic</th>
                            <th>Payload</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>
                    <?php
                        $i = 1; 
                        
                        if(mysqli_num_rows($result) > 0){
                            while ($i <=5){
                            foreach($result as $row){
                ?>
                    <tbody>
                        <tr>
                        <td><?=$i?></td>
                        <td><?=$row['topic']?></td>
                        <td><?=$row['payload']?></td>
                        <td><?=$row['time']?></td>
                        </tr>
                        <?php 
                    $i++;    
                    }
                        
                    }
                }
            ?>
                    </tbody>
                </table>
            </div>
        </main>

        <!-- ================= RIGHT ==================  -->
        <div class="right">
            <!-- -------------------- TOP ----------------------- -->
            <div class="top">
                <div class="logo">
                    <img src="image/logo.png" alt="IOT">
                    <h2>My<span class="danger">IOT</span></h2>
                </div>
            </div>
            <!-- ------------------------- NOTE --------------------- -->
            <div class="note">
                <h2>NOTE</h2>
                <div class="notes">
                    <div class="note">
                        <div class="icon">
                            <img src="image/led.png" alt="LED">
                        </div>
                        <div class="msgnoted">
                            <p><b>LED</b> Hanya bisa dimonitoring tanpa bisa di kontrol dari web ini</p>
                        </div>
                    </div>
                    <div class="note">
                        <div class="icon">
                            <img src="image/hum.png" alt="LED">
                        </div>
                        <div class="msgnoted">
                            <p><b>Kelembapan</b> Hanya bisa dimonitoring tanpa bisa di kontrol dari web ini</p>
                        </div>
                    </div>
                    <div class="note">
                        <div class="icon">
                            <img src="image/temp.png" alt="LED">
                        </div>
                        <div class="msgnoted">
                            <p><b>Suhu</b> Hanya bisa dimonitoring tanpa bisa di kontrol dari web ini</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="./index.js"></script>
        </body>
        </html>     