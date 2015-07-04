<?php
include "functions.php";
$showhint = "";
$q = sanitizeString($_GET['q']);

$query = "SELECT user FROM members WHERE user LIKE '%$q%' ORDER BY user";
$result = queryMysql($query);
$num = mysql_num_rows($result);

if ($num) {
    for ($j = 0; $j < $num; ++$j) {
        $row = mysql_fetch_row($result);
        echo "<a href='members.php?view=$row[0]'>$row[0]</a><br />";
    }
} else echo "No suggestions<br />";
?>
