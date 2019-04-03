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
if(!isset($_SESSION['user_id']))    //if no user is logged in
{
    header('Location: expired.php');    //jump to expired page
}

if(!empty($_POST))
{
    if(UI_validatePasswordConfirm($_POST["new_pass"], $_POST["new_pass2"]))
    {
        $db_host="localhost";
        $db_user="root";
        $db_pass="";
        $db_name="users";
        $conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
        if(UI_validateOldPassword($conn))
            UI_updatePassword($conn);
        $conn->close();
    }
}
?>

<div class="container">
<div class="row">
<div class="col-md-10">
    <h3>Change password</h3>
    <div class="form-group col-md-5">
        <form action="" method="post">
            Current password: <input type="password" name="crt_pass" required class="form-control"><br>
            New password: <input type="password" name="new_pass" required class="form-control"><br>
            Confirm password: <input type="password" name="new_pass2" required class="form-control"><br>
            <input type="submit" value="Change password" class="btn btn-success"> <br>
        </form>
    </div>
    <a href="profile.php">Go back</a> 

</div>
</div>
</div>
</body>
</html>