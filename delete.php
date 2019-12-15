<?php
/**
 * Delete Page
 */
$pagename = "Delete";
include_once "header.inc.php";

//SET INITIAL VARIABLES
$showform = 1; // show form is true

if($_SERVER['REQUEST_METHOD'] == "POST") {
    try {
        //query the data
        $sql = "DELETE FROM tfmalamut_content WHERE ID = :ID";
        //prepares a statement for execution
        $stmt = $pdo->prepare($sql);
        //binds the actual value of $_GET['ID'] to
        $stmt->bindValue(':ID', $_POST['ID']); //notice this is NOT submitted
        //executes a prepared statement
        $stmt ->execute();
        //hide the form
        $showform = 0;
        //provides useful confirmation to user
        echo "<p>The " . $_POST['prof'] . " profession has been deleted. <a href='manage.php'>Return to list</a>?</p>";
    } catch (PDOException $e) {

    }//end try/catch
}//submit

//display form if Show Form Flag is true
if($showform == 1)
{
    ?>
    <p>Are you sure you want to delete <?php echo $_GET['P'];?>?</p>

    <form id="delete" name="delete" method="post" action="<?php echo $currentfile;?>">
        <input type="hidden" id="ID" name="ID" value="<?php echo $_GET['ID'];?>">
        <input type="hidden" id="prof" name="prof" value="<?php echo $_GET['P'];?>">
        <input type="submit" id="delete" name="delete" value="YES">
        <input type="button" id="nodelete" name="nodelete" value="NO" onClick="window.location='manage.php'">
    </form>


    <?php
}//end showform





include_once "footer.inc.php";
?>