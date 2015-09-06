<?php
/**
 * Created by PhpStorm.
 * User: sid
 * Date: 9/5/15
 * Time: 2:40 AM
 */
session_start();
$servername = "us-cdbr-azure-east-b.cloudapp.net";
$username = "b0e812d8bf4e3e";
$password = "124f7801";
if(!isset($_SESSION['id']) || $_SESSION['loggedin'] === false){
    header("Location: login.html");
    die();
}
try {
    $conn = new PDO("mysql:host=$servername;dbname=complaydb", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $game = $_POST['game'];
    //$memberlimit = $_POST['memberlimit'];
    $memberlimit = 0;
    $locx = $_POST['locx'];
    $locy = $_POST['locy'];
    $name = $_POST['name'];
    $skill = $_POST['skill'];
    $skill--;
    //$privacy = $_POST['privacy'];
    $privacy = "open";
    $posters = $_POST['posters'];
    $timetoexpire = $_POST['timetoexpire'];
    $timetoexpire = $timetoexpire * 3600;
    $ownerid = $_SESSION['id'];
    $insert_group = $conn->prepare("INSERT INTO groups VALUES (LEFT(UUID(), 10), :game, :memberlimit, POINT(:locx, :locy), :groupname, :skill, :privacy, :posters, FROM_UNIXTIME(UNIX_TIMESTAMP(CURRENT_TIMESTAMP()) + :timetoexpire), CURRENT_TIMESTAMP(), :ownerid)");
    $insert_group->bindParam(":game", $game);
    $insert_group->bindParam(":memberlimit", $memberlimit);
    $insert_group->bindParam(":locx", $locx);
    $insert_group->bindParam(":locy", $locy);
    $insert_group->bindParam(":groupname", $name);
    $insert_group->bindParam(":skill", $skill);
    $insert_group->bindParam(":privacy", $privacy);
    $insert_group->bindParam(":posters", $posters);
    $insert_group->bindParam(":timetoexpire", $timetoexpire);
    $insert_group->bindParam(":ownerid", $_SESSION['id']);
    $insert_group->execute();
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>