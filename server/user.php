<?php
class User
{
    private $id;
    private $firstname;
    private $lastname;
    private $password;
    private $email;
    private $date_joined;

    public function __construct($id = null, $firstname, $lastname, $password, $email, $date_joined = null)
    {
        $this->id = $id;
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->password = $password;
        $this->email = $email;
        if ($date_joined != null) {
            $this->date_joined = $date_joined;
        } else {
            $this->date_joined = date("Y-m-d H:i:s");
        }
    }

    public function getID()
    {
        return $this->id;
    }

    public function getFirstName()
    {
        return $this->firstname;
    }

    public function getLastName()
    {
        return $this->lastname;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getDateJoined()
    {
        return $this->date_joined;
    }
}
