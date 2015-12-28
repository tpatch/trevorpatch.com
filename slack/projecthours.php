<?php

$connect = mysqli_connect('localhost', 'trevorpatch.com', 'Y=Fx64#&kzs!g=AY', 'trevorpatchcom');
if (!$connect) {
    $msg = "Script failed to connect to database";
    die($msg);
    echo $msg;
}

# Grab some of the values from the slash command, create vars for post back to Slack
$command = $_POST['command'];
$text = $_POST['text'];
$token = $_POST['token'];
$channelname = $_POST['channel_name'];
$user = $_POST['user_name'];
$time = rand(1, 12);

# Check the token and make sure the request is from our team 
if($token != '4P2J7rxDyWQ4by4OFrzNzwqe'){
  $msg = "The token for the slash command doesn't match. Check your script.";
  die($msg);
  echo $msg;
}

if ( strpos($text, 'status') !== false ) {
    $monday = strtotime('last monday', strtotime('tomorrow'));
    $sunday = strtotime('next sunday', strtotime('yesterday'));
    $sql = "SELECT SUM(hours) AS TotalHours FROM hours WHERE Project = ? AND DateAdded >= ? AND DateAdded <= ? GROUP BY Project";
    if ($stmt = mysqli_prepare($connect, $sql)) {
        $stmt->bind_param("sss", $channelname, $monday, $sunday);
        $stmt->execute();

        $res = $stmt->get_result();
        while($row = $res->fetch_array(MYSQLI_ASSOC)) {
            $totalhours = $row;
        }

        $reply = ":clock". $time .": There are currently 8 hours on the ". $totalhours ." project this week.";
    } else {
        $reply = "Sorry, your hours couldn't be saved. Try again in a few moments.";
    }

    $reply = ":clock". $time .": There are currently ". $text ." hours logged this week on this project.";
} else if ( is_int(intval(trim($text))) ) {
    $text = intval(trim($text));
    $sql = "INSERT INTO hours VALUES (NULL, ?, ?, ?, NOW())";
    if ($stmt = mysqli_prepare($connect, $sql)) {
        $stmt->bind_param("ssi", $channelname, $user, $text);
        $stmt->execute();

        $reply = ":clock". $time .": Thanks, we have you down for ". $text ." hours on ". $channelname ."!";
    } else {
        $reply = "Sorry, your hours couldn't be saved. Try again in a few moments.";
    }

    mysqli_close($connect);
} else {
    $reply = "Sorry, that's not a valid command.";
}

# Send the reply back to the user. 
echo $reply;