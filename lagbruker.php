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
  <title>Create New User</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="main.css">
  <style>
body {
  font-family: Arial, sans-serif;
  background-color: white;
}

h1 {
  font-size: 40px;
  text-align: center;
  padding-top: 200px;
}

form {
  width: 600px;
  margin: auto;
  padding: 20px;
  background-color: white;
  border: 1px solid black;
  border-radius: 4px;
}

form label {
  display: block;
}

input[type=text], input[type=password], select {
  width: 30em;
  height: 3em;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  box-sizing: border-box;
}

button {
  width: 8em;
  height: 3em;
  font-size: 16px;
  margin-top: 8px;
  background-color: black;
  color: white;
  border: none;
  cursor: pointer;
}

button:hover {
  opacity: 0.8;
}

#message {
  color: red;
  font-size: 14px;
  text-align: center;
}

#glemtpassord {
  font-size: 14px;
  text-align: center;
}

  </style>
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
  <h1>Lag Ny Bruker</h1>
  <form action="createuser.php" method="post">
    <label for="username"><b>Brukernavn</b></label>
    <input type="text" placeholder="Brukernavn" name="username" required>

    <label for="password"><b>Passord</b></label>
    <input type="password" placeholder="Passord" name="password" required>

    <label for="firstname"><b>Fornavn</b></label>
    <input type="text" placeholder="Ole" name="firstname" required>

    <label for="lastname"><b>Etternavn</b></label>
    <input type="text" placeholder="Nordmann" name="lastname" required>

    <label for="security"><b>Sikkerhet (0 eller 1)</b></label>
    <select name="security" id="security" required>
        <option value="">Velg sikkerhetsnivå</option>
        <option value="0">0 (Administrator)</option>
        <option value="1">1 (Vanlig bruker)</option>
    </select>


    <button type="submit">Lag bruker</button>
  </form>

  <div id="message"></div>

  <script>
// Følger med på submit til skjemat
document.querySelector('form').addEventListener('submit', function(event) {
  event.preventDefault();  // Gjør at skjemaet ikke blir sendt på den vanlige måten


  var formData = new FormData(event.target);

  // Sender en POST-forespørsel til createuser.php
  fetch('createuser.php', {
    method: 'POST',
    body: formData
  })
  .then(response => response.text())
  .then(text => {
    // Viser svarteksten i 'message'-diven
    document.querySelector('#message').innerText = text;
  })
  .catch(error => console.error(error));
});
</script>



</main>

</body>
</html>
