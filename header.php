<?php
include 'functions.php';
session_start();

if (isset($_SESSION['user'])) {
    $user = $_SESSION['user'];
    $loggedin = TRUE;
} else $loggedin = FALSE;

echo "<html><head><title>$appname";
if ($loggedin) echo " ($user)";
echo "</title></head><style>body {width: 54%; margin: 0 auto} a {font-size: 14px}</style><body><font face='verdana' size='2'>";
echo "<h2>$appname</h2>";

if ($loggedin) {
    echo "<b>Welcome $user!</b><br />
        <a href='members.php?view=$user'>Home</a> |
        <a href='members.php'>Members</a> |
        <a href='friends.php'>Friends</a> |
        <a href='messages.php'>Messages</a> |
        <a href='profile.php'>Profile</a> |
        <a href='search.php'>Search Members</a> |
        <a href='logout.php'>Log out</a> |";
} else {
    echo "<a href='index.php'>Home</a> | 
         <a href='signup.php'>Sign up</a> | 
         <a href='login.php'>Log in</a>";
}

echo "</body></html>";
?>
