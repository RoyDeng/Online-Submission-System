<?php
namespace App\Conference;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Conference\Topic as TopicEloquent;
use App\Conference\Decision as DecisionEloquent;
use App\Conference\Invitation as InvitationEloquent;
use App\Conference\ReInvitation as ReInvitationEloquent;

class Editor extends Authenticatable {
    protected $table = 'editor';
    public $timestamps = false;
    
    protected $fillable = [
        'title', 'email', 'password', 'firstname', 'middlename', 'lastname', 'tel', 'institution', 'country'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    //編者屬於某題目
    public function topic() {
        return $this -> belongsTo(TopicEloquent::class);
    }

    //編者有多筆決定
    public function decision() {
        return $this -> hasMany(DecisionEloquent::class);
    }

    //編者有多筆邀請
    public function invitation() {
        return $this -> hasMany(InvitationEloquent::class);
    }

    //編者有多筆邀請
    public function re_invitation() {
        return $this -> hasMany(ReInvitationEloquent::class);
    }
}
