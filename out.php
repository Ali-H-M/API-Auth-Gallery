<?php
session_start();  // Start or resume the session

// Clear all session variables
session_unset();

// Destroy the session
session_destroy();

// Redirect to the Sign-in Page
header("Location: elements/signin.php");
exit();
?>