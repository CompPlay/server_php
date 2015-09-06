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
    $userid = $_GET['userid'];
    $select_user = $conn->prepare("SELECT userid, username, timecreated, name, bio, email, phone, X(location) AS latitude, Y(location) AS longitude FROM users WHERE userid=:userid");
    $select_user->bindParam(":userid", $userid);
    $select_user->execute();
    $select_user->setFetchMode(PDO::FETCH_ASSOC);
    $results = $select_user->fetchAll();
    echo json_encode($results[0]);
} catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}
?>