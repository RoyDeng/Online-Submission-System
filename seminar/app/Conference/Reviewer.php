<?php
namespace App\Conference;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Conference\Conference as ConferenceEloquent;
use App\Conference\Invitation as InvitationEloquent;
use App\Conference\ReInvitation as ReInvitationEloquent;

class Reviewer extends Authenticatable {
    protected $table = 'reviewer';
    public $timestamps = false;
    
    protected $fillable = [
        'title', 'email', 'password', 'firstname', 'middlename', 'lastname', 'tel', 'institution', 'country'
    ];

    protected $hidden = [
        'password', 'remember_token'
    ];

    //評論者屬於某研討會
    public function conference() {
        return $this -> belongsTo(ConferenceEloquent::class);
    }

    //評論者有多筆邀請
    public function invitation() {
        return $this -> hasMany(InvitationEloquent::class);
    }

    //評論者有多筆再次邀請
    public function re_invitation() {
        return $this -> hasMany(ReInvitationEloquent::class);
    }
}
