<?php
namespace App\Http\Controllers\Conference\Reviewer;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Hash;
use Mail;
use App\Conference\Conference;
use App\Conference\Invitation;
use App\Conference\Review;
use App\Conference\ReviewFile;
use App\Conference\ReInvitation;
use App\Conference\ReReview;
use App\Conference\ReReviewFile;

class ReviewerController extends Controller {
    public function Invitations() {
        $reviewer = Auth::user();
        $pending = Invitation::where('reviewer_id', $reviewer -> id) -> where('status', 0) -> count();
        $re_pending = ReInvitation::where('reviewer_id', $reviewer -> id) -> where('status', 0) -> count();
        $conference = Conference::find($reviewer -> conference_id);
        $invitations = Invitation::where('reviewer_id', $reviewer -> id) -> orderBy('added_time', 'desc') -> get();
        return view('conference.reviewer.invitations', ['pending' => $pending, 're_pending' => $re_pending, 'conference' => $conference, 'invitations' => $invitations]);
    }

    public function GetInvitation(Request $request) {
		if ($request -> ajax()) {
            $invitation = Invitation::find($request -> id);
			return response() -> json($invitation);
		}
    }
    
    public function AcceptInvitationPage(Request $request) {
        $reviewer = Auth::user();
        $pending = Invitation::where('reviewer_id', $reviewer -> id) -> where('status', 0) -> count();
        $re_pending = ReInvitation::where('reviewer_id', $reviewer -> id) -> where('status', 0) -> count();
        $invitation = Invitation::find($request -> id);
        if ($invitation -> reviewer -> id == $reviewer -> id && $invitation -> review == '' && $invitation -> status == 0) return view('conference.reviewer.upload_review', ['pending' => $pending, 're_pending' => $re_pending, 'invitation' => $invitation]);
        else return redirect() -> action('Conference\Reviewer\ReviewerController@Invitations');
    }

    public function UploadReview(Request $request) {
        $deadline = Invitation::find($request -> invitation_id) -> deadline;
        if ($deadline < date('Y-m-d')) return back() -> with('warn', "The invitation is expired!");
        else {
            $reviewer = Auth::user();
            $pending = Invitation::where('reviewer_id', $reviewer -> id) -> where('status', 0) -> count();
            $re_pending = ReInvitation::where('reviewer_id', $reviewer -> id) -> where('status', 0) -> count();
            $invitation = Invitation::find($request -> invitation_id);
            $invitation -> status = 1;
            $invitation -> modified_time = Carbon::now() -> format('Y-m-d H:i:s');
            $invitation -> save();
            $review = new Review;
            $review -> comment_author = $request -> comment_author;
            $review -> comment_editor = $request -> comment_editor;
            $review -> invitation_id = $request -> invitation_id;
            $review -> status = $request -> status;
            $review -> added_time = Carbon::now() -> format('Y-m-d H:i:s');
            $review -> save();
            if ($request -> hasFile('file')) {
                $file = $request -> file('file');
                $url = $file -> getClientOriginalName().'-'.strval(time()).str_random(5).'.'.$file -> getClientOriginalExtension();
                $file -> move(public_path().'/upload/', $url);
                $new_file = new ReviewFile;
                $new_file -> review_id = $review -> id;
                $new_file -> name = $file -> getClientOriginalName();
                $new_file -> url = $url;
                $new_file -> type = $file -> getClientOriginalExtension();
                $new_file -> save();
            }
            $to = [
                'email' => $invitation -> editor -> email,
                'name' => $invitation -> editor -> firstname.' '.$invitation -> editor -> middlename.' '.$invitation -> editor -> lastname
            ];
            $data = [
                'editor_title' => $invitation -> editor -> title,
                'editor_firstname' => $invitation -> editor -> firstname,
                'editor_middlename' => $invitation -> editor -> middlename,
                'editor_lastname' => $invitation -> editor -> lastname,
                'manuscript_number' => $invitation -> manuscript -> number,
                'manuscript_title' => $invitation -> manuscript -> title,
                'conference' => $invitation -> manuscript -> topic -> conference -> title,
                'number' => $invitation -> manuscript -> topic -> number,
                'reviewer_title' => $invitation -> reviewer -> title,
                'reviewer_firstname' => $invitation -> reviewer -> firstname,
                'reviewer_middlename' => $invitation -> reviewer -> middlename,
                'reviewer_lastname' => $invitation -> reviewer -> lastname
            ];
            Mail::send('email.conference.upload_review', $data, function($message) use ($to) {
                $message -> to($to['email'], $to['name']) -> subject('Online Submission and Review System - Notification about Receiving Review');
            });
            return view('conference.reviewer.upload_success', ['pending' => $pending, 're_pending' => $re_pending]);
        }
    }

