<!DOCTYPE html>
<html>
<body>

<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" 
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<?php
require "UI/UI.php";

session_start();
if(isset($_SESSION['user_id']))     //if an user is already logged
{
    header('Location: profile.php');    //jump to profile page
}

$db_host="localhost";
$db_user="root";
$db_pass="";
$db_name="users";

if(isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['email']) && isset($_POST['username']) && isset($_POST['password']))
{
    if(UI_validateEmail($_POST['email']))
    {
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        UI_addUserAccount($conn);
        $conn->close();
    }
}
?>

<div class="container">
<div class="row">
<div class="col-md-5">
    <h2>Create an account</h2>
    <div class="form-group">
        <form action="" method="post">
            <label>First name</label>
            <input type="text" name="fname" required autocomplete="off" class="form-control">
            <label>Last name</label>
            <input type="text" name="lname" required autocomplete="off" class="form-control"><br>
            <label>Email</label>
            <input type="text" name="email" required autocomplete="off" class="form-control"><br>
            <label>Username</label>
            <input type="text" name="username" required autocomplete="off" class="form-control"><br>
            <label>Password</label>
            <input type="password" name="password" required autocomplete="off" class="form-control"><br>
            <input type="submit" value="Create account" class="btn btn-primary">
        </form>
    </div>
    <a href="index.php">Go back</a> 
</div>
</div>
</div>

</body>
</html>