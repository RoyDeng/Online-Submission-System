<?php
namespace App\Conference;

use Illuminate\Database\Eloquent\Model;
use App\Conference\Revision as RevisionEloquent;
use App\Conference\RevisedFile as RevisedFileEloquent;
use App\Conference\ReDecision as ReDecisionEloquent;
use App\Conference\ReInvitation as ReInvitationEloquent;

class RevisedManuscript extends Model {
    protected $table = 'revised_manuscript';
    public $timestamps = false;

    //修訂稿件屬於某修訂
    public function revision() {
        return $this -> belongsTo(RevisionEloquent::class);
    }

    //修訂稿件有多筆修訂檔案
    public function revised_file() {
        return $this -> hasMany(RevisedFileEloquent::class);
    }

    //修訂稿件有一筆再次決定
    public function re_decision() {
        return $this -> hasOne(ReDecisionEloquent::class);
    }

    //修訂稿件有多筆再次邀請
    public function re_invitation() {
        return $this -> hasMany(ReInvitationEloquent::class);
    }
}
