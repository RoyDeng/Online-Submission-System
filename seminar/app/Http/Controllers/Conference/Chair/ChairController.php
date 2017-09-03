<?php
namespace App\Http\Controllers\Conference\Chair;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Hash;
use Mail;
use App\Conference\Conference;
use App\Conference\ConferenceType;
use App\Conference\Topic;
use App\Conference\Editor;
use App\Conference\Manuscript;
use App\Conference\Invitation;
use App\Conference\Review;
use App\Conference\FinalDecision;
use App\Conference\Revision;
use App\Conference\ReInvitation;
use App\Conference\ReReview;

class ChairController extends Controller {
    public function Conference() {
        $chair = Auth::user();
        $conference = Conference::find($chair -> conference_id);
        $conference_type_abstract = ConferenceType::where('submission_type_id', 1) -> where('conference_id', $chair -> conference_id) -> first();
        $conference_type_extended_abstract = ConferenceType::where('submission_type_id', 2) -> where('conference_id', $chair -> conference_id) -> first();
        $conference_type_full_paper = ConferenceType::where('submission_type_id', 3) -> where('conference_id', $chair -> conference_id) -> first();
        return view('conference.chair.conference', ['conference' => $conference, 'conference_type_abstract' => $conference_type_abstract, 'conference_type_extended_abstract' => $conference_type_extended_abstract, 'conference_type_full_paper' => $conference_type_full_paper]);
    }

    public function EditConference(Request $request) {
        $chair = Auth::user();
        $conference = Conference::find($chair -> conference_id);
        $deadline = $conference -> exist_deadline;
        if ($deadline < $request -> abstract_submission_deadline || $deadline < $request -> extended_abstract_submission_deadline || $deadline < $request -> full_paper_submission_deadline) return back() -> with('warn', 'The daedline is not valid!');
        else {
            $conference -> title = $request -> title;
            $conference -> modified_time = Carbon::now() -> format('Y-m-d H:i:s');
            $conference -> save();
            $conference_type_abstract = ConferenceType::where('submission_type_id', 1) -> where('conference_id', $chair -> conference_id) -> first();
            if ($request -> has('abstract')) $conference_type_abstract -> status = 1;
            else $conference_type_abstract -> status = 0;
            $conference_type_abstract -> submission_deadline = $request -> abstract_submission_deadline;
            $conference_type_abstract -> save();
            $conference_type_extended_abstract = ConferenceType::where('submission_type_id', 2) -> where('conference_id', $chair -> conference_id) -> first();
            if ($request -> has('extended_abstract')) $conference_type_extended_abstract -> status = 1;
            else $conference_type_extended_abstract -> status = 0;
            $conference_type_extended_abstract -> submission_deadline = $request -> extended_abstract_submission_deadline;
            $conference_type_extended_abstract -> save();
            $conference_type_full_paper = ConferenceType::where('submission_type_id', 3) -> where('conference_id', $chair -> conference_id) -> first();
            if ($request -> has('full_paper')) $conference_type_full_paper -> status = 1;
            else $conference_type_full_paper -> status = 0;
            $conference_type_full_paper -> submission_deadline = $request -> full_paper_submission_deadline;
            $conference_type_full_paper -> save();
            return back() -> with('success', 'You have successfully updated the conference!');
        }
    }

    public function Topics() {
        $chair = Auth::user();
        $conference = Conference::find($chair -> conference_id);
        $topics = Topic::where('conference_id', $conference -> id) -> orderBy('added_time', 'desc') -> get();
        return view('conference.chair.topics', ['conference' => $conference, 'topics' => $topics]);
    }

    public function CreateTopic(Request $request) {
        $chair = Auth::user();
        $conference = Conference::find($chair -> conference_id);
        $topic = new Topic;
        $topic -> conference_id = $conference -> id;
        $topic -> title = $request -> title;
        $topic -> number = $conference -> number.'-'.str_random(5);
        $topic -> added_time = Carbon::now() -> format('Y-m-d H:i:s');
        $topic -> save();
        return back() -> with('success', 'You have successfully created a topic!');
    }

