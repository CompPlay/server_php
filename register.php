<?php
/**
 * Created by PhpStorm.
 * User: sid
 * Date: 9/5/15
 * Time: 2:26 AM
 */
session_start();
$servername = "us-cdbr-azure-east-b.cloudapp.net";
$username = "b0e812d8bf4e3e";
$password = "124f7801";
try {
    $conn = new PDO("mysql:host=$servername;dbname=complaydb", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $login_username = $_POST['username'];
    $login_password = $_POST['password'];
    $login_name = $_POST['name'];
    $login_email = $_POST['email'];
    $login_phone = $_POST['phone'];
    $register_user = $conn->prepare("SELECT * FROM users WHERE username=:username OR email=:email");
    $register_user->bindParam(":username", $login_username);
    $register_user->bindParam(":email", $login_email);
    $register_user->execute();
    $results = $register_user->setFetchMode(PDO::FETCH_ASSOC);
    $results = $register_user->fetchAll();
    if(count($results) === 0) {
        $add_user = $conn->prepare("INSERT INTO users VALUES (LEFT(UUID(), 10), :username, :passwd, CURRENT_TIMESTAMP(), :realname, NULL, :email, :phone, NULL)");
        $add_user->bindParam(":username", $login_username);
        $add_user->bindParam(":passwd", password_hash($login_password, PASSWORD_DEFAULT));
        $add_user->bindParam(":realname", $login_name);
        $add_user->bindParam(":email", $login_email);
        $add_user->bindParam(":phone", $login_phone);
        $add_user->execute();
        echo "inserted";
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $login_username;
        $_SESSION['name'] = $login_name;
        $get_id = $conn->prepare("SELECT userid FROM users WHERE username=:username");
        $get_id->bindParam(":username", $login_username);
        $get_id->execute();
        $get_id->setFetchMode(PDO::FETCH_ASSOC);
        $id = $get_id->fetchAll();
        if(count($id) == 1){
            $_SESSION['id'] = $id[0]['userid'];
        }
        header("Location: map.html");
        die();
    } else {
        header("Location: error.html");
        die();
    }
} catch(PDOException $e) {
    header("Location: error.html");
    die();
}
?>