<?php
session_start();

if (!isset($_SESSION['innlogget_bruker']) || $_SESSION['brukertype'] !== 'admin') {
    header('Location: index.html');
    exit();
}
?>

<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="main.css">
<style>
    main {
        padding-top:200px;
        width:600px;
        margin:auto;
    }

    table {
                border-collapse: collapse;
                width: 100%;
                /*margin-top:50px;*/
            }

    th, td {
        text-align: left;
        padding: 8px;
    }

    tr:nth-child(odd) {
        background-color:#DADADA;
    }

</style>
<title>Ruter</title>
</head>

<body>
<header>
<nav class="menybar">
<label for="drop" class="toggle">Meny</label>
<input type="checkbox" id="drop" />
<ul class="meny">
    <li>
        <a href="bruker.php">Bruker</a>
        <ul class="dropdown">
            <li><a href="lagbruker.php">Lag Bruker</a></li>
        </ul>
    </li>
    <li><a href="ruter.php">Ruter</a></li>
    <li><a href="timer.php">Timer</a></li>
    <li><a href="loggut.php">Logg ut</a></li>
</ul>
</nav>
</header>

<main>
<!-- Søke bar-->
<form class="søke_bar"method="POST">
		<input class="bar" type="text" name="query" placeholder="Søke Bruker...">
		<input class="bar_button"type="submit" value="Søk" >
</form>

<h1>Ruter</h1>
<div id="map" style="width: 100%; height: 60vh; display: none;"></div>
<?php

include('conn.php');

/* Henter koordinater til ønsket rute og putter i arrays*/
if(isset($_GET['Dato'], $_GET['Brukernavn'])){
    echo "<style>#map{display: block !important;}</style>";
	$Dato = $_GET['Dato'];
	$Brukernavn = $_GET['Brukernavn'];
    $koordinater = array();
    $latitudes = array();
    $longitudes = array();

    // Velger alle radene i rute tabellen som hører til valgt dato og brukernavn

    $sql_søk = $conn->prepare("SELECT * FROM Rute R WHERE Dato=? AND Brukernavn=?");
    $sql_søk->bind_param("ss", $Dato, $Brukernavn);
    $sql_søk->execute();

    $resultat = $sql_søk->get_result();

    while ($row = mysqli_fetch_array($resultat)) {
        $latitudes[] = $row['Latitude'];
        $longitudes[] = $row['Longitude'];
        $koordinater[] = 'new google.maps.LatLng(' . $row['Latitude'] .','. $row['Longitude'] .'),';
    }

    //Fjerner komma ',' fra siste koordinat
    $lastcount = count($koordinater)-1;
    $koordinater[$lastcount] = trim($koordinater[$lastcount], ",");	
}

if (empty($_POST['query'])){
    $sql_søk = $conn->prepare("SELECT * FROM Rute R LEFT JOIN Bruker B ON R.Brukernavn = B.Brukernavn GROUP BY Dato, R.Brukernavn");
    $sql_søk ->execute();
    $resultat = $sql_søk->get_result();
    
    if (mysqli_num_rows($resultat) > 0) {
        echo "<table>";
        echo "<tr><th>Fornavn</th><th>Brukernavn</th><th>Dato (d-m-y)</th><th>Rute</th></tr>";
        
        while ($rad = mysqli_fetch_assoc($resultat)) {
            echo "<tr>";
            echo "<td>" . $rad["Fornavn"] . '<br>'. $rad["Etternavn"] ."</td>";
            echo "<td>" . $rad["Brukernavn"] . "</td>";
            $dato_fra_db = $rad["Dato"];
            $sql_date = date_create($dato_fra_db);
            $php_date = date_format($sql_date, "d-m-y");
            echo "<td>" . $php_date . "</td>";
            echo "<td id='leggTil'>
            <form action='{$_SERVER['PHP_SELF']}'>
                <input type='hidden' name='Dato' id='Dato' value='{$rad['Dato']}'>
                <input type='hidden' name='Brukernavn' id='Brukernavn' value='{$rad['Brukernavn']}'>
                <a href='#map'>
                <input type='submit' value='Vis i kart'>
                </a>
            </form>
            </td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "No matching records found.";
    }
} else {
    if (isset($_POST["query"])) {
    $søke_query = $_POST["query"];
    $sql_søk = $conn->prepare("SELECT * FROM Rute R  LEFT JOIN Bruker B ON R.Brukernavn = B.Brukernavn WHERE R.Brukernavn LIKE '%".$søke_query."%' GROUP BY Dato, R.Brukernavn");
    $sql_søk ->execute();
    $resultat = $sql_søk->get_result();
    
    if (mysqli_num_rows($resultat) > 0) {
        echo "<table>";
        echo "<tr><th>Fornavn</th><th>Brukernavn</th><th>Dato</th><th>Rute</th></tr>";
            
        while ($rad = mysqli_fetch_assoc($resultat)) {
            echo "<tr>";
            echo "<td>" . $rad["Fornavn"] . '<br>'. $rad["Etternavn"] ."</td>";
            echo "<td>" . $rad["Brukernavn"] . "</td>";
            $dato_fra_db = $rad["Dato"];
            $sql_date = date_create($dato_fra_db);
            $php_date = date_format($sql_date, "d-m-y");
            echo "<td>" . $php_date . "</td>";
            echo "<td id='leggTil'>
            <form action='{$_SERVER['PHP_SELF']}'>
                <input type='hidden' name='Dato' id='Dato' value='{$rad['Dato']}'>
                <input type='hidden' name='Brukernavn' id='Brukernavn' value='{$rad['Brukernavn']}'>
                <a href='#map'>
                <input type='submit' value='Vis i kart'>
                </a>
            </form>
            </td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "No matching records found.";
    }
    } else {
        $søke_query = "";
    }
}
mysqli_close($conn);
?>

    <script>
        function initMap() {
            var mapOptions = {
            zoom: 18,
            center: {<?php echo'lat:'. $latitudes[0] .', lng:'. $longitudes[0] ;?>},
            mapTypeId: google.maps.MapTypeId.SATELITE
            };

            var map = new google.maps.Map(document.getElementById('map'),mapOptions);

            var RuteKoordinater = [
            <?php
                $i = 0;
                while ($i < count($koordinater)) {
                    echo $koordinater[$i];
                    $i++;
                }
            ?>
            ];

            var RoutePath = new google.maps.Polyline({
            path: RuteKoordinater,
            geodesic: true,
            strokeColor: '#00B0FF',
            strokeOpacity: 1.0,
            strokeWeight: 8
            });

            pin_a = 'Bilder/A.png';
            pin_b = 'Bilder/B.png';

            startPoint = {<?php echo'lat:'. $latitudes[0] .', lng:'. $longitudes[0] ;?>};
            endPoint = {<?php echo'lat:'.$latitudes[$lastcount] .', lng:'. $longitudes[$lastcount] ;?>};

            var marker = new google.maps.Marker({
            position: startPoint,
            map: map,
            icon: pin_a,
            title:"Startpunkt",
            animation: google.maps.Animation.DROP
            });

            var marker = new google.maps.Marker({
            position: endPoint,
            map: map,
            icon: pin_b,
            title:"Sluttpunkt",
            animation: google.maps.Animation.DROP
        });
            RoutePath.setMap(map);
        }

        google.maps.event.addDomListener(window, 'load', initialize);
    </script>

    <!--Google Maps API key puttes mellom "key=" og "&callback"-->
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAoIHbo-sd98tGb-wvnzdddUMErAB4_1UY&callback=initMap"></script>
</main>
</body>

</html>