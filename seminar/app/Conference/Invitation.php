<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\Manuscript as ManuscriptEloquent;
use App\Conference\Reviewer as ReviewerEloquent;
use App\Conference\Editor as EditorEloquent;
use App\Conference\Review as ReviewEloquent;

class Invitation extends Model {
    protected $table = 'invitation';
    public $timestamps = false;
    
    protected $fillable = [
        'status', 'reply', 'deadline'
    ];

    //邀請屬於某稿件
    public function manuscript() {
        return $this -> belongsTo(ManuscriptEloquent::class);
    }

    //邀請屬於某評論者
    public function reviewer() {
        return $this -> belongsTo(ReviewerEloquent::class);
    }

    //邀請屬於某編者
    public function editor() {
        return $this -> belongsTo(EditorEloquent::class);
    }

    //邀請有一筆評論
    public function review() {
        return $this -> hasOne(ReviewEloquent::class);
    }
}
