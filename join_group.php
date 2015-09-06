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
    $userid = $_SESSION['id'];
    $groupid = $_POST['groupid'];
    $add_self_to_group = $conn->prepare("INSERT INTO groupmembers VALUES(:userid, :groupid)");
    $add_self_to_group->bindParam(":userid", $userid);
    $add_self_to_group->bindParam(":groupid", $groupid);
    $add_self_to_group->execute();
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>