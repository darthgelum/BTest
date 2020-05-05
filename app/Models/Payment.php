<?php

namespace App\Models;
use App\Models\Base\Model;

class Payment extends Model
{
    public $id;
    public $user_id;
    public $summ;
}