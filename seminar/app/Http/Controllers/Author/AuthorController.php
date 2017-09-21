<?php
namespace App\Http\Controllers\Author;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use DB;
use Hash;
use Mail;
use App\Conference\Conference;
use App\Conference\Topic;
use App\Conference\ConferenceType;
use App\Conference\Manuscript;
use App\Conference\File;
use App\Conference\Invitation;
use App\Conference\FinalDecision;
use App\Conference\Revision;
use App\Conference\Review;
use App\Conference\RevisedManuscript;
use App\Conference\RevisedFile;
use App\Conference\Editor;
use App\Conference\ReReview;

class AuthorController extends Controller {
    //研討會頁面
    public function Topics($number) {
        $conference = Conference::where('number', $number) -> first();
        return view('author.conference.topics', ['conference' => $conference]);
    }
    
    //上傳稿件頁面
    public function UploadManuscriptPage($number) {
        $topic = Topic::where('number', $number) -> first();
        $conference = $topic -> conference;
        //若條件符合URL的參數Number的題目狀態為開啟(1)時，才能讀取上傳稿件的頁面→避免作者透過URL參數操作已經關閉的題目
        if ($topic -> status == 1) return view('author.conference.upload_manuscript', ['conference' => $conference, 'topic' => $topic]);
        //重新導向至控制器的動作(AuthorController的Conferences方法)
        else return redirect() -> action('Author\AuthorController@Topics', ['number' => $conference -> number]);
    }

    //上傳稿件操作
    public function UploadManuscript(Request $request) {
        $conference = Topic::find($request -> topic_id) -> conference;
        //取得作者所選提交類型的截止日期
        $deadline = ConferenceType::where('submission_type_id', $request -> submission_type_id) -> where('conference_id', $conference -> id) -> first() -> submission_deadline;
        //如果提交類型的截止日期已經小於今日，表示已過期
        if ($deadline < date('Y-m-d')) return back() -> with('warn', "The submission type is expired!");
        else {
            if ($request -> hasFile('files')) {
                $author = Auth::user();
                $topic = Topic::find($request -> topic_id);
                $conference = $topic -> conference;
                $manuscript = new Manuscript;
                $manuscript -> topic_id = $request -> topic_id;
                $manuscript -> submission_type_id = $request -> submission_type_id;
                $manuscript -> author_id = $author -> id;
                $conference -> count = $conference -> count + 1;
                $conference -> save();
                $count = $conference -> count;
                $manuscript -> number = $topic -> number.'-'.$count;
                $manuscript -> type = $request -> type;
                $manuscript -> title = $request -> title;
                $manuscript -> abstract = $request -> abstract;
                $manuscript -> added_time = Carbon::now() -> format('Y-m-d H:i:s');
                $manuscript -> save();
                //取得所有檔案
                $files = $request -> file('files');
                //使用迴圈上傳所有檔案
                foreach ($files as $file) {
                    //更改檔案名稱(原黨名+字串(時間)+長度5隨機字串)→為避免相同檔名的覆蓋問題
                    $url = $file -> getClientOriginalName().'-'.strval(time()).str_random(5).'.'.$file -> getClientOriginalExtension();
                    //移動上傳的檔案，第一個參數儲存位置(public/upload)，第二個參數是檔案名稱
                    $file -> move(public_path().'/upload/', $url);
                    $new_file = new File;
                    $new_file -> manuscript_id = $manuscript -> id;
                    $new_file -> name = $file -> getClientOriginalName();
                    $new_file -> url = $url;
                    $new_file -> type = $file -> getClientOriginalExtension();
                    $new_file -> save();
                }
                //取得條件符合題目主鍵的所有編者
                $editors = Editor::where('topic_id', $request -> topic_id) -> get();
                //使用迴圈寄信給所有編者
                foreach ($editors as $editor) {
                    $to = [
                        'email' => $editor -> email,
                        'name' => $editor -> firstname.' '.$editor -> middlename.' '.$editor -> lastname
                    ];
                    $data = [
                        'editor_title' => $editor -> title,
                        'editor_firstname' => $editor -> firstname,
                        'editor_middlename' => $editor -> middlename,
                        'editor_lastname' => $editor -> lastname,
                        'manuscript_number' => $manuscript -> number,
                        'manuscript_title' => $manuscript -> title,
                        'conference' => $manuscript -> topic -> conference -> title,
                        'number' => $manuscript -> topic -> number,
                        'author_title' => $manuscript -> author -> title,
                        'author_firstname' => $manuscript -> author -> firstname,
                        'author_middlename' => $manuscript -> author -> middlename,
                        'author_lastname' => $manuscript -> author -> lastname
                    ];
                    Mail::send('email.conference.upload_manuscript', $data, function($message) use ($to) {
                        $message -> to($to['email'], $to['name']) -> subject('Online Submission and Review System - Notification about Receiving Manuscript');
                    });
                }
                return view('author.conference.upload_success', ['conference' => $conference]);
            } else return back() -> with('danger', "You haven't selected any file to upload!");
        }
    }

