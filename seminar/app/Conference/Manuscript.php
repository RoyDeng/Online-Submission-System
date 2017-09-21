<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\Topic as TopicEloquent;
use App\Conference\SubmissionType as SubmissionTypeEloquent;
use App\Author as AuthorEloquent;
use App\Conference\File as FileEloquent;
use App\Conference\Invitation as InvitationEloquent;
use App\Conference\Decision as DecisionEloquent;
use App\Conference\FinalDecision as FinalDecisionEloquent;

class Manuscript extends Model {
    protected $table = 'manuscript';
    public $timestamps = false;
    
    protected $fillable = [
        'type', 'title', 'abstract'
    ];

    //稿件屬於某題目
    public function topic() {
        return $this -> belongsTo(TopicEloquent::class);
    }

    //稿件屬於某提交類型
    public function submission_type() {
        return $this -> belongsTo(SubmissionTypeEloquent::class);
    }

    //稿件屬於某作者
    public function author() {
        return $this -> belongsTo(AuthorEloquent::class);
    }

    //稿件有多筆檔案
    public function file() {
        return $this -> hasMany(FileEloquent::class);
    }

    //稿件有一筆邀請
    public function invitation() {
        return $this -> hasMany(InvitationEloquent::class);
    }

    //稿件有一筆決定
    public function decision() {
        return $this -> hasOne(DecisionEloquent::class);
    }

    //稿件有一筆最終決定
    public function final_decision() {
        return $this -> hasOne(FinalDecisionEloquent::class);
    }
}
