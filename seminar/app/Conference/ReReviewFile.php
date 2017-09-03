<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\ReReview as ReReviewEloquent;

class ReReviewFile extends Model {
    protected $table = 're_review_file';
    public $timestamps = false;
    
    //檔案屬於某再次評論
    public function re_review() {
        return $this -> belongsTo(ReReviewEloquent::class);
    }
}
