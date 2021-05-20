<?php


class User
{
    public $id;
    public $username;
    public $password;
    public $email;

    /**
     * User constructor.
     * @param $id
     * @param $username
     * @param $password
     * @param null $email
     */
    public function __construct($id, $username, $password, $email = null)
    {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->email = $email;
    }

    static function fromDataArray(?array $data): ?User
    {
        if (!$data) return null;
        if ($data[3] == 'NULL') $data[3] = null;
        return new User($data[0], $data[1], $data[2], $data[3]);
    }
}