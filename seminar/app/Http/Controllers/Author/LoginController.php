<?php
namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Hash;
use Mail;
use App\Conference\Conference;
use App\Author;
use App\Maintainer;

class LoginController extends Controller {
	use AuthenticatesUsers;

    public function __construct() {
        $this -> middleware('guest') -> except('logout');
    }

    function LoginPage($number) {
		$conference = Conference::where('number', $number) -> first();
		$maintainer = Maintainer::find(1);
		if ($conference -> exist_deadline > date('Y-m-d') && $conference -> status == 1) return view('author.conference.login', ['conference' => $conference, 'maintainer' => $maintainer]);
		else return view('expire_page');
	}

	function ForgotPasswordPage() {
		return view('author.forgot_password');
	}

	public function ResetPassword(Request $request) {
		$count = Author::where('email', $request -> email) -> count();
		if ($count > 0) {
			$author = Author::where('email', $request -> email) -> first();
			$new_password = str_random(6);
			$author -> password = bcrypt($new_password);
			$author -> save();
			$to = [
				'email' => $request -> email,
				'name' => $author -> firstname.' '.$author -> middlename.' '.$author -> lastname
			];
			$data = [
				'title' => $author -> title,
				'firstname' => $author -> firstname,
				'middlename' => $author -> middlename,
				'lastname' => $author -> lastname,
				'password' => $new_password
			];
			Mail::send('email.author_forgot_password', $data, function($message) use ($to) {
				$message -> to($to['email'], $to['name']) -> subject('Online Submission and Review System - Author Password Changed Successfully');
			});
			return back() -> with('success', 'A password reset email has been sent!');
		} else return back() -> with('danger', 'That email does not exist!');
	}

	//作者註冊頁面
	function RegisterPage($number) {
		$conference = Conference::where('number', $number) -> first();
		return view('author.register', ['conference' => $conference]);
	}

	//作者註冊操作
	function register(Request $request) {
		$email = Author::where('email', $request -> email) -> count();
		if ($email > 0) return back() -> with('danger', 'Email has already been registered!');
		else {
			if ($request -> confirm_password != $request -> password) return back() -> with('warn', 'Your password and confirm password do not match!');
			else {
				$author = new Author;
				$author -> title = $request -> title;
				$author -> email = $request -> email;
				$author -> password = bcrypt($request -> password);
				$author -> firstname = $request -> firstname;
				$author -> middlename = $request -> middlename;
				$author -> lastname = $request -> lastname;
				$author -> tel = $request -> tel;
				$author -> institution = $request -> institution;
				$author -> country = $request -> country;
				$author -> added_time = Carbon::now() -> format('Y-m-d H:i:s');
				$author -> save();
				$to = [
					'email' => $request -> email,
					'name' => $request -> firstname.$request -> middlename.$request -> lastname
				];
				$data = [
					'title' => $request -> title,
					'firstname' => $request -> firstname,
					'middlename' => $request -> middlename,
					'lastname' => $request -> lastname
				];
				Mail::send('email.create_author', $data, function($message) use ($to) {
					$message -> to($to['email'], $to['name']) -> subject('Online Submission and Review System - Author Registration Successful');
				});
				return back() -> with('success', 'You have successfully registered!');
			}
		}
	}

    protected function guard() {
		return \Auth::guard('author');
	}

	protected function credentials(Request $request) {
		return array_merge($request -> only($this -> username(), 'password'), ['status' => 1]);
	}

	protected function authenticated(Request $request) {
		return redirect('author/conference/'.$request -> number);
	}

	public function logout(Request $request) {
		$conference = Conference::where('number', $request -> number) -> first();
		$this -> guard() -> logout();
		$request -> session() -> flush();
		$request -> session() -> regenerate();
		return redirect('conference/author/login/'.$conference -> number);
	}
}