    public function GetManuscript($id) {
        $manuscript = Manuscript::find($id);
        return response() -> json($manuscript);
	}

    //稿件頁面
    public function Manuscripts($number) {
        $author = Auth::user();
        $conference = Conference::where('number', $number) -> first();
        $topics = Topic::where('conference_id', $conference -> id) -> get();
        $manuscripts = Manuscript::whereIn('topic_id', $topics -> pluck('id') -> toArray()) -> where('author_id', $author -> id) -> orderBy('added_time', 'desc') -> get();
        return view('author.conference.manuscripts', ['conference' => $conference, 'manuscripts' => $manuscripts]);
    }

    public function DeleteManuscript(Request $request) {
        $manuscript = Manuscript::find($request -> id);
        $files = File::where('manuscript_id', $manuscript -> id) -> get();
        foreach ($files as $file) $file -> delete();
        $manuscript -> delete();
        $editors = Editor::where('topic_id', $manuscript -> topic -> id) -> get();
        foreach ($editors as $editor) {
            $to = [
                'email' => $editor -> email,
                'name' => $editor -> firstname.' '.$editor -> middlename.' '.$editor -> lastname
            ];
            $data = [
                'editor_title' => $editor -> title,
                'editor_firstname' => $editor -> firstname,
                'editor_middlename' => $editor -> middlename,
                'editor_lastname' => $editor -> lastname,
                'manuscript_number' => $manuscript -> number,
                'manuscript_title' => $manuscript -> title,
                'conference' => $manuscript -> topic -> conference -> title,
                'number' => $manuscript -> topic -> number,
                'author_title' => $manuscript -> author -> title,
                'author_firstname' => $manuscript -> author -> firstname,
                'author_middlename' => $manuscript -> author -> middlename,
                'author_lastname' => $manuscript -> author -> lastname
            ];
            Mail::send('email.conference.delete_manuscript', $data, function($message) use ($to) {
                $message -> to($to['email'], $to['name']) -> subject('Online Submission and Review System - Notification about Deleting Manuscript');
            });
        }
        return back() -> with('success', 'You have successfully deleted the manuscript!');
    }

    //稿件資料頁面
    public function Manuscript(Request $request) {
        $author = Auth::user();
        $manuscript = Manuscript::where('number', $request -> number) -> where('author_id', $author -> id) -> first();
        $conference = $manuscript -> topic -> conference;
        $invitations = Invitation::where('manuscript_id', $manuscript -> id) -> where('status', 1) -> get();
        //取得條件符合稿件主鍵的所有最終決定(集合)
        $final_decisions = FinalDecision::where('manuscript_id', $manuscript -> id) -> get();
        //使用whereIn方法取得條件符合最終決定主鍵(集合)的所有修訂
        $revisions = Revision::whereIn('final_decision_id', $final_decisions -> pluck('id') -> toArray()) -> get();
        if ($manuscript -> author -> id == $author -> id) return view('author.conference.manuscript', ['conference' => $conference, 'manuscript' => $manuscript, 'invitations' => $invitations, 'revisions' => $revisions]);
        else return redirect() -> action('Author\AuthorController@Manuscripts', ['number' => $conference -> number]);
    }

    public function GetReview(Request $request) {
		if ($request -> ajax()) {
            $review = Review::with('review_file') -> where('id', $request -> id) -> first();
			return response() -> json($review);
		}
    }

    //上傳修訂稿件頁面
    public function UploadRevisionPage(Request $request) {
        $author = Auth::user();
        $revision = Revision::find($request -> id);
        $conference = $revision -> final_decision -> manuscript -> topic -> conference;
        if ($revision -> final_decision -> manuscript -> author -> id == $author -> id && $revision -> revised_manuscript == '') return view('author.conference.upload_revision', ['conference' => $conference, 'revision' => $revision]);
        else return redirect() -> action('Author\AuthorController@Manuscripts', ['number' => $conference -> number]);
    }

