<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Maintainer extends Authenticatable {
    protected $table = 'maintainer';
    public $timestamps = false;
    
    protected $fillable = [
        'email', 'password'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];
}
