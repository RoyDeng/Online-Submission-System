<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\Registration as RegistrationEloquent;
use App\Conference\Payment as PaymentEloquent;

class RegistrationItem extends Model {
    protected $table = 'registration_item';
    public $timestamps = false;
    
    protected $fillable = [
        'quantity'
    ];

    //登記項目屬於某登記
    public function registration() {
        return $this -> belongsTo(RegistrationEloquent::class);
    }

    //登記項目屬於某支付項目
    public function payment() {
        return $this -> belongsTo(PaymentEloquent::class);
    }
}