    public function RejectInvitation(Request $request) {
        $invitation = Invitation::find($request -> id);
        $invitation -> status = 2;
        $invitation -> reply = $request -> reply;
        $invitation -> modified_time = Carbon::now() -> format('Y-m-d H:i:s');
        $invitation -> save();
        $to = [
            'email' => $invitation -> editor -> email,
            'name' => $invitation -> editor -> firstname.' '.$invitation -> editor -> middlename.' '.$invitation -> editor -> lastname
        ];
        $data = [
            'editor_title' => $invitation -> editor -> title,
            'editor_firstname' => $invitation -> editor -> firstname,
            'editor_middlename' => $invitation -> editor -> middlename,
            'editor_lastname' => $invitation -> editor -> lastname,
            'manuscript_number' => $invitation -> manuscript -> number,
            'manuscript_title' => $invitation -> manuscript -> title,
            'conference' => $invitation -> manuscript -> topic -> conference -> title,
            'number' => $invitation -> manuscript -> topic -> number,
            'reviewer_title' => $invitation -> reviewer -> title,
            'reviewer_firstname' => $invitation -> reviewer -> firstname,
            'reviewer_middlename' => $invitation -> reviewer -> middlename,
            'reviewer_lastname' => $invitation -> reviewer -> lastname,
            'reason' => $invitation -> reply
        ];
        Mail::send('email.conference.reject_invitation', $data, function($message) use ($to) {
            $message -> to($to['email'], $to['name']) -> subject('Online Submission and Review System - Notification about Rejecting Invitation');
        });
        return back() -> with('success', 'You have successfully rejected the invitation!');
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

    public function ReInvitations() {
        $reviewer = Auth::user();
        $pending = Invitation::where('reviewer_id', $reviewer -> id) -> where('status', 0) -> count();
        $re_pending = ReInvitation::where('reviewer_id', $reviewer -> id) -> where('status', 0) -> count();
        $conference = Conference::find($reviewer -> conference_id);
        $re_invitations = ReInvitation::where('reviewer_id', $reviewer -> id) -> orderBy('added_time', 'desc') -> get();
        return view('conference.reviewer.re_invitations', ['pending' => $pending, 're_pending' => $re_pending, 'conference' => $conference, 're_invitations' => $re_invitations]);
    }

    public function GetReInvitation(Request $request) {
		if ($request -> ajax()) {
            $invitation = ReInvitation::find($request -> id);
			return response() -> json($invitation);
		}
    }

    public function AcceptReInvitationPage(Request $request) {
        $reviewer = Auth::user();
        $pending = Invitation::where('reviewer_id', $reviewer -> id) -> where('status', 0) -> count();
        $re_pending = ReInvitation::where('reviewer_id', $reviewer -> id) -> where('status', 0) -> count();
        $invitation = ReInvitation::find($request -> id);
        if ($invitation -> reviewer -> id == $reviewer -> id && $invitation -> re_review == '' && $invitation -> status == 0) return view('conference.reviewer.upload_re_review', ['pending' => $pending, 're_pending' => $re_pending, 'invitation' => $invitation]);
        else return redirect() -> action('Conference\Reviewer\ReviewerController@ReInvitations');
    }

    public function UploadReReview(Request $request) {
        $deadline = ReInvitation::find($request -> invitation_id) -> deadline;
        if ($deadline < date('Y-m-d')) return back() -> with('warn', "The invitation is expired!");
        else {
            $reviewer = Auth::user();
            $pending = Invitation::where('reviewer_id', $reviewer -> id) -> where('status', 0) -> count();
            $re_pending = ReInvitation::where('reviewer_id', $reviewer -> id) -> where('status', 0) -> count();
            $invitation = ReInvitation::find($request -> invitation_id);
            $invitation -> status = 1;
            $invitation -> modified_time = Carbon::now() -> format('Y-m-d H:i:s');
            $invitation -> save();
            $review = new ReReview;
            $review -> comment_author = $request -> comment_author;
            $review -> comment_editor = $request -> comment_editor;
            $review -> re_invitation_id = $request -> invitation_id;
            $review -> status = $request -> status;
            $review -> added_time = Carbon::now() -> format('Y-m-d H:i:s');
            $review -> save();
            if ($request -> hasFile('file')) {
                $file = $request -> file('file');
                $url = $file -> getClientOriginalName().'-'.strval(time()).str_random(5).'.'.$file -> getClientOriginalExtension();
                $file -> move(public_path().'/upload/', $url);
                $new_file = new ReReviewFile;
                $new_file -> re_review_id = $review -> id;
                $new_file -> name = $file -> getClientOriginalName();
                $new_file -> url = $url;
                $new_file -> type = $file -> getClientOriginalExtension();
                $new_file -> save();
            }
            $to = [
                'email' => $invitation -> editor -> email,
                'name' => $invitation -> editor -> firstname.' '.$invitation -> editor -> middlename.' '.$invitation -> editor -> lastname
            ];
            $data = [
                'editor_title' => $invitation -> editor -> title,
                'editor_firstname' => $invitation -> editor -> firstname,
                'editor_middlename' => $invitation -> editor -> middlename,
                'editor_lastname' => $invitation -> editor -> lastname,
                'manuscript_number' => $invitation -> revised_manuscript -> revision -> final_decision -> manuscript -> number,
                'manuscript_title' => $invitation -> revised_manuscript -> revision -> final_decision -> manuscript -> title,
                'conference' => $invitation -> revised_manuscript -> revision -> final_decision -> manuscript -> topic -> conference -> title,
                'number' => $invitation -> revised_manuscript -> revision -> final_decision -> manuscript -> topic -> number,
                'reviewer_title' => $invitation -> reviewer -> title,
                'reviewer_firstname' => $invitation -> reviewer -> firstname,
                'reviewer_middlename' => $invitation -> reviewer -> middlename,
                'reviewer_lastname' => $invitation -> reviewer -> lastname,
            ];
            Mail::send('email.conference.upload_revision_review', $data, function($message) use ($to) {
                $message -> to($to['email'], $to['name']) -> subject('Online Submission and Review System - Notification about Receiving Revision Review');
            });
            return view('conference.reviewer.upload_success', ['pending' => $pending, 're_pending' => $re_pending]);
        }
    }

    public function RejectReInvitation(Request $request) {
        $invitation = ReInvitation::find($request -> id);
        $invitation -> status = 2;
        $invitation -> reply = $request -> reply;
        $invitation -> modified_time = Carbon::now() -> format('Y-m-d H:i:s');
        $invitation -> save();
        $to = [
            'email' => $invitation -> editor -> email,
            'name' => $invitation -> editor -> firstname.' '.$invitation -> editor -> middlename.' '.$invitation -> editor -> lastname
        ];
        $data = [
            'editor_title' => $invitation -> editor -> title,
            'editor_firstname' => $invitation -> editor -> firstname,
            'editor_middlename' => $invitation -> editor -> middlename,
            'editor_lastname' => $invitation -> editor -> lastname,
            'manuscript_number' => $invitation -> revised_manuscript -> revision -> final_decision -> manuscript -> number,
            'manuscript_title' => $invitation -> revised_manuscript -> revision -> final_decision -> manuscript -> title,
            'conference' => $invitation -> revised_manuscript -> revision -> final_decision -> manuscript -> topic -> conference -> title,
            'number' => $invitation -> revised_manuscript -> revision -> final_decision -> manuscript -> topic -> number,
            'reviewer_title' => $invitation -> reviewer -> title,
            'reviewer_firstname' => $invitation -> reviewer -> firstname,
            'reviewer_middlename' => $invitation -> reviewer -> middlename,
            'reviewer_lastname' => $invitation -> reviewer -> lastname,
            'reason' => $invitation -> reply
        ];
        Mail::send('email.conference.reject_revision_invitation', $data, function($message) use ($to) {
            $message -> to($to['email'], $to['name']) -> subject('Online Submission and Review System - Notification about Rejecting Revision Invitation');
        });
        return back() -> with('success', 'You have successfully rejected the revision invitation!');
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

    public function Profile() {
        $reviewer = Auth::user();
        $pending = Invitation::where('reviewer_id', $reviewer -> id) -> where('status', 0) -> count();
        $re_pending = ReInvitation::where('reviewer_id', $reviewer -> id) -> where('status', 0) -> count();
        return view('conference.reviewer.profile', ['pending' => $pending, 're_pending' => $re_pending]);
    }

    public function EditProfile(Request $request) {
        $reviewer = Auth::user();
        $reviewer -> title = $request -> title;
        $reviewer -> firstname = $request -> firstname;
        $reviewer -> middlename = $request -> middlename;
        $reviewer -> lastname = $request -> lastname;
        $reviewer -> tel = $request -> tel;
        $reviewer -> institution = $request -> institution;
        $reviewer -> country = $request -> country;
        $reviewer -> modified_time = Carbon::now() -> format('Y-m-d H:i:s');
        $reviewer -> save();
        return back() -> with('success', 'You have successfully updated your profile!');
    }

    public function ChangePassword(Request $request) {
        $reviewer = Auth::user();
        if ($request -> confirm_password == $request -> new_password) {
            if (!Hash::check($request -> current_password, $reviewer -> password)) return back() -> with('danger', 'Your current password is incorrect!');
            else {
                $reviewer -> password =  bcrypt($request -> new_password);
                $reviewer -> save();
                return back() -> with('success', 'You have successfully changed your password!');
            }
        } else return back() -> with('warn', 'Your password and confirm password do not match!');
    }
}
