<!DOCTYPE html>
<html>

<head>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>

<div class="container">
    <div class="row">
        <div class="col-md-5">
            <h1>Bank account manager</h1>
            <h3>Please login:</h3>

            <div class="form-group">
                <form action="" method="post">
                    <label>Username</label>
                    <input type="text" name="username" required class="form-control"><br>
                    <label>Password</label>
                    <input type="password" name="password" required class="form-control"><br>
                    <input type="submit" value="Log in" class="btn btn-primary">
                </form>
                <br>
                <form action="register.php" method="post">
                    <input type="submit" value="Create account" class="btn btn-primary" name="register">
                </form>
            </div>
        </div>
    </div>



<?php
session_start();
if(isset($_SESSION['user_id']))     //if an user is already logged
{
    header('Location: profile.php');    //jump to profile page
}
$db_host="localhost";
$db_user="root";
$db_pass="";
$db_name="users";

if ( ! empty( $_POST ) )
{
    if ( isset( $_POST['username'] ) && isset( $_POST['password'] ) )
    {
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);  //connect to db
        $cmd = $conn->prepare("SELECT * FROM accounts WHERE Username = ?");
        $cmd->bind_param('s', $_POST['username']);
        $cmd->execute();
        $result = $cmd->get_result();   //execute sql
    	$user = $result->fetch_object();
        if ( $user && password_verify( $_POST['password'], $user->PasswordHash ) )   //compare passwords hashes
        {
            $_SESSION['user_id'] = $user->ID;   //curent user
            mysqli_close($conn);
            header('Location: profile.php');    //jump to account page
        }
        else
        {
            echo "
                <div class=\"alert alert-danger\" role=\"alert\">
                    Invalid username or password!
                </div>";    //the password hashes do not match
            mysqli_close($conn);
        }
    }
}
?>


</body>
</html>
