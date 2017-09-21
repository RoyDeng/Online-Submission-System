<?php
//Maintainer登入的路徑
Route::get('admin/login', 'Admin\LoginController@LoginPage') -> name('admin.login');
Route::post('admin/login', 'Admin\LoginController@login');
Route::get('admin/forgot_password', 'Admin\LoginController@ForgotPasswordPage');
Route::post('admin/reset_password', 'Admin\LoginController@ResetPassword');

//需要驗證的路徑
Route::group(['middleware' => ['auth:admin'], 'prefix' => 'admin'], function() {
    //登出
    Route::get('logout', 'Admin\LoginController@logout') -> name('admin.logout');

    //研討會
    Route::get('/', 'Admin\MaintainerController@Conferences') -> name('admin');
    Route::post('create_conference', 'Admin\MaintainerController@CreateConference');
    Route::get('get_conference', 'Admin\MaintainerController@GetConference');
    Route::post('edit_conference', 'Admin\MaintainerController@EditConference');
    Route::post('close_conference', 'Admin\MaintainerController@CloseConference');
    Route::post('open_conference', 'Admin\MaintainerController@OpenConference');

    //主席
    Route::get('conference/chairs/{number}', 'Admin\MaintainerController@Chairs');
    Route::post('conference/create_chair', 'Admin\MaintainerController@CreateChair');
    Route::get('conference/get_chair', 'Admin\MaintainerController@GetChair');
    Route::post('conference/suspend_chair', 'Admin\MaintainerController@SuspendChair');
    Route::post('conference/resume_chair', 'Admin\MaintainerController@ResumeChair');

    //作者
    Route::get('authors', 'Admin\MaintainerController@Authors');
    Route::get('get_author', 'Admin\MaintainerController@GetAuthor');
    Route::post('suspend_author', 'Admin\MaintainerController@SuspendAuthor');
    Route::post('resume_author', 'Admin\MaintainerController@ResumeAuthor');

    //個人資料
    Route::get('profile', 'Admin\MaintainerController@Profile');
    Route::post('edit_profile', 'Admin\MaintainerController@EditProfile');
    Route::post('change_email', 'Admin\MaintainerController@ChangeEmail');
    Route::post('change_password', 'Admin\MaintainerController@ChangePassword');
});

