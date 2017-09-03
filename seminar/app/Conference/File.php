<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\Manuscript as ManuscriptEloquent;

class File extends Model {
    protected $table = 'file';
    public $timestamps = false;
    
    //檔案屬於某稿件
    public function manuscript() {
        return $this -> belongsTo(ManuscriptEloquent::class);
    }
}
