<?php
namespace App\Http\Controllers\Conference\Editor;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Hash;
use Mail;
use App\Conference\Topic;
use App\Conference\Editor;

class LoginController extends Controller {
    use AuthenticatesUsers;
    protected $redirectTo = 'conference/editor';

    public function __construct() {
        $this -> middleware('guest') -> except('logout');
    }

    function LoginPage($number) {
		$topic = Topic::where('number', $number) -> first();
		if ($topic -> conference -> exist_deadline > date('Y-m-d') && $topic -> conference -> status == 1 && $topic -> status == 1) return view('conference.editor.login', ['topic' => $topic]);
		else return view('expire_page');
	}

	function ForgotPasswordPage($number) {
		$topic = Topic::where('number', $number) -> first();
		if ($topic -> conference -> exist_deadline > date('Y-m-d') && $topic -> conference -> status == 1 && $topic -> status == 1) return view('conference.editor.forgot_password', ['topic' => $topic]);
		else return view('expire_page');
	}

	public function ResetPassword(Request $request) {
		$count = Editor::where('email', $request -> email) -> where('topic_id', $request -> topic_id) -> count();
		if ($count > 0) {
			$editor = Editor::where('email', $request -> email) -> where('topic_id', $request -> topic_id) -> first();
			$new_password = str_random(6);
			$editor -> password = bcrypt($new_password);
			$editor -> save();
			$to = [
				'email' => $request -> email,
				'name' => $editor -> firstname.' '.$editor -> middlename.' '.$editor -> lastname
			];
			$data = [
				'title' => $editor -> title,
				'firstname' => $editor -> firstname,
				'middlename' => $editor -> middlename,
				'lastname' => $editor -> lastname,
				'password' => $new_password,
				'number' => $editor -> topic -> conference -> number
			];
			Mail::send('email.editor_forgot_password', $data, function($message) use ($to) {
				$message -> to($to['email'], $to['name']) -> subject('Online Submission System - Editor Password Changed Successfully');
			});
			return back() -> with('success', 'A password reset email has been sent!');
		} else return back() -> with('danger', 'That email does not exist!');
	}

    protected function guard() {
		return \Auth::guard('editor');
	}

	protected function credentials(Request $request) {
		$topic = Topic::where('number', $request -> number) -> first();
		return array_merge($request -> only($this -> username(), 'password'), ['topic_id' => $topic -> id, 'status' => 1]);
	}

    public function logout(Request $request) {
		$editor = Auth::user();
		$topic = Topic::find($editor -> topic_id);
		$this -> guard() -> logout();
		$request -> session() -> flush();
		$request -> session() -> regenerate();
		return redirect('conference/editor/login/'.$topic -> number);
	}
}
