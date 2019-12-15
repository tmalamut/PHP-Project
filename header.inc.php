<?php
session_start();
/**
 * User: tfmalamut
 * Date: 10/8/2019
 */

$currentfile = basename($_SERVER['PHP_SELF']); //get current filename
$rightnow = time(); //set current time
//turn on error reporting for debugging - Page 699
error_reporting(E_ALL);
ini_set('display_errors','1'); //change this after testing is complete

//set the time zone
ini_set( 'date.timezone', 'America/New_York');
date_default_timezone_set('America/New_York');

//if username is set, declare uname variable to use on line 56
if (isset($_SESSION['uname'])) {
    $uname = $_SESSION['uname'];
}//end if


//required files
require_once "connect.inc.php";
require_once "functions.inc.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Project Management</title>
    <link rel="stylesheet" href="styles/styles.css" />
    <script src="js/html5shiv.js"></script>
    <script src="https://cloud.tinymce.com/stable/tinymce.min.js?apiKey=5o7mj88vhvtv3r2c5v5qo4htc088gcb5l913qx5wlrtjn81y"></script>
    <script>tinymce.init({ selector:'textarea' });</script>
</head>

<body>
<header class="col-sm-12">
    <h1>About Project Management</h1>



<nav>
    <?php
    echo ($currentfile == "index.php") ? "Home  " : "<a href = 'index.php'>Home</a> ";
    echo ($currentfile == "register.php") ? "Register " : "<a href ='register.php'>Register</a> ";
    //echo ($currentfile =="login.php") ? "Log in" : "<a href='login.php'>Log in</a> ";
    echo (isset($_SESSION['ID'])) ? "<a href='logout.php'>Log Out</a>" : "<a href='login.php'>Log In</a> ";
    echo ($currentfile == "public.php") ? "Public " : "<a href ='public.php'>Public</a> ";
    echo ($currentfile == "addcontent.php") ? "Add Content " : "<a href ='addcontent.php'>Add Content</a> ";
    echo ($currentfile == "manage.php") ? "Management " : "<a href ='manage.php'>Management</a> ";
    ?>
</nav>
    <?php echo (isset($_SESSION['ID'])) ? "<p class='welcome'>Welcome Back $uname</p>"  : "<p class='welcome'>Welcome. Please register or login.</p> ";?>

</header>
<h2><?php echo $pagename; ?></h2>

