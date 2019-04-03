<?php
/*
UI functions for web app. They display error or success messages.
The functions need only the connection to database, as they can access $_POST variables directly.
*/

require "Services/service.php";

function UI_addTransaction($conn)
{
    if(!is_numeric($_POST['sum']))
    {
        echo
        "<div class=\"alert alert-danger\" role=\"alert\">
            Invalid sum!
        </div>";
        return;
    }
    if($_POST['recipient']==getUsername($conn, $_SESSION['user_id']))
    {
        echo
        "<div class=\"alert alert-danger\" role=\"alert\">
            Invalid destination!
        </div>";
        return;
    }
    if(getUserBalance($conn, $_SESSION['user_id'])<$_POST['sum'])
    {
        echo
        "<div class=\"alert alert-danger\" role=\"alert\">
            You do not have enough money!
        </div>";
        return;
    }
    $t=new Transaction;
    $t->setSender(getUsername($conn, $_SESSION['user_id']));
    $t->setRecipient($_POST['recipient']);
    $t->setDate(date('Y/m/d H:i:s'));
    $t->setSum($_POST['sum']);
    $t->setDescription($_POST['description']);
    if(!addTransaction($conn, $t))
    {
        echo
        "<div class=\"alert alert-danger\" role=\"alert\">
            The transaction was not created!
        </div>";
        die("DATABASE ERROR!");
    }
    else
        echo
        "<div class=\"alert alert-success\" role=\"alert\">
            The transaction was created.
        </div>";
}

/*
Function that fills up the transaction table.
in: elems-array of elements, type "Transaction"
out: -
*/
function printTransactionList($elems)
{
    $len = count($elems);
    for($i=0; $i<$len; $i++)
    {
        echo "
        <tr>
            <td>" . ($i+1) . "</td>
            <td>" . $elems[$i]->getID() . "</td>
            <td>" . $elems[$i]->getDate() . "</td>
            <td>" . $elems[$i]->getSum() . "</td>
            <td>" . $elems[$i]->getRecipient() . "</td>
            <td>" . $elems[$i]->getDescription() . "</td>
        </tr>";
    }
}

function UI_displayTransactions($conn)
{
    $sort_string="";
    if(isset($_POST['order']))
        $sortType=$_POST['order'];
    else $sortType="";
    $id=$_SESSION['user_id'];
    if($sortType=="asc_date")
    {
        $sort_string="order by Date asc";
        echo "<script>
                document.getElementById('order_by').selectedIndex = 0;
            </script>";
    }
    else if($sortType=="desc_date")
    {
        $sort_string="order by Date desc";
        echo "<script>
                document.getElementById('order_by').selectedIndex = 1;
            </script>";
    }
    else if($sortType=="asc_sum")
    {
        $sort_string="order by Sum asc";
        echo "<script>
                document.getElementById('order_by').selectedIndex = 2;
            </script>";
    }
    else if($sortType=="desc_sum")
    {
        $sort_string="order by Sum desc";
        echo "<script>
                document.getElementById('order_by').selectedIndex = 3;
            </script>";
    }
    $elems=getUserTransactions($conn, $id, $sort_string);
    if(!$elems)
        echo '<h3>0 transactions</h3>';
    else
        printTransactionList($elems);
}

/*
Function that validates a password confirmation.
in: x, y
out: true, if x==y
    false, x!=y
pre: x, y - strings
post: -
*/
function UI_validatePasswordConfirm($x, $y)
{
    if($x!=$y)
    {
        echo "
            <div class=\"alert alert-danger\" role=\"alert\">
                The passwords do not match!
            </div>";
        return FALSE;
    }
    return TRUE;
}

function UI_validateOldPassword($conn)
{
    $passHash=getPasswordHash($conn, $_SESSION['user_id']);
    if(!password_verify($_POST['crt_pass'], $passHash))
    {
        echo "
            <div class=\"alert alert-danger\" role=\"alert\">
                The old password is wrong!
            </div>";
        return FALSE;
    }
    return TRUE;
}

function UI_updatePassword($conn)
{
    $passHash=password_hash($_POST['new_pass'], PASSWORD_DEFAULT);
    if(!updatePasswordHash($conn, $_SESSION['user_id'], $passHash))
    {
        echo "
            <div class=\"alert alert-danger\" role=\"alert\">
                Database error!
            </div>";
        return FALSE;
    }
    else
    {
        echo
        "<div class=\"alert alert-success\" role=\"alert\">
            The password was changed.
        </div>";
        return TRUE;
    }
}

/*
Function that verify if x is a valid email.
*/
function UI_validateEmail($x)
{
    if (!filter_var($x, FILTER_VALIDATE_EMAIL))
    {
        echo "
            <div class=\"alert alert-danger\" role=\"alert\">
                Invalid email!
            </div>";
        return FALSE;
    }
    return TRUE;
}

function UI_addUserAccount($conn)
{
    $t=new UserAccount;
    $t->setFirstname($_POST['fname']);
    $t->setLastname($_POST['lname']);
    $t->setEmail($_POST['email']);
    $t->setUsername($_POST['username']);
    $t->setPassHash(password_hash($_POST['password'], PASSWORD_DEFAULT));
    if(!addUserAccount($conn, $t))
        echo "
        <div class=\"alert alert-danger\" role=\"alert\">
            Error!
        </div>";
    else
        echo
        "<div class=\"alert alert-success\" role=\"alert\">
            The account was created.
        </div>";
}

?>