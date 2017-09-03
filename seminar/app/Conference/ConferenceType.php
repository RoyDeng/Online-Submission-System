<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\SubmissionType as SubmissionTypeEloquent;
use App\Conference\Conference as ConferenceEloquent;

class ConferenceType extends Model {
    protected $table = 'conference_type';
    public $timestamps = false;
    
    protected $fillable = [
        'submission_deadline'
    ];

    //研討會類型屬於某提交類型
    public function submission_type() {
        return $this -> belongsTo(SubmissionTypeEloquent::class);
    }

    //研討會類型屬於某研討會
    public function conference() {
        return $this -> belongsTo(ConferenceEloquent::class);
    }
}