    //上傳修訂稿件操作
    public function UploadRevision(Request $request) {
        $deadline = Revision::find($request -> revision_id) -> deadline;
        if ($deadline < date('Y-m-d')) return back() -> with('warn', "The revision type is expired!");
        else {
            if ($request -> hasFile('files')) {
                $author = Auth::user();
                $revision = Revision::find($request -> revision_id);
                $conference = $revision -> final_decision -> manuscript -> topic -> conference;
                $manuscript = Manuscript::find($revision -> final_decision -> manuscript -> id);
                $manuscript -> title = $request -> title;
                $manuscript -> abstract = $request -> abstract;
                $manuscript -> save();
                $revised_manuscript = new RevisedManuscript;
                $count = $manuscript -> final_decision -> revision -> count();
                $revised_manuscript -> number = $manuscript -> number.'-R'.$count;
                $revised_manuscript -> revision_id = $request -> revision_id;
                $revised_manuscript -> added_time = Carbon::now() -> format('Y-m-d H:i:s');
                $revised_manuscript -> save();
                $files = $request -> file('files');
                foreach ($files as $file) {
                    $url = $file -> getClientOriginalName().'-'.strval(time()).str_random(5).'.'.$file -> getClientOriginalExtension();
                    $file -> move(public_path().'/upload/', $url);
                    $revised_file = new RevisedFile;
                    $revised_file -> revised_manuscript_id = $revised_manuscript -> id;
                    $revised_file -> name = $file -> getClientOriginalName();
                    $revised_file -> url = $url;
                    $revised_file -> type = $file -> getClientOriginalExtension();
                    $revised_file -> save();
                }
                $editors = Editor::where('topic_id', $manuscript -> topic -> id) -> get();
                foreach ($editors as $editor) {
                    $to = [
                        'email' => $editor -> email,
                        'name' => $editor -> firstname.' '.$editor -> middlename.' '.$editor -> lastname
                    ];
                    $data = [
                        'editor_title' => $editor -> title,
                        'editor_firstname' => $editor -> firstname,
                        'editor_middlename' => $editor -> middlename,
                        'editor_lastname' => $editor -> lastname,
                        'manuscript_number' => $revised_manuscript -> number,
                        'manuscript_title' => $manuscript -> title,
                        'conference' => $manuscript -> topic -> conference -> title,
                        'number' => $manuscript -> topic -> number,
                        'author_title' => $manuscript -> author -> title,
                        'author_firstname' => $manuscript -> author -> firstname,
                        'author_middlename' => $manuscript -> author -> middlename,
                        'author_lastname' => $manuscript -> author -> lastname
                    ];
                    Mail::send('email.conference.upload_revision_manuscript', $data, function($message) use ($to) {
                        $message -> to($to['email'], $to['name']) -> subject('Online Submission and Review System - Notification about Receiving Revised Manuscript');
                    });
                }
                return view('author.conference.upload_success', ['conference' => $conference]);
            } else return back() -> with('danger', "You haven't selected any file to upload!");
        }
    }

    //修訂資料頁面
    public function Revision(Request $request) {
        $author = Auth::user();
        $revision = Revision::find($request -> id);
        $conference = $revision -> final_decision -> manuscript -> topic -> conference;
        if ($revision -> final_decision -> manuscript -> author -> id == $author -> id) return view('author.conference.revision', ['conference' => $conference, 'revision' => $revision]);
        else return redirect() -> action('Author\AuthorController@Manuscripts', ['number' => $conference -> number]);
    }

    public function GetReReview(Request $request) {
        $review = ReReview::with('re_review_file') -> where('id', $request -> id) -> first();
        return response() -> json($review);
    }

    public function Profile($number) {
        $conference = Conference::where('number', $number) -> first();
        return view('author.conference.profile', ['conference' => $conference]);
    }

    //編輯個人資料
    public function EditProfile(Request $request) {
        $author = Auth::user();
        $author -> title = $request -> title;
        $author -> firstname = $request -> firstname;
        $author -> middlename = $request -> middlename;
        $author -> lastname = $request -> lastname;
        $author -> tel = $request -> tel;
        $author -> institution = $request -> institution;
        $author -> country = $request -> country;
        $author -> modified_time = Carbon::now() -> format('Y-m-d H:i:s');
        $author -> save();
        return back() -> with('success', 'You have successfully updated your profile!');
    }

    public function ChangePassword(Request $request) {
        $author = Auth::user();
        if ($request -> confirm_password == $request -> new_password) {
            if (!Hash::check($request -> current_password, $author -> password)) return back() -> with('danger', 'Your current password is incorrect!');
            else {
                $author -> password = bcrypt($request -> new_password);
                $author -> save();
                return back() -> with('success', 'You have successfully changed your password!');
            }
        } else return back() -> with('warn', 'Your password and confirm password do not match!');
    }
}
