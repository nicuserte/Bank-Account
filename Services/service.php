<?php
require "Model/transaction.php";
require "Model/user_account.php";

/*
Function that makes a transaction.
in: conn, t
out: true, if success
        else false
pre: conn-connection to database
    t-the transaction to be added
post: the transaction is added and the balances are changed
*/
function addTransaction($conn, $t)
{
    if(addTransactionToDB($conn, $t))
        if(sendMoney($conn, $t))
            if(receiveMoney($conn, $t))
                return TRUE;
    return FALSE;
}

/*
Function that adds a transaction to a database, using an open connection.
in: conn, t
out: true, if success, else false
pre: conn-the connection to database
    t-the transaction to be added
post: the transaction is added to database
*/
function addTransactionToDB($conn, $t)
{
    $cmd = $conn->prepare("insert into transactions(Sender, Recipient, Date, Sum, Description) values(?, ?, ?, ?, ?)");
    $p1=$t->getSender();
    $p2=$t->getRecipient();
    $p3=$t->getDate();
    $p4=$t->getSum();
    $p5=$t->getDescription();
    $cmd->bind_param('sssss', $p1, $p2, $p3, $p4, $p5);
    $cmd->execute();
    return $cmd;
}

/*
Function that returns a username by id.
in: conn, id
out: username, or false if fails
pre: conn-the connection to database
    id-user id
post: -
*/
function  getUsername($conn, $id)
{
    $cmd = $conn->prepare("SELECT Username FROM accounts WHERE ID = ?");
    $cmd->bind_param('s', $id);
    $cmd->execute();
    $result=$cmd->get_result();
    if ($result->num_rows==1)
    {
        $row = $result->fetch_assoc();
        return $row['Username'];
    }
    return FALSE;
}

/*
Returns a given user's balance, by user's id.
in: conn, user
out: balance
pre: conn-mysql connection
    id-user's id
post: -
*/
function getUserBalance($conn, $id)
{
    $cmd = $conn->prepare("SELECT Balance FROM accounts WHERE ID = ?");
    $cmd->bind_param('s', $id);
    $cmd->execute();
    $result=$cmd->get_result();
    if ($result->num_rows==1)
    {
        $row = $result->fetch_assoc();
        return $row['Balance'];
    }
}

function getUserID($conn, $uname)
{
    $cmd = $conn->prepare("SELECT ID FROM accounts WHERE Username = ?");
    $cmd->bind_param('s', $uname);
    $cmd->execute();
    $result=$cmd->get_result();
    if ($result->num_rows==1)
    {
        $row = $result->fetch_assoc();
        return $row['ID'];
    }
}

/*
Function that substracts money from sender.
in: conn, t
out: true, if success, else false
pre: conn-the connection to database
    t-the transaction
post: sender_balance = sender_balance - transaction_value
*/
function sendMoney($conn, $t)
{
    $sender=getUserID($conn, $t->getSender());
    $balance=getUserBalance($conn, $sender) - $t->getSum();
    $cmd = $conn->prepare("update accounts set Balance=? where ID=?");
    $cmd->bind_param('ss', $balance, $sender);
    $cmd->execute();
    return $cmd;
}

/*
Function that adds money to recepient.
in: conn, t
out: true, if success, else false
pre: conn-the connection to database
    t-the transaction
post: recepient_balance = recepient_balance + transaction_value
*/
function receiveMoney($conn, $t)
{
    $recipient=getUserID($conn, $t->getRecipient());
    $balance=getUserBalance($conn, $recipient) + $t->getSum();
    $cmd = $conn->prepare("update accounts set Balance=? where ID=?");
    $cmd->bind_param('ss', $balance, $recipient);
    $cmd->execute();
    return $cmd;
}

/*
Function that makes an array of transactions from sql query.
in: cmd
out: array
pre: cmd-the command from where every row will be extracted
post: -
*/
function makeArray($cmd)
{
    $result=$cmd->get_result();
    $stack=array();
    if ($result->num_rows > 0)
    {
        while($row = $result->fetch_assoc())
        {
            $t=new Transaction;
            $t->setID($row["ID"]);
            $t->setDate($row["Date"]);
            $t->setSum($row["Sum"]);
            $t->setRecipient($row["Recipient"]);
            $t->setDescription($row["Description"]);
            $t->setSender($row["Sender"]);
            array_push($stack, $t);
        }
        return $stack;
    }
}

/*
Function that returns a user's transactions list.
in: conn, id, sort_string
out: list
pre: conn-the connection to database
    id-the user's id
    sort_string-the last part of sql string, in format "order by X asc/desc"
post: list-the list of user's transactions
*/
function getUserTransactions($conn, $id, $sort_string)
{
    $uname=getUsername($conn, $id);
    $cmd = $conn->prepare("SELECT * FROM transactions WHERE Sender = ? " . $sort_string);
    $cmd->bind_param('s', $uname);
    $cmd->execute();
    if(!$cmd)
        die("DATABASE ERROR");
    return makeArray($cmd);
}

/*
Function that adds an account into database.
in: conn, t
out: true if success, else false
pre: conn-the connection to database
    t-the account
post: the account t is added into database
*/
function addUserAccount($conn, $t)
{
    $cmd = $conn->prepare("insert into accounts(Username, PasswordHash, Balance, Firstname, Lastname, Email) values(?, ?, ?, ?, ?, ?)");
    $p1=$t->getUsername();
    $p2=$t->getPassHash();
    $p3=0;
    $p4=$t->getFirstname();
    $p5=$t->getLastname();
    $p6=$t->getEmail();
    $cmd->bind_param('ssssss', $p1, $p2, $p3, $p4, $p5, $p6);
    $cmd->execute();
    return $cmd;
}

/*
Function that returns a user's password hash.
in: conn, id
out: hash
pre: conn-the connection to database
    id-the user's id
post: -
*/
function getPasswordHash($conn, $id)
{
    $cmd = $conn->prepare("SELECT PasswordHash FROM accounts WHERE ID=?");
    $cmd->bind_param('s', $id);
    $cmd->execute();
    $result = $cmd->get_result();
    $row=$result->fetch_assoc();
    return $row['PasswordHash'];
}

/*
Function that updates a user's password hash.
in: conn, id, hash
out: true if success, else false
pre: conn-the connection to database
    id-the user's id
    hash-the new password hash
post: the user's old password hash is replaced with the new hash
*/
function updatePasswordHash($conn, $id, $hash)
{
    $cmd = $conn->prepare("update accounts set PasswordHash=? where ID=?");
    $cmd->bind_param('ss', $hash, $id);
    $cmd->execute();
    return $cmd;
}

?>
