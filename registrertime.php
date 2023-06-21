<?php
if( isset($_POST['currentDate']) && isset($_POST['elapsedSeconds']) && isset($_POST['username'])){
    require_once "conn.php";
    require_once "validate.php";
    $currentDate = validate($_POST['currentDate']);
    $elapsedSeconds = validate($_POST['elapsedSeconds']);
    $username = validate($_POST['username']);
    

    $sql = "insert into Arbeidstid (Dato, ArbeidsTimer, Brukernavn) values('$currentDate','$elapsedSeconds', '$username')";
    

    if(!$conn->query($sql)){
        echo "fail";
    }else{
        echo "success";
    }

}

?>

