<?php
class Transaction
{
    private $id, $sender, $recipient, $date, $sum, $type, $description;
    //getters:
    public function getID()
    {
        return $this->id;
    }

    public function getSender()
    {
        return $this->sender;
    }

    public function getRecipient()
    {
        return $this->recipient;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getSum()
    {
        return $this->sum;
    }

    public function getType()
    {
        return $this->type;
    }

    public function getDescription()
    {
        return $this->description;
    }
    //setters:
    public function setID($x)
    {
        $this->id=$x;
    }

    public function setSender($x)
    {
        $this->sender=$x;
    }

    public function setRecipient($x)
    {
        $this->recipient=$x;
    }

    public function setDate($x)
    {
        $this->date=$x;
    }

    public function setSum($x)
    {
        $this->sum=$x;
    }

    public function setType($x)
    {
        $this->type=$x;
    }

    public function setDescription($x)
    {
        $this->description=$x;
    }
}
?>