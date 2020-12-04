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

    public function toJSON()
    {
        return get_object_vars($this);
    }
    
    public function __serialize()
    {
        return array(
            'id' => $this->getID(),
            'firstname' => $this->getFirstName(),
            'lastname' => $this->getLastName(),
            'password' => $this->getPassword(),
            'email' => $this->getEmail(),
            'date_joined' => $this->getDateJoined()
        );
    }

    public function __unserialize($data)
    {
        $this->id = $data["id"];
        $this->firstname = $data["firstname"];
        $this->lastname = $data["lastname"];
        $this->password = $data["password"];
        $this->email = $data["email"];
        $this->date_joined = $data["date_joined"];
    }
}
