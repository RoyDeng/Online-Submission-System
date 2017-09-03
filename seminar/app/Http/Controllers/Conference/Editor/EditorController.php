<?php
namespace App\Http\Controllers\Conference\Editor;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Hash;
use Mail;
use App\Conference\Topic;
use App\Conference\Manuscript;
use App\Conference\Reviewer;
use App\Conference\Review;
use App\Conference\Invitation;
use App\Conference\Decision;
use App\Conference\FinalDecision;
use App\Conference\Revision;
use App\Conference\RevisedManuscript;
use App\Conference\ReInvitation;
use App\Conference\ReReview;
use App\Conference\ReDecision;

class EditorController extends Controller {
    public function Manuscripts() {
        $editor = Auth::user();
        $topic = Topic::find($editor -> topic_id);
        $manuscripts = Manuscript::where('topic_id', $topic -> id) -> orderBy('added_time', 'desc') -> get();
        return view('conference.editor.manuscripts', ['topic' => $topic, 'manuscripts' => $manuscripts]);
    }

    public function SendInvitationPage(Request $request) {
        $editor = Auth::user();
        $topic = Topic::find($editor -> topic_id);
        if ($topic -> id == $editor -> topic_id) {
            $manuscript = Manuscript::where('number', $request -> number) -> first();
            $invitations = Invitation::where('manuscript_id', $manuscript -> id) -> get();
            $reviewers = Reviewer::where('conference_id', $topic -> conference -> id) -> get();
            return view('conference.editor.send_invitation', ['manuscript' => $manuscript, 'invitations' => $invitations, 'reviewers' => $reviewers]);
        } else return redirect() -> action('Conference\Editor\EditorController@Manuscripts');
    }

    public function SendInvitation(Request $request) {
        $editor = Auth::user();
        $count = Invitation::where('manuscript_id', $request -> manuscript_id) -> where('reviewer_id', $request -> reviewer_id) -> count();
        if ($count > 0) return back() -> with('danger', 'You have already sent a invitation to the reviewer!');
        else {
            $deadline = Manuscript::find($request -> manuscript_id) -> topic -> conference -> exist_deadline;
            if ($deadline < $request -> deadline) return back() -> with('warn', 'The daedline is not valid!');
            else {
                $invitation = new Invitation;
                $invitation -> manuscript_id = $request -> manuscript_id;
                $invitation -> reviewer_id = $request -> reviewer_id;
                $invitation -> editor_id = $editor -> id;
                $invitation -> deadline = $request -> deadline;
                $invitation -> added_time = Carbon::now() -> format('Y-m-d H:i:s');
                $invitation -> save();
                $to = [
                    'email' => $invitation -> reviewer -> email,
                    'name' => $invitation -> reviewer -> firstname.' '.$invitation -> reviewer -> middlename.' '.$invitation -> reviewer -> lastname
                ];
                $data = [
                    'reviewer_title' => $invitation -> reviewer -> title,
                    'reviewer_firstname' => $invitation -> reviewer -> firstname,
                    'reviewer_middlename' => $invitation -> reviewer -> middlename,
                    'reviewer_lastname' => $invitation -> reviewer -> lastname,
                    'manuscript_number' => $invitation -> manuscript -> number,
                    'manuscript_title' => $invitation -> manuscript -> title,
                    'conference' => $invitation -> manuscript -> topic -> conference -> title,
                    'deadline' => $invitation -> deadline,
                    'number' => $invitation -> manuscript -> topic -> conference -> number,
                    'editor_title' => $invitation -> editor -> title,
                    'editor_firstname' => $invitation -> editor -> firstname,
                    'editor_middlename' => $invitation -> editor -> middlename,
                    'editor_lastname' => $invitation -> editor -> lastname
                ];
                Mail::send('email.send_invitation', $data, function($message) use ($to)
                {
                    $message -> to($to['email'], $to['name']) -> subject('Online Submission System - Invitation to Review Manuscript');
                });
                return back() -> with('success', 'You have successfully sent a invitation!');
            }
        }
    }

    public function ReceivedReviews(Request $request) {
        $editor = Auth::user();
        $manuscript = Manuscript::where('number', $request -> number) -> first();
        if ($manuscript -> topic_id == $editor -> topic_id) {
            $invitations = Invitation::where('manuscript_id', $manuscript -> id) -> get();
            return view('conference.editor.received_reviews', ['manuscript' => $manuscript, 'invitations' => $invitations]);
        } else return redirect() -> action('Conference\Editor\EditorController@Manuscripts');
    }

    public function GetReview(Request $request) {
		if ($request -> ajax()) {
            $review = Review::with('review_file') -> where('id', $request -> id) -> first();
			return response() -> json($review);
		}
    }

    public function GetReply(Request $request) {
		if ($request -> ajax()) {
            $invitation = Invitation::find($request -> id);
			return response() -> json($invitation);
		}
    }

