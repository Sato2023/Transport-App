<?php
include('conn.php');

if (isset($_POST['slett']) && isset($_POST['brukernavn'])) {
    $brukernavn = $_POST['brukernavn'];

    $sql = "DELETE FROM Bruker WHERE Brukernavn=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $brukernavn);
    $stmt->execute();

    header('Location: bruker.php');
    exit();
}
?>
