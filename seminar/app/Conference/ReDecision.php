<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\RevisedManuscript as RevisedManuscriptEloquent;
use App\Conference\Editor as EditorEloquent;

class ReDecision extends Model {
    protected $table = 're_decision';
    public $timestamps = false;
    
    protected $fillable = [
        'status', 'comment'
    ];

    //再次決定屬於某修訂稿件
    public function revised_manuscript() {
        return $this -> belongsTo(RevisedManuscriptEloquent::class);
    }

    //再次決定屬於某編者
    public function editor() {
        return $this -> belongsTo(EditorEloquent::class);
    }
}