    public function MakeDecision(Request $request) {
        $editor = Auth::user();
        $decision = new Decision;
        $decision -> manuscript_id = $request -> manuscript_id;
        $decision -> editor_id = $editor -> id;
        $decision -> status = $request -> status;
        $decision -> comment = $request -> comment;
        $decision -> added_time = Carbon::now() -> format('Y-m-d H:i:s');
        $decision -> save();
        $chairs = Chair::where('conference_id', $decision -> manuscript -> topic -> conference -> id) -> get();
        foreach ($chairs as $chair) {
            $to = [
                'email' => $chair -> email,
                'name' => $chair -> firstname.' '.$chair -> middlename.' '.$chair -> lastname
            ];
            $data = [
                'chair_title' => $chair -> title,
                'chair_firstname' => $chair -> firstname,
                'chair_middlename' => $chair -> middlename,
                'chair_lastname' => $chair -> lastname,
                'manuscript_number' => $decision -> manuscript -> number,
                'manuscript_title' => $decision -> manuscript -> title,
                'conference' => $decision -> manuscript -> topic -> conference -> title,
                'number' => $decision -> manuscript -> topic -> conference -> number,
                'editor_title' => $decision -> editor -> title,
                'editor_firstname' => $decision -> editor -> firstname,
                'editor_middlename' => $decision -> editor -> middlename,
                'editor_lastname' => $decision -> editor -> lastname
            ];
            Mail::send('email.make_decision', $data, function($message) use ($to) {
                $message -> to($to['email'], $to['name']) -> subject('Online Submission System - Notification about Receiving Decision');
            });
        }
        return view('conference.editor.upload_success');
    }

    public function Revisions(Request $request) {
        $editor = Auth::user();
        $manuscript = Manuscript::where('number', $request -> number) -> first();
        if ($manuscript -> topic_id == $editor -> topic_id) {
            $final_decision = FinalDecision::where('manuscript_id', $manuscript -> id) -> first();
            $revisions = Revision::where('final_decision_id', $final_decision -> id) -> get();
            return view('conference.editor.revisions', ['manuscript' => $manuscript, 'revisions' => $revisions]);
        } else return redirect() -> action('Conference\Editor\EditorController@Manuscripts');
    }

    public function ReceivedReReviews(Request $request) {
        $editor = Auth::user();
        $revision = Revision::find($request -> id);
        if ($revision -> final_decision -> manuscript -> topic_id == $editor -> topic_id) {
            return view('conference.editor.received_re_reviews', ['revision' => $revision]);
        } else return redirect() -> action('Conference\Editor\EditorController@Manuscripts');
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
        $editor = Auth::user();
        $decision = new ReDecision;
        $decision -> revised_manuscript_id = $request -> manuscript_id;
        $decision -> editor_id = $editor -> id;
        $decision -> status = $request -> status;
        $decision -> comment = $request -> comment;
        $decision -> added_time = Carbon::now() -> format('Y-m-d H:i:s');
        $decision -> save();
        $chairs = Chair::where('conference_id', $decision -> revised_manuscript -> revision -> final_decision -> manuscript -> topic -> conference -> id) -> get();
        foreach ($chairs as $chair) {
            $to = [
                'email' => $chair -> email,
                'name' => $chair -> firstname.' '.$chair -> middlename.' '.$chair -> lastname
            ];
            $data = [
                'chair_title' => $chair -> title,
                'chair_firstname' => $chair -> firstname,
                'chair_middlename' => $chair -> middlename,
                'chair_lastname' => $chair -> lastname,
                'manuscript_number' => $decision -> revised_manuscript -> revision -> final_decision -> manuscript -> number,
                'manuscript_title' => $decision -> revised_manuscript -> revision -> final_decision -> manuscript -> title,
                'conference' => $decision -> revised_manuscript -> revision -> final_decision -> manuscript -> topic -> conference -> title,
                'number' => $decision -> revised_manuscript -> revision -> final_decision -> manuscript -> topic -> conference -> number,
                'editor_title' => $decision -> editor -> title,
                'editor_firstname' => $decision -> editor -> firstname,
                'editor_middlename' => $decision -> editor -> middlename,
                'editor_lastname' => $decision -> editor -> lastname
            ];
            Mail::send('email.make_revision_decision', $data, function($message) use ($to) {
                $message -> to($to['email'], $to['name']) -> subject('Online Submission System - Notification about Receiving Revision Decision');
            });
        }
        return view('conference.editor.upload_success');
    }

    public function SendReReInvitationPage(Request $request) {
        $editor = Auth::user();
        $topic = Topic::find($editor -> topic_id);
        $revision = Revision::find($request -> id);
        if ($revision -> final_decision -> manuscript -> topic_id == $editor -> topic_id) {
            $reviewers = Reviewer::where('conference_id', $topic -> conference -> id) -> get();
            return view('conference.editor.send_re_invitation', ['revision' => $revision, 'reviewers' => $reviewers]);
        } else return redirect() -> action('Conference\Editor\EditorController@Manuscripts');
    }

