<?php
include_once 'header.php';
echo "<h3>Member Log in</h3>";
$error = $user = $pass = "";

if (isset($_POST['user'])) {
    $user = sanitizeString($_POST['user']);
    $pass = sanitizeString($_POST['pass']);
    if ($user == "" || $pass == "") {
        $error = "Please enter all fields.<br />";
    } else {
        $query = "SELECT user,pass FROM members WHERE user='$user' AND pass='$pass'";
        if (mysql_num_rows(queryMysql($query)) == 0) {
            $error = "Invalid username or password<br />";
        } else {
            $_SESSION['user'] = $user;
            $_SESSION['pass'] = $pass;
            die("You are now logged in. Please <a href='members.php?view=$user'>Click here</a>.");
        }
    }
}

echo <<<_END
<style>
pre {font-size: 16px}
</style>
<form method='post' action='login.php'>$error<pre>
    Name <input type='text' maxlength='16' name='user' value='$user' /><br />
Password <input type='password' maxlength='16' name='pass' value='$pass' /><br />
         <input type='submit' value='Login' />
</pre>
</form>
_END;
?>
