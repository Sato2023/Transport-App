<?php
include('conn.php');

if (isset($_POST['endre_nivå']) && isset($_POST['brukernavn'])) {
    $sikkerhet = $_POST['endre_nivå'] == 'Admin' ? 0 : 1;
    $brukernavn = $_POST['brukernavn'];

    $sql = "UPDATE Bruker SET Sikkerhet=? WHERE Brukernavn=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $sikkerhet, $brukernavn);
    $stmt->execute();

    header('Location: bruker.php');
    exit();
}
?>
