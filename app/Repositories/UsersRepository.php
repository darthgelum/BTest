<?php
namespace App\Repositories;
use App\Models\User;
use App\Repositories\Base\ORM;

class UsersRepository extends ORM
{
    protected $table = "users";
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

    public function getBalanceByUserId($id)
    {
        return (new BalancesRepository())->getByUserId($id);
    }
    /**
     * @return User
     */
    protected function getModel()
    {
        return new User();
    }
}