<?php
/**
 *
 */
$pagename = "Public Page";
require_once "header.inc.php";


try {
    $sql = "SELECT * FROM tfmalamut_content ORDER BY prof";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    // print_r($row); don't use print_r, foreach declares row on the fly.
    echo "<hr>";

    echo "<table
           <tr><th>Username</th><th>Profession</th><th>Description</th><th>Date</th></tr>";
    foreach ($result as $row) {
        echo "<tr>";
        echo "<td>" . $row['username'] . "</td>";
        echo "<td>" . $row['prof'] . "</td>\n";
        echo "<td>" . $row['descr'] . "</td>\n";
        echo "<td>" . date("m/d/Y", $row['inputdate']) . "</td>\n";
        echo "</tr>\n";
    }//end foreach
    echo "</table>";
} catch (PDOException $e) {
    die($e->getMessage());
}//end try/catch













require_once "footer.inc.php";
?>