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
    $minlat = $_POST['minlat'];
    $maxlat = $_POST['maxlat'];
    $minlong = $_POST['minlong'];
    $maxlong = $_POST['maxlong'];
    $searchstring = $_POST['search'];
    $get_group_locations = $conn->prepare("SELECT *, X(location) AS xcoord, Y(location) AS ycoord FROM groupid WHERE X(location) BETWEEN :minlat AND :maxlat AND Y(location) BETWEEN :minlong AND :maxlong AND name LIKE :searchstring");
    $get_group_locations->bindParam(":minlat", $minlat);
    $get_group_locations->bindParam(":maxlat", $maxlat);
    $get_group_locations->bindParam(":minlong", $minlong);
    $get_group_locations->bindParam(":maxlong", $maxlong);
    $get_group_locations->bindParam(":searchstring", $searchstring);
    $get_group_locations->execute();
    $get_group_locations->setFetchMode(PDO::FETCH_ASSOC);
    $results = $get_group_locations->fetchAll();
    echo json_encode($results);
} catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>