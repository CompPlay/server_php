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
    $postsettings = $conn->prepare("SELECT * FROM messages WHERE groupid = :groupid");
    $groupid = $_POST['groupid'];
    $postsettings->bindParam(":groupid", $groupid);
    $postsettings->execute();
    $postsettings->setFetchMode(PDO::FETCH_ASSOC);
    $results = $postsettings->fetchAll();
    echo json_encode($results);
} catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}
?>