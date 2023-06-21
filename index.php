<!DOCTYPE html>
<html>

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="stylesheet" href="main.css">
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
        <li><a href="index.php">Bruker</a></li>
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
session_start();

include('conn.php');

if (empty($_POST['query'])){
    $sql_søk = $conn->prepare("SELECT * FROM Bruker");
    $sql_søk ->execute();
    $resultat = $sql_søk->get_result();

    if (mysqli_num_rows($resultat) > 0) {
        echo "<table>";
        echo "<tr><th>Fornavn</th><th>Brukernavn</th><th>Sikkerhetnivå</th><th>Table Header</th></tr>";
            
        while ($rad = mysqli_fetch_assoc($resultat)) {
            echo "<tr>";
            echo "<td>" . $rad["Fornavn"] . '<br>' . $rad["Etternavn"] . "</td>";
            echo "<td>" . $rad["Brukernavn"] . "</td>";
            echo "<td>" . $rad["Sikkerhet"] . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "Bruker Finnes ikke";
    }
} else {
    if (isset($_POST["query"])) {
    $søke_query = $_POST["query"];
    $sql_søk = $conn->prepare("SELECT * FROM Bruker WHERE Brukernavn LIKE '%".$søke_query."%'");
    $sql_søk ->execute();
    $resultat = $sql_søk->get_result();

    if (mysqli_num_rows($resultat) > 0) {
        echo "<table>";
        echo "<tr><th>Fornavn</th><th>Brukernavn</th><th>Sikkerhetnivå</th><th>Table Header</th></tr>";
            
        while ($rad = mysqli_fetch_assoc($resultat)) {
            echo "<tr>";
            echo "<td>" . $rad["Fornavn"] . '<br>' . $rad["Etternavn"] . "</td>";
            echo "<td>" . $rad["Brukernavn"] . "</td>";
            echo "<td>" . $rad["Sikkerhet"] . "</td>";
            echo "</tr>";
        }
        
        echo "</table>";
    } else {
        echo "Bruker Finnes ikke";
    }
  } else {
    $søke_query = "";
}
}
mysqli_close($conn);
?>
<!-- Filler slutt --->

</main>
</body>

</html>