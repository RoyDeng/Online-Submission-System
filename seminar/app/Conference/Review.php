<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\Invitation as InvitationEloquent;
use App\Conference\ReviewFile as ReviewFileEloquent;

class Review extends Model {
    protected $table = 'review';
    public $timestamps = false;
    
    protected $fillable = [
        'comment_author', 'comment_editor'
    ];

    //評論屬於某邀請
    public function invitation() {
        return $this -> belongsTo(InvitationEloquent::class);
    }

    //評論有一筆檔案
    public function review_file() {
        return $this -> hasOne(ReviewFileEloquent::class);
    }
}
