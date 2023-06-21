<?php

session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$_SESSION['brukertype'] = "0";
include('conn.php');  
$username = $_POST['username'];  
$password = $_POST['password'];  

//SQL injection protection
$username = mysqli_real_escape_string($conn, $username);  
$password = mysqli_real_escape_string($conn, $password);  

$sql = "SELECT * FROM Bruker WHERE Brukernavn = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();


// bytte til denne etter testing er ferdig

// if ($result->num_rows > 0) {
//     $user = $result->fetch_assoc();
//     if (md5($password) === $user['Passord'] && $user['Sikkerhet'] == 0) {
//         $_SESSION['brukertype'] = 'admin';
//         $_SESSION['innlogget_bruker'] = $username;
//         echo "success";
//     } else {
//         echo "failure";  // Hvis passord er feil / eller sikkerhetsnivå ikke er riktig
//     }
// } else {
//     echo "failure";
// }

// ...

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    if (md5($password) === $user['Passord']) {
        $_SESSION['brukertype'] = ($user['Sikkerhet'] == 0) ? "admin" : "user";
        $_SESSION['innlogget_bruker'] = $username;
        echo "success"; 
    } else {
        echo "failure"; 
    }
} else {
    echo "failure";
}
