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



    //to initialize and define variables  with empty value
    $name = $sname = $email = $gender = $hobby = "";
    $name_err = $sname_err = $email_err = $gender_err = $hobby_err = "";

    //processing form data when submitted 

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        //validate name
        $input_name = trim($_POST["name"]);
        // var_dump($input_name);
        if(empty($input_name)){
            $name_err = "Please enter a name.";
        }elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
            $name_err = "Please enter a valid name.";
        }else{
            $name = $input_name;
        }

        //validate sname
        $input_sname = trim($_POST["sname"]);
        if(empty($input_sname)){
            $sname_err = "Please enter a sname.";
        }elseif(!filter_var($input_sname, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
            $sname_err = "Please enter a valid short name.";
        }else{
            $sname = $input_sname;
        }

        //validate email 
        $input_email = trim($_POST["email"]);
        if(empty($input_email)){
            $email_err = "Please enter an email address.";
        }elseif(!filter_var($input_email, FILTER_VALIDATE_EMAIL)){  
            $email_err = "Please enter a valid email address.";
        }else{
            $email = $input_email;
        }

        //validate gender
        $input_gender = trim($_POST["gender"]);
        if(isset($_REQUEST['gender']) && $_REQUEST['gender'] == '0'){
            $gender_err = "Please select gender";
        }else{
            $gender = $input_gender;
        }

        //validate hobby 
        // $input_hobby = trim($_POST["hobby"]);
        // if(isset($_REQUEST['hobby']) && $_REQUEST['hobby'] == '0'){
        //     $hobby_err ="Please check any hobby";
        // }else{
        //     $hobby = $input_hobby;
        // }

        // $input_hobby = trim($_POST["hobby"]);
        
        // if(isset($_POST['hobby[]']) == '0'){
        //     echo "here";
        //     $hobby_err = "Please check any hobby";

        // }else{
        //     $hobby = $checkbox;
        // }
        $checkbox = $_POST['hobby'];
        $chk = "";
        foreach($checkbox as $chk1){
            $chk .= $chk1.",";
        }
        
        
        // if(!empty($_POST['hobby'])){
        //     foreach($_POST['hobby'] as $selected){
        //         $hobby = $selected;
        //         var_dump($hobby);
        //     }
        // }
        // $input_hobby = trim($_POST['hobby']);
        // if(isset($_POST['hobby']) && $_REQUEST['hobby'] == '0'){
        //     $hobby_err ="Please check any hobby";
        // }else{
        //     $hobby = $input_hobby;
        // }

        // var_dump($name);
        // var_dump($sname);

        // var_dump($email);
        // var_dump($gender);
        // var_dump($hobby);

        
        //check input error before inserting it into the database
        if(empty($name_err) && empty($sname_err) && empty($email_err) && empty($gender_err) && empty($hobby_err) && $_POST['update'] !='update' && $_POST['update'] !='delete'){
            // var_dump('$name_err');
            //prepare and insert statement 

            // echo "herr";
            $sql = "INSERT INTO users (name, sname, email, gender, hobby) VALUES (?,?,?,?,?)";

            // var_dump($sql);

            if($stmt = mysqli_prepare($conn, $sql)){
                //bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "sssss" , $param_name, $param_sname, $param_email, $param_gender, $param_hobby);

                //set parameters
                $param_name = $name;
                $param_sname = $sname;
                $param_email = $email;
                $param_gender = $gender;
                $param_hobby = $chk;

                // var_dump($param_hobby);


                //attempt to execute the prepared statment

                if(mysqli_stmt_execute($stmt)){
                    //records created successfuly, redirect to the same page
                    header("location: index.php");
                    echo "Records has been submitted";
                    exit();
                }else{
                    echo "Something went wrong.";
                }
            }
            //close statement
            mysqli_stmt_close($stmt);
        }

        //close connection
        // mysqli_close($conn);

    }


    //to update the record
    
    //check input errors before inserting in database 
    
    // $id = 0;
            

    $edit_state =  strpos($_SERVER['REQUEST_URI'],'id')?true:false;     
        //    var_dump($_POST);
   

    if(isset($_POST["id"]) && !empty($_POST["id"])){

            $id = $_POST["id"];

    
    // if(empty($name_err) && empty($sname_err) && empty($email_err) && empty($gender_err) && empty($hobby_err)){
        if(($_POST['update'] =='update')){
            // var_dump("hello");
            // var_dump("i am inside");
        //prepare an update statement

        $sql = "UPDATE users SET name=?, sname=?, email=?, gender=?, hobby=? WHERE id=?";

        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "sssssi", $param_name, $param_sname, $param_email, $param_gender, $param_hobby, $param_id);
            
            // Set parameters
            $param_name = $name;
            $param_sname = $sname;
            $param_email = $email;
            $param_gender = $gender;
            $param_hobby = $hobby;
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Records updated successfully. Redirect to landing page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        mysqli_stmt_close($stmt);
    }
    elseif((isset($_POST['update']) && $_POST['update'] =='delete')){
        // elseif(3==4){
            // var_dump("aaaa");
        
            // Prepare a delete statement
            $sql = "DELETE FROM users WHERE id = ?";
            
            if($stmt = mysqli_prepare($conn, $sql)){
                // Bind variables to the prepared statement as parameters
                mysqli_stmt_bind_param($stmt, "i", $param_id);
                
                // Set parameters
                $param_id = trim($_POST["id"]);
                
                // Attempt to execute the prepared statement
                if(mysqli_stmt_execute($stmt)){
                    // Records deleted successfully. Redirect to landing page
                    header("location: index.php");
                    exit();
                } else{
                    echo "Oops! Something went wrong. Please try again later.";
                }
            }
             
            // Close statement
            mysqli_stmt_close($stmt);
        }
    
    // Close connection
    // mysqli_close($conn);
}
//delete record
// Process delete operation after confirmation
// if(isset($_POST["id"]) && !empty($_POST["id"])){
  
