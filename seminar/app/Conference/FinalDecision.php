<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\Manuscript as ManuscriptEloquent;
use App\Conference\Chair as ChairEloquent;
use App\Conference\Revision as RevisionEloquent;

class FinalDecision extends Model {
    protected $table = 'final_decision';
    public $timestamps = false;
    
    protected $fillable = [
        'status', 'comment'
    ];

    //最終決定屬於某稿件
    public function manuscript() {
        return $this -> belongsTo(ManuscriptEloquent::class);
    }

    //最終決定屬於某主席
    public function chair() {
        return $this -> belongsTo(ChairEloquent::class);
    }

    //最終決定有多筆修訂
    public function revision() {
        return $this -> hasMany(RevisionEloquent::class);
    }
}