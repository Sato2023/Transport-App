<?php
session_start();

if (!isset($_SESSION['innlogget_bruker'])) {
    header('Location: index.html');
    exit();
}

?>

<!DOCTYPE html>
<html>
<head>
  <title>Forgot Password</title>
  <style>
    body {
      font-family: Arial, sans-serif;
    }

    .container {
      width: 300px;
      padding: 16px;
      background-color: white;
      margin: 0 auto;
      margin-top: 100px;
      border: 1px solid black;
      border-radius: 4px;
    }

    input[type=text], input[type=password] {
      width: 100%;
      padding: 12px 20px;
      margin: 8px 0;
      display: inline-block;
      border: 1px solid #ccc;
      box-sizing: border-box;
    }

    button {
      background-color: #4CAF50;
      color: white;
      padding: 14px 20px;
      margin: 8px 0;
      border: none;
      cursor: pointer;
      width: 100%;
    }

    button:hover {
      opacity: 0.8;
    }
  </style>
</head>
<body>

<div class="container">
  <form action="forgot_password.php" method="post">
    <label for="username"><b>Username</b></label>
    <input type="text" placeholder="Enter Username" name="username" required>

    <label for="old_password"><b>Old Password</b></label>
    <input type="password" placeholder="Enter Old Password" name="old_password" required>

    <label for="new_password"><b>New Password</b></label>
    <input type="password" placeholder="Enter New Password" name="new_password" required>

    <button type="submit">Update Password</button>
  </form>
</div>

</body>
</html>
