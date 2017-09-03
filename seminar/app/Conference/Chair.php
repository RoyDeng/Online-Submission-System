<?php
namespace App\Conference;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Conference\Conference as ConferenceEloquent;
use App\Conference\FinalDecision as FinalDecisionEloquent;

class Chair extends Authenticatable {
    protected $table = 'chair';
    public $timestamps = false;
    
    protected $fillable = [
        'title', 'email', 'password', 'firstname', 'middlename', 'lastname', 'tel', 'institution', 'country'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    //主席屬於某會議
    public function conference() {
        return $this -> belongsTo(ConferenceEloquent::class);
    }

    //主席有多筆最終決定
    public function final_decision() {
        return $this -> hasMany(FinalDecisionEloquent::class);
    }
}
