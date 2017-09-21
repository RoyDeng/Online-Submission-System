<?php
namespace App\Http\Controllers\Conference\Reviewer;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Hash;
use Mail;
use App\Conference\Conference;
use App\Conference\Reviewer;
use App\Maintainer;

class LoginController extends Controller {
    use AuthenticatesUsers;
    protected $redirectTo = 'conference/reviewer';

    public function __construct() {
        $this -> middleware('guest') -> except('logout');
    }

    function LoginPage($number) {
		$conference = Conference::where('number', $number) -> first();
		$maintainer = Maintainer::find(1);
		if ($conference -> exist_deadline > date('Y-m-d') && $conference -> status == 1) return view('conference.reviewer.login', ['conference' => $conference, 'maintainer' => $maintainer]);
		else return view('expire_page');
	}

	function ForgotPasswordPage($number) {
		$conference = Conference::where('number', $number) -> first();
		if ($conference -> exist_deadline > date('Y-m-d') && $conference -> status == 1) return view('conference.reviewer.forgot_password', ['conference' => $conference]);
		else return view('expire_page');
	}

	public function ResetPassword(Request $request) {
		$count = Reviewer::where('email', $request -> email) -> where('conference_id', $request -> conference_id) -> count();
		if ($count > 0) {
			$reviewer = Reviewer::where('email', $request -> email) -> where('conference_id', $request -> conference_id) -> first();
			$new_password = str_random(6);
			$reviewer -> password = bcrypt($new_password);
			$reviewer -> save();
			$to = [
				'email' => $request -> email,
				'name' => $reviewer -> firstname.' '.$reviewer -> middlename.' '.$reviewer -> lastname
			];
			$data = [
				'title' => $reviewer -> title,
				'firstname' => $reviewer -> firstname,
				'middlename' => $reviewer -> middlename,
				'lastname' => $reviewer -> lastname,
				'password' => $new_password,
				'number' => $reviewer -> conference -> number
			];
			Mail::send('email.conference.reviewer_forgot_password', $data, function($message) use ($to) {
				$message -> to($to['email'], $to['name']) -> subject('Online Submission and Review System - Reviewer Password Changed Successfully');
			});
			return back() -> with('success', 'A password reset email has been sent!');
		} else return back() -> with('danger', 'That email does not exist!');
	}

    protected function guard() {
		return \Auth::guard('reviewer');
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
		return redirect('conference/reviewer/login/'.$conference -> number);
	}
}
