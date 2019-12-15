<?php
session_start();
session_unset();
session_destroy();
header("Location: confirm.php?state=1");
exit();
?>