<?php
class UserAccount
{
    private $username, $passhash, $balance=0, $fname, $lname, $email;
    //getters:
    public function getUsername()
    {
        return $this->username;
    }
    public function getPassHash()
    {
        return $this->passhash;
    }
    public function getBalance()
    {
        return $this->balance;
    }
    public function getFirstname()
    {
        return $this->fname;
    }
    public function getLastname()
    {
        return $this->lname;
    }
    public function getEmail()
    {
        return $this->email;
    }
    //setters:
    public function setUsername($x)
    {
        $this->username=$x;
    }
    public function setPassHash($x)
    {
        $this->passhash=$x;
    }
    public function setBalance($x)
    {
        $this->balance=$x;
    }
    public function setFirstname($x)
    {
        $this->fname=$x;
    }
    public function setLastname($x)
    {
        $this->lname=$x;
    }
    public function setEmail($x)
    {
        $this->email=$x;
    }
}
?>