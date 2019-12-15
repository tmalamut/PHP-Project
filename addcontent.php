<?php
session_start();
/**
 * Page for logged in users to add content to tfmalamut_content table.
 * Content: ID,  Inputdate, name, profession (instead of category), description
 */

$pagename = "Add Content";
require_once "header.inc.php";

// CHECK IF USER IS LOGGED IN
if(!isset($_SESSION['ID']))
{
    echo '<p>Log in to view this page. </p>';
    require_once "footer.inc.php";
    exit();
}//end if
// SET INITIAL VARIABLES

$showform = 1;
$errmsg = 0;
$errusername = "";
$errprof = "";
$errdescr = "";
$userid = $_SESSION['ID'];



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
    $username = trim(strtolower($_POST['username']));
    $prof = trim(strtolower($_POST['prof']));
    $descr = trim($_POST['descr']);






    /* ***********************************************************************
     * CHECK EMPTY FIELDS
     * Check for empty data for every REQUIRED  field
     * Do not do for things like apartment number, middle initial, etc.
     * CAUTION:  Radio buttons with 0 as a value = use isset() not empty()
     *    see https://www.htmlcenter.com/blog/empty-and-isset-in-php/
     * NOTE:  For any error, we set the $errmsg variable to TRUE to display message.
     * ***********************************************************************
     */

    if (empty($username)) {
        $errusername = "<span class='error'>The name is required.</span>";
        $errmsg = 1;
    }//end if


    if (empty($prof)) {
        $errprof = "<span class='error'>The profession is required.</span>";
        $errmsg = 1;
    }//end if


    if (empty($descr)) {
        $errdescr = "<span class='error'>The description is required.</span>";
        $errmsg = 1;
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
    /*
     * DON'T NEED FOR ADDING CONTENT.
    $sql = "SELECT * FROM tfmalamut_content WHERE uname=?";

    $count = checkDup($pdo, $sql, $name);
    if($count > 0) {
        $errmsg = 1;
        $erruname = "The username is taken";
    }//end if
*/
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


        /* ***********************************************************************
         * INSERT INTO THE DATABASE
         * NOT ALL data comes from the form - Watch for this!
         *    For example, input dates are not entered from the form
         * ***********************************************************************
         */
        try {
            $sql = "INSERT INTO tfmalamut_content (inputdate, username, prof, descr, userid) VALUES (:inputdate, :username, :prof, :descr, :userid)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':inputdate', $rightnow);
            $stmt->bindValue(':username', $username);
            $stmt->bindValue(':prof', $prof);
            $stmt->bindValue('descr', $descr);
            $stmt->bindValue(':userid', $userid);
            $stmt->execute();
            $showform = 0; // hide the form
            echo "<p class='success'>Thanks for adding your content.</p>";
        }//end try
        catch (PDOException $e) {
            die($e->getMessage() );
        }//end catch

    } // else errormsg
}//submit
//display form if Show Form Flag is true
if($showform == 1){
    ?>

    <form name="addcontent" id="addcontent" action="<?php echo $currentfile;?>" method="post">

        <?php if(isset($errusername) ){echo $errusername;} ?><br>
        <label for="username">Name</label><br>
        <input type="text" name="username" id="username" placeholder="Name" maxlength="40" size="50" value="<?php if(isset($username)) {echo $username; }?>">
        <br>

        <?php if(isset($errprof)) {echo $errprof;} ?><br>
        <label for="prof">Profession</label><br>
        <input type="text" name="prof" id="prof" placeholder="prof" maxlength="40" size="50" value="<?php if(isset($prof)) {echo $prof; }?>">
        <br>


        <?php if(isset($errdescr)) {echo $errdescr;}?>
        <label for="descr">Description</label><br>
        <textarea name="descr" id="descr" placeholder="Tell us about you (required)"><?php if(isset($descr)) {echo $descr;}?></textarea>
        <br>

        <label for="submit">Submit</label>
        <input type="submit" name="submit" id="submit">
    </form>



    <?php
}//end showform
require_once "footer.inc.php";
?>
