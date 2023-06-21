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

<script>
    function confirmDelete() {
        return confirm("Er du sikker på at du vil slette denne brukeren??");
    }
</script>

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
<title>Bruker</title>
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
    <input class="bar_button" type="submit" value="Søk" >
</form>

<!-- Filler, kan byttes med php etc-->
<h1>Brukeradministrasjon</h1>
<?php
include('conn.php');

if (empty($_POST['query'])){
    $sql_søk = $conn->prepare("SELECT * FROM Bruker");
    $sql_søk ->execute();
    $resultat = $sql_søk->get_result();

    if (mysqli_num_rows($resultat) > 0) {
        echo "<table>";
        echo "<tr><th>Fornavn</th><th>Etternavn</th><th>Brukernavn</th><th>Sikkerhetnivå</th><th>Endre nivå</th><th>Slett ansatt</th></tr>"; 

        while ($rad = mysqli_fetch_assoc($resultat)) {
            echo "<tr>";
            echo "<td>" . $rad["Fornavn"] . "</td>"; 
            echo "<td>" . $rad["Etternavn"] . "</td>"; 
            echo "<td>" . $rad["Brukernavn"] . "</td>"; 
            $brukertype = $rad["Sikkerhet"] == 0 ? "0 (admin)" : "1 (bruker)";
            echo "<td>" . $brukertype . "</td>"; 

            // Endre nivå
            echo "<td>";
            echo "<form action='endre_sikkerhet.php' method='POST'>";
            echo "<input type='hidden' name='brukernavn' value='" . $rad["Brukernavn"] . "'>";
            echo "<input type='submit' name='endre_nivå' value='Bruker'>";
            echo "<input type='submit' name='endre_nivå' value='Admin'>";
            echo "</form>";
            echo "</td>";
        
            // Slett ansatt
            echo "<td>";
            echo "<form action='slett_bruker.php' method='POST'>";
            echo "<input type='hidden' name='brukernavn' value='" . $rad["Brukernavn"] . "'>";
            echo "<input type='submit' name='slett' value='Slett' onClick='return confirmDelete()'>";
            echo "</form>";
            echo "</td>";            
        
            echo "</tr>";
        }
        
        echo "</table>";
        
        } else {
            echo "Ingen brukere funnet.";
        }
        
        } else {
            if (isset($_POST["query"])) {
            $søke_query = $_POST["query"];
            $sql_søk = $conn->prepare("SELECT * FROM Bruker WHERE Brukernavn LIKE '%".$søke_query."%'");
            $sql_søk ->execute();
            $resultat = $sql_søk->get_result();
        
            if (mysqli_num_rows($resultat) > 0) {
                echo "<table>";
                echo "<tr><th>Fornavn</th><th>Etternavn</th><th>Brukernavn</th><th>Sikkerhetnivå</th><th>Endre nivå</th><th>Slett ansatt</th></tr>"; 
                
                while ($rad = mysqli_fetch_assoc($resultat)) {
                    echo "<tr>";
                    echo "<td>" . $rad["Fornavn"] . "</td>";
                    echo "<td>" . $rad["Etternavn"] . "</td>";
                    echo "<td>" . $rad["Brukernavn"] . "</td>";
                    $brukertype = $rad["Sikkerhet"] == 0 ? "0 (admin)" : "1 (bruker)";
                    echo "<td>" . $brukertype . "</td>";
        
                    // Endre nivå
                    echo "<td>";
                    echo "<form action='slett_bruker.php' method='POST'>";
                    echo "<input type='hidden' name='brukernavn' value='" . $rad["Brukernavn"] . "'>";
                    echo "<input type='submit' name='slett' value='Slett' onClick='return confirmDelete()'>";
                    echo "</form>";
                    echo "</td>";
                    
        
                    // Slett ansatt
                    echo "<td>";
                    echo "<form action='slett_bruker.php' method='POST'>";
                    echo "<input type='hidden' name='brukernavn' value='" . $rad["Brukernavn"] . "'>";
                    echo "<input type='submit' name='slett' value='Slett'>";
                    echo "</form>";
                    echo "</td>";
        
                    echo "</tr>";
                }
                
                echo "</table>";
            } else {
                echo "Ingen brukere funnet.";
            }
          } else {
            $søke_query = "";
        }
        }
        $conn->close();
?>
</main>
</body>
</html>
