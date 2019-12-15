<?php
session_start();
$pagename = "Management Page";
require_once "header.inc.php";
// CHECK IF USER IS LOGGED IN
if(!isset($_SESSION['ID']))
{
    echo '<p>Log in to view this page. </p>';
    require_once "footer.inc.php";
    exit();
}//end if


echo "<a href = 'addcontent.php'>Add Content</a>";

try {
    $sql = "SELECT ID, prof FROM tfmalamut_content WHERE userid =" . $_SESSION['ID'] . " ORDER BY prof";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // print_r($row); don't use print_r, foreach declares row on the fly.
    echo "<hr>";

    echo "<table
           <tr><th colspan='3'>Options</th><th>ID</th><th>Profession</th></tr>";
    foreach ($result as $row) {
        echo "<tr>
                <td><a href='view.php?ID=" . $row['ID'] . "'>VIEW</a></td>
                <td><a href='delete.php?ID=" . $row['ID'] . "&P=" . $row['prof'] . "'>DELETE</a></td>
                <td><a href='update.php?ID=" . $row['ID'] . "'>UPDATE</a></td>";
        echo "<td>" . $row['ID'] . "</td>";
        echo "<td>" . $row['prof'] . "</td>\n";
        echo "</tr>\n";
    }//end foreach
    echo "</table>";
} catch (PDOException $e) {
    die($e->getMessage());
}//end try/catch

require_once "footer.inc.php";