<?php
$dbhost = 'localhost';
$dbname = 'network';
$dbuser = 'pma'; //need to configure phpMyAdmin in WampServer following steps 
                 //in http://stackoverflow.com/questions/11506224/connection-for-controluser-as-defined-in-your-configuration-failed-phpmyadmin-xa
$dbpass = 'yiyang';
$appname = "MyNetWork";

mysql_connect($dbhost, $dbuser, $dbpass) or die(mysql_error());
mysql_select_db($dbname) or die(mysql_error());

function createTable($tablename, $query) {
    if (tableExists($tablename)) {
        echo "Table '$tablename' already exists<br />";
    } else {
        queryMysql("CREATE Table $tablename($query)");
        echo "Table '$tablename' created<br />";
    }
}
        
function TableExists($tablename) {
    $result = queryMysql("SHOW TABLES LIKE '$tablename'");
    return mysql_num_rows($result);
}

function queryMysql($query) {
    $result = mysql_query($query) or die(mysql_error());
    return $result;
}

function destroySession() {
    $_SESSION = array();
    if (session_id() != "" || isset($_COOKIE[session_name()]))
        setcookie(session_name(), '', time()-2592000, '/');
    session_destroy();
}

function sanitizeString($var) {
    $var = strip_tags($var);
    $var = htmlentities($var);
    $var = stripslashes($var);
    return mysql_real_escape_string($var);
}

function showProfile($user) {
    if (file_exists("$user.jpg"))
        echo "<img src='$user.jpg' border='1' align='left' />";
    $result = queryMysql("SELECT * FROM profiles WHERE user='$user'");
    if (mysql_num_rows($result)) {
        $row = mysql_fetch_row($result);
        echo stripslashes($row[1] . "<br clear=left /><br />");
    }
}
?>
