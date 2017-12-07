<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\Conference as ConferenceEloquent;
use App\Author as AuthorEloquent;
use App\Conference\RegistrationItem as RegistrationItemEloquent;

class Registration extends Model {
    protected $table = 'registration';
    public $timestamps = false;
    
    protected $fillable = [
        'tx_seq', 'amount', 'status', 'note'
    ];

    //登記屬於某研討會
    public function conference() {
        return $this -> belongsTo(ConferenceEloquent::class);
    }

    //登記屬於某作者
    public function author() {
        return $this -> belongsTo(AuthorEloquent::class);
    }
	
	//登記會有多登記項目
    public function registration_item() {
        return $this -> hasMany(RegistrationItemEloquent::class);
    }
}
