<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Hash;
use Mail;
use App\Maintainer;

class LoginController extends Controller {
	use AuthenticatesUsers;
	//成功驗證後導向的URL
    protected $redirectTo = '/admin';

    public function __construct() {
        $this -> middleware('guest') -> except('logout');
    }

	//登入頁面
    function LoginPage() {
		return view('admin.login');
	}

	//忘記密碼頁面
	function ForgotPasswordPage() {
		return view('admin.forgot_password');
	}

	//重新設置管理員的密碼
	public function ResetPassword(Request $request) {
		//計算符合Email的管理員數量
		$count = Maintainer::where('email', $request -> email) -> count();
		//數量大於0，表示管理員存在
		if ($count > 0) {
			//取得條件符合Email的第一位管理員
			$maintainer = Maintainer::where('email', $request -> email) -> first();
			//產生長度6的隨機字串為新密碼
			$new_password = str_random(6);
			//使用Bcrypt加密來保存新密碼
			$maintainer -> password = bcrypt($new_password);
			//新增到資料庫
			$maintainer -> save();
			//設定收件者的訊息
			$to = [
				'email' => $request -> email,
				'name' => $maintainer -> firstname.' '.$maintainer -> middlename.' '.$maintainer -> lastname
			];
			//傳遞到郵件頁面的資料
			$data = [
				'name' => $maintainer -> name,
				'password' => $new_password
			];
			//使用Mail::send方法來寄送電子郵件，第一個參數為郵件視圖的名稱，第二個是傳遞給該視圖的資料，通常是一個關聯式陣列，讓視圖可透過鍵值來取得資料項目，第三個參數是一個閉包，可以對訊息進行各種設定
			Mail::send('email.maintainer_forgot_password', $data, function($message) use ($to) {
				$message -> to($to['email'], $to['name']) -> subject('Online Submission System - Maintainer Password Changed Successfully');
			});
			//返回前一個URL，同時加上快閃訊息(一次性Session)，第一個參數是鍵值，第二個參數是數值
			return back() -> with('success', 'A password reset email has been sent!');
		} else return back() -> with('danger', 'That email does not exist!');
	}

	//指定認證所用的守衛為「admin」，所指定的守衛名稱必須對應到auth.php設定檔中guards陣列的其中一個鍵名
    protected function guard() {
		return \Auth::guard('admin');
	}

	//登出操作
    public function logout(Request $request) {
		$this -> guard() -> logout();
		$request -> session() -> flush();
		$request -> session() -> regenerate();
		return redirect('/admin/login');
	}
}
