<?php
/**
 * Created by PhpStorm.
 * User: sid
 * Date: 9/5/15
 * Time: 8:00 AM
 */
session_start();
$servername = "us-cdbr-azure-east-b.cloudapp.net";
$username = "b0e812d8bf4e3e";
$password = "124f7801";
if(!isset($_SESSION['id']) || $_SESSION['loggedin'] === false){
    header("Location: login.html");
    die();
}
try{
    $conn = new PDO("mysql:host=$servername;dbname=complaydb", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $groupid = $_GET['groupid'];
    $select_group = $conn->prepare("SELECT groupid, game, memberlimit, X(location) AS latitude, Y(location) AS longitude, name, skill, privacy, posters, expirytime, timecreated, ownerid FROM groups WHERE groupid = :groupid");
    $select_group->bindParam(":groupid", $groupid);
    $select_group->execute();
    $select_group->setFetchMode(PDO::FETCH_ASSOC);
    $results = $select_group->fetchAll();
    echo json_encode($results[0]);
} catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}
?>