<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\api\UserAuthController;
use App\Http\Controllers\NotificationController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::post('register',[UserAuthController::class,'register']);
Route::post('login',[UserAuthController::class,'login']);
Route::get('specialists',[UserAuthController::class,'specialists']);
Route::get('category',[UserAuthController::class,'category']);
Route::get('language',[UserAuthController::class,'language']);
Route::get('accent',[UserAuthController::class,'accent']);
Route::get('cities',[UserAuthController::class,'cities']);
Route::get('countries',[UserAuthController::class,'countries']);
Route::get('nationalities',[UserAuthController::class,'nationalities']);
Route::get('selectionData',[UserAuthController::class,'selectionData']);
Route::get('days',[UserAuthController::class,'days']);
Route::get('checkMobile',[UserAuthController::class,'checkMobile']);
Route::post('updatePassword',[UserAuthController::class,'updatePassword']);
Route::post('send_otp',[UserAuthController::class,'sendOtp']);
Route::post('send_otp_again',[UserAuthController::class,'sendOtpAgain']);

Route::post('/send-fcm', [NotificationController::class, 'sendNotification']);

Route::middleware('auth:sanctum')->group( function () {
    Route::post('logout',[UserAuthController::class,'logout']);
    Route::post('otp_confirmed',[UserAuthController::class,'otp_confirmed']);
    Route::get('send_otp_get/{mobile}/{otp}',[UserAuthController::class,'sendGetOtp']);
    Route::post('userNotifications',[UserAuthController::class,'userNotifications']);
    Route::post('listDoctors',[UserAuthController::class,'listDoctors']);
    Route::post('listDoctorDetails',[UserAuthController::class,'listDoctorDetails']);
    Route::post('userBookings',[UserAuthController::class,'userBookings']);
    Route::post('doctorSessionNote',[UserAuthController::class,'doctorSessionNote']);
    Route::post('confirmBookingZoom',[UserAuthController::class,'confirmBookingZoom']);
    Route::post('confirmBookingChat',[UserAuthController::class,'confirmBookingChat']);
    
    Route::post('addDoctorPersonal',[UserAuthController::class,'addDoctorPersonal']);
    Route::post('updateDoctorPersonal',[UserAuthController::class,'updateDoctorPersonal']);
    Route::post('updateDoctorDocuments',[UserAuthController::class,'updateDoctorDocuments']);
    Route::post('showDoctorCertificates',[UserAuthController::class,'showDoctorCertificates']);
    Route::post('addDoctorCertificate',[UserAuthController::class,'addDoctorCertificate']);
    Route::post('showDoctorPhds',[UserAuthController::class,'showDoctorPhds']);
    Route::post('showDoctorExperiences',[UserAuthController::class,'showDoctorExperiences']);
    Route::post('addTimeSchedule',[UserAuthController::class,'addTimeSchedule']);
    Route::post('deleteTimeSchedule',[UserAuthController::class,'deleteTimeSchedule']);
    Route::post('listDoctorTimeSchedule',[UserAuthController::class,'listDoctorTimeSchedule']);
    Route::post('listDoctorTimeScheduleByDay',[UserAuthController::class,'listDoctorTimeScheduleByDay']);
    Route::post('doctorNotifications',[UserAuthController::class,'doctorNotifications']);
    Route::post('popularDoctors',[UserAuthController::class,'popularDoctors']);
    Route::post('doctorDetails',[UserAuthController::class,'doctorDetails']);
    Route::post('doctorShortDetails',[UserAuthController::class,'doctorShortDetails']);
    Route::post('doctorToken',[UserAuthController::class,'doctorToken']);
    Route::post('doctorBookings',[UserAuthController::class,'doctorBookings']);
    Route::post('appointmentsBooking',[UserAuthController::class,'appointmentsBooking']);
    Route::post('checkDoctorAppointment',[UserAuthController::class,'checkDoctorAppointment']);
    Route::post('specialistsDepartments',[UserAuthController::class,'specialistsDepartments']);
    Route::post('specialistsDepartmentsDoctors',[UserAuthController::class,'specialistsDepartmentsDoctors']);
    Route::post('specialistListDoctors',[UserAuthController::class,'specialistListDoctors']);
    Route::post('mobilePrivacyPolicy',[UserAuthController::class,'mobilePrivacyPolicy']);
    Route::post('userJoinSession',[UserAuthController::class,'userJoinSession']);
    Route::post('userCancelSession',[UserAuthController::class,'userCancelSession']);
    Route::post('doctorJoinSession',[UserAuthController::class,'doctorJoinSession']);
    Route::post('doctorCancelSession',[UserAuthController::class,'doctorCancelSession']);
    Route::post('doctorChatJoinSession',[UserAuthController::class,'doctorChatJoinSession']);
    Route::post('doctorChatCancelSession',[UserAuthController::class,'doctorChatCancelSession']);
    Route::post('userChatJoinSession',[UserAuthController::class,'userChatJoinSession']);
    Route::post('userChatCancelSession',[UserAuthController::class,'userChatCancelSession']);
    Route::post('userBanner',[UserAuthController::class,'userBanner']);
    Route::post('doctorBanner',[UserAuthController::class,'doctorBanner']);
    Route::post('userAdvs',[UserAuthController::class,'userAdvs']);
    Route::post('changeConsultantStatus',[UserAuthController::class,'changeConsultantStatus']);
    Route::post('doctorAdvs',[UserAuthController::class,'doctorAdvs']);
    Route::post('userReviews',[UserAuthController::class,'userReviews']);
    Route::post('userAddReviews',[UserAuthController::class,'userAddReviews']);
    Route::post('userUpdateReview',[UserAuthController::class,'userUpdateReview']);
    Route::post('userCollectsList',[UserAuthController::class,'userCollectsList']);
    Route::post('userCollectRequest',[UserAuthController::class,'userCollectRequest']);
    Route::post('userCollectRequests',[UserAuthController::class,'userCollectRequests']);
    
    

    Route::post('userDeleteReview',[UserAuthController::class,'userDeleteReview']);
    Route::post('doctorReviews',[UserAuthController::class,'doctorReviews']);
    Route::post('paymentTransactionStore',[UserAuthController::class,'paymentTransactionStore']);
    Route::post('mobileTermsAndConditions',[UserAuthController::class,'mobileTermsAndConditions']);
    Route::post('helpCenter',[UserAuthController::class,'helpCenter']);
    // Route::post('rate',[UserAuthController::class,'rate']);
    Route::post('userPaymentSavedCards',[UserAuthController::class,'userPaymentSavedCards']);
    Route::post('listUserPaymentCards',[UserAuthController::class,'listUserPaymentCards']);
    Route::post('userWallet',[UserAuthController::class,'userWallet']);
    Route::post('userUpdate',[UserAuthController::class,'userUpdate']);
    // Route::post('doctorUpdate',[UserAuthController::class,'doctorUpdate']);

    Route::post('doctorHomePage',[UserAuthController::class,'doctorHomePage']);
    Route::post('bookingFilter',[UserAuthController::class,'bookingFilter']);


    // consultant
    Route::post('scheduleGenerate',[UserAuthController::class,'scheduleGenerate']);
    Route::post('updateBio',[UserAuthController::class,'updateBio']);
    Route::post('updateDoctorFees',[UserAuthController::class,'updateDoctorFees']);
    Route::post('updateDoctorExperienceYrs',[UserAuthController::class,'updateDoctorExperienceYrs']);

    Route::post('updateID',[UserAuthController::class,'updateID']);
    Route::post('updateDoctorah',[UserAuthController::class,'updateDoctorah']);
    Route::post('updateCertificates',[UserAuthController::class,'updateCertificates']);
    Route::post('updateDoctorSpecialists',[UserAuthController::class,'updateDoctorSpecialists']);
    Route::post('doctorBookingNotes',[UserAuthController::class,'doctorBookingNotes']);
    Route::post('doctorBookingCancelNotes',[UserAuthController::class,'doctorBookingCancelNotes']);
    Route::post('userInfo',[UserAuthController::class,'userInfo']);
    Route::post('doctorSpecialistsSelector',[UserAuthController::class,'doctorSpecialistsSelector']);
    Route::post('doctorSpecialistsUpdate',[UserAuthController::class,'doctorSpecialistsUpdate']);
    Route::post('doctorPhotoUpdate',[UserAuthController::class,'doctorPhotoUpdate']);
    Route::post('doctorOffDurations',[UserAuthController::class,'doctorOffDurations']);
    Route::post('deleteDoctorOffDurations',[UserAuthController::class,'deleteDoctorOffDurations']);
    Route::post('doctorVisitorsAdd',[UserAuthController::class,'doctorVisitorsAdd']);
    Route::post('listDoctorOffDurations',[UserAuthController::class,'listDoctorOffDurations']);
    Route::post('userNotificationOpened',[UserAuthController::class,'userNotificationOpened']);
    Route::post('userAllNotificationsOpened',[UserAuthController::class,'userAllNotificationsOpened']);
    Route::post('checkBookingAvailableTest',[UserAuthController::class,'checkBookingAvailableTest']);
    Route::post('drRevCollect',[UserAuthController::class,'drRevCollect']);
    Route::post('doctorRevenue',[UserAuthController::class,'doctorRevenue']);
    Route::post('doctorRevenueDurations',[UserAuthController::class,'doctorRevenueDurations']);
    Route::post('doctorCollectRequest',[UserAuthController::class,'doctorCollectRequest']);
    Route::post('doctorCollectRequests',[UserAuthController::class,'doctorCollectRequests']);
    Route::post('doctorInfo',[UserAuthController::class,'doctorInfo']);
    Route::post('customerSupport',[UserAuthController::class,'customerSupport']);
});