    public function SendReInvitation(Request $request) {
        $editor = Auth::user();
        $count = ReInvitation::where('revised_manuscript_id', $request -> revised_manuscript_id) -> where('reviewer_id', $request -> reviewer_id) -> count();
        if ($count > 0) return back() -> with('danger', 'You have already sent a invitation to the reviewer!');
        else {
            $deadline = RevisedManuscript::find($request -> revised_manuscript_id) -> revision -> final_decision -> manuscript -> topic -> conference -> exist_deadline;
            if ($deadline < $request -> deadline) return back() -> with('warn', 'The daedline is not valid!');
            else {
                $invitation = new ReInvitation;
                $invitation -> revised_manuscript_id = $request -> revised_manuscript_id;
                $invitation -> reviewer_id = $request -> reviewer_id;
                $invitation -> editor_id = $editor -> id;
                $invitation -> deadline = $request -> deadline;
                $invitation -> added_time = Carbon::now() -> format('Y-m-d H:i:s');
                $invitation -> save();
                $to = [
                    'email' => $invitation -> reviewer -> email,
                    'name' => $invitation -> reviewer -> firstname.' '.$invitation -> reviewer -> middlename.' '.$invitation -> reviewer -> lastname
                ];
                $data = [
                    'reviewer_title' => $invitation -> reviewer -> title,
                    'reviewer_firstname' => $invitation -> reviewer -> firstname,
                    'reviewer_middlename' => $invitation -> reviewer -> middlename,
                    'reviewer_lastname' => $invitation -> reviewer -> lastname,
                    'manuscript_number' => $invitation -> revised_manuscript -> revision -> final_decision -> manuscript -> number,
                    'manuscript_title' => $invitation -> revised_manuscript -> revision -> final_decision -> manuscript -> title,
                    'conference' => $invitation -> revised_manuscript -> revision -> final_decision -> manuscript -> topic -> conference -> title,
                    'deadline' => $invitation -> deadline,
                    'number' => $invitation -> revised_manuscript -> revision -> final_decision -> manuscript -> topic -> conference -> number,
                    'editor_title' => $invitation -> editor -> title,
                    'editor_firstname' => $invitation -> editor -> firstname,
                    'editor_middlename' => $invitation -> editor -> middlename,
                    'editor_lastname' => $invitation -> editor -> lastname
                ];
                Mail::send('email.send_revision_invitation', $data, function($message) use ($to)
                {
                    $message -> to($to['email'], $to['name']) -> subject('Online Submission System - Invitation to Review Manuscript');
                });
                return back() -> with('success', 'You have successfully sent a invitation!');
            }
        }
    }

    public function Reviewers() {
        $editor = Auth::user();
        $topic = Topic::find($editor -> topic_id);
        $reviewers = Reviewer::where('conference_id', $topic -> conference -> id) -> get();
        return view('conference.editor.reviewers', ['topic' => $topic, 'reviewers' => $reviewers]);
    }
    
    public function CreateReviewer(Request $request) {
        $email = Reviewer::where('email', $request -> email) -> where('conference_id', $request -> conference_id) -> count();
        if ($email > 0) return back() -> with('danger', 'Email has already been registered!');
        else {
            $reviewer = new Reviewer;
            $reviewer -> conference_id = $request -> conference_id;
            $reviewer -> title = $request -> title;
            $reviewer -> email = $request -> email;
            $reviewer -> password = bcrypt($request -> password);
            $reviewer -> firstname = $request -> firstname;
            $reviewer -> middlename = $request -> middlename;
            $reviewer -> lastname = $request -> lastname;
            $reviewer -> tel = $request -> tel;
            $reviewer -> institution = $request -> institution;
            $reviewer -> country = $request -> country;
            $reviewer -> added_time = Carbon::now() -> format('Y-m-d H:i:s');
            $reviewer -> save();
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
                'conference' => $reviewer -> conference -> title,
                'number' => $reviewer -> conference -> number
            ];
            Mail::send('email.create_reviewer', $data, function($message) use ($to) {
                $message -> to($to['email'], $to['name']) -> subject('Online Submission System - Reviewer Registration Successful');
            });
            return back() -> with('success', 'You have successfully created a editor!');
        }
    }

    public function GetReviewer(Request $request) {
		if ($request -> ajax()) {
            $reviewer = Reviewer::find($request -> id);
			return response() -> json($reviewer);
		}
	}

    public function SuspendReviewer(Request $request) {
        $reviewer = Reviewer::find($request -> id);
        $reviewer -> status = 0;
        $reviewer -> save();
        return back() -> with('success', 'You have successfully suspended the chair!');
    }

    public function ResumeReviewer(Request $request) {
        $reviewer = Reviewer::find($request -> id);
        $reviewer -> status = 1;
        $reviewer -> save();
        return back() -> with('success', 'You have successfully resumed the chair');
    }

    public function Profile() {
        return view('conference.editor.profile');
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
                $chair -> password =  bcrypt($request -> new_password);
                $chair -> save();
                return back() -> with('success', 'You have successfully changed your password!');
            }
        } else return back() -> with('warn', 'Your password and confirm password do not match!');
    }
}
