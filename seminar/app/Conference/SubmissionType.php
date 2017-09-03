<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\ConferenceType as ConferenceTypeEloquent;
use App\Conference\Manuscript as ManuscriptEloquent;

class SubmissionType extends Model {
    protected $table = 'submission_type';
    public $timestamps = false;

    //提交類型有一筆研討會類型
    public function conference_type() {
        return $this -> hasOne(ConferenceTypeEloquent::class);
    }

    //提交類型有多筆搞件
    public function manuscript() {
        return $this -> hasMany(ManuscriptEloquent::class);
    }
}
