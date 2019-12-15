<?php
/**
 *
 */
$pagename = "View";
require_once "header.inc.php";

try {
    //query the data
    $sql = "SELECT * FROM tfmalamut_content WHERE ID = :ID";
    //prepares a statement for execution
    $stmt = $pdo->prepare($sql);
    //binds the actual value of $_GET['ID'] to
    $stmt->bindValue(':ID', $_GET['ID']);
    //executes a prepared statement
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    //display to the screen
    echo "<table>
           <tr><th>ID</th><td>{$row['ID']}</td></tr>
           <tr><th>Profession</th><td>{$row['prof']}</td></tr>
           <tr><th>Description</th><td>{$row['descr']}</td></tr>
           </table>";


} catch (PDOException $e) {
    die ($e->getMessage());
}//end try/catch

require_once "footer.inc.php";
?>