//Chair登入的路徑
Route::prefix('conference')->group(function () {
    Route::get('chair/login/{number}', 'Conference\Chair\LoginController@LoginPage') -> name('chair.login');
    Route::post('chair/login', 'Conference\Chair\LoginController@login');
    Route::get('chair/forgot_password/{number}', 'Conference\Chair\LoginController@ForgotPasswordPage');
    Route::post('chair/reset_password', 'Conference\Chair\LoginController@ResetPassword');
    //需要驗證的路徑
    Route::group(['middleware' => ['auth:chair'], 'prefix' => 'chair'], function() {
        //登出
        Route::get('logout', 'Conference\Chair\LoginController@logout') -> name('conference.chair.logout');

        //研討會
        Route::get('conference', 'Conference\Chair\ChairController@Conference');
        Route::post('edit_conference', 'Conference\Chair\ChairController@EditConference');

        //題目
        Route::get('/', 'Conference\Chair\ChairController@Topics') -> name('chair');
        Route::post('create_topic', 'Conference\Chair\ChairController@CreateTopic');
        Route::get('get_topic', 'Conference\Chair\ChairController@GetTopic');
        Route::post('edit_topic', 'Conference\Chair\ChairController@EditTopic');
        Route::post('close_topic', 'Conference\Chair\ChairController@CloseTopic');
        Route::post('open_topic', 'Conference\Chair\ChairController@OpenTopic');

        //編者
        Route::get('editors/{number}', 'Conference\Chair\ChairController@Editors');
        Route::post('create_editor', 'Conference\Chair\ChairController@CreateEditor');
        Route::get('get_editor', 'Conference\Chair\ChairController@GetEditor');
        Route::post('suspend_editor', 'Conference\Chair\ChairController@SuspendEditor');
        Route::post('resume_editor', 'Conference\Chair\ChairController@ResumeEditor');

        //稿件
        Route::get('manuscripts', 'Conference\Chair\ChairController@Manuscripts');
        Route::get('received_decision/{number}', 'Conference\Chair\ChairController@ReceivedDecision');
        Route::get('get_review', 'Conference\Chair\ChairController@GetReview');
        Route::post('make_decision', 'Conference\Chair\ChairController@MakeDecision');
        Route::get('revisions/{number}', 'Conference\Chair\ChairController@Revisions');
        Route::get('received_re_reviews/{id}', 'Conference\Chair\ChairController@ReceivedReReviews');
        Route::get('get_decision', 'Conference\Chair\ChairController@GetDecision');
        Route::get('get_re_review', 'Conference\Chair\ChairController@GetReReview');
        Route::get('get_re_reply', 'Conference\Chair\ChairController@GetReReply');
        Route::post('make_re_decision', 'Conference\Chair\ChairController@MakeReDecision');

        //個人資料
        Route::get('profile', 'Conference\Chair\ChairController@Profile');
        Route::post('edit_profile', 'Conference\Chair\ChairController@EditProfile');
        Route::post('change_password', 'Conference\Chair\ChairController@ChangePassword');
    });

    //Editor登入的路徑
    Route::get('editor/login/topics/{number}', 'Conference\Editor\LoginController@TopicsPage');
    Route::get('editor/login/{number}', 'Conference\Editor\LoginController@LoginPage') -> name('editor.login');
    Route::post('editor/login', 'Conference\Editor\LoginController@login');
    Route::get('editor/forgot_password/{number}', 'Conference\Editor\LoginController@ForgotPasswordPage');
    Route::post('editor/reset_password', 'Conference\Editor\LoginController@ResetPassword');

    //需要驗證的路徑
    Route::group(['middleware' => ['auth:editor'], 'prefix' => 'editor'], function() {
        //登出
        Route::get('logout', 'Conference\Editor\LoginController@logout') -> name('conference.editor.logout');
        
        //搞件
        Route::get('/', 'Conference\Editor\EditorController@Manuscripts') -> name('editor');
        Route::get('send_invitation/{number}', 'Conference\Editor\EditorController@SendInvitationPage');
        Route::post('send_invitation', 'Conference\Editor\EditorController@SendInvitation');

        //評論
        Route::get('received_reviews/{number}', 'Conference\Editor\EditorController@ReceivedReviews');
        Route::get('get_review', 'Conference\Editor\EditorController@GetReview');
        Route::get('get_reply', 'Conference\Editor\EditorController@GetReply');
        Route::post('make_decision', 'Conference\Editor\EditorController@MakeDecision');

        //再次評論
        Route::get('revisions/{number}', 'Conference\Editor\EditorController@Revisions');
        Route::get('received_re_reviews/{id}', 'Conference\Editor\EditorController@ReceivedReReviews');
        Route::get('get_re_review', 'Conference\Editor\EditorController@GetReReview');
        Route::get('get_re_reply', 'Conference\Editor\EditorController@GetReReply');
        Route::get('send_re_invitation/{id}', 'Conference\Editor\EditorController@SendReReInvitationPage');
        Route::post('send_re_invitation', 'Conference\Editor\EditorController@SendReInvitation');
        Route::post('make_re_decision', 'Conference\Editor\EditorController@MakeReDecision');

        //評論者
        Route::get('reviewers', 'Conference\Editor\EditorController@Reviewers');
        Route::post('create_reviewer', 'Conference\Editor\EditorController@CreateReviewer');
        Route::get('get_reviewer', 'Conference\Editor\EditorController@GetReviewer');
        Route::post('suspend_reviewer', 'Conference\Editor\EditorController@SuspendReviewer');
        Route::post('resume_reviewer', 'Conference\Editor\EditorController@ResumeReviewer');

        //個人資料
        Route::get('profile', 'Conference\Editor\EditorController@Profile');
        Route::post('edit_profile', 'Conference\Editor\EditorController@EditProfile');
        Route::post('change_password', 'Conference\Editor\EditorController@ChangePassword');
    });

    //Reviewer登入的路徑
    Route::get('reviewer/login/{number}', 'Conference\Reviewer\LoginController@LoginPage') -> name('reviewer.login');
    Route::post('reviewer/login', 'Conference\Reviewer\LoginController@login');
    Route::get('reviewer/forgot_password/{number}', 'Conference\Reviewer\LoginController@ForgotPasswordPage');
    Route::post('reviewer/reset_password', 'Conference\Reviewer\LoginController@ResetPassword');

    //需要驗證的路徑
    Route::group(['middleware' => ['auth:reviewer'], 'prefix' => 'reviewer'], function() {
        //登出
        Route::get('logout', 'Conference\Reviewer\LoginController@logout') -> name('conference.reviewer.logout');
        
        //邀請
        Route::get('/', 'Conference\Reviewer\ReviewerController@Invitations') -> name('reviewer');
        Route::get('get_invitation', 'Conference\Reviewer\ReviewerController@GetInvitation');
        Route::get('accept_invitation/{id}', 'Conference\Reviewer\ReviewerController@AcceptInvitationPage');
        Route::post('upload_review', 'Conference\Reviewer\ReviewerController@UploadReview');
        Route::post('reject_invitation', 'Conference\Reviewer\ReviewerController@RejectInvitation');
        Route::get('get_review', 'Conference\Reviewer\ReviewerController@GetReview');
        Route::get('get_reply', 'Conference\Reviewer\ReviewerController@GetReply');

        //再次邀請
        Route::get('re_invitations', 'Conference\Reviewer\ReviewerController@ReInvitations');
        Route::get('get_re_invitation', 'Conference\Reviewer\ReviewerController@GetReInvitation');
        Route::get('accept_re_invitation/{id}', 'Conference\Reviewer\ReviewerController@AcceptReInvitationPage');
        Route::post('upload_re_review', 'Conference\Reviewer\ReviewerController@UploadReReview');
        Route::post('reject_re_invitation', 'Conference\Reviewer\ReviewerController@RejectReInvitation');
        Route::get('get_re_review', 'Conference\Reviewer\ReviewerController@GetReReview');
        Route::get('get_re_reply', 'Conference\Reviewer\ReviewerController@GetReReply');

        //個人資料
        Route::get('profile', 'Conference\Reviewer\ReviewerController@Profile');
        Route::post('edit_profile', 'Conference\Reviewer\ReviewerController@EditProfile');
        Route::post('change_password', 'Conference\Reviewer\ReviewerController@ChangePassword');
    });
});

