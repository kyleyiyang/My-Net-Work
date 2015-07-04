<?php
include_once "header.php";
$user = $view = $name = "";

if (isset($_SESSION['user'])) $user = $_SESSION['user'];
else {
    echo "<style>form {display: none}</style>";
    echo "<br /><br />You must be logged in to view this page";
}

if (isset($_POST['view'])) {
    $view = sanitizeString($_POST['view']);
    $query = "SELECT user FROM members WHERE user='$view'";
    if (mysql_num_rows(queryMysql($query)) == 0) {
        echo "<br /><br />Sorry. Member $view does not exist.";
    } else {
        if ($view == $user) $name = "Your";
        else $name = "$view's";
        
        echo "<h3>$name Page</h3>";
        showProfile($view);
        echo "<a href='messages.php?view=$view'>$name messages</a><br />";
        echo "<a href='friends.php?view=$view'>$name Friends</a><br />";
        // echo "<style>form {display: none}</style>";
    }
}

echo <<<_END
<html>
<head>
<style>
form {width: 80%}
#livesearch {width: 146px}
</style>
<script>
function showResult(name) {
    if (name.length == 0) {
        document.getElementById("livesearch").innerHTML = "";
        document.getElementById("livesearch").style.border = "0px";
        return;
    }
    if (window.XMLHttpRequest) { //for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp = new XMLHttpRequest();
    } else { //for IE6, IE5
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
            document.getElementById("livesearch").innerHTML = xmlhttp.responseText;
            document.getElementById("livesearch").style.border = "1px solid #A5ACB2";
        }
    }
    xmlhttp.open("GET", "livesearch.php?q=" + name, true);
    xmlhttp.send();
}
</script>
</head>
<body>
<form method='post' action='search.php'><br /><br />
Search members by Name:<br />
<input type='text' maxlength='16' onkeyup='showResult(this.value)' name='view' value='$view' />
<div id='livesearch'></div>
<input type='submit' value='Search' />
</form>
</body>
</html>
_END;
?>
