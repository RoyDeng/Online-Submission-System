<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Conference\Manuscript as ManuscriptEloquent;
use App\Conference\Registration as RegistrationEloquent;

class Author extends Authenticatable {
    protected $table = 'author';
    public $timestamps = false;
    
    protected $fillable = [
        'title', 'email', 'password', 'firstname', 'middlename', 'lastname', 'tel', 'institution', 'country'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    //作者有多筆稿件
    public function manuscript() {
        return $this -> hasMany(ManuscriptEloquent::class);
    }

    //作者有多筆登記
    public function registration() {
        return $this -> hasMany(RegistrationEloquent::class);
    }
}
