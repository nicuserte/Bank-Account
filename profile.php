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
$db_host="localhost";
$db_user="root";
$db_pass="";
$db_name="users";
//connect to db:
$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);
if(isset($_POST['add_clicked']))
{
    UI_AddTransaction($conn);
}
?>
<div class="container">
<div class="row">
<div class="col-md-10">

    <h1>Welcome <?php echo getUsername($conn, $_SESSION['user_id']); echo "!"; ?> </h1>
    <p>This is your bank account. Here you can make online payments.</p>
    <h4>Current balance: <span class="badge badge-secondary"> <?php echo getUserBalance($conn, $_SESSION['user_id']); ?>$ </span></h4>
    <h4>Make a payment:</h4>
    <div class="form-group col-md-5">
        <form action="" method="post">
            Sum: <input type="text" name="sum" required class="form-control"><br>
            To: <input type="text" name="recipient" required class="form-control"><br>
            Description: <textarea name="description" class="form-control"></textarea><br>
            <input type="submit" value="Create transaction" name="add_clicked" class="btn btn-success"> <br>
        </form>
    </div>
    <form action="logout.php">
        <input type="submit" value="Log out" class="btn btn-danger">
    </form>
    <a href="edit_profile.php">Change password</a> 
    <h1>Transactions:</h1>
    Sort:
    <form action="" method="post">
        <select name="order" id="order_by">
            <option value="asc_date">Ascending by date</option>
            <option value="desc_date" selected="selected">Descending by date</option>
            <option value="asc_sum">Ascending by sum</option>
            <option value="desc_sum">Descending by sum</option>
        </select> 
        <input type="submit" value="Sort" name="sort_clicked" class="btn btn-info"> <br>
    </form>

    <table class="table table-striped">
        <tr>
            <th>#</th>
            <th>ID</th>
            <th>Date</th>
            <th>Sum</th>
            <th>Recipient</th>
            <th>Description</th>
        </tr>
        <?php
            UI_displayTransactions($conn);
            $conn->close();
        ?>
    </table> 
</div>
</div>
</div>
</body>
</html>
