<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users_activation extends Model
{
    //
	protected $table = "users_activations";

    protected $fillable = [
        'id_user', 'token', 
    ];
}
