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
    $postsettings = $conn->prepare("SELECT posters, ownerid FROM groups WHERE groupid = :groupid");
    $postsettings->bindParam(":groupid", $groupid);
    $postsettings->execute();
    $postsettings->setFetchMode(PDO::FETCH_ASSOC);
    $results = $postsettings->fetchAll();
    $posters = $results[0]['posters'];
    $ownerid = $results[0]['ownerid'];
    $checkifmember = $conn->prepare("SELECT COUNT(*) FROM groupmembers WHERE groupid = :groupid AND userid = :userid");
    $checkifmember->bindParam(":groupid", $groupid);
    $checkifmember->bindParam(":userid", $userid);
    $checkifmember->execute();
    $checkifmember->setFetchMode(PDO::FETCH_ASSOC);
    $results = $checkifmember->fetchAll();
    if($posters === "owner" && $ownerid != $_SESSION['id']){
        die();
    } else if($posters === "members" && $results[0]["COUNT(*)"] === 0) {
        die();
    }
    $insert_msg = $conn->prepare("INSERT INTO messages VALUES (LEFT(UUID(), 8), :messagetext, CURRENT_TIMESTAMP(), :userid, :groupid)");
    $insert_msg->bindParam(":messagetext", $messagetext);
    $insert_msg->bindParam(":userid", $_SESSION['id']);
    $insert_msg->bindParam(":groupid", $groupid);
    $insert_msg->execute();
} catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}
?>