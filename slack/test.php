<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    $connect = new mysqli('localhost', 'trevorpatch.com', 'Y=Fx64#&kzs!g=AY', 'trevorpatchcom');
    if($connect->connect_errno > 0){
        $msg = "Script failed to connect to database";
        die($msg);
        echo $msg;
    }

    $monday = date('Y-m-d', strtotime('last monday', strtotime('tomorrow')));
    $sunday = date('Y-m-d', strtotime('next sunday', strtotime('yesterday')));
    $site = 'sanford-sitecore';
    $sql = "SELECT SUM(hours) AS TotalHours FROM hours WHERE Project = ? AND DateAdded >= ? AND DateAdded <= ? GROUP BY Project";

    $stmt = $connect->stmt_init();
    if ($stmt->prepare($sql)) {
        $stmt->bind_param("sss", $site, $monday, $sunday);
        $stmt->execute();
        $stmt->bind_result($hoursbinding);

        //var_dump($stmt);

        //$stmt->fetch();
        while($stmt->fetch()) {
            $totalhours = $hoursbinding;
        }

        var_dump($totalhours);

        $stmt->close();
    }

    $connect->close();
?>