else{
    // var_dump("in else");
    // Check existence of id parameter before processing further
    if(isset($_GET["id"]) && !empty(trim($_GET["id"]))){
        // Get URL parameter
        $id =  trim($_GET["id"]);
        
        // Prepare a select statement
        $sql = "SELECT * FROM users WHERE id = ?";
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "i", $param_id);
            
            // Set parameters
            $param_id = $id;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                $result = mysqli_stmt_get_result($stmt);
    
                if(mysqli_num_rows($result) == 1){
                    /* Fetch result row as an associative array. Since the result set
                    contains only one row, we don't need to use while loop */
                    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    
                    // Retrieve individual field value
                    $name = $row["name"];
                    $sname = $row["sname"];
                    $email = $row["email"];
                    $gender = $row["gender"];
                    $hobby = $row["hobby"];

                    // var_dump($row);
                }
            }
            //     } else{
                    
            //         // URL doesn't contain valid id. Redirect to error page
            //         header("location: error.php");
            //         exit();
            //     }
                
            // } else{
            //     echo "Oops! Something went wrong. Please try again later.";
            // }
        }
        
        // Close statement
        mysqli_stmt_close($stmt);
        
        // Close connection
        // mysqli_close($conn);
    }
    // }  else{
    //     // URL doesn't contain id parameter. Redirect to error page
    //     header("location: error.php");
    //     exit();
    // }
}


    // Close connection
    // mysqli_close($conn);
// } else{
//     // Check existence of id parameter
//     if(empty(trim($_GET["id"]))){
//         // URL doesn't contain id parameter. Redirect to error page
//         header("location: error.php");
//         exit();
//     }
// }

