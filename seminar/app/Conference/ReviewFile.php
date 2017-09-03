<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\Review as ReviewEloquent;

class ReviewFile extends Model {
    protected $table = 'review_file';
    public $timestamps = false;
    
    //檔案屬於某評論
    public function review() {
        return $this -> belongsTo(ReviewEloquent::class);
    }
}
