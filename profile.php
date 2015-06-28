<?php
include_once 'header.php';

if (!isset($_SESSION['user'])) die("<br /><br />You need to login to view this page");
$user = $_SESSION['user'];

echo "<h3>Edit your profile</h3>";

if (isset($_POST['text'])) {
    $text = sanitizeString($_POST['text']);
    $text = preg_replace('/\s\s+/', ' ', $text);
    $query = "SELECT * FROM profiles WHERE user='$user'";
    if (mysql_num_rows(queryMysql($query))) {
        queryMysql("UPDATE profiles SET text='$text' WHERE user='$user'");
    } else {
        $query = "INSERT INTO profiles VALUES('$user', '$text')";
        queryMysql($query);
    }
} else {
    $query = "SELECT * FROM profiles WHERE user='$user'";
    $result = queryMysql($query);
    if (mysql_num_rows($result)) {
        $row = mysql_fetch_row($result);
        $text = stripslashes($row[1]);
    } else $text = "";
}
$text = stripslashes(preg_replace('/\s\s+/', ' ', $text));

if (isset($_FILES['image']['name'])) {
    $saveto = "$user.jpg";
    move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
    $typeok = FALSE;
    switch($_FILES['image']['type']) {
        case "image/gif": $src = imagecreatefromgif($saveto); $typeok = TRUE; break;
        case "image/jpeg": $src = imagecreatefromjpeg($saveto); $typeok = TRUE; break; //Both regular and progressive jpegs
        case "image/pjpeg": $src = imagecreatefromjpeg($saveto); $typeok = TRUE; break;
        case "image/png": $src = imagecreatefrompng($saveto); $typeok = TRUE; break;
        default: $typok = FALSE; break;
    }
    
    if ($typeok) {
        list($w, $h) = getimagesize($saveto);
        $max = 100;
        $tw = $w;
        $th = $h;
        
        if ($w > $h && $max < $w) {
            $th = $max / $w * $h;
            $tw = $max;
        } elseif ($h > $w && $max < $h) {
            $tw = $max / $h * $w;
            $th = $max;
        } elseif ($max < $w) {
            $tw = $th = $max;
        }
        $tmp = imagecreatetruecolor($tw, $th);
        imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
        imageconvolution($tmp, array(  //Sharpen image
                               array(-1, -1, -1),
                               array(-1, 16, -1),
                               array(-1, -1, -1)
                               ), 8, 0);
        imagejpeg($tmp, $saveto);
        imagedestroy($tmp);
        imagedestroy($src);
    }
}

showProfile($user);

echo <<<_END
<form method='post' action='profile.php' enctype='multipart/form-data'>
Enter or edit your details and/or upload an image:<br />
<textarea name='text' cols='40' rows='3'>$text</textarea><br />
Image: <input type='file' name='image' size='14' maxlength='32' /><br />
<input type='submit' value='Save Profile' />
</form>
_END;
?>
