<?php

namespace App\Repositories;

use App\Models\Balance;
use App\Repositories\Base\ORM;

class BalancesRepository extends ORM
{
    protected $table = "balances";

    /**
     * @param $id
     * @return Balance
     */
    public function getByUserId($id)
    {
        return $this->selectFullRowsAsObjects("WHERE user_id = '{$id}'")[0];
    }

    protected function getModel()
    {
        return new Balance();
    }
}