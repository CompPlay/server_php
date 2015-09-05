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
    $groupid = $_POST['groupid'];
    $userid = $_SESSION['id'];
    $check_if_owner = $conn->prepare("SELECT ownerid FROM groups WHERE groupid=:groupid");
    $check_if_owner->bindParam(":groupid", $groupid);
    $check_if_owner->execute();
    $check_if_owner->setFetchMode(PDO::FETCH_ASSOC);
    $results = $check_if_owner->fetchAll();
    if(count($results) == 1 && $results[0]['ownerid'] === $userid){
        $delete_group = $conn->prepare("DELETE FROM groups WHERE groupid=:groupid");
        $delete_group->bindParam(":groupid", $groupid);
        $delete_group->execute();
    }
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>