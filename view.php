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
//to check existance of id parameter before processing further

if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
    //prepare a select statement
    echo "here";
    $sql = "SELECT * FROM users WHERE id = ?";
    

    if($stmt = mysqli_prepare($conn, $sql)){
        //bind variables to the prepared statement as parameters
        mysqli_stmt_bind_param($stmt, "i", $param_id);
        
        //set parameters
        $param_id = trim($_GET["id"]);
        var_dump($param_id);

        //attempt to execute the prepared statement
        if(mysqli_stmt_execute($stmt)){
            $result = mysqli_stmt_get_result($stmt);

            if(mysqli_num_rows($result) == 1){
                // fetch result row as assciative array, since the result set containes only one rows , we dont need to 
                // use the while loop 
                $row = mysqli_fetch_array($result, MYSQLI_ASSOC);

                //retrieve individual field balue 
                $name = $row["name"];
                $sname = $row["sname"];
                $email = $row["email"];
                $gender = $row["gender"];
                $hobby = $row["hobby"];


            }else{
                //url doesn't contain valid id parameter. 
                echo "fetch num rows could not find";
                // header("location: error.php");
            }
        }else{
            echo "stmt execution  error";
        }

    }
    //close statemnet 
    mysqli_stmt_close($stmt);
}else{
    echo "did not get the id : error";
}


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="mt-5 mb-3">View Record</h1>
                    <div class="form-group">
                        <label>Name</label>
                        <p><b><?php echo $row["name"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Short Name</label>
                        <p><b><?php echo $row["sname"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <p><b><?php echo $row["email"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <p><b><?php echo $row["gender"]; ?></b></p>
                    </div>
                    <div class="form-group">
                        <label>Hobby</label>
                        <p><b><?php echo $row["hobby"]; ?></b></p>
                    </div>
                    <p><a href="index.php" class="btn btn-primary">Back</a></p>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>