<?php
include_once 'functions.php';

if (isset($_POST['user'])) {
    $user = sanitizeString($_POST['user']);
    $query = "SELECT * FROM members WHERE user='$user'";
    
    if (mysql_num_rows(queryMysql($query))) {
        echo "<font color=red>&nbsp;&larr; Sorry, already taken</font>";
    } else {
        //echo "<font color=green>&nbsp;&larr; Username available</font>";
    }
}
?>
