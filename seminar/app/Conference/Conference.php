<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\Chair as ChairEloquent;
use App\Conference\Reviewer as ReviewerEloquent;
use App\Conference\Topic as TopicEloquent;
use App\Conference\ConferenceType as ConferenceTypeEloquent;
use App\Conference\Payment as PaymentEloquent;
use App\Conference\Registration as RegistrationEloquent;

class Conference extends Model {
    protected $table = 'conference';
    public $timestamps = false;
    
    protected $fillable = [
        'title', 'exist_deadline'
    ];

    //研討會有多位主席
    public function chair() {
        return $this -> hasMany(ChairEloquent::class);
    }

    //研討會有多位評論者
    public function reviewer() {
        return $this -> hasMany(ReviewerEloquent::class);
    }

    //研討會有多筆題目
    public function topic() {
        return $this -> hasMany(TopicEloquent::class);
    }

    //研討會有多筆研討會類型
    public function conference_type() {
        return $this -> hasMany(ConferenceTypeEloquent::class);
    }

    //研討會有多筆付款
    public function payment() {
        return $this -> hasMany(PaymentEloquent::class);
    }

    //研討會有多筆登記
    public function registration() {
        return $this -> hasMany(RegistrationEloquent::class);
    }
}
