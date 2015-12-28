<?php
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
    $monday = strtotime('last monday', strtotime('tomorrow'));
    $sunday = strtotime('next sunday', strtotime('yesterday'));
    $sql = "SELECT SUM(hours) AS TotalHours FROM hours WHERE Project = ? AND DateAdded >= ? AND DateAdded <= ? GROUP BY Project";

    $stmt = $connect->prepare($sql);
    $stmt->bind_param("sss", 'sanford-sitecore', $monday, $sunday);
    $stmt->execute();
    $stmt->bind_result($hoursbinding);

    while($stmt->fetch()){
        $totalhours = $hoursbinding;
    };

    $stmt->free_result();
?>