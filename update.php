<?php
session_start();
/**
 * Page for logged in users to add content to tfmalamut_content table.
 * Content: ID,  Inputdate, name, profession (instead of category), description
 */

$pagename = "Update Content";
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
$errprof = "";
$errdescr = "";
$userid = $_SESSION['ID'];

if($_SERVER['REQUEST_METHOD'] == "GET" && isset($_GET['ID'])) {
    $id = $_GET['ID'];
}//end if

elseif($_SERVER['REQUEST_METHOD'] == "POST" && isset($_POST['ID'])) {
    $id = $_POST['ID'];
}//end elseif
else {
    echo "<p class='error'>Something happened! Cannot obtain the correct entry. </p>";
    $errmsg = 1;
}//end else


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
    //$username = trim(strtolower($_POST['username']));
    $prof = trim(strtolower($_POST['prof']));
    $descr = trim($_POST['descr']);

    //Use $formdata instead to sanitize user date
    $formdata['prof'] = trim(strtolower($_POST['prof']));
    $formdata['descr'] = trim($_POST['descr']);





    /* ***********************************************************************
     * CHECK EMPTY FIELDS
     * Check for empty data for every REQUIRED  field
     * Do not do for things like apartment number, middle initial, etc.
     * CAUTION:  Radio buttons with 0 as a value = use isset() not empty()
     *    see https://www.htmlcenter.com/blog/empty-and-isset-in-php/
     * NOTE:  For any error, we set the $errmsg variable to TRUE to display message.
     * ***********************************************************************
     */

    if (empty($formdata['prof'])) {
        $errprof = "<span class='error'>The profession is required.</span>";
        $errmsg = 1;
    }//end if


    if (empty($formdata['descr'])) {
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
           // $sql = "INSERT INTO tfmalamut_content (inputdate, username, prof, descr, userid) VALUES (:inputdate, :username, :prof, :descr, :userid)";
            $sql = "UPDATE tfmalamut_content SET prof = :prof, descr = :descr WHERE ID = :ID";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':prof', $formdata['prof']);
            $stmt->bindValue('descr', $formdata['descr']);
            $stmt->bindValue(':ID', $id);
            $stmt->execute();
            $showform = 0; // hide the form
            echo "<p class='success'>Thanks for updating your content.</p>";
        }//end try
        catch (PDOException $e) {
            die($e->getMessage() );
        }//end catch

    } // else errormsg
}//submit

//display form if Show Form Flag is true
if($showform == 1){
    //Collect original data to populate form
    $sql = "SELECT * FROM tfmalamut_content WHERE ID = :ID";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':ID', $id);
    $stmt->execute();
    $row = $stmt->fetch();
    ?>

    <form name="update" id="update" action="update.php" method="post">
        <table>
            <tr><th><label for="prof">Profession: </label></th>
                <td><input name="prof"
                           id="prof"
                           type="text"
                           maxlength = "40"
                           placeholder="Profession"
                           value="<?php
                                if(isset($formdata['prof']) && !empty($formdata['prof'])) {
                                    echo $formdata['prof'];
                                }//end if
                                else {
                                    echo $row['prof'];
                                }//end else
                           ?>"
                    />
                 <span class="error">*<?php if(isset($errprof)) {echo $errprof;} ?></span></td>
            </tr>
            <tr><th><label for="descr">Description: </label></th>
                <td><textarea name="descr"
                           id="descr"
                           placeholder="Description"
                           <?php
                           if(isset($formdata['descr']) && !empty($formdata['descr'])) {
                               echo $formdata['descr'];
                           }//end if
                           else {
                               echo $row['descr'];
                           }//end else
                           ?>
                    </textarea></td>
            </tr>


            <tr><th><label for="submit">Submit: </label></th>
                <td><input type="hidden" id="ID" name="ID" value="<?php echo $row['ID']; ?>" />
                    <input type="submit" name="submit" id="submit" value="submit"/></td>
            </tr>
        </table>
    </form>
    <?php
}//end showform
require_once "footer.inc.php";
?>