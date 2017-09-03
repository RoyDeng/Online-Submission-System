<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\FinalDecision as FinalDecisionEloquent;
use App\Conference\RevisedManuscript as RevisedManuscriptEloquent;

class Revision extends Model {
    protected $table = 'revision';
    public $timestamps = false;
    
    protected $fillable = [
        'status', 'comment', 'deadline'
    ];

    //修訂屬於某最終決定
    public function final_decision() {
        return $this -> belongsTo(FinalDecisionEloquent::class);
    }

    //修訂有一筆修訂稿件
    public function revised_manuscript() {
        return $this -> hasOne(RevisedManuscriptEloquent::class);
    }
}
