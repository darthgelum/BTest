<?php


class User extends ORM
{
    protected $table = "users";
    public $id;
    public $name;
    public $surname;
    public $login;
    public $pass_hash;

    /**
     * @param $login
     * @return User
     */
    public function getByLogin($login)
    {
        return $this->selectFullRowsAsObjects("WHERE login = '{$login}'")[0];
    }

    /**
     * @param $id
     * @return User
     */
    public function getById($id)
    {
        return $this->selectFullRowsAsObjects("WHERE id = '{$id}'")[0];
    }
}