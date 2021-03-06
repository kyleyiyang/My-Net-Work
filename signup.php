<?php
include_once 'header.php';

echo <<<_END
<script>
function checkUser(user) {
    if (user.value == '') {
        document.getElementById('info').innerHTML = ''
        return
    }
    
    params = "user=" + user.value
    request = new ajaxRequest()
    request.open("POST", "checkuser.php", true)
    request.setRequestHeader("Content-type", "application/x-www-form-urlencoded")
    request.setRequestHeader("Content-length", params.length)
    request.setRequestHeader("Connection", "close")
    
    request.onreadystatechange = function() {
        if (this.readyState == 4) {
            if (this.status == 200) {
                if (this.responseText != null) {
                    document.getElementById('info').innerHTML = this.responseText
                } else alert("Ajax error: No data received")
            } else alert("Ajax error: " + this.statusText)
        }
    }
    request.send(params)
}

function ajaxRequest() {
    try {
        var request = new XMLHttpRequest()
    }
    catch(e1) {
        try {
            request = new ActiveXObject("Msxml2.XMLHTTP")
        }
        catch(e2) {
            try {
                request = new ActiveXObject("Microsoft.XMLHTTP")
            }
            catch(e3) {
                request = false
            }
        }
    }
    return request
}
</script>
_END;

$nameErr = $emailErr = $passwordErr = "";
$user = $email = $pass = $comment = "";

if (isset($_SESSION['user'])) destroySession();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["user"])) {
        $nameErr = "Name is required";
    } else {
        $user = sanitizeString($_POST["user"]);
        if (!preg_match("/^[a-zA-Z ]*$/",$user)) {
            $nameErr = "Only letters and white space allowed";
        } else {
            $query = "SELECT * FROM members WHERE user='$user'";  //The following if clause is included in checkuser.php
            if (mysql_num_rows(queryMysql($query))) {
                $nameErr = "That username already exists";
            }
        }
    }
    
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = sanitizeString($_POST["email"]);
        if (!filter_var($email,FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }
    
    if (empty($_POST["pass"])) {
        $passwordErr = "Password is required";
    } else {
        $pass = sanitizeString($_POST["pass"]);
    }
    
    if (empty($_POST["comment"])) {
        $comment = "";
    } else {
        $comment = sanitizeString($_POST["comment"]);
    }
    
    //The following if clause may be placed bellow the form's html.
    if (!$nameErr and !$emailErr and !$passwordErr) {
        $query = "INSERT INTO members VALUES('$user', '$pass', '$email')";
        queryMysql($query);
        echo "<h2>Account created!</h2>Please Log in";
        echo "<style>div {display: none}</style>";
    }
}

echo <<<_END
<html>
<head>
<title>Registration Form</title>
<style>
body {width: 54%; margin: 0 auto}
.error {color: red}
p {color: red}
pre {font-size: 16px}
</style>
</head>
<body>
<div>
<h3>Please register</h3>
<p>* required field</p>
<form method="post" action="signup.php"><pre>
    Name: <input type="text" maxlength='16' name="user" value='$user' onBlur='checkUser(this)' /><span class="error" id='info'> * $nameErr</span><br>
  E-mail: <input type="text" maxlength='50' name="email" value='$email' /><span class="error"> * $emailErr</span><br>
Password: <input type="password" maxlength='16' name="pass" value='$pass' /><span class="error"> * $passwordErr</span><br>
 Comment: <textarea name="comment" rows="5" cols="40">$comment</textarea><br>
          <input type="submit" name="submit" value="Submit" />
</pre>
</form>
</body>
</div>
</html>
_END;

// echo "<h2>Your Input:</h2>";
// echo $user;
// echo "<br>";
// echo $email;
// echo "<br>";
// echo $pass;
// echo "<br>";
// echo $comment;
?>
