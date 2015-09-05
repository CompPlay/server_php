<?php
/**
 * Created by PhpStorm.
 * User: sid
 * Date: 9/5/15
 * Time: 6:52 AM
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
    $messagetext = $_POST['messagetext'];
    $groupid = $_POST['groupid'];
    $insert_msg = $conn->prepare("INSERT INTO messages VALUES (LEFT(UUID(), 8), :messagetext, CURRENT_TIMESTAMP(), :userid, :groupid)");
    $insert_msg->bindParam(":messagetext", $messagetext);
    $insert_msg->bindParam(":userid", $_SESSION['id']);
    $insert_msg->bindParam(":groupid", $groupid);
    $insert_msg->execute();
} catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}
?>