?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <title>
        Single Page CRUD
    </title>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <form action="index.php" method="post">
                    <input type="hidden" name="id" value="<?php echo $id; ?>">
                    <label for="name">Name:</label><br>
                    <input type="text" name="name" class="form-control<?php echo(!empty($name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $name; ?>">
                    <span class="invalid-feedback"><?php echo $name_err; ?></span><br>

                    <label for="sname">Short Name:</label><br>
                    <input type="text" name="sname" class="form-control<?php echo(!empty($sname_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $sname; ?>">
                    <span class="invalid-feedback"><?php echo $sname_err; ?></span><br>

                    <label for="email">Email:</label><br>
                    <input type="email" name="email" class="form-control<?php echo(!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>" >
                    <span class="invalid-feedback"><?php echo $email_err; ?></span><br>

                    <label for="gender">Gender:</label>
                        <select id="gender" name="gender" class="form-control<?php echo(!empty($gender_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $gender; ?>">
                        <span class="invalid-feedback"><?php echo $gender_err; ?></span>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Others">Others</option>
                        </select><br>

                    <label for="hobby">Hobbies:

                    <label class="container">Sing
                    <input type="checkbox" name="hobby[]" checked="checked" class="form-control<?php echo(!empty($hobby_err)) ? 'is-invalid' : ''; ?>" value="sing">
                        <span class="invalid-feedback"><?php echo $hobby_err; ?></span>
                    <span class="checkmark"></span>
                    </label><br>

                    <label class="container">Dance
                    <input type="checkbox" name="hobby[]" checked="checked" class="form-control<?php echo(!empty($hobby_err)) ? 'is-invalid' : ''; ?>" value="dance">
                        <span class="invalid-feedback"><?php echo $hobby_err; ?></span>
                    <span class="checkmark"></span>
                    </label><br>

                    <label class="container">Sketch
                    <input type="checkbox" name="hobby[]" checked="checked" class="form-control<?php echo(!empty($hobby_err)) ? 'is-invalid' : ''; ?>" value="sketch">
                        <span class="invalid-feedback"><?php echo $hobby_err; ?></span>
                    <span class="checkmark"></span>
                    </label><br>

                    <label class="container">Play
                    <input type="checkbox" name="hobby[]" checked="checked" class="form-control<?php echo(!empty($hobby_err)) ? 'is-invalid' : ''; ?>" value="play">
                        <span class="invalid-feedback"><?php echo $hobby_err; ?></span>
                    <span class="checkmark"></span>
                    </label><br>

                    </label>
                    <!-- <button type="submit" name="submit">Submit</button> -->
                    <?php if($edit_state == false): ?>
                    <button type="submit" name="submit">Submit</button>
                    <?php else: ?>
                    <button type="submit" name="update" value="update">Update</button>
                    <button type="submit" name="update" value="delete" >Delete</button>
                    <?php endif ?>
                    
                </form>
            </div>
            <div class="col-md-6">
                <div class="wrapper">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="mt-5 mb-3 clearfix">
                                    <!-- <h5 class="pull-left">Employees Details</h5> -->
                                    <!-- <a href="index.php" class="btn btn-success pull-right"><i class="fa fa-plus"></i> Add New Employee</a> -->
                                </div>
                                <?php
                                
                                // Attempt select query execution
                                $sql = "SELECT * FROM users";
                                if($result = mysqli_query($conn, $sql)){
                                    if(mysqli_num_rows($result) > 0){
                                        echo '<table class="table table-bordered table-striped">';
                                            echo "<thead>";
                                                echo "<tr>";
                                                    echo "<th>#</th>";
                                                    echo "<th>Name</th>";
                                                    echo "<th>ShortName</th>";
                                                    echo "<th>Email</th>";
                                                    echo "<th>Action</th>";
                                                echo "</tr>";
                                            echo "</thead>";
                                            echo "<tbody>";
                                            while($row = mysqli_fetch_array($result)){
                                                echo "<tr>";
                                                    echo "<td>" . $row['id'] . "</td>";
                                                    echo "<td>" . $row['name'] . "</td>";
                                                    echo "<td>" . $row['sname'] . "</td>";
                                                    echo "<td>" . $row['email'] . "</td>";
                                                    echo "<td>";
                                                        echo '<a href="view.php?id='. $row['id'] .'" class="mr-3" title="View Record" data-toggle="tooltip"><span class="fa fa-eye">View</span></a>';
                                                        echo '<a href="index.php?id='. $row['id'] .'" class="mr-3" title="Update Record" data-toggle="tooltip"><span class="fa fa-pencil">Edit</span></a>';
                                                        echo '<a href="index.php?id='. $row['id'] .'" title="Delete Record" data-toggle="tooltip"><span class="fa fa-trash">Delete</span></a>';
                                                    echo "</td>";
                                                echo "</tr>";
                                            }
                                            echo "</tbody>";                            
                                        echo "</table>";
                                        // Free result set
                                        mysqli_free_result($result);
                                    } else{
                                        echo '<div class="alert alert-danger"><em>No records were found.</em></div>';
                                    }
                                } else{
                                    echo "Oops! Something went wrong. Please try again later.";
                                }
            
                                // // Close connection
                                // mysqli_close($conn);
                                ?>
                            </div>
                        </div>        
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</body>

</html>