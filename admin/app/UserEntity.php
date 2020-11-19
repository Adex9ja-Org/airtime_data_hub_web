<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class UserEntity extends Authenticatable
{
    protected $primaryKey = "email";
    protected $table = 'user_entity';
    public $incrementing = false;
    use Notifiable;
}
