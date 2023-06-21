<?php

include('conn.php');

$username = $_POST['username'];  
$password = $_POST['password'];  
$firstname = $_POST['firstname'];  
$lastname = $_POST['lastname'];  
$security = $_POST['security'];  

// SQL injection protection
$username = mysqli_real_escape_string($conn, $username);  
$password = mysqli_real_escape_string($conn, $password);  
$firstname = mysqli_real_escape_string($conn, $firstname);  
$lastname = mysqli_real_escape_string($conn, $lastname);  
$security = mysqli_real_escape_string($conn, $security);  

// Hash password using md5
$hashed_password = md5($password);

$sql = "INSERT INTO Bruker (Brukernavn, Passord, Fornavn, Etternavn, Sikkerhet) VALUES (?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssi", $username, $hashed_password, $firstname, $lastname, $security);
$result = $stmt->execute();

if ($result) {
    echo "Bruker har blitt opprettet.";
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}


$conn->close();
?>
