<?php

namespace App\Models;
use App\Models\Base\Model;

class User extends Model
{
    public $id;
    public $name;
    public $surname;
    public $login;
    public $pass_hash;
}