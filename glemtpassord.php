<?php
$dbhost = "localhost";
$dbuser = "Adam";
$dbpass = "Adam1";
$dbname = "adamdb";

// Create connection
$conn  = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Check connection
if (!$conn) {
    die("Tilkobling mislyktes: " . mysqli_connect_error());
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['username']) && isset($_POST['old_password']) && isset($_POST['new_password'])) {
        $username = $_POST['username'];
        $oldpassword = $_POST['old_password'];
        $newpassword = $_POST['new_password'];

        $sql = "SELECT * FROM Bruker WHERE Brukernavn=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($oldpassword, $user['Passord'])) {
                $newpassword_hashed = password_hash($newpassword, PASSWORD_DEFAULT);
                $sql = "UPDATE Bruker SET Passord=? WHERE Brukernavn=?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ss", $newpassword_hashed, $username);
                if ($stmt->execute()) {
                    echo "Passordet ble oppdatert";
                } else {
                    echo "En feil oppstod. Vennligst prøv igjen senere.";
                }
            } else {
                echo "Feil opplysninger angitt. Vennligst prøv igjen.";
            }
        } else {
            echo "Feil opplysninger angitt. Vennligst prøv igjen.";
        }
    } else {
        echo "Vennligst fyll ut hele skjemaet.";
    }
}

mysqli_close($conn);
?>
