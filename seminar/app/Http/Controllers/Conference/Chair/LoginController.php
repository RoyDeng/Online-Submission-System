<?php
namespace App\Http\Controllers\Conference\Chair;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Hash;
use Mail;
use App\Conference\Conference;
use App\Conference\Chair;

class LoginController extends Controller {
    use AuthenticatesUsers;
    protected $redirectTo = 'conference/chair';

    public function __construct() {
        $this -> middleware('guest') -> except('logout');
    }

    function LoginPage($number) {
		$conference = Conference::where('number', $number) -> first();
		if ($conference -> exist_deadline > date('Y-m-d') && $conference -> status == 1) return view('conference.chair.login', ['conference' => $conference]);
		else return view('expire_page');
	}

	function ForgotPasswordPage($number) {
		$conference = Conference::where('number', $number) -> first();
		if ($conference -> exist_deadline > date('Y-m-d') && $conference -> status == 1) return view('conference.chair.forgot_password', ['conference' => $conference]);
		else return view('expire_page');
	}

	public function ResetPassword(Request $request) {
		$count = Chair::where('email', $request -> email) -> where('conference_id', $request -> conference_id) -> count();
		if ($count > 0) {
			$chair = Chair::where('email', $request -> email) -> where('conference_id', $request -> conference_id) -> first();
			$new_password = str_random(6);
			$chair -> password = bcrypt($new_password);
			$chair -> save();
			$to = [
				'email' => $request -> email,
				'name' => $chair -> firstname.' '.$chair -> middlename.' '.$chair -> lastname
			];
			$data = [
				'title' => $chair -> title,
				'firstname' => $chair -> firstname,
				'middlename' => $chair -> middlename,
				'lastname' => $chair -> lastname,
				'password' => $new_password,
				'number' => $chair -> conference -> number
			];
			Mail::send('email.chair_forgot_password', $data, function($message) use ($to) {
				$message -> to($to['email'], $to['name']) -> subject('Online Submission System - Chair Password Changed Successfully');
			});
			return back() -> with('success', 'A password reset email has been sent!');
		} else return back() -> with('danger', 'That email does not exist!');
	}

    protected function guard() {
		return \Auth::guard('chair');
	}

	protected function credentials(Request $request) {
		$conference = Conference::where('number', $request -> number) -> first();
		return array_merge($request -> only($this -> username(), 'password'), ['conference_id' => $conference -> id, 'status' => 1]);
	}

    public function logout(Request $request) {
		$chair = Auth::user();
		$conference = Conference::find($chair -> conference_id);
		$this -> guard() -> logout();
		$request -> session() -> flush();
		$request -> session() -> regenerate();
		return redirect('conference/chair/login/'.$conference -> number);
	}
}
