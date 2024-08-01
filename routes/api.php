<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/save-teacher-detail','TeacherDetails@save_teacher_detail');
// Route for Publisher //


Route::get('get-user-detail','DemoController@get_user_data'); 
Route::get('external-link','DemoController@add_external_link'); 

Route::group(['prefix' => 'admin'], function () {

    //for admin login and registration
    Route::post('login', 'admin\AdminloginController@login');
    Route::post('registration', 'admin\AdminloginController@register');

    Route::apiResource('adminaccount', 'admin\AdminaccountController');

    Route::apiResource('adminlog', 'admin\AdminlogController');

    Route::apiResource('adminlogin', 'admin\AdminloginController');

    Route::apiResource('book', 'admin\BookController');
//     Route::get('book/{id}/edit','admin\BookController@edit');
    Route::get('book/{id}', 'admin\BookController@show');
    Route::post('book/{id}', 'admin\BookController@update');
    Route::get('book/{id}/edit', 'admin\BookController@edit');

    Route::get('recent', 'admin\BookController@recent');
    Route::post('searchallbook', 'admin\BookController@getSearchBooks');
    Route::get('getFreeBooks', 'admin\BookController@getFreeBooks');
    // Route::get('allgenre','admin\BookController@AllGenre');
    //  Route::post('bookbygenre','admin\BookController@BookByGenry');
    Route::get('randombook', 'admin\BookController@Randombook');

    Route::apiResource('publisher','API\PublisherRegisterApiController');
    Route::get('visitor','API\VisitorController@index');    

// for carousel

    Route::post('carousel/{id}', 'user\CarouselController@update');
    Route::get('carousels', 'user\CarouselController@getCarousel');
    Route::apiResource('carousel', 'user\CarouselController');

//  for advertisement banner
    Route::post('advertisement/{id}', 'user\AdvertisementController@update');
    Route::get('getBanner/{id}', 'AppAdController@getAds');
    Route::get('advertisements', 'user\AdvertisementController@getAdvertisement');
    Route::apiResource('advertisement', 'user\AdvertisementController');
// fleged genre
    Route::post('flagedgenre/{id}', 'user\FlagedgenreController@update');
    Route::get('flagedgenres', 'user\FlagedgenreController@getFlagedgenre');
    Route::apiResource('flagedgenre', 'user\FlagedgenreController');

    Route::post('userfav/{id}', 'user\UserfavController@update');
    Route::get('userfavs', 'user\UserfavController@getUserfav');
    Route::post('verifyfav', 'user\UserfavController@verifyFav');
    Route::apiResource('userfav', 'user\UserfavController');

    Route::post('removeuserfav', 'user\UserfavController@RemoveFav');

// for notification
    Route::post('notification/{id}', 'user\NotificationController@update');
    Route::get('notification', 'user\NotificationController@getNotification');
    Route::apiResource('notification', 'user\NotificationController');

//for has read book
    Route::post('getReadStat', 'user\HasreadController@getHasreadStat');
    Route::post('hasread/{id}', 'user\HasreadController@update');
    Route::get('hasreads', 'user\HasreadController@getHasread');
    Route::apiResource('hasread', 'user\HasreadController');

//for global notification

    Route::post('gnotification/{id}', 'user\GlobalnotificationController@update');
    Route::get('gnotifications', 'user\GlobalnotificationController@getGlobalnotification');
    Route::apiResource('gnotification', 'user\GlobalnotificationController');

//for red global notification
    Route::post('rglobalnotification/{id}', 'user\RedglobalnotiController@update');
    Route::get('rglobalnotifications', 'user\RedglobalnotiController@getRedglobalnotification');
    Route::apiResource('rglobalnotification', 'user\RedglobalnotiController');

// for rating
    Route::post('rating/{id}', 'user\RatingController@update');
    Route::get('ratings', 'user\RatingController@getHasread');
    Route::apiResource('rating', 'user\RatingController');

// for completed readbook
    Route::post('completeread/{id}', 'user\CompletedreadController@update');
    Route::get('completereads', 'user\CompletedreadController@getCompletedread');
    Route::apiResource('completeread', 'user\CompletedreadController');

    //for genre
    Route::get('getBookGenre/{id}', 'admin\BookgenreController@bookgenreIDS');
    Route::apiResource('genre', 'admin\GenreController');
//     Route::get('getBookGenre', 'admin\BookGenreController@bookgenreIDS');
    Route::get('genre/{id}/edit', 'admin\GenreController@edit');
    Route::post('genre/{id}', 'admin\GenreController@update');

    Route::get('getbookbygenre/{genre_id}', 'admin\BookgenreController@GetbookbyGenre');

    Route::get('countbookbysinglegenre', 'admin\BookgenreController@CountBookbysinglegenre');

    //  Route::get('genre/{id}/edit','admin\GenreController@edit');
    //  Route::post('genre/{id}','admin\GenreController@update');

    Route::apiResource('sendtoken', 'user\ResetpasswordController');
    // Route::post('resetpassword/{id}', 'user\ResetpasswordController@ResetPassword');
    Route::post('resetpassword', 'user\ResetpasswordController@ResetUserPassword');

    //forget password for userfront
    Route::apiResource('sendotp', 'user\ForgetpasswordController');
    Route::post('verifyOTP', 'user\ForgetpasswordController@VerifyOTP');

    Route::apiResource('userlogin', 'user\UserloginController');

    Route::apiResource('useraddress', 'user\UseraddressController');
    Route::get('useraddress/{id}/edit', 'user\UseraddressController@edit');
    Route::post('useraddress/{id}', 'user\UseraddressController@update');

    Route::apiResource('bookread', 'user\BooksreadController');
    Route::get('bookread/{id}/edit', 'user\BooksreadController@edit');
    Route::post('bookread/{id}', 'user\BooksreadController@update');

    Route::apiResource('readlogs', 'user\ReadlogsController');
    Route::get('readlogs/{id}/edit', 'user\ReadlogsController@edit');
    Route::post('readlogs/{id}', 'user\ReadlogsController@update');

    Route::apiResource('user', 'user\UserController');
    Route::get('user/{id}/edit', 'user\UserController@edit');
    Route::post('user/{id}', 'user\UserController@update');
    Route::post('users', 'user\UserController@verifyLogin');
    Route::post('userloginverify', 'user\UserController@userLogin');


    //user front login
    Route::post('userfrontregister', 'userfront\UserfrontController@register');
    Route::post('userfrontlogin', 'userfront\UserfrontController@login');
    Route::post('google', 'userfront\UserfrontController@google');
    Route::post('facebook', 'userfront\UserfrontController@facebook');
    Route::post('authExternalAccess', 'userfront\UserfrontController@authCheckForExternalLogin');
    Route::apiResource('userlog', 'user\UserlogController')->middleware('auth:api');
    Route::get('getUserDetails{token}', 'Auth\LoginController@getUserByToken');
    Route::get('googleLogin', 'Auth\LoginController@handleGoogleCallback')->middleware('auth:api');

    Route::post('updateprofile/{id}', 'userfront\UserfrontController@UpdateProfile');
    Route::get('getSubscriptionInfo', 'userfront\UserfrontController@getSubsInfo');

    // Route::post('forgetpassword','userfront\UserfrontController@ForgetPassword');

    //payment routes

    Route::get('testpay', 'payment\paymentController@pay');
    Route::post('paytmresponse', 'payment\paymentController@confirmPayment');
    
    Route::apiResource('temp', 'admin\TempBookController');

    Route::get('getBookPdf/{id}', 'user\BooksreadController@getBookPdf');
    Route::get('stream/{id}', 'user\BooksreadController@stream');
    Route::get('stream2/{id}', 'user\BooksreadController@stream2');
    Route::get('getReadList/{id}', 'user\BooksreadController@getReadList');

    Route::get('recommended/{id}', 'user\BooksreadController@recommended');
    Route::get('genreBooks/{id}', 'user\BooksreadController@genreBooks');
    Route::get('departmentBooks/{id}', 'user\BooksreadController@departmentBooks');
    Route::apiResource('app_subscription', 'Subscription\SubscriptionController');
    Route::post('cancle_subscription', 'Subscription\SubscriptionController@cancleSubscription');
   
});

Route::get('available_plan_list', 'Subscription\SubscriptionController@index');
Route::resource('app_departments', 'app_departmentController');
Route::resource('app_subjects', 'app_subjectController');

// https://ebook.netbookflix.com/admin/save-teacher-detail?teacher_name=demo&mobile_no=987654321&email=demo@gmail.com&institute_name=demo&department=demo&designation=demo&subject_taught=demo&resource_planning=demo&teaching_resource=demo&student_strength=demo
