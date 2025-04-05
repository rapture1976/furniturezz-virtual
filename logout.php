<?php
session_start();

if (isset($_POST['logout']) && $_POST['logout'] == '1') {
    
    unset($_SESSION['username']);
    unset($_SESSION['password']); 
    // Remove all session variables
    session_unset(); 
    // Destroy the session
    session_destroy(); 
    // Redirect to login 
    header("Location: home.html");  
    exit;
    //error handling if no logout
} else {
    echo "No logout request detected."; 
}
?>