    public function GetTopic(Request $request) {
		if ($request -> ajax()) {
            $topic = Topic::find($request -> id);
			return response() -> json($topic);
		}
	}

    public function EditTopic(Request $request) {
        $topic = Topic::find($request -> id);
        $topic -> title = $request -> title;
        $topic -> modified_time = Carbon::now() -> format('Y-m-d H:i:s');
        $topic -> save();
        return back() -> with('success', 'You have successfully updated the topic!');
    }

    public function CloseTopic(Request $request) {
        $topic = Topic::find($request -> id);
        $topic -> status = 0;
        $topic -> save();
        return back() -> with('success', 'You have successfully closed the topic!');
    }

    public function OpenTopic(Request $request) {
        $topic = Topic::find($request -> id);
        $topic -> status = 1;
        $topic -> save();
        return back() -> with('success', 'You have successfully opened the topic');
    }

    public function Editors($number) {
        $chair = Auth::user();
        $topic = Topic::where('number', $number) -> first();
        if ($topic -> conference -> id == $chair -> conference_id) {
            $editors = Editor::where('topic_id', $topic -> id) -> get();
            return view('conference.chair.editors', ['topic' => $topic, 'editors'=> $editors]);
        } else return redirect() -> action('Conference\Chair\ChairController@Topics');
    }
    
