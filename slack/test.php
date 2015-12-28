<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $connect = new mysqli('localhost', 'trevorpatch.com', 'Y=Fx64#&kzs!g=AY', 'trevorpatchcom');
    if($connect->connect_errno > 0){
        $msg = "Script failed to connect to database";
        die($msg);
        echo $msg;
    }

    $monday = strtotime('last monday', strtotime('tomorrow'));
    $sunday = strtotime('next sunday', strtotime('yesterday'));
    $site = 'sanford-sitecore';
    $sql = "SELECT SUM(hours) AS TotalHours FROM hours WHERE Project = ? AND DateAdded >= ? AND DateAdded <= ? GROUP BY Project";

    $stmt = $connect->stmt_init();
    if ($stmt->prepare($sql)) {
        $stmt->bind_param("sss", $site, $monday, $sunday);
        $stmt->execute();
        $stmt->bind_result($hoursbinding);

        while($stmt->fetch()) {
            printf("%d\n", $hoursbinding);
        }

        $stmt->close();
    }

    $connect->close();
?>