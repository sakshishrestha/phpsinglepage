<?php 

$server = "localhost";
$username = "root";
$password = "";
$database = "phpsinglepage";

//initialize db connection OOP  -- db instanciated everytime
$conn = new mysqli($server,$username,$password,$database);

// var_dump($conn);

//check if the connection is valid or not
if($conn->connect_error){
    die("connection failed" . $conn->connect_error);
}

?>