<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\ReInvitation as ReInvitationEloquent;
use App\Conference\ReReviewFile as ReReviewFileEloquent;

class ReReview extends Model {
    protected $table = 're_review';
    public $timestamps = false;
    
    protected $fillable = [
        'comment_author', 'comment_editor'
    ];

    //再次評論屬於某再次邀請
    public function invitation() {
        return $this -> belongsTo(ReInvitationEloquent::class);
    }

    //再次評論有一筆檔案
    public function re_review_file() {
        return $this -> hasOne(ReReviewFileEloquent::class);
    }
}