    public function CreateEditor(Request $request) {
        $email = Editor::where('email', $request -> email) -> where('topic_id', $request -> topic_id) -> count();
        if ($email > 0) return back() -> with('danger', 'Email has already been registered!');
        else {
            $editor = new Editor;
            $editor -> topic_id = $request -> topic_id;
            $editor -> title = $request -> title;
            $editor -> email = $request -> email;
            $editor -> password = bcrypt($request -> password);
            $editor -> firstname = $request -> firstname;
            $editor -> middlename = $request -> middlename;
            $editor -> lastname = $request -> lastname;
            $editor -> tel = $request -> tel;
            $editor -> institution = $request -> institution;
            $editor -> country = $request -> country;
            $editor -> added_time = Carbon::now() -> format('Y-m-d H:i:s');
            $editor -> save();
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
                'conference' => $editor -> topic -> conference -> title,
                'topic' => $editor -> topic -> title,
                'number' => $editor -> topic -> number
            ];
            Mail::send('email.create_editor', $data, function($message) use ($to) {
                $message -> to($to['email'], $to['name']) -> subject('Online Submission System - Editor Registration Successful');
            });
            return back() -> with('success', 'You have successfully created a editor!');
        }
    }

    public function GetEditor(Request $request) {
		if ($request -> ajax()) {
            $editor = Editor::find($request -> id);
			return response() -> json($editor);
		}
	}

    public function SuspendEditor(Request $request) {
        $editor = Editor::find($request -> id);
        $editor -> status = 0;
        $editor -> save();
        return back() -> with('success', 'You have successfully suspended the chair!');
    }

    public function ResumeEditor(Request $request) {
        $editor = Editor::find($request -> id);
        $editor -> status = 1;
        $editor -> save();
        return back() -> with('success', 'You have successfully resumed the chair');
    }

    public function Manuscripts() {
        $chair = Auth::user();
        $conference = Conference::find($chair -> conference_id);
        $topics = Topic::where('conference_id', $conference -> id) -> get();
        $manuscripts = Manuscript::whereIn('topic_id', $topics -> pluck('id') -> toArray()) -> get();
        return view('conference.chair.manuscripts', ['conference' => $conference, 'manuscripts' => $manuscripts]);
    }

    public function ReceivedDecision(Request $request) {
        $chair = Auth::user();
        $manuscript = Manuscript::where('number', $request -> number) -> first();
        if ($manuscript -> topic -> conference -> id == $chair -> conference_id) {
            $invitations = Invitation::where('manuscript_id', $manuscript -> id) -> get();
            $reviews = Review::whereIn('invitation_id', $invitations -> pluck('id') -> toArray()) -> get();
            return view('conference.chair.received_decision', ['manuscript' => $manuscript, 'reviews' => $reviews]);
        } else return redirect() -> action('Conference\Chair\ChairController@Manuscripts');
    }

    public function GetReview(Request $request) {
		if ($request -> ajax()) {
            $review = Review::with('review_file') -> where('id', $request -> id) -> first();
			return response() -> json($review);
		}
    }

    public function MakeDecision(Request $request) {
        $chair = Auth::user();
        $decision = new FinalDecision;
        $decision -> manuscript_id = $request -> manuscript_id;
        $decision -> chair_id = $chair -> id;
        $decision -> status = $request -> status;
        $decision -> comment = $request -> comment;
        $decision -> added_time = Carbon::now() -> format('Y-m-d H:i:s');
        $decision -> save();
        //針對搞件審閱情形寄送電子郵件，狀態為1表示通過，為0表示退件，其他表示需要修訂
        if ($request -> status == 1) {
            $to = [
                'email' => $decision -> manuscript -> author -> email,
                'name' => $decision -> manuscript -> author -> firstname.' '.$decision -> manuscript -> author -> middlename.' '.$decision -> manuscript -> author -> lastname
            ];
            $data = [
                'author_title' => $decision -> manuscript -> author -> title,
                'author_firstname' => $decision -> manuscript -> author -> firstname,
                'author_middlename' => $decision -> manuscript -> author -> middlename,
                'author_lastname' => $decision -> manuscript -> author -> lastname,
                'manuscript_number' => $decision -> manuscript -> number,
                'manuscript_title' => $decision -> manuscript -> title,
                'conference' => $decision -> manuscript -> topic -> conference -> title,
                'chair_title' => $decision -> chair -> title,
                'chair_firstname' => $decision -> chair -> firstname,
                'chair_middlename' => $decision -> chair -> middlename,
                'chair_lastname' => $decision -> chair -> lastname
            ];
            Mail::send('email.pass_manuscript', $data, function($message) use ($to) {
                $message -> to($to['email'], $to['name']) -> subject('Online Submission System - Notification about Passing Manuscript');
            });
        } else if ($request -> status == 0) {
            $to = [
                'email' => $decision -> manuscript -> author -> email,
                'name' => $decision -> manuscript -> author -> firstname.' '.$decision -> manuscript -> author -> middlename.' '.$decision -> manuscript -> author -> lastname
            ];
            $data = [
                'author_title' => $decision -> manuscript -> author -> title,
                'author_firstname' => $decision -> manuscript -> author -> firstname,
                'author_middlename' => $decision -> manuscript -> author -> middlename,
                'author_lastname' => $decision -> manuscript -> author -> lastname,
                'manuscript_number' => $decision -> manuscript -> number,
                'manuscript_title' => $decision -> manuscript -> title,
                'conference' => $decision -> manuscript -> topic -> conference -> title,
                'chair_title' => $decision -> chair -> title,
                'chair_firstname' => $decision -> chair -> firstname,
                'chair_middlename' => $decision -> chair -> middlename,
                'chair_lastname' => $decision -> chair -> lastname
            ];
            Mail::send('email.reject_manuscript', $data, function($message) use ($to) {
                $message -> to($to['email'], $to['name']) -> subject('Online Submission System - Notification about Rejecting Manuscript');
            });
        } else {
            $deadline = FinalDecision::find($decision -> id) -> manuscript -> topic -> conference -> exist_deadline;
            if ($deadline < $request -> deadline) return back() -> with('warn', 'The daedline is not valid!');
            else {
                $revision = new Revision;
                $revision -> final_decision_id = $decision -> id;
                $revision -> comment = $request -> revision_comment;
                $revision -> deadline = $request -> deadline;
                $revision -> added_time = Carbon::now() -> format('Y-m-d H:i:s');
                $revision -> save();
                $to = [
                    'email' => $decision -> manuscript -> author -> email,
                    'name' => $decision -> manuscript -> author -> firstname.' '.$decision -> manuscript -> author -> middlename.' '.$decision -> manuscript -> author -> lastname
                ];
                $data = [
                    'author_title' => $decision -> manuscript -> author -> title,
                    'author_firstname' => $decision -> manuscript -> author -> firstname,
                    'author_middlename' => $decision -> manuscript -> author -> middlename,
                    'author_lastname' => $decision -> manuscript -> author -> lastname,
                    'manuscript_number' => $decision -> manuscript -> number,
                    'manuscript_title' => $decision -> manuscript -> title,
                    'conference' => $decision -> manuscript -> topic -> conference -> title,
                    'deadline' => $revision -> deadline,
                    'chair_title' => $decision -> chair -> title,
                    'chair_firstname' => $decision -> chair -> firstname,
                    'chair_middlename' => $decision -> chair -> middlename,
                    'chair_lastname' => $decision -> chair -> lastname
                ];
                Mail::send('email.manuscript_need_revision', $data, function($message) use ($to) {
                    $message -> to($to['email'], $to['name']) -> subject('Online Submission System - Notification about Revising Manuscript');
                });
            }
        }
        return view('conference.chair.upload_success');
    }

    public function Revisions(Request $request) {
        $chair = Auth::user();
        $manuscript = Manuscript::where('number', $request -> number) -> first();
        if ($manuscript -> topic -> conference -> id == $chair -> conference_id) {
            $final_decision = FinalDecision::where('manuscript_id', $manuscript -> id) -> first();
            $revisions = Revision::where('final_decision_id', $final_decision -> id) -> get();
            return view('conference.chair.revisions', ['manuscript' => $manuscript, 'revisions' => $revisions]);
        } else return redirect() -> action('Conference\Chair\ChairController@Manuscripts');
    }

    public function ReceivedReReviews(Request $request) {
        $chair = Auth::user();
        $revision = Revision::find($request -> id);
        if ($revision -> final_decision -> manuscript -> topic -> conference_id == $chair -> conference_id) return view('conference.chair.received_re_reviews', ['revision' => $revision]);
        else return redirect() -> action('Conference\Chair\ChairController@Manuscripts');
    }

    public function GetDecision(Request $request) {
		if ($request -> ajax()) {
            $decision = FinalDecision::find($request -> id);
			return response() -> json($decision);
        }
    }

    public function GetReReview(Request $request) {
		if ($request -> ajax()) {
            $review = ReReview::with('re_review_file') -> where('id', $request -> id) -> first();
			return response() -> json($review);
        }
    }

    public function GetReReply(Request $request) {
		if ($request -> ajax()) {
            $invitation = ReInvitation::find($request -> id);
			return response() -> json($invitation);
		}
    }

    public function MakeReDecision(Request $request) {
        $chair = Auth::user();
        $decision = FinalDecision::where('manuscript_id', $request -> manuscript_id) -> first();
        $decision -> chair_id = $chair -> id;
        $decision -> status = $request -> status;
        $decision -> comment = $request -> comment;
        $decision -> added_time = Carbon::now() -> format('Y-m-d H:i:s');
        $decision -> save();
        $revision = Revision::where('final_decision_id', $decision -> id) -> first();
        $revision -> status = $request -> status;
        $revision -> modified_time = Carbon::now() -> format('Y-m-d H:i:s');
        $revision -> save();
        if ($request -> status == 1) {
            $to = [
                'email' => $decision -> manuscript -> author -> email,
                'name' => $decision -> manuscript -> author -> firstname.' '.$decision -> manuscript -> author -> middlename.' '.$decision -> manuscript -> author -> lastname
            ];
            $data = [
                'author_title' => $decision -> manuscript -> author -> title,
                'author_firstname' => $decision -> manuscript -> author -> firstname,
                'author_middlename' => $decision -> manuscript -> author -> middlename,
                'author_lastname' => $decision -> manuscript -> author -> lastname,
                'manuscript_number' => $decision -> manuscript -> number,
                'manuscript_title' => $decision -> manuscript -> title,
                'conference' => $decision -> manuscript -> topic -> conference -> title,
                'chair_title' => $decision -> chair -> title,
                'chair_firstname' => $decision -> chair -> firstname,
                'chair_middlename' => $decision -> chair -> middlename,
                'chair_lastname' => $decision -> chair -> lastname
            ];
            Mail::send('email.pass_revised_manuscript', $data, function($message) use ($to) {
                $message -> to($to['email'], $to['name']) -> subject('Online Submission System - Notification about Passing Revised Manuscript');
            });
        } else if ($request -> status == 0) {
            $to = [
                'email' => $decision -> manuscript -> author -> email,
                'name' => $decision -> manuscript -> author -> firstname.' '.$decision -> manuscript -> author -> middlename.' '.$decision -> manuscript -> author -> lastname
            ];
            $data = [
                'author_title' => $decision -> manuscript -> author -> title,
                'author_firstname' => $decision -> manuscript -> author -> firstname,
                'author_middlename' => $decision -> manuscript -> author -> middlename,
                'author_lastname' => $decision -> manuscript -> author -> lastname,
                'manuscript_number' => $decision -> manuscript -> number,
                'manuscript_title' => $decision -> manuscript -> title,
                'conference' => $decision -> manuscript -> topic -> conference -> title,
                'chair_title' => $decision -> chair -> title,
                'chair_firstname' => $decision -> chair -> firstname,
                'chair_middlename' => $decision -> chair -> middlename,
                'chair_lastname' => $decision -> chair -> lastname
            ];
            Mail::send('email.reject_revised_manuscript', $data, function($message) use ($to) {
                $message -> to($to['email'], $to['name']) -> subject('Online Submission System - Notification about Rejecting Revised Manuscript');
            });
        } else {
            $deadline = FinalDecision::find($decision -> id) -> manuscript -> topic -> conference -> exist_deadline;
            if ($deadline < $request -> deadline) return back() -> with('warn', 'The daedline is not valid!');
            else {
                $revision = new Revision;
                $revision -> final_decision_id = $decision -> id;
                $revision -> comment = $request -> revision_comment;
                $revision -> deadline = $request -> deadline;
                $revision -> added_time = Carbon::now() -> format('Y-m-d H:i:s');
                $revision -> save();
                $to = [
                    'email' => $decision -> manuscript -> author -> email,
                    'name' => $decision -> manuscript -> author -> firstname.' '.$decision -> manuscript -> author -> middlename.' '.$decision -> manuscript -> author -> lastname
                ];
                $data = [
                    'author_title' => $decision -> manuscript -> author -> title,
                    'author_firstname' => $decision -> manuscript -> author -> firstname,
                    'author_middlename' => $decision -> manuscript -> author -> middlename,
                    'author_lastname' => $decision -> manuscript -> author -> lastname,
                    'manuscript_number' => $decision -> manuscript -> number,
                    'manuscript_title' => $decision -> manuscript -> title,
                    'conference' => $decision -> manuscript -> topic -> conference -> title,
                    'deadline' => $revision -> deadline,
                    'chair_title' => $decision -> chair -> title,
                    'chair_firstname' => $decision -> chair -> firstname,
                    'chair_middlename' => $decision -> chair -> middlename,
                    'chair_lastname' => $decision -> chair -> lastname
                ];
                Mail::send('email.revised_manuscript_need_revision', $data, function($message) use ($to) {
                    $message -> to($to['email'], $to['name']) -> subject('Online Submission System - Notification about Revising Revised Manuscript');
                });
            }
        }
        return view('conference.chair.upload_success');
    }

    public function Profile() {
        return view('conference.chair.profile');
    }

    public function EditProfile(Request $request) {
        $chair = Auth::user();
        $chair -> title = $request -> title;
        $chair -> firstname = $request -> firstname;
        $chair -> middlename = $request -> middlename;
        $chair -> lastname = $request -> lastname;
        $chair -> tel = $request -> tel;
        $chair -> institution = $request -> institution;
        $chair -> country = $request -> country;
        $chair -> modified_time = Carbon::now() -> format('Y-m-d H:i:s');
        $chair -> save();
        return back() -> with('success', 'You have successfully updated your profile!');
    }

    public function ChangePassword(Request $request) {
        $chair = Auth::user();
        if ($request -> confirm_password == $request -> new_password) {
            if (!Hash::check($request -> current_password, $chair -> password)) return back() -> with('danger', 'Your current password is incorrect!');
            else {
                $chair -> password = bcrypt($request -> new_password);
                $chair -> save();
                return back() -> with('success', 'You have successfully changed your password!');
            }
        } else return back() -> with('warn', 'Your password and confirm password do not match!');
    }
}
