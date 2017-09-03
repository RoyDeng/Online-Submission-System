<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Hash;
use Mail;
use App\Conference\Conference;
use App\Conference\SubmissionType;
use App\Conference\ConferenceType;
use App\Conference\Chair;
use App\Author;

class MaintainerController extends Controller {
    //研討會頁面
    public function Conferences() {
        //取得所有的研討會，並以加入時間排序(遞減)
        $conferences = Conference::orderBy('added_time', 'desc') -> get();
        //View方法的第一個參數會對應到resources/views資料夾內視圖檔案的名稱，第二個參數是一個能夠在視圖內取用的資料陣列
        return view('admin.conferences', ['conferences' => $conferences]);
    }

    //建立研討會
    public function CreateConference(Request $request) {
        $conference = new Conference;
        $conference -> title = $request -> title;
        $conference -> number = $request -> abbr.'-'.date("Y");
        $conference -> exist_deadline = $request -> deadline;
        $conference -> added_time = Carbon::now() -> format('Y-m-d H:i:s');
        $conference -> save();
        //取得所有的提交類型
        $submission_types = SubmissionType::all();
        //使用迴圈建立研討會所有的提交類型
        for ($i = 1; $i <= $submission_types -> count(); $i++) {
            $conference_type = new ConferenceType;
            $conference_type -> submission_type_id = $i;
            $conference_type -> conference_id = $conference -> id;
            $conference_type -> save();
        }
        return back() -> with('success', 'You have successfully created a conference!');
    }

    //取得研討會資料(AJAX)
    public function GetConference(Request $request) {
		if ($request -> ajax()) {
            $conference = Conference::find($request -> id);
			return response() -> json($conference);
		}
	}

    //編輯研討會
    public function EditConference(Request $request) {
        //取得主鍵符合id的研討會
        $conference = Conference::find($request -> id);
        $conference -> exist_deadline = $request -> deadline;
        $conference -> modified_time = Carbon::now() -> format('Y-m-d H:i:s');
        $conference -> save();
        return back() -> with('success', 'You have successfully updated the conference!');
    }

    //關閉研討會
    public function CloseConference(Request $request) {
        $conference = Conference::find($request -> id);
        //變更狀態為0
        $conference -> status = 0;
        $conference -> save();
        return back() -> with('success', 'You have successfully closed the conference!');
    }

    //開啟研討會
    public function OpenConference(Request $request) {
        $conference = Conference::find($request -> id);
        //變更狀態為1
        $conference -> status = 1;
        $conference -> save();
        return back() -> with('success', 'You have successfully opened the conference！');
    }

    //主席頁面
    public function Chairs($number) {
        //取得條件符合Number的研討會
        $conference = Conference::where('number', $number) -> first();
        //取得條件符合研討會主鍵的主席
        $chairs = Chair::where('conference_id', $conference -> id) -> get();
        return view('admin.chairs', ['conference' => $conference, 'chairs'=> $chairs]);
    }

    public function CreateChair(Request $request) {
        //計算條件符合Email和研討會主鍵的主席數量
        $email = Chair::where('email', $request -> email) -> where('conference_id', $request -> conference_id) -> count();
        //數量大於0，表示此研討會已存在具此Email的主席
        if ($email > 0) return back() -> with('danger', 'Email has already been registered!');
        else {
            $chair = new Chair;
            $chair -> conference_id = $request -> conference_id;
            $chair -> title = $request -> title;
            $chair -> email = $request -> email;
            $chair -> password = bcrypt($request -> password);
            $chair -> firstname = $request -> firstname;
            $chair -> middlename = $request -> middlename;
            $chair -> lastname = $request -> lastname;
            $chair -> tel = $request -> tel;
            $chair -> institution = $request -> institution;
            $chair -> country = $request -> country;
            $chair -> added_time = Carbon::now() -> format('Y-m-d H:i:s');
            $chair -> save();
            $to = [
                'email' => $request -> email,
                'name' => $request -> firstname.' '.$request -> middlename.' '.$request -> lastname
            ];
            $data = [
                'title' => $request -> title,
                'firstname' => $request -> firstname,
                'middlename' => $request -> middlename,
                'lastname' => $request -> lastname,
                'password' => $request -> password,
                'conference' => $chair -> conference -> title,
                'number' => $chair -> conference -> number
            ];
            Mail::send('email.create_chair', $data, function($message) use ($to) {
                $message -> to($to['email'], $to['name']) -> subject('Online Submission System - Chair Registration Successful');
            });
            return back() -> with('success', 'You have successfully created a chair!');
        }
    }

    public function GetChair(Request $request) {
		if ($request -> ajax()) {
            $chair = Chair::find($request -> id);
			return response() -> json($chair);
		}
	}

    public function SuspendChair(Request $request) {
        $chair = Chair::find($request -> id);
        $chair -> status = 0;
        $chair -> save();
        return back() -> with('success', 'You have successfully suspended the chair!');
    }

    public function ResumeChair(Request $request) {
        $chair = Chair::find($request -> id);
        $chair -> status = 1;
        $chair -> save();
        return back() -> with('success', 'You have successfully resumed the chair');
    }

    public function Authors() {
        $authors = Author::orderBy('added_time', 'desc') -> get();
        return view('admin.authors', ['authors'=> $authors]);
    }

    public function GetAuthor(Request $request) {
		if ($request -> ajax()) {
            $author = Author::find($request -> id);
			return response() -> json($author);
		}
	}

    public function SuspendAuthor(Request $request) {
        $author = Author::find($request -> id);
        $author -> status = 0;
        $author -> save();
        return back() -> with('success', 'You have successfully suspend the author!');
    }

    public function ResumeAuthor(Request $request) {
        $author = Author::find($request -> id);
        $author -> status = 1;
        $author -> save();
        return back() -> with('success', 'You have successfully resumed the author!');
    }

    //個人資料頁面
    public function Profile() {
        return view('admin.profile');
    }

    //變更密碼操作
    public function ChangePassword(Request $request) {
        //透過Auth來存取認證的管理員資料
        $maintainer = Auth::user();
        //確認密碼必須符合新密碼
        if ($request -> confirm_password == $request -> new_password) {
            //目前密碼必須符合管理員的密碼
            if (!Hash::check($request -> current_password, $maintainer -> password)) return back() -> with('danger', 'Your current password is incorrect!');
            else {
                $maintainer -> password =  bcrypt($request -> new_password);
                $maintainer -> save();
                return back() -> with('success', 'You have successfully changed your password!');
            }
        } else return back() -> with('warn', 'Your password and confirm password do not match!');
    }

    //聯絡我們頁面(如研討會已過期，登入頁面會自動導向此頁)
    public function ContactPage() {
        return view('contact');
    }

    //聯絡我們操作(寄信給教授)
    public function Contact(Request $request) {
        $to = [
            'email' => 'hcweng@cycu.edu.tw',
            'name' => 'Huei Chu Weng'
        ];
        $data = [
            'title' => $request -> title,
            'firstname' => $request -> firstname,
            'middlename' => $request -> middlename,
            'lastname' => $request -> lastname,
            'email' => $request -> email,
            'tel' => $request -> tel,
            'institution' => $request -> institution,
            'country' => $request -> country,
            'content' => $request -> content
        ];
        Mail::send('email.contact_us', $data, function($message) use ($to) {
            $message -> to($to['email'], $to['name']) -> subject('Online Submission System');
        });
        return back() -> with('success', 'You have successfully sent your problem!');
    }
}
