<?php
if(isset($_POST['username']) && isset($_POST['password'])){
    require_once "conn.php";
    require "validate.php";
    $username = validate($_POST['username']);
    $password = validate($_POST['password']);
    $sql = "select * from bruker where brukernavn = '$username' and passord = '"  . md5($password)  .  "'"; #Hashing md5 style for security
    $result = $conn->query($sql);
    if($result->num_rows > 0){
        echo "success";
    }else{
        echo "fail";
    }
}
?>