<?php
if(isset($_POST['username']) && isset($_POST['name']) && isset($_POST['lastname'])&& isset($_POST['password'])&& isset($_POST['security'])){
    require_once "conn.php";
    require_once "validate.php";
    $username = validate($_POST['username']);
    $name = validate($_POST['name']);
    $lastname = validate($_POST['lastname']);
    $password = validate($_POST['password']);
    $security = validate($_POST['security']);

    $sql = "insert into bruker values('$username','"  . md5($password)  .  "', '$name', '$lastname', '$security')";

    if(!$conn->query($sql)){
        echo "fail";
    }else{
        echo "success";
    }
}
?>