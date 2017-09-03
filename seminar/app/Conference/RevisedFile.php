<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\RevisedManuscript as RevisedManuscriptEloquent;

class RevisedFile extends Model {
    protected $table = 'revised_file';
    public $timestamps = false;

    //修訂檔案屬於某修訂稿件
    public function revised_manuscript() {
        return $this -> belongsTo(RevisedManuscriptEloquent::class);
    }
}
