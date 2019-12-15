<?php


$pagename = "Registration Form";
require_once "header.inc.php";
// SET INITIAL VARIABLES

$showform = 1;
$errmsg = 0;
$errfname = "";
$errlname = "";
$erruname = "";
$erremail = "";
$errpwd = "";
$errpwd2 = "";
$errbio = "";



if($_SERVER['REQUEST_METHOD'] == "POST")
{
    /* ***********************************************************************
     * SANITIZE USER DATA
     * Use for ALL fields where the data is typed in - not for select or radio, etc
     * Use strtolower()  for emails, usernames and other case-sensitive info
     * Use trim() for ALL user-typed data -- even those not required EXCEPT pwd
     * CAUTION:  Radio buttons are a bit different.
     *    see https://www.htmlcenter.com/blog/empty-and-isset-in-php/
     * ***********************************************************************
     */
    $uname = trim(strtolower($_POST['uname']));
    $fname = trim(strtolower($_POST['fname']));
    $lname = trim(strtolower($_POST['lname']));
    $email = trim(strtolower($_POST['email']));
    $pwd = $_POST['pwd'];
    $pwd2 = $_POST['pwd2'];
    $bio = trim($_POST['bio']);






    /* ***********************************************************************
     * CHECK EMPTY FIELDS
     * Check for empty data for every REQUIRED  field
     * Do not do for things like apartment number, middle initial, etc.
     * CAUTION:  Radio buttons with 0 as a value = use isset() not empty()
     *    see https://www.htmlcenter.com/blog/empty-and-isset-in-php/
     * NOTE:  For any error, we set the $errmsg variable to TRUE to display message.
     * ***********************************************************************
     */

    if (empty($uname)) {
        $erruname = "<span class='error'>The username is required.</span>";
        $errmsg = 1;
    }//end if

    if (empty($fname)) {
        $errfname = "<span class='error'>The first name is required.</span>";
        $errmsg = 1;
    }//end if

    if (empty($lname)) {
        $errlname = "<span class='error'>The last name is required.</span>";
        $errmsg = 1;
    }//end if

    if (empty($email)) {
        $erremail = "<span class='error'>The email is required.</span>";
        $errmsg = 1;
    }//end if

    if (empty($pwd)) {
        $errpwd = "<span class='error'>The password is required.</span>";
        $errmsg = 1;
    }//end if

    if (empty($pwd2)) {
        $errpwd2 = "<span class='error'>The password confirmation is required.</span>";
        $errmsg = 1;
    }//end if

    if (empty($bio)) {
        $errbio = "<span class='error'>The bio is required.</span>";
        $errmsg = 1;
    }//end if





    /* ***********************************************************************
     * CHECK MATCHING FIELDS
     * Check to see if important fields match
     * Usually used for passwords and sometimes emails.  We'll do passwords.
     * ***********************************************************************
     */

    if ($pwd != $pwd2) {
        $errmsg = 1;
        $errpwd2 = "<span class = 'error'>The passwords do not match.</span>";
    }//end if

    /* ***********************************************************************
     * CHECK EXISTING DATA
     * Check data to avoid duplicates
     * Usually used with emails and usernames - We'll do usernames
     * ***********************************************************************
    */

    /* THIS IS THE CODE THAT WAS REPLACED WITH THE checkDup() FUNCTION
   try {
       $sql = "SELECT * FROM examples WHERE uname = :uname";
       $stmt = $pdo->prepare($sql);
       $stmt->bindValue(':uname', $uname);
       $stmt->execute();
       $countuname = $stmt->rowCount();
       if ($countuname > 0 ) {
           $errmsg = 1;
           $erruname = "<span class='error'>Username is taken.</span>";
       }//end if
   } catch (PDOException $e) {

   }//end try/catch
*/
    $sql = "SELECT * FROM tfmalamut_members WHERE uname=?";

    $count = checkDup($pdo, $sql, $uname);
    if($count > 0) {
        $errmsg = 1;
        $erruname = "The username is taken";
    }//end if

    /* *************************************************************
     * CONTROL CODE
     * This section is used to control whether we enter the block of code to
     * insert the data into the database or not. If not, display
     * the errors.
     * *************************************************************
     */

    if($errmsg == 1){
        echo "<p class='error'>There are errors.  Please make corrections and resubmit.</p>";
    }//end if
    else{
        echo "<p>GREAT! There are no errors!</p>";

        /* ***********************************************************************
         * HASH SENSITIVE DATA
         * Used for passwords and other sensitive data
         * If checked for matching fields, do NOT hash and insert both to the DB
         * ***********************************************************************
         */

        $hashedpwd = password_hash($pwd, PASSWORD_BCRYPT);

        /* ***********************************************************************
         * INSERT INTO THE DATABASE
         * NOT ALL data comes from the form - Watch for this!
         *    For example, input dates are not entered from the form
         * ***********************************************************************
         */
        try {
            $sql = "INSERT INTO tfmalamut_members (fname, lname, uname, email, pwd, bio) VALUES (:fname, :lname, :uname, :email, :pwd, :bio)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':fname', $fname);
            $stmt->bindValue(':lname', $lname);
            $stmt->bindValue(':uname', $uname);
            $stmt->bindValue(':email', $email);
            $stmt->bindValue(':pwd', $hashedpwd);
            $stmt->bindValue(':bio', $bio);
            $stmt->execute();
            $showform = 0; // hide the form
            echo "<p class='success'>Thanks for entering your information.</p>";
        }//end try
        catch (PDOException $e) {
            die($e->getMessage() );
        }//end catch

    } // else errormsg
}//submit
//display form if Show Form Flag is true
if($showform == 1){
    ?>

    <form name="register" id="register" action="<?php echo $currentfile;?>" method="post">

        <?php if(isset($errfname) ){echo $errfname;} ?><br>
        <label for="uname">First Name</label><br>
        <input type="text" name="fname" id="fname" placeholder="First Name" maxlength="40" size="50" value="<?php if(isset($fname)) {echo $fname; }?>">
        <br>

        <?php if(isset($errlname)) {echo $errlname;} ?><br>
        <label for="lname">Last Name</label><br>
        <input type="text" name="lname" id="lname" placeholder="Last Name" maxlength="40" size="50" value="<?php if(isset($lname)) {echo $lname; }?>">
        <br>


        <?php if(isset($erruname)) {echo $erruname;} ?><br>
        <label for="uname">Required Username</label><br>
        <input type="text" name="uname" id="uname" placeholder="Username" maxlength="40" size="50" value="<?php if(isset($uname)) {echo $uname; }?>">
        <br>

        <?php if(isset($erremail)) {echo $erremail;} ?><br>
        <label for="email">E-Mail</label><br>
        <input type="text" name="email" id="email" placeholder="E-Mail" maxlength="50" size="50" value="<?php if(isset($email)) {echo $email;} ?>">
        <br>

        <?php if(isset($errpwd)) {echo $errpwd;} ?><br>
        <label for="pwd">Required Password</label>
        <input type="password" name="pwd" id="pwd" placeholder="Password" size="100">
        <br>

        <?php if(isset($errpwd2)) {echo $errpwd2;}?><br>
        <label for="pwd2">Required Confirmation Password</label>
        <input type="password" name="pwd2" id="pwd2" placeholder="Confirmation Password" size="100">
        <br>

        <?php if(isset($errbio)) {echo $errbio;}?>
        <label for="bio">Skills Description</label><br>
        <textarea name="bio" id="bio" placeholder="Tell us about you (required)"><?php if(isset($bio)) {echo $bio;}?></textarea>
        <br>

        <label for="submit">Submit</label>
        <input type="submit" name="submit" id="submit">
    </form>



    <?php
}//end showform
require_once "footer.inc.php";
?>
