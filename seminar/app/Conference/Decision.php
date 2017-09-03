<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\Manuscript as ManuscriptEloquent;
use App\Conference\Editor as EditorEloquent;

class Decision extends Model {
    protected $table = 'decision';
    public $timestamps = false;
    
    protected $fillable = [
        'status', 'comment'
    ];

    //決定屬於某稿件
    public function manuscript() {
        return $this -> belongsTo(ManuscriptEloquent::class);
    }

    //決定屬於某編者
    public function editor() {
        return $this -> belongsTo(EditorEloquent::class);
    }
}