//Author登入的路徑
Route::get('conference/author/login/{number}', 'Author\LoginController@LoginPage') -> name('login');
Route::post('conference/author/login', 'Author\LoginController@login');
Route::get('author/forgot_password', 'Author\LoginController@ForgotPasswordPage');
Route::post('author/reset_password', 'Author\LoginController@ResetPassword');
Route::get('author/register', 'Author\LoginController@RegisterPage') -> name('register');
Route::post('author/register', 'Author\LoginController@register');

//需要驗證的路徑
Route::group(['middleware' => ['auth:author'], 'prefix' => 'author'], function() {
    //登出
    Route::get('logout/{number}', 'Author\LoginController@logout') -> name('author.logout');

    //題目
    Route::get('conference/{number}', 'Author\AuthorController@Topics') -> name('author');

    //稿件
    Route::get('conference/upload_manuscript/{number}', 'Author\AuthorController@UploadManuscriptPage');
    Route::post('conference/upload_manuscript', 'Author\AuthorController@UploadManuscript');
    Route::get('conference/get_manuscript/{id}', 'Author\AuthorController@GetManuscript');
    Route::get('conference/manuscripts/{number}', 'Author\AuthorController@Manuscripts');
    Route::post('conference/delete_manuscript', 'Author\AuthorController@DeleteManuscript');
    Route::get('conference/manuscript/{number}', 'Author\AuthorController@Manuscript');
    Route::get('conference/get_review/{id}', 'Author\AuthorController@GetReview');
    Route::get('conference/upload_revision/{id}', 'Author\AuthorController@UploadRevisionPage');
    Route::post('conference/upload_revision', 'Author\AuthorController@UploadRevision');
    Route::get('conference/revision/{id}', 'Author\AuthorController@Revision');
    Route::get('conference/get_re_review/{id}', 'Author\AuthorController@GetReReview');

    //個人資料
    Route::get('conference/profile/{number}', 'Author\AuthorController@Profile');
    Route::post('edit_profile', 'Author\AuthorController@EditProfile');
    Route::post('change_password', 'Author\AuthorController@ChangePassword');
});

Route::get('contact', 'Admin\MaintainerController@ContactPage');
Route::post('contact', 'Admin\MaintainerController@Contact');
