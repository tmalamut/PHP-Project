<?php
$pagename = "Confirmation Page";
require_once "header.inc.php";


if ($_GET['state']==1) { // GET variable called state is 1
    echo "You have logged out. ";
    echo "<br>";
    echo "<a href = 'login.php'>Log back in</a>";
    //echo login.php link
}//end if

/*
if ($_GET[$state]==2) { // GET variable called state is 2
    echo "Welcome" . $_SESSION['uname'];
}//end if
*/
else { // Provide message to choose an option from the menu
    echo "Choose option from menu";
    echo "<br>";
    echo "<a href = 'addcontent.php'>Add Content</a>";
    echo "<br>";
    echo "<a href = 'manage.php'>View Management Page</a>";
    echo "<br>";
    echo "<a href = 'logout.php'>Log out</a>";
}//end else

require_once "footer.inc.php";
?>