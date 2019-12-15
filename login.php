<?php
/**
 * Created by PhpStorm.
 * User: tfmalamut
 * Date: 10/31/2019
 * Time: 5:27 AM
 */

$pagename = "Login";  //pagename var is used in the header
require_once "header.inc.php";
echo "Once you login, you will be able to add content, view the management page, and log out. Also accessible via navigation bar.";
//set initial variables
$showform = 1;  // show form is true
$errormsg = 0;
$erruname = "";
$errpwd = "";

if($_SERVER['REQUEST_METHOD'] == "POST")
{
    //create variables to store data from form - we never use POST directly w/ user input
    // CHANGED USERNAME TO LOWERCASE
    $uname = trim(strtolower($_POST['uname']));
    $pwd = $_POST['pwd'];

    //check for empty fields
    if (empty($uname)) {
        $erruname = "The username is required.";
        $errormsg = 1;
    }
    if (empty($pwd)) {
        $errpwd = "The password is required.";
        $errormsg = 1;
    }

    if($errormsg == 1)
    {
        echo "<p class='error'>There are errors.  Please make corrections and resubmit.</p>";
    }
    else{
        /* VERIFY THE PASSWORD */
        $sql = "SELECT * FROM tfmalamut_members WHERE uname = :uname";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':uname', $uname);
        $stmt->execute();
        $row = $stmt->fetch();
        if (password_verify($pwd, $row['pwd'])) {
            $_SESSION['ID'] = $row['ID'];
            $_SESSION['uname'] = $row['uname'];
            $showform = 0;
            header("Location: confirm.php?state=2");
        } else {
            echo "<p class='error'>The uname and password combination you entered is not correct.  Please try again.</p>";
        }
    } // else errormsg
}//submit
if($showform == 1){
    ?>
    <form name="login" id="login" method="POST" action="login.php">

    <table>
        <tr><th><label for="uname">Username:</label><span class="error">*</span></th>
            <td><input name="uname" id="uname" type="text" placeholder="Required Username"
                       value="<?php if(isset($uname))
                       {echo $uname;
                       }?>" /><span class="error"><?php if(isset($erruname)){echo $erruname;}?></span></td>
        </tr>
        <tr><th><label for="pwd">Password:</label><span class="error">*</span></th>
            <td><input name="pwd" id="pwd" type="password" placeholder="Required Password"/>
                <span class="error"><?php if(isset($errpwd)){echo $errpwd;}?></span></td>
        </tr>
        <tr><th><label for="submit">Submit: </label></th>
            <td><input type="submit" name="submit" id="submit" value="submit"/></td>
        </tr>
    </table>

    <?php
}//end showform
require_once "footer.inc.php";
?>