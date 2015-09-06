<?php
/**
 * Created by PhpStorm.
 * User: sid
 * Date: 9/5/15
 * Time: 7:30 AM
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
    $userid = $_SESSION['id'];
    $groupid = $_GET['groupid'];
    $get_group_locations = $conn->prepare("SELECT COUNT(*) FROM groupmembers WHERE userid=:userid AND groupid=:groupid");;
    $get_group_locations->bindParam(":userid", $userid);
    $get_group_locations->bindParam(":groupid", $groupid);
    $get_group_locations->execute();
    $get_group_locations->setFetchMode(PDO::FETCH_ASSOC);
    $results = $get_group_locations->fetchAll();
    if($results[0]['COUNT(*)'] >= 1) {
        echo "true";
    } else {
        echo "false";
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>