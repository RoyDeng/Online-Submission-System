<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\Conference as ConferenceEloquent;
use App\Conference\Editor as EditorEloquent;
use App\Conference\Manuscript as ManuscriptEloquent;

class Topic extends Model {
    protected $table = 'topic';
    public $timestamps = false;
    
    protected $fillable = [
        'title', 'deadline'
    ];

    //題目屬於某研討會
    public function conference() {
        return $this -> belongsTo(ConferenceEloquent::class);
    }

    //題目有多位編者
    public function editor() {
        return $this -> hasMany(EditorEloquent::class);
    }

    //題目有多筆稿件
    public function manuscript() {
        return $this -> hasMany(ManuscriptEloquent::class);
    }
}
