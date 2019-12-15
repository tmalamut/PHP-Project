<?php
function checkDup($pdo, $sql, $userentry) {
    try {
        $stmt = $pdo ->prepare($sql);
        $stmt->bindValue(1, $userentry);
        $stmt->execute();
        return $stmt->rowCount();
    } catch (PDOException $e) {
        echo "Error checking duplicate entries" . $e->getMessage();
    }//end try/catch
}//end checkDup

?>