<?php
session_start();

// Fjerner alle sessions
if(session_destroy())
{
    // Sender deg til loginn siden
    header("Location: index.html");
}
?>
