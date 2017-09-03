<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\RevisedManuscript as RevisedManuscriptEloquent;
use App\Conference\Reviewer as ReviewerEloquent;
use App\Conference\Editor as EditorEloquent;
use App\Conference\ReReview as ReReviewEloquent;

class ReInvitation extends Model {
    protected $table = 're_invitation';
    public $timestamps = false;
    
    protected $fillable = [
        'status', 'reply', 'deadline'
    ];

    //再次邀請屬於某修訂稿件
    public function revised_manuscript() {
        return $this -> belongsTo(RevisedManuscriptEloquent::class);
    }

    //再次邀請屬於某評論者
    public function reviewer() {
        return $this -> belongsTo(ReviewerEloquent::class);
    }

    //再次邀請屬於某編者
    public function editor() {
        return $this -> belongsTo(EditorEloquent::class);
    }

    //再次邀請有一筆再次評論
    public function re_review() {
        return $this -> hasOne(ReReviewEloquent::class);
    }
}
