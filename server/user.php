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
    
    public static function buildAndSanitize($data)
    {
        $user = new User(
            intval(filter_var($data["id"], FILTER_SANITIZE_NUMBER_INT)),
            filter_var($data["firstname"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["lastname"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["password"], FILTER_SANITIZE_FULL_SPECIAL_CHARS),
            filter_var($data["email"], FILTER_SANITIZE_EMAIL),
            filter_var($data["date_joined"], FILTER_SANITIZE_FULL_SPECIAL_CHARS)
        );
        return $user;
    }
}
