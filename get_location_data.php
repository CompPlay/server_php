<?php
/**
 * Created by PhpStorm.
 * User: sid
 * Date: 9/5/15
 * Time: 7:30 AM
 */
class MapDataPoint {
    private $type;
    private $lat;
    private $long;
    private $id;

    /**
     * MapDataPoint constructor.
     * @param $type
     * @param $lat
     * @param $long
     * @param $id
     */
    public function __construct($type, $lat, $long, $id) {
        $this->type = $type;
        $this->lat = $lat;
        $this->long = $long;
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getType() {
        return $this->type;
    }

    /**
     * @return mixed
     */
    public function getLat() {
        return $this->lat;
    }

    /**
     * @return mixed
     */
    public function getLong() {
        return $this->long;
    }

    public function getId() {
        return $this->id;
    }

    public function setType($newtype){
        if($newtype === "group" || $newtype === "user"){
            $this->type = $newtype;
        }
    }

}
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
    $get_locations = $conn->prepare("SELECT userid, X(location) AS xcoord, Y(location) AS ycoord FROM users WHERE X(location) BETWEEN :minlat AND :maxlat AND Y(location) BETWEEN :minlong AND :maxlong");
    $get_locations->bindParam(":minlat", $minlat);
    $get_locations->bindParam(":maxlat", $maxlat);
    $get_locations->bindParam(":minlong", $minlong);
    $get_locations->bindParam(":maxlong", $maxlong);
    $get_locations->execute();
    $get_locations->setFetchMode(PDO::FETCH_ASSOC);
    $results = $get_locations->fetchAll();
    $mappoints = [];
    foreach($results as $row){
        $mappoints[] = new MapDataPoint("user", $row['xcoord'], $row['ycoord'], $row['userid']);
    }
    $get_group_locations = $conn->prepare("SELECT groupid, X(location) AS xcoord, Y(location) AS ycoord FROM groupid WHERE X(location) BETWEEN :minlat AND :maxlat AND Y(location) BETWEEN :minlong AND :maxlong");
    $get_group_locations->bindParam(":minlat", $minlat);
    $get_group_locations->bindParam(":maxlat", $maxlat);
    $get_group_locations->bindParam(":minlong", $minlong);
    $get_group_locations->bindParam(":maxlong", $maxlong);
    $get_group_locations->execute();
    $get_group_locations->setFetchMode(PDO::FETCH_ASSOC);
    $results = $get_group_locations->fetchAll();
    foreach($results as $row){
        $mappoints[] = new MapDataPoint("group", $row['xcoord'], $row['ycoord'], $row['userid']);
    }
    echo json_encode($mappoints);
} catch(PDOException $e){
    echo "Connection failed: " . $e->getMessage();
}
?>