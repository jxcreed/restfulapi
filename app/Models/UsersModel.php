<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UsersModel extends Model
{
    protected $table = "users";
    // public $timestamps = false;

    protected $fillable = [
    	'id',
    	'token',
    	'firstname',
    	'lastname',
    	'gender',
    	'email',
    	'password',
    	'status',
    ];
}
