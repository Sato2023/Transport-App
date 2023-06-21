<?php
if(isset($_POST['currentDate']) && isset($_POST['latitude']) && isset($_POST['longitude'])&& isset($_POST['username'])){
    require_once "conn.php";
    require_once "validate.php";
    $currentDate = validate($_POST['currentDate']);
    $latitude = validate($_POST['latitude']);
    $longitude = validate($_POST['longitude']);
    $username = validate($_POST['username']);
    

    $sql = "insert into Rute (Dato, Latitude, Longitude, Brukernavn) values('$currentDate','$latitude', '$longitude', '$username')";

    if(!$conn->query($sql)){
        echo "fail";
    }else{
        echo "success";
    }
}
?>