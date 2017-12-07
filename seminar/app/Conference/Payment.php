<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\Conference as ConferenceEloquent;
use App\Conference\RegistrationItem as RegistrationItemEloquent;

class Payment extends Model {
    protected $table = 'payment';
    public $timestamps = false;
    
    protected $fillable = [
        'name', 'price'
    ];

    //付款屬於某研討會
    public function conference() {
        return $this -> belongsTo(ConferenceEloquent::class);
    }
	
	//付款有多筆登記項目
	public function registration_item() {
        return $this -> hasMany(RegistrationItemEloquent::class);
    }
}
