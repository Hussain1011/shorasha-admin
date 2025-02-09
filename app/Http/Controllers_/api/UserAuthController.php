<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use DB;
use Auth;
use Carbon\Carbon;
use Softon\Sms\Facades\Sms;
use Illuminate\Support\Str;

class UserAuthController extends Controller
{
    public function register(Request $request){
        # validations
        if($request->name == ''){
            return response()->json([
                'status' => 'error',
                'message_en'=>'Name Is Empty',
                'message_ar'=>'الاسم مطلوب',
                // 'token' => $token
            ]);
        }

        if($request->email == ''){
            return response()->json([
                'status' => 'error',
                'message_en'=>'Email Is Empty',
                'message_ar'=>'البريد مطلوب',
                // 'token' => $token
            ]);
        }

        if($request->password == ''){
            return response()->json([
                'status' => 'error',
                'message_en'=>'Password Is Empty',
                'message_ar'=>'كلمة المرور مطلوبة',
                // 'token' => $token
            ]);
        }

        if($request->mobile == ''){
            return response()->json([
                'status' => 'error',
                'message_en'=>'Mobile Is Empty',
                'message_ar'=>'الهاتف مطلوب',
                // 'token' => $token
            ]);
        }
        # check email exist
        $checkEmailExist = DB::table('users')->where('email', $request->email)->count();
        if($checkEmailExist > 0){
            return response()->json([
                'status' => 'error',
                'message_en'=>'Email Exist',
                'message_ar'=>'البريد موجود بالفعل',
                // 'token' => $token
            ]);
        }
        // dd($checkEmailExist);
        # check mobile exist
        $checkMobileExist = DB::table('users')->where('mobile', $request->mobile)->count();
        if($checkMobileExist > 0){
            return response()->json([
                'status' => 'error',
                'message_en'=>'Mobile Exist',
                'message_ar'=>'الهاتف موجود بالفعل',
                // 'token' => $token
            ]);
        }

        $user = User::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
            'user_type' => $request->user_type,
            'mobile' => $request->mobile,
            'device_token' => $request->device_token
        ]);

        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;
        $user = User::where('mobile',$request->mobile)->first();
        if(is_null($user)){
            return response()->json([
                
                'status' => 'error',
                'message_en' => 'Not Found',
                'message_ar' => 'غير موجود'
            ]);
        }
        $user_personal = DB::table('user_personal')->where('user_id', $user->id)->first();
        if(!$user || !Hash::check($request['password'],$user->password)){
            return response()->json([
                'status' => 'error',
                'message_en' => 'Invalid Credentials',
                'message_ar' => 'البيانات غير صحيحة'
            ],401);
        }
        // $accessToken = Auth::user()->createToken('auth-token')->accessToken;
        return response()->json([
            'status' => 'success',
            'message_en'=>'User Registered Success',
            'message_ar'=>'تم التسجيل بنجاح',
            'access_token' => $token,
            'user' => $user,
            'user_personal' =>$user_personal
        ]);
    }

    public function login(Request $request){
        # user data
        // $user = DB::table('users')->where('mobile', $request->mobile)->where('user_type', $request->user_type)->first();
        
        $user = User::where('mobile',$request->mobile)->first();//->where('user_type', $request->user_type)->first();
        // dd($user, $request);
        if(is_null($user)){
            return response()->json([
                'status' => 'error',
                'message_en' => 'Not Found',
                'message_ar' => 'غير موجود'
            ]);
        }
        # check otp 
        // if($user->otp_confirmed == 0){
        //     return response()->json([
        //         'status' => 'error',
        //         'message_en' => 'The Mobile Not Activated Yet',
        //         'message_ar' => 'هذا الهاتف غير مفعل بعد'
        //     ]);
        // }
        
        $user_personal = DB::table('user_personal')->where('user_id', $user->id)->first();
        if(!$user || !Hash::check($request['password'],$user->password)){
            return response()->json([
                'status' => 'error',
                'message_en' => 'Invalid Credentials',
                'message_ar' => 'البيانات غير صحيحة'
            ],401);
        }
        $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;
        # update device_token
        $updateDeviceToken = DB::table('users')->where('id', $user->id)->update(['device_token' => $request->device_token]);

        # send push notification test
        # send firebase push notification
        $SERVER_API_KEY = 'AAAAqICtRL8:APA91bEouQSzCcYpDHy3Sec3xYsNfQVQHOj2VxBFON6PuBC1Rqga2ycgfq6YRpNLablpBrjVmd1YII7tejs_u_KkU8d_8pMzXcjmh8gM3QUSmqA0AjQ9iRCr1Ml9GUR5QIMGUhGf102G';//'AAAAj5ate9k:APA91bFg7DXD_RjKee3xbK1sVFdC87cg-bZbSwio8qmRnEMwWMS50LmILAy9Ot5NJZn3Kj8IEaO6lufZN5UPYanS7VATLhsumKq4_7vBv04IS-_YXAi8RIEZkRCfVLXUgA5qQgI8ktln';
        // $token_1 = 'ekGDNzZtS-mgE5S9BicTly:APA91bGKheG-0AABfNtIYOJRAHYTu34oOQOaW9HCrcjP-N4Jt8e82YJGnS71MnxARQmRquBou_JRFEGCwTBYGZ1XGz50GPrzhG4W5_3o0IvBqKfqFijPRET22uJr7ZzxLhI2JWb5qawS';
        $data = [
            "registration_ids" => [
                $request->device_token//$token_1
            ],
            "notification" => [
                "title" => 'عملية دخول جديدة',
                "body" => 'تم تسجيل عملية دخول جديدة لحسابك',
                "sound"=> "default" // required for sound on ios
            ],
            "data" => [
                "type" => 'payment'
            ]
        ];

        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);

        return response()->json([
            'access_token' => $token,
            'message_en' => 'logged Success',
            'message_ar' => 'تم الدخول بنجاح',
            'status' => 'success',
            'user' => $user,
            'user_personal' =>$user_personal
        ]);
    }

    public function logout(Request $req){
        // dd(Auth::user()->id);
        DB::table('personal_access_tokens')->where('tokenable_id', Auth::user()->id)->delete();
        // dd($req, auth()->user()->tokens());
        // auth()->user()->tokens()->delete();
    
        return response()->json([
            'status'=>'success',
            "message_en"=>"logged out",
            "message_ar"=>"تم تسجيل الخروج"
        ]);
    }
    

    public function otp_confirmed(Request $req){
        # otp verified
        $user= DB::table('users')->where('id', $req->user_id)->first();
      
        if($req->otp_confirmed == 'true'){
            $updated = DB::table('users')
            ->where('id', $req->user_id)
            ->update([
                'otp_confirmed' => 1,
                // 'user_id' =>$req->user_id
            ]);

            $created = Carbon::now();
            # db notification
            $notified = DB::table('user_notifications')->insert([
                'user_id' =>  $req->user_id,
                "title_ar" => 'تفعيل الهاتف',
                "content_ar" => 'تم تفعيل هاتفك وربطه بحسابك',
                "title_en" => 'Mobile Activating',
                "content_en" => 'Your Mobile Activated Success',
                'created' => $created->getTimestampMs().'-'.$req->user_id,
                'read' => 0
            ]);

            # send push notification test
            # send firebase push notification
            $SERVER_API_KEY = 'AAAAqICtRL8:APA91bEouQSzCcYpDHy3Sec3xYsNfQVQHOj2VxBFON6PuBC1Rqga2ycgfq6YRpNLablpBrjVmd1YII7tejs_u_KkU8d_8pMzXcjmh8gM3QUSmqA0AjQ9iRCr1Ml9GUR5QIMGUhGf102G';//'AAAAj5ate9k:APA91bFg7DXD_RjKee3xbK1sVFdC87cg-bZbSwio8qmRnEMwWMS50LmILAy9Ot5NJZn3Kj8IEaO6lufZN5UPYanS7VATLhsumKq4_7vBv04IS-_YXAi8RIEZkRCfVLXUgA5qQgI8ktln';
            // $token_1 = 'ekGDNzZtS-mgE5S9BicTly:APA91bGKheG-0AABfNtIYOJRAHYTu34oOQOaW9HCrcjP-N4Jt8e82YJGnS71MnxARQmRquBou_JRFEGCwTBYGZ1XGz50GPrzhG4W5_3o0IvBqKfqFijPRET22uJr7ZzxLhI2JWb5qawS';
            $data = [
                "registration_ids" => [
                    $user->device_token//$token_1
                ],
                "notification" => [
                    "title" => 'تفعيل الهاتف',
                    "body" => 'تم تفعيل هاتفك وربطه بحسابك',
                    "sound"=> "default" // required for sound on ios
                ],
                "data" => [
                    "type" => 'payment'
                ]
            ];

            $dataString = json_encode($data);
            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $response = curl_exec($ch);

            return response()->json([
                "status"=> 'success',
                'message_en'=>'OTP Confirmed Success',
                'message_ar'=>'تم تأكيد الكود'
            ]);
        }else{
            return response()->json([
                "status"=> 'error'
            ]);
        }
    }

    public function sendOtp(Request $req){
        # check mobile exist
        $existRecord = DB::table('users')->where('mobile',$req->mobile)->count();
        
        if($existRecord < 1){
            return response()->json([
                "status"=> 'error',
                'message_en'=>'Mobile Not Found',
                'message_ar'=>'هذا الهاتف ليس لدينا'
            ]);
        }

        # check have sam code don't send again
        $checkExistOtpUser = DB::table('users')->where('otp', $req->otp)->where('mobile', $req->mobile)->count();
        if($checkExistOtpUser > 0){
            return response()->json([
                "status"=> 'error',
                'message_en'=>'Already Otp Was Sent Before',
                'message_ar'=>'تم ارسال مود التفعيل بالفعل من قبل'
            ]);
        }
        # mobile, otp
        // $digits = 6;
        // $otpCode =  str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
        $otpMessage = 'Shorasha OTP Code is: '.$req->otp.'';
        $mobile = $req->mobile;
        $otp = $req->otp;
        # sms test
        //Please Enter Your Details
        $user="Naraakom"; //your username
        $password="B@sirah33QA"; //your password
        $mobilenumbers=$mobile; //enter Mobile numbers comma seperated
        $message = $otpMessage; //enter Your Message
        $senderid="Shorasha"; //Your senderid
        $messagetype="N"; //Type Of Your Message
        $DReports="Y"; //Delivery Reports
        $url="http://www.smscountry.com/SMSCwebservice_Bulk.aspx";
        // $message = urlencode($message);
        $ch = curl_init();
        if (!$ch){die("Couldn't initialize a cURL handle");}
        $ret = curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt ($ch, CURLOPT_POSTFIELDS,
        "User=$user&passwd=$password&mobilenumber=$mobilenumbers&message=$message&sid=$senderid&mtype=$messagetype&DR=$DReports");
        $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //If you are behind proxy then please uncomment below line and provide your proxy ip with port.
        // $ret = curl_setopt($ch, CURLOPT_PROXY, "PROXY IP ADDRESS:PORT");
        $curlresponse = curl_exec($ch); // execute
        if(curl_errno($ch))
            echo 'curl error : '. curl_error($ch);
        if (empty($ret)) {
            // some kind of an error happened
            die(curl_error($ch));
            curl_close($ch); // close cURL handler
        } else {
            $info = curl_getinfo($ch);
            curl_close($ch); // close cURL handler
            // echo $curlresponse; //echo "Message Sent Succesfully" ;
        }
        # insert otp and message in db
        DB::table('users')->where('mobile', $mobilenumbers)->update([
            'otp' =>$otp,
            'otp_message' => $message
        ]);
        
        return response()->json([
            "status"=> 'success',
            'message_en'=>'OTP Sent Success',
            'message_ar'=>'تم ارسال الكود'
        ]);
    }

    public function sendOtpAgain(Request $req){
        # check mobile exist
        $existRecord = DB::table('users')->where('mobile',$req->mobile)->count();
        
        if($existRecord < 1){
            return response()->json([
                "status"=> 'error',
                'message_en'=>'Mobile Not Found',
                'message_ar'=>'هذا الهاتف ليس لدينا'
            ]);
        }

        # check have sam code don't send again
        // $checkExistOtpUser = DB::table('users')->where('otp', $req->otp)->where('mobile', $req->mobile)->count();
        // if($checkExistOtpUser > 0){
        //     return response()->json([
        //         "status"=> 'error',
        //         'message_en'=>'Already Otp Was Sent Before',
        //         'message_ar'=>'تم ارسال مود التفعيل بالفعل من قبل'
        //     ]);
        // }
        # mobile, otp
        // $digits = 6;
        // $otpCode =  str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
        $otpMessage = 'Shorasha OTP Code is: '.$req->otp.'';
        $mobile = $req->mobile;
        $otp = $req->otp;
        # sms test
        //Please Enter Your Details
        $user="Naraakom"; //your username
        $password="B@sirah33QA"; //your password
        $mobilenumbers=$mobile; //enter Mobile numbers comma seperated
        $message = $otpMessage; //enter Your Message
        $senderid="Shorasha"; //Your senderid
        $messagetype="N"; //Type Of Your Message
        $DReports="Y"; //Delivery Reports
        $url="http://www.smscountry.com/SMSCwebservice_Bulk.aspx";
        // $message = urlencode($message);
        $ch = curl_init();
        if (!$ch){die("Couldn't initialize a cURL handle");}
        $ret = curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt ($ch, CURLOPT_POSTFIELDS,
        "User=$user&passwd=$password&mobilenumber=$mobilenumbers&message=$message&sid=$senderid&mtype=$messagetype&DR=$DReports");
        $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //If you are behind proxy then please uncomment below line and provide your proxy ip with port.
        // $ret = curl_setopt($ch, CURLOPT_PROXY, "PROXY IP ADDRESS:PORT");
        $curlresponse = curl_exec($ch); // execute
        if(curl_errno($ch))
            echo 'curl error : '. curl_error($ch);
        
        if (empty($ret)) {
            // some kind of an error happened
            die(curl_error($ch));
            curl_close($ch); // close cURL handler
        } else {
            $info = curl_getinfo($ch);
            curl_close($ch); // close cURL handler
            // echo $curlresponse; //echo "Message Sent Succesfully" ;
            # insert otp and message in db
            DB::table('users')->where('mobile', $mobilenumbers)->update([
                'otp' =>$otp,
                'otp_message' => $message
            ]);
            
            return response()->json([
                "status"=> 'success',
                'message_en'=>'OTP Sent Success',
                'message_ar'=>'تم ارسال الكود'
            ]);
        }
    }
    


    public function sendGetOtp($mobile, $otp){
        # check mobile exist
        $existRecord = DB::table('users')->where('mobile',$mobile)->count();
        if($existRecord < 1){
            return response()->json([
                "status"=> 'error',
                'message_en'=>'Mobile Not Found',
                'message_ar'=>'هذا الهاتف ليس لدينا'
            ]);
        }
        # mobile, otp
        // $digits = 6;
        // $otpCode =  str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);
        $otpMessage = 'Shorasha OTP Code is: '.$otp.'';
        $mobile = $mobile;
        // $otp = $otp;
        # sms test
        //Please Enter Your Details
        $user="Naraakom"; //your username
        $password="B@sirah33QA"; //your password
        $mobilenumbers=$mobile; //enter Mobile numbers comma seperated
        $message = $otpMessage; //enter Your Message
        $senderid="Shorasha"; //Your senderid
        $messagetype="N"; //Type Of Your Message
        $DReports="Y"; //Delivery Reports
        $url="http://www.smscountry.com/SMSCwebservice_Bulk.aspx";
        // $message = urlencode($message);
        $ch = curl_init();
        if (!$ch){die("Couldn't initialize a cURL handle");}
        $ret = curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt ($ch, CURLOPT_POSTFIELDS,
        "User=$user&passwd=$password&mobilenumber=$mobilenumbers&message=$message&sid=$senderid&mtype=$messagetype&DR=$DReports");
        $ret = curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //If you are behind proxy then please uncomment below line and provide your proxy ip with port.
        // $ret = curl_setopt($ch, CURLOPT_PROXY, "PROXY IP ADDRESS:PORT");
        $curlresponse = curl_exec($ch); // execute
        if(curl_errno($ch))
            echo 'curl error : '. curl_error($ch);
        if (empty($ret)) {
            // some kind of an error happened
            die(curl_error($ch));
            curl_close($ch); // close cURL handler
        } else {
            $info = curl_getinfo($ch);
            curl_close($ch); // close cURL handler
            // echo $curlresponse; //echo "Message Sent Succesfully" ;
            # insert otp and message in db
            DB::table('users')->where('mobile', $mobilenumbers)->update([
                'otp' =>$otp,
                'otp_message' => $message
            ]);
            
            return response()->json([
                "status"=> 'success',
                'message_en'=>'OTP Sent Success',
                'message_ar'=>'تم ارسال الكود'
            ]);
        }

        return response()->json([
            "status"=> 'success',
            'message_en'=>'OTP Sent Success',
            'message_ar'=>'تم ارسال الكود'
        ]);
    }



    public function addDoctorPersonal(Request $req){
        $specialists = explode(',', $req->specialist_id);
        $photo='';
        $profile='';
        $certificate='';
        // $passport='';
        $dateBirth = Carbon::create($req->date_birth);
        # validations
        if($req->user_id ==''){
            return response()->json([
                "status"=> 'error',
                'message_en' => 'Consultant Is Required!',
                'message_ar' => 'الاستشارى مطلوب'
            ]);
        }

        // if($req->city_id ==''){
        //     return response()->json([
        //         "status"=> 'error',
        //         'message_en' => 'City Is Required!',
        //         'message_ar' => 'المدينة مطلوبة'
        //     ]);
        // }

        if($req->city_title ==''){
            return response()->json([
                "status"=> 'error',
                'message_en' => 'City Is Required!',
                'message_ar' => 'المدينة مطلوبة'
            ]);
        }


        if($req->nationality_id ==''){
            return response()->json([
                "status"=> 'error',
                'message_en' => 'Nationality_id Is Required!',
                'message_ar' => 'الجنسية مطلوبة'
            ]);
        }

        if($specialists[0] == '0' || $specialists[0] == ''){
            return response()->json([
                "status"=> 'error',
                'message_en' => 'Specialist Is Required!',
                'message_ar' => 'التخصص مطلوب'
            ]);
        }
        if(count($specialists) < 1){
            return response()->json([
                "status"=> 'error',
                'message_en' => 'Specialist Is Required!',
                'message_ar' => 'التخصص مطلوب'
            ]);
        }

        if($req->country_id ==''){
            return response()->json([
                "status"=> 'error',
                'message_en' => 'Country Is Required!',
                'message_ar' => 'البلد مطلوبة'
            ]);
        }

        if($req->date_birth ==''){
            return response()->json([
                "status"=> 'error',
                'message_en' => 'Date Birth Is Required!',
                'message_ar' => 'تاريخ الميلاد مطلوب'
            ]);
        }

        if($req->certificate ==''){
            return response()->json([
                "status"=> 'error',
                'message_en' => 'Certificate Is Required!',
                'message_ar' => 'الشهادة مطلوبة'
            ]);
        }

        if($req->id_expired_date ==''){
            return response()->json([
                "status"=> 'error',
                'message_en' => 'ID expired date is required!',
                'message_ar' => 'تاريخ الصلاحية مطلوب'
            ]);
        }

        // if($req->passport_id ==''){
        //     return response()->json([
        //         "status"=> 'error',
        //         'message_en' => 'ID Is Required!',
        //         'message_ar' => 'تحقيق الشخصية مطلوبة'
        //     ]);
        // }

        if($req->fees ==''){
            return response()->json([
                "status"=> 'error',
                'message_en' => 'Fees Is Required!',
                'message_ar' => 'التكلفة مطلوبة'
            ]);
        }

        // if($req->cv ==''){
        //     return response()->json([
        //         "status"=> 'error',
        //         'message_en' => 'CV Is Required!',
        //         'message_ar' => 'السيرة الذاتية مطلوبة'
        //     ]);
        // }

        if($req->experience_years =='' || is_null($req->experience_years)){
            return response()->json([
                "status"=> 'error',
                'message_en' => 'ُExperience Years Required',
                'message_ar' => 'سنوات الخبرة مطلوبة'
            ]);
        }

        if($req->id_face_1 =='' || is_null($req->id_face_1)){
            return response()->json([
                "status"=> 'error',
                'message_en' => 'ID Face One Required',
                'message_ar' => 'تحقيق الشخصية الامامية مطلوبة'
            ]);
        }

        if($req->id_face_2 =='' || is_null($req->id_face_2)){
            return response()->json([
                "status"=> 'error',
                'message_en' => 'ID Face Two Required',
                'message_ar' => 'تحقيق الشخصية الخلفية مطلوبة'
            ]);
        }

        # upload user photo if have
        if(!is_null($req->photo)){
            $photo = $req->user_id.'.'.$req->photo->extension();  
            $req->photo->move(public_path('uploads/photos/doctors/'), $photo);
            $photoUrl = url('/public/uploads/photos/doctors/'.$photo);
        }

       # upload user id face 1 if have
       if(!is_null($req->id_face_1)){
        $id_face_1 = $req->user_id.'.'.$req->id_face_1->extension();  
        $req->id_face_1->move(public_path('uploads/IDs/'), $id_face_1);
        $id_face_1Url = url('/public/uploads/IDs/'.$id_face_1);
    }

        # upload user id face 2 if have
        if(!is_null($req->id_face_2)){
            $id_face_2 = $req->user_id.'.'.$req->id_face_2->extension();  
            $req->id_face_2->move(public_path('uploads/IDs/'), $id_face_2);
            $id_face_2Url = url('/public/uploads/IDs/'.$id_face_2);
        }

        # upload user cv
        if(!is_null($req->cv)){
            $cv = $req->user_id.'.'.$req->cv->extension();  
            $req->cv->move(public_path('uploads/cv/doctors/'), $cv);
            $cvUrl = url('/public/uploads/cv/doctors/'.$cv);
        }
        # upload user certificate
        if(!is_null($req->certificate)){
            $certificate = $req->user_id.'.'.$req->certificate->extension();  
            $req->certificate->move(public_path('uploads/certificates/doctors/'), $certificate);
            $certificateUrl = url('/public/uploads/certificates/doctors/'.$certificate);
        }
        # upload user passport_id
        // if(!is_null($req->passport_id)){
        //     $passport = $req->user_id.'.'.$req->passport_id->extension();  
        //     $req->passport_id->move(public_path('uploads/passports/doctors/'), $passport);
        // }
        # check exist
        $count = DB::table('user_personal')->where('user_id', Auth::user()->id)->count();

        # delete doctor specialists
        $deleted = DB::table('doctor_specialists')->where('doctor_id', Auth::user()->id)->delete();
        if($count > 0){
        # updating
            
        $updated = DB::table('user_personal')->where('user_id', Auth::user()->id)->update([
            'user_id' => Auth::user()->id,
            'address' =>$req->address,
            // 'city_id'=>$req->city_id,
            'city_title' => $req->city_title,
            'city_title'=>$req->city_title,
            'nationality_id' => $req->nationality_id,
            'id_expired_date' => $req->id_expired_date,
            'country_id'=>$req->country_id,
            'date_birth' =>$dateBirth,
            'specialist_id' => $specialists[0],
            // 'id_expired_date' => $id_expired_date,
            'photo' => $photoUrl,//asset('public/uploads/photo/doctors').'/'.$photo.'',
            'cv' => (is_null($req->cv))? null : $cvUrl,//asset('public/uploads/cv/doctors').'/'.$cv.'',
            'certificate' => $certificateUrl, //asset('public/uploads/certificates/doctors').'/'.$certificate.'',
            'fees' => $req->fees,
            'experience_yrs' => $req->experience_years,
            'id_face_1' => $id_face_1Url,
            'id_face_2' =>$id_face_2Url
        ]);

            # add specialists to doctor
            foreach ($specialists as $key => $value) {
                $checkExist = DB::table('doctor_specialists')->where('doctor_id', Auth::user()->id)->where('specialist_id', $value)->count();
                // dd($value, $checkExist);
                if($checkExist < 1){
                    $created = Carbon::now();
                    $inserted = DB::table('doctor_specialists')->insert([
                        'doctor_id' => Auth::user()->id,
                        'specialist_id' => $value,
                        'active' => 1,
                        'created' => $created->getTimestampMs().'-'.$key
                    ]);
                }
            }

            return response()->json([
                "status"=> 'success',
                'message_en' => 'Updated Success',
                'message_ar' => 'تم التحديث بنجاح'
            ]);

        }else{
            # inserting
            $created = Carbon::now();
            $inserted = DB::table('user_personal')->insert([
                'user_id' => Auth::user()->id,
                'address' =>$req->address,
                // 'city_id'=>$req->city_id,
                'city_title' => $req->city_title,
                'nationality_id' => $req->nationality_id,
                'id_expired_date' => $req->id_expired_date,
                'country_id'=>$req->country_id,
                'date_birth' =>$dateBirth,
                'photo' => (is_null($photo))? null : asset('public/uploads/photos/doctors').'/'.$photo.'',
                'certificate' => (is_null($certificate))? null : asset('public/uploads/certificates').'/'.$certificate.'',
                // 'cv' => (is_null($cv))? null : asset('public/uploads/cv/doctors').'/'.$cv.'',
                'cv' => (is_null($req->cv))? null : $cvUrl,
                'fees' => $req->fees,
                'specialist_id' => $specialists[0],
                'id_face_1' => $id_face_1Url,
                'id_face_2' =>$id_face_2Url,
                'experience_yrs' => $req->experience_years,
                'created' => $created->getTimestampMs()

            ]);

             # add specialists to doctor
             foreach ($specialists as $key => $value) {
                $checkExist = DB::table('doctor_specialists')->where('doctor_id', Auth::user()->id)->where('specialist_id', $value)->count();
                // dd($value, $checkExist);
                if($checkExist < 1){
                    $created = Carbon::now();
                    $inserted = DB::table('doctor_specialists')->insert([
                        'doctor_id' => Auth::user()->id,
                        'specialist_id' => $value,
                        'active' => 1,
                        'created' => $created->getTimestampMs().'-'.$key
                    ]);
                }
            }

            return response()->json([
                "status"=> 'success',
                'message_en' => 'Inserted Success',
                'message_ar' => 'تم الاضافة بنجاح'
            ]);
        }
       

    }



    public function updateDoctorPersonal(Request $req){
        $specialists = explode(',', $req->specialist_id);
        // dd(count($specialists), $specialists);
        $photo='';
        $profile='';
        $certificate='';
        $passport='';
        $dateBirth = Carbon::create($req->date_birth);
        # validations
        if($req->user_id ==''){
            return response()->json([
                "status"=> 'error',
                'message_en' => 'User Is Required!',
                'message_ar' => 'المستخدم مطلوب'
            ]);
        }

        // if($req->city_id !=''){
        //     # update city
        //     $updateCity = DB::table('user_personal')->where('user_id', $req->user_id)->update(['city_id' => $req->city_id]);
        // }

        if($req->city_title !='' || !(is_null($req->city_title))){
            //     # update city
                $updateCity = DB::table('user_personal')->where('user_id', $req->user_id)->update(['city_title' => $req->city_title]);
        }

        if($req->nationality_id !=''){
            # update city
            $updateCity = DB::table('user_personal')->where('user_id', $req->user_id)->update(['nationality_id' => $req->nationality_id]);
        }

        if($req->country_id !=''){
            # update city
            $updateCountry = DB::table('user_personal')->where('user_id', $req->user_id)->update(['country_id' => $req->country_id]);
        }

        if($specialists[0] == '0' || $specialists[0] == ''){
            return response()->json([
                "status"=> 'error',
                'message_en' => 'Specialist Is Required!',
                'message_ar' => 'التخصص مطلوب'
            ]);
        }
       

        if($req->date_birth ==''){
            return response()->json([
                "status"=> 'error',
                'message_en' => 'Date Birth Is Required!',
                'message_ar' => 'تاريخ الميلاد مطلوب0'
            ]);
        }

        if($req->certificate ==''){
            return response()->json([
                "status"=> 'error',
                'message_en' => 'Certificate Is Required!',
                'message_ar' => 'الشهادة مطلوبة'
            ]);
        }

        // if($req->passport_id ==''){
        //     return response()->json([
        //         "status"=> 'error',
        //         'message_en' => 'ID Is Required!',
        //         'message_ar' => 'تحقيق الشخصية مطلوبة'
        //     ]);
        // }

        if($req->fees ==''){
            return response()->json([
                "status"=> 'error',
                'message_en' => 'Fees Is Required!',
                'message_ar' => 'التكلفة مطلوبة'
            ]);
        }

        // if($req->cv ==''){
        //     return response()->json([
        //         "status"=> 'error',
        //         'message_en' => 'Profile Is Required!',
        //         'message_ar' => 'السيرة الذاتية مطلوبة'
        //     ]);
        // }

        # upload user photo if have
        if(!is_null($req->photo)){
            $photo = $req->user_id.'.'.$req->photo->extension();  
            $req->photo->move(public_path('uploads/photos/'), $photo);
        }

        # upload user profile
        if(!is_null($req->profile)){
            $profile = $req->user_id.'.'.$req->profile->extension();  
            $req->profile->move(public_path('uploads/cv/doctors/'), $profile);
        }
        # upload user certificate
        if(!is_null($req->certificate)){
            $certificate = $req->user_id.'.'.$req->certificate->extension();  
            $req->certificate->move(public_path('uploads/certificates/'), $certificate);
        }
        # upload user passport_id
        // if(!is_null($req->passport_id)){
        //     $passport = $req->user_id.'.'.$req->passport_id->extension();  
        //     $req->passport_id->move(public_path('uploads/passports/'), $passport);
        // }
        # check exist
        $count = DB::table('user_personal')->where('user_id', Auth::user()->id)->count();

        # delete doctor specialists
        $deleted = DB::table('doctor_specialists')->where('doctor_id', Auth::user()->id)->delete();
        if($count > 0){
            # updating
            
            $updated = DB::table('user_personal')->where('user_id', Auth::user()->id)->update([
                'user_id' => Auth::user()->id,
                'address' =>$req->address,
                // 'city_id'=>$req->city_id,
                'city_title' => $req->city_title,
                'nationality_id' => $req->nationality_id,
                'country_id'=>$req->country_id,
                'date_birth' =>$dateBirth,
                'photo' => asset('public/uploads/cv/doctors').'/'.$photo.'',
                'profile' => asset('public/uploads/cv/doctors').'/'.$profile.'',
                'certificate' => asset('public/uploads/certificates').'/'.$certificate.'',
                // 'passport_id' => asset('public/uploads/passports').'/'.$passport.'',
                'fees' => $req->fees,
                'specialist_id' => $specialists[0]
            ]);

            # add specialists to doctor
            foreach ($specialists as $key => $value) {
                $checkExist = DB::table('doctor_specialists')->where('doctor_id', Auth::user()->id)->where('specialist_id', $value)->count();
                // dd($value, $checkExist);
                if($checkExist < 1){
                    $created = Carbon::now();
                    $inserted = DB::table('doctor_specialists')->insert([
                        'doctor_id' => Auth::user()->id,
                        'specialist_id' => $value,
                        'active' => 1,
                        'created' => $created->getTimestampMs().'-'.$key
                    ]);
                }
            }

            return response()->json([
                "status"=> 'success',
                'message_en' => 'Updated Success',
                'message_ar' => 'تم التحديث بنجاح'
            ]);

        }else{
            # inserting
            $created = Carbon::now();
            $inserted = DB::table('user_personal')->insert([
                'user_id' => Auth::user()->id,
                'address' =>$req->address,
                // 'city_id'=>$req->city_id,
                'city_title' => $req->city_title,
                'nationality_id' => $req->nationality_id,
                // 'specialist_id' => $req->specialist_id,
                'country_id'=>$req->country_id,
                'date_birth' =>$dateBirth,
                'photo' => (is_null($photo))? null : asset('public/uploads/profile/doctors').'/'.$photo.'',
                'certificate' => (is_null($certificate))? null : asset('public/uploads/certificates').'/'.$certificate.'',
                // 'passport_id' => (is_null($passport))? null : asset('public/uploads/passports').'/'.$passport.'',
                'profile' => (is_null($profile))? null : asset('public/uploads/cv/doctors').'/'.$profile.'',
                'fees' => $req->fees,
                'specialist_id' => $specialists[0],
                'created' => $created->getTimestampMs()

            ]);

             # add specialists to doctor
             foreach ($specialists as $key => $value) {
                $checkExist = DB::table('doctor_specialists')->where('doctor_id', Auth::user()->id)->where('specialist_id', $value)->count();
                // dd($value, $checkExist);
                if($checkExist < 1){
                    $created = Carbon::now();
                    $inserted = DB::table('doctor_specialists')->insert([
                        'doctor_id' => Auth::user()->id,
                        'specialist_id' => $value,
                        'active' => 1,
                        'created' => $created->getTimestampMs().'-'.$key
                    ]);
                }
            }

            return response()->json([
                "status"=> 'success',
                'message_en' => 'Inserted Success',
                'message_ar' => 'تم الاضافة بنجاح'
            ]);
        }
       

    }

    public function specialists(Request $req){
        $data = DB::table('specialists')->where('active', 1)->get();
        return response()->json([
            "status"=> 'success',
            // 'message_en' => 'Inserted Success',
            'data' => $data
        ]);
    }

    public function cities(Request $req){
        $data = DB::table('cities')->where('active', 1)->get();
        return response()->json([
            "status"=> 'success',
            // 'message_en' => 'Inserted Success',
            'data' => $data
        ]);
    }

    public function nationalities(Request $req){
        $data = DB::table('nationalities')->where('active', 1)->get();
        return response()->json([
            "status"=> 'success',
            // 'message_en' => 'Inserted Success',
            'data' => $data
        ]);
    }

    public function selectionData(Request $req){
        $data = [];
        $data['nationalities'] = DB::table('nationalities')->where('active', 1)->get();
        // $data['cities'] = DB::table('cities')->where('active', 1)->get();
        $data['specialists'] = DB::table('specialists')->where('active', 1)->get();
        $data['countries'] = DB::table('countries')->where('active', 1)->get();
        return response()->json([
            "status"=> 'success',
            // 'message_en' => 'Inserted Success',
                'data' => $data
        ]);
    }

    public function days(){
        $days = DB::table('days')->where('active', 1)->get();
        return response()->json([
            "status"=> 'success',
            'data' => $days
        ]);
    }

    public function addTimeSchedule(Request $req){
        # validation
        $from = date(''.$req->time_from.'');
        $to = date(''.$req->time_to.'');
            
        if($to <= $from){
            // dd('from < to');
            return response()->json([
                "status"=> 'error',
                'message_ar'=>'تأكد من صحة التوقيت',
                'message_en'=>'Check Your Appointment!'
            ]);
        }
        
        # check exist
        $checkExist = DB::table('doctor_schedule')
        ->where('doctor_id', $req->doctor_id)
        ->where('day_index', $req->day_index)
        ->where('from_hour', $from)
        ->where('to_hour', $to)
        ->count();
        
        if($checkExist > 0){
            return response()->json([
                "status"=> 'error',
                'message_ar'=>'موجود بالفعل',
                'message_en'=>'Already Exist'
            ]);
        }
        # check valid appointment from to timeline
        # check from
        $checkFrom = DB::table('doctor_schedule')
        ->where('doctor_id', $req->doctor_id)
        ->where('day_index', $req->day_index)
        ->where('from_hour', $from)
        ->where('to_hour', $to)
        ->count();
        
        if($checkFrom > 0){
            return response()->json([
                "status"=> 'error',
                'message_ar'=>'هذا الموعد موجود بالفعل',
                'message_en'=>'This Appointment Already Exist!'
            ]);
        }

        $checkTo = DB::table('doctor_schedule')
        ->where('doctor_id', $req->doctor_id)
        ->where('day_index', $req->day_index)
        ->where('to_hour', $to)
        ->count();
        
        if($checkTo > 0){
            return response()->json([
                "status"=> 'error',
                'message_ar'=>'هذا الموعد موجود بالفعل',
                'message_en'=>'This Appointment Already Exist!'
            ]);
        }
        // dd($checkFrom);
        // ->whereTime('from_hour', '<', 'exchanges.start_time')
        // ->whereTime('finaltrade.created_at', '>' 'exchanges.end_time');

        $created = Carbon::now();
        $insertSchedule = DB::table('doctor_schedule')->insert([
            'doctor_id' => $req->doctor_id,
            'day_index' => $req->day_index,
            'from_hour' => $from,
            'to_hour' => $to,
            'created' => $created->getTimestampMs(),
            'created_by' => Auth::user()->id
        ]);

        if(!$insertSchedule){
            return response()->json([
                "status"=> 'error',
                'message_ar'=>'خطأ فى الانشاء كود الخطأ 1',
                'message_en'=>'Error Create, Err Code 1'
            ]);
        }

        return response()->json([
            "status"=> 'success',
            'message_ar'=>'تم حفظ الموعد بنجاح',
            'message_en'=>'Appointment Saved Success'
        ]);
            
    }

    public function deleteTimeSchedule(Request $req){
        $deleted = DB::table('doctor_schedule')->where('created', $req->created)->delete();
        if(!$deleted){
            return response()->json([
                "status"=> 'error',
                'message_ar'=> 'خطأ فى عملية الحذف .. خطأ رقم 2',
                'message_en'=> 'Error Deleting Proccess..Err Code: 2'
            ]);
        }else{
            return response()->json([
                "status"=> 'success',
                'message_ar'=> 'تم الحذف بنجاح',
                'message_en'=> 'Deleted Success'
            ]);
        }
    }

    public function checkMobile(Request $req){
        # trim + from mobie
        // $mobile = $req->mobile
        $checkExist = DB::table('users')->where('mobile', 'like' ,'%'.$req->mobile.'%')->first();
        if(is_null($checkExist)){
            return response()->json([
                "status"=> 'error',
                'checked' => 0,
                'message_ar'=> 'الهاتف غير موجود لدينا',
                'message_en'=> 'This Number Not Found'
            ]);
        }else{
            return response()->json([
                "status"=> 'success',
                'checked' => 1,
                'message_ar'=> 'هذا الرقم موجود بالفعل',
                'message_en'=> 'Already Exist'
            ]);
            
        }
    }

    public function updatePassword(Request $req){
        # validation
        if($req->mobile == ''){
            return response()->json([
                'status' => 'error',
                'message_en'=>'Mobile Is Empty',
                'message_ar'=>' الهاتف مطلوب',
                // 'token' => $token
            ]);
        }

        if($req->password == ''){
            return response()->json([
                'status' => 'error',
                'message_en'=>'Password Is Empty',
                'message_ar'=>'كلمة المرور مطلوبة',
            ]);
        }
        # trim + from mobile
        // $mobile = Str::of($req->mobile)->trim('+');
        // dd($req->mobile, $mobile);
        # check exist mobile
        $checkExistMobile = DB::table('users')->where('mobile', $req->mobile)->count();
        if($checkExistMobile < 1){
            return response()->json([
                'status' => 'error',
                'message_en'=>'The Mobile Not Found',
                'message_ar'=>'هذا الهاتف غير موجود لدينا',
            ]);
        }
        # update password
        $updated = DB::table('users')
        ->where('mobile', $req->mobile)
        ->update([
            'password' => Hash::make($req->password),
        ]);

        if(!$updated){
            return response()->json([
                'status' => 'error',
                // 'message_en'=>'Password Is Empty',
            ]);
        }else{
            return response()->json([
                'status' => 'success',
            ]);
        }

        
    }
    
    public function userNotifications(Request $req){
        # user id
        $userNotifications = DB::table('user_notifications')->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $userNotifications
        ]);
    }

    public function doctorNotifications(Request $req){
        # user id
        $userNotifications = DB::table('user_notifications')->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        $totalUnRead = DB::table('user_notifications')->where('user_id', Auth::user()->id)->where('read', 0)->count();
        return response()->json([
            'status' => 'success',
            'data' => $userNotifications,
            'totalUnRead' => $totalUnRead
        ]);
    }

    public function listDoctors(Request $req){
        $data = [];
        $data = DB::table('users')
        ->join('user_personal','user_personal.user_id','=','users.id')
        // ->join('cities','cities.id','=','user_personal.city_id')
        ->join('nationalities','nationalities.id','=','user_personal.nationality_id')
        ->join('specialists','specialists.id','=','user_personal.specialist_id')
        ->join('countries','countries.id','=','user_personal.country_id')
        ->where('doctor_verified', 1)
        ->select (
            'users.name as name','users.user_type',
            'user_personal.*','nationalities.id as nationality_id','nationalities.title_en as nationality_title_en',
            'nationalities.title_ar as nationality_title_ar',
            // 'cities.id as city_id','cities.title_en as city_title_en','cities.title_ar as city_title_ar',
            'countries.id as country_id','countries.title_en as country_title_en',
            'countries.title_ar as country_title_ar','specialists.title_en as specialitst_title_en','specialists.title_ar as specialitst_title_ar',
            'specialists.id as specialist_id')
        ->get()
            ->map(function ($record){
                // dd($record);
                # doctor rating
                $rates = DB::table('users_reviews')->where('doctor_id', $record->user_id)->sum('rate');
                $totalRates = DB::table('users_reviews')->where('doctor_id', $record->user_id)->count();
                if($totalRates < 1){
                    $rating = 0;
                }else{
                    $rating = round(($rates / $totalRates),1);
                }
                // $record->rating = $rating;
                $data['rating'] = $rating;
                # doctor departments
                $data['departments'] = DB::table('specialists')
                ->join('doctor_departments','doctor_departments.department_id','=','specialists.id')
                ->where('doctor_departments.doctor_id', $record->user_id)->get();
                $record->departments = $data['departments'];

                # doctor from to schedule appointment
                $data['start_from'] = DB::table('doctor_schedule')
                ->where('doctor_id', $record->user_id)
                ->select(\DB::raw('MIN(from_hour) as start_from'))->first();
                $record->startHour = $data['start_from'];

                $data['end_to'] = DB::table('doctor_schedule')
                ->where('doctor_id', $record->user_id)
                ->select(\DB::raw('MAX(to_hour) as end_to'))->first();
                $record->toHour = $data['end_to'];

                # doctor appointments
                $data['doctorAppointments'] = DB::table('appointments_booking')->where('doctor_id', $record->user_id)->get();
                $record->doctorAppointments = $data['doctorAppointments'];

                # doctor schedule
                $data['doctorSchedule'] = DB::table('doctor_schedule')->where('doctor_id', $record->user_id)->get();
                $record->doctorSchedule = $data['doctorSchedule'];
                return $record;
            });
        // dd();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function listDoctorDetails(Request $req){
        $data = [];
        $data = DB::table('users')
        ->join('user_personal','user_personal.user_id','=','users.id')
        // ->join('cities','cities.id','=','user_personal.city_id')
        ->join('nationalities','nationalities.id','=','user_personal.nationality_id')
        ->join('specialists','specialists.id','=','user_personal.specialist_id')
        ->join('countries','countries.id','=','user_personal.country_id')
        ->where('doctor_verified', 1)
        ->where('user_personal.user_id', $req->doctor_id)
        ->select (
            'users.name as name','users.user_type',
            'user_personal.*','nationalities.id as nationality_id','nationalities.title_en as nationality_title_en',
            'nationalities.title_ar as nationality_title_ar',
            // 'cities.id as city_id','cities.title_en as city_title_en','cities.title_ar as city_title_ar',
            'countries.id as country_id','countries.title_en as country_title_en',
            'countries.title_ar as country_title_ar','specialists.title_en as specialitst_title_en','specialists.title_ar as specialitst_title_ar',
            'specialists.id as specialist_id')
        ->get()
            ->map(function ($record) use($req){
                $data['doctorSpecialists'] = DB::table('doctor_specialists')
                    ->join('specialists','specialists.id','=','doctor_specialists.specialist_id')
                    ->where('doctor_specialists.active', 1)
                    ->where('doctor_specialists.doctor_id', $record->user_id)
                    ->select('specialists.title_en','specialists.title_ar')
                    ->get();
                $record->doctorSpecialists = $data['doctorSpecialists'];

                # doctor from to schedule appointment
                $data['start_from'] = DB::table('doctor_schedule')
                ->where('doctor_id', $req->doctor_id)
                ->select(\DB::raw('MIN(from_hour) as start_from'))->first();
                $record->startHour = $data['start_from'];

                $data['end_to'] = DB::table('doctor_schedule')
                ->where('doctor_id', $req->doctor_id)
                ->select(\DB::raw('MAX(to_hour) as end_to'))->first();
                $record->toHour = $data['end_to'];

                # doctor appointments
                $data['doctorAppointments'] = DB::table('appointments_booking')->where('doctor_id', $req->doctor_id)->get();
                $record->doctorAppointments = $data['doctorAppointments'];

                # doctor schedule
                $data['doctorSchedule'] = DB::table('doctor_schedule')->where('doctor_id', $req->doctor_id)->get();
                $record->doctorSchedule = $data['doctorSchedule'];
                return $record;
            });
        
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function specialistListDoctors(Request $req){
        $data = []; //doctor_specialists
        $data = DB::table('users')
        ->join('user_personal','user_personal.user_id','=','users.id')
        // ->join('cities','cities.id','=','user_personal.city_id')
        ->join('nationalities','nationalities.id','=','user_personal.nationality_id')
        ->join('doctor_specialists','doctor_specialists.doctor_id','=','user_personal.user_id' )
        ->join('specialists','specialists.id','=','doctor_specialists.specialist_id')
        ->join('countries','countries.id','=','user_personal.country_id')
        ->where('doctor_verified', 1)
        ->where('doctor_specialists.specialist_id', $req->specialist_id)
        ->select (
            'users.name as name','users.user_type',
            'user_personal.*','nationalities.id as nationality_id','nationalities.title_en as nationality_title_en',
            'nationalities.title_ar as nationality_title_ar',
            // 'cities.id as city_id','cities.title_en as city_title_en','cities.title_ar as city_title_ar',
            'countries.id as country_id','countries.title_en as country_title_en',
            'countries.title_ar as country_title_ar','specialists.title_en as specialitst_title_en','specialists.title_ar as specialitst_title_ar',
            'specialists.id as specialist_id')
        ->get()
            ->map(function ($record){

                
                # doctor rating
                $rates = DB::table('users_reviews')->where('doctor_id', $record->user_id)->sum('rate');
                $totalRates = DB::table('users_reviews')->where('doctor_id', $record->user_id)->count();
                if($totalRates < 1){
                    $rating = 0;
                }else{
                    $rating = round(($rates / $totalRates),1);
                }
                // $record->rating = $rating;
                // $data['rating'] = $rating;
                $record->rating = $rating;
                // dd($record);
                $data['doctorSpecialists'] = DB::table('doctor_specialists')
                    ->join('specialists','specialists.id','=','doctor_specialists.specialist_id')
                    ->where('doctor_specialists.active', 1)
                    ->where('doctor_specialists.doctor_id', $record->user_id)
                    ->select('specialists.title_en','specialists.title_ar')
                    ->get();
                $record->doctorSpecialists = $data['doctorSpecialists'];
                
                # doctor departments
                $data['departments'] = DB::table('specialists')
                ->join('doctor_departments','doctor_departments.department_id','=','specialists.id')
                ->where('doctor_departments.doctor_id', $record->user_id)->get();
                $record->departments = $data['departments'];

                # doctor from to schedule appointment
                $data['start_from'] = DB::table('doctor_schedule')
                ->where('doctor_id', $record->user_id)
                ->select(\DB::raw('MIN(from_hour) as start_from'))->first();
                $record->startHour = $data['start_from'];

                $data['end_to'] = DB::table('doctor_schedule')
                ->where('doctor_id', $record->user_id)
                ->select(\DB::raw('MAX(to_hour) as end_to'))->first();
                $record->toHour = $data['end_to'];

                # doctor appointments
                $data['doctorAppointments'] = DB::table('appointments_booking')->where('doctor_id', $record->user_id)->get();
                $record->doctorAppointments = $data['doctorAppointments'];

                # doctor schedule
                $data['doctorSchedule'] = DB::table('doctor_schedule')->where('doctor_id', $record->user_id)->get();
                $record->doctorSchedule = $data['doctorSchedule'];
                return $record;
            });
            // dd($data);
        
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function popularDoctors(Request $req){
        $data = [];
        $data = DB::table('users')
        ->join('user_personal','user_personal.user_id','=','users.id')
        // ->join('cities','cities.id','=','user_personal.city_id')
        ->join('nationalities','nationalities.id','=','user_personal.nationality_id')
        ->join('countries','countries.id','=','user_personal.country_id')
        ->join('doctor_specialists','doctor_specialists.doctor_id','=','user_personal.user_id')
        ->join('specialists','specialists.id','=','doctor_specialists.specialist_id')
        ->where('user_personal.doctor_verified', 1)
        ->select (
            'users.name as name','users.user_type',
            'user_personal.*','nationalities.id as nationality_id','nationalities.title_en as nationality_title_en',
            'nationalities.title_ar as nationality_title_ar',
            // 'cities.id as city_id','cities.title_en as city_title_en','cities.title_ar as city_title_ar',
            'countries.id as country_id','countries.title_en as country_title_en',
            'countries.title_ar as country_title_ar','specialists.title_en as specialitst_title_en',
            'specialists.title_ar as specialitst_title_ar',
            'specialists.id as specialist_id')
        ->get()
            ->map(function ($record){
                # doctor rating
                $rates = DB::table('users_reviews')->where('doctor_id', $record->user_id)->sum('rate');
                $totalRates = DB::table('users_reviews')->where('doctor_id', $record->user_id)->count();
                if($totalRates < 1){
                    $rating = 0;
                }else{
                    $rating = round(($rates / $totalRates),1);
                }
                // $record->rating = $rating;
                // $data['rating'] = $rating;
                # doctor specialists
                $record->rating = $rating;
                $data['doctorSpecialists'] = DB::table('doctor_specialists')
                    ->join('specialists','specialists.id','=','doctor_specialists.specialist_id')
                    ->where('doctor_specialists.active', 1)
                    ->where('doctor_specialists.doctor_id', $record->user_id)
                    ->select('specialists.title_en','specialists.title_ar')->get();
                $record->doctorSpecialists = $data['doctorSpecialists'];
                // # doctor departments
                // $data['departments'] = DB::table('specialists')
                // ->join('doctor_departments','doctor_departments.department_id','=','specialists.id')
                // ->where('doctor_departments.doctor_id', $record->user_id)->get();
                // $record->departments = $data['departments'];

                # doctor from to schedule appointment
                $data['start_from'] = DB::table('doctor_schedule')
                ->where('doctor_id', $record->user_id)
                ->select(\DB::raw('MIN(from_hour) as start_from'))->first();
                $record->startHour = $data['start_from'];

                $data['end_to'] = DB::table('doctor_schedule')
                ->where('doctor_id', $record->user_id)
                ->select(\DB::raw('MAX(to_hour) as end_to'))->first();
                $record->toHour = $data['end_to'];

                # doctor appointments
                $data['doctorAppointments'] = DB::table('appointments_booking')->where('doctor_id', $record->user_id)->get();
                $record->doctorAppointments = $data['doctorAppointments'];

                # doctor schedule
                $data['doctorSchedule'] = DB::table('doctor_schedule')->where('doctor_id', $record->user_id)->get();
                $record->doctorSchedule = $data['doctorSchedule'];

                return $record;
            });
            // dd($data);
        
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function listDoctorTimeSchedule(Request $req){
        $data = DB::table('doctor_schedule')
        ->where('doctor_id', $req->doctor_id)
        // ->where('doctor_verified', 1)
        ->orderBy('day_index', 'asc')
        ->orderBy('from_hour', 'asc')
        ->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function listDoctorTimeScheduleByDay(Request $req){
        $data = DB::table('doctor_schedule')
        ->where('doctor_id', $req->doctor_id)
        // ->where('doctor_verified', 1)
        ->where('day_index', $req->day_index)
        ->orderBy('day_index', 'asc')
        ->orderBy('from_hour', 'asc')
        ->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
    
    public function appointmentsBooking(Request $req){
        $data = DB::table('appointments_booking')
        ->where('doctor_id', $req->doctor_id)
        ->where('day_index', $req->day_index)
        ->orderBy('day_index', 'asc')
        ->orderBy('from_hour', 'asc')
        ->get();
    }

    public function doctorBooking(Request $req){
        // $data = DB::table('');
    }

    public function doctorDetails(Request $req){
        $data = [];
        $data['doctorDetails'] = DB::table('user_personal')
        ->join('specialists','specialists.id','=','user_personal.specialist_id')
        ->where('user_id', $req->doctor_id)
        ->select('user_personal.*','specialists.*')
        ->first();
        
        $data['upcommingSessions'] = DB::table('appointments_booking')->where('doctor_id', $req->doctor_id)->where('booking_status', 0)->get();
        $data['completedSessions'] = DB::table('appointments_booking')->where('doctor_id', $req->doctor_id)->where('booking_status', 1)->get();
        $data['canceledSessions'] = DB::table('appointments_booking')->where('doctor_id', $req->doctor_id)->where('booking_status', 2)->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function doctorShortDetails(Request $req){
        $data = DB::table('user_personal')->where('user_id', $req->doctor_id)->first();
        // $token = DB::table('users')->where('id', $req->doctor_id)->select('device_token')->first();
        return response()->json([
            'status' => 'success',
            'data' => $data,
            // 'token' => $token->device_token
        ]);
    }
    
    public function doctorToken(Request $req){
        $token = DB::table('users')->where('id', $req->doctor_id)->select('device_token')->first();
        return response()->json([
            'status' => 'success',
            'data' => $token->device_token
        ]);
    }

    public function checkDoctorAppointment(Request $req){
        $todayDate = Carbon::now()->toDateString();
        $date = $req->date; //Carbon::create($req->date);
        $offDurationCheck = null;
        $scheduleOff = DB::table('doctor_off_durations')
        ->where('doctor_id', $req->doctor_id)
        ->where('date_from','>=', ''.$todayDate.'')
        ->where('active', 1)->get()->map(function($duration) use ($date, $offDurationCheck){
            $durationFrom = Carbon::create($duration->date_from);
            $durationTo = Carbon::create($duration->date_to);
            $datePicked = Carbon::create($date);
            $datePicked->greaterThanOrEqualTo($durationFrom);
            $datePicked->lessThanOrEqualTo($durationTo);
            // dd($durationFrom, $durationTo, $datePicked, $datePicked->greaterThan($durationFrom), $datePicked->lessThanOrEqualTo($durationTo));
            if($datePicked->greaterThanOrEqualTo($durationFrom) && $datePicked->lessThanOrEqualTo($durationTo)){
                $offDurationCheck = 1;
                $duration->available = 'no';
                return $duration;
            }else{
                $offDurationCheck = 1;
                $duration->available = 'yes';
                return $duration;
            }
        });

        foreach ($scheduleOff as $key => $value) {
            if($value->available == 'no'){
                $offDurationCheck = 1;
            }
        }
        // dd($offDurationCheck, $scheduleOff);
        if($offDurationCheck == 1){
            return response()->json([
                'status' => 'error',
                'message_en' => 'Consultant Not Available This Day',
                'message_ar' => 'الاستشارى غير متاح هذا اليوم'
            ]);
        }
        
    
        $doctorSchedule = DB::table('doctor_schedule')
            ->where('doctor_id', $req->doctor_id)
            ->where('day_index', $req->day_index)
            ->where('active', 1)
            ->get()

            ->map(function($doctorSchedule)use($req, $date){
                # check available in doctor booked appointments
                $bookings = DB::table('appointments_booking')
                    ->where('doctor_id',$req->doctor_id)
                    ->where('day_index', $req->day_index)
                    ->where('schedule_code', $doctorSchedule->created)
                    ->where('date',$date)
                    // ->orWhere('booking_status', 0)
                    ->get();
                
                # check any doctor off duration

                if(count($bookings) > 0){
                    foreach ($bookings as $key => $booking) {
                        if($booking->schedule_code == $doctorSchedule->created && $booking->booking_status == 1 || $booking->booking_status == 0){
                            $doctorSchedule->available = 'no';
                            return $doctorSchedule;
                        }
                    }
                }

                $doctorSchedule->available = 'yes';
                return $doctorSchedule;
                // return $doctorSchedule;
            });
       
        
        return response()->json([
            'status' => 'success',
            'data' => $doctorSchedule
        ]);

    }

    public function specialistsDepartments(Request $req){
        $data = DB::table('specialists')->where('active', 1)->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function specialistsDepartmentsDoctors(Request $req){
        $data = DB::table('user_personal')->where('specialist_id', $req->specialist_id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function mobilePrivacyPolicy(Request $req){
        $data = DB::table('mobile_pages')->where('page_code', 101)->where('active', 1)->first();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function mobileTermsAndConditions(Request $req){
        $data = DB::table('mobile_pages')->where('page_code', 102)->where('active', 1)->first();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function helpCenter(Request $req){
        $data = DB::table('questions')->where('active', 1)->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function showDoctorCertificates(Request $req){
        $data = DB::table('doctor_certificates')->where('doctor_id', $req->doctor_id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function addDoctorCertificate(Request $req){
        # check count
        if($req->file == null){
            return response()->json([
                'status' => 'error',
                'message_en' => 'No Files Found',
                'message_ar' => 'لا يوجد ملفات'
            ]);
        }

        # loop files
        foreach ($req->file as $key => $value) {
            $created = Carbon::now();
            $photo = $req->doctor_id.'-'.$key.'.'.$req->file->extension();  
            $req->file->move(public_path('uploads/certificates/'), $photo);
        }

        return response()->json([
            'status' => 'success',
            'message_en' => 'Procced Success',
            'message_ar' => 'تم بنجاح'
        ]);

    }

    public function showDoctorPhds(Request $req){
        $data = DB::table('doctor_phds')->where('doctor_id', $req->doctor_id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function showDoctorExperiences(Request $req){
        $data = DB::table('doctor_experiences')->where('doctor_id', $req->doctor_id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function updateDoctorDocuments(Request $req){
      
        # upload user photo if have
        if(!is_null($req->phd)){
            $photo = $req->user_id.'.'.$req->phd->extension();  
            $req->photo->move(public_path('uploads/phd/'), $photo);
        }

        # upload user certificates
        if(!is_null($req->certificates)){
            $certificate = $req->user_id.'.'.$req->certificates->extension();  
            $req->certificate->move(public_path('uploads/certificates/'), $certificates);
        }

        return response()->json([
            'status' => 'success',
            'message_ar' => 'تم بنجاح',
            'message_en' => 'Done Success',
            'data' => $req->certificates
        ]);

    }

    public function doctorBookings(Request $req){
        $currentDatetime = Carbon::now();
        $data = [];

        $data['all'] = DB::table('appointments_booking')
            ->join('users','users.id','=','appointments_booking.user_id')
            ->join('doctor_schedule','doctor_schedule.created','=','appointments_booking.schedule_code')
            ->where('appointments_booking.doctor_id', $req->doctor_id)
            ->where('appointments_booking.tmp', 0)
            ->select(
                'users.name',
                'doctor_schedule.from_hour',
                'appointments_booking.*'
                )
            ->get()->map(function($record){
                $age = 28;
                $record->age = $age;
                return $record;
            });

        $data['allTotal']  = count($data['all']);

        $data['upComming'] = DB::table('appointments_booking')
            ->join('users','users.id','=','appointments_booking.user_id')
            ->join('doctor_schedule','doctor_schedule.created','=','appointments_booking.schedule_code')
            ->where('appointments_booking.doctor_id', $req->doctor_id)
            ->where('appointments_booking.tmp', 0)
            ->where('booking_status',0)
            ->where('date','>',$currentDatetime->toDateString())
            ->select(
                'users.name',
                'doctor_schedule.from_hour',
                'appointments_booking.*'
                )
            ->get()->map(function($record){
                $age = 28;
                $record->age = $age;
                return $record;
            });

        $data['upCommingTotal']  = count($data['upComming']);

        $data['completed'] =  DB::table('appointments_booking')
            ->join('users','users.id','=','appointments_booking.user_id')
            ->join('doctor_schedule','doctor_schedule.created','=','appointments_booking.schedule_code')
            ->where('appointments_booking.doctor_id', $req->doctor_id)
            ->where('appointments_booking.tmp', 0)
            ->where('booking_status',1)
            ->select(
                'users.name',
                'doctor_schedule.from_hour',
                'appointments_booking.*'
                )
            ->get()->map(function($record){
                $age = 28;
                $record->age = $age;
                return $record;
            });
        
        $data['completedTotal']  = count($data['completed']);

        $data['canceled']  =  DB::table('appointments_booking')
            ->join('users','users.id','=','appointments_booking.user_id')
            ->join('doctor_schedule','doctor_schedule.created','=','appointments_booking.schedule_code')
            ->where('appointments_booking.doctor_id', $req->doctor_id)
            ->where('appointments_booking.tmp', 0)
            ->where('booking_status',2)
            ->select(
                'users.name',
                'doctor_schedule.from_hour',
                'appointments_booking.*'
                )
            ->get()->map(function($record){
                $age = 28;
                $record->age = $age;
                return $record;
            });

        $data['canceledTotal']  = count($data['canceled']);


        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function userBookings(Request $req){
        $data = DB::table('appointments_booking')
        ->join('user_personal','user_personal.user_id','=','appointments_booking.doctor_id')
        ->join('users','users.id','=','appointments_booking.doctor_id')
        ->join('specialists','specialists.id','=','user_personal.specialist_id')
        ->join('doctor_schedule','doctor_schedule.created','=','appointments_booking.schedule_code')
        ->where('appointments_booking.user_id', Auth::user()->id)
        ->where('appointments_booking.tmp', 0)
        ->select(
            'users.name','user_personal.photo',
            'specialists.title_ar','specialists.title_en',
            'doctor_schedule.from_hour',
            'appointments_booking.*'
            )
        ->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function confirmBookingZoom(Request $req){
        # validations
        if(is_null($req->doctor_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'كود الاستشارى مطلوب',
                'message_en' => 'Consultant ID Required',
            ]);
        }

        if(is_null($req->user_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'كود المستخدم مطلوب',
                'message_en' => 'User ID Required',
            ]);
        }

        if(is_null($req->created)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'كود الحجز مطلوب',
                'message_en' => 'Booking Code Required',
            ]);
        }

        if(is_null($req->specialist_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'التخصص مطلوب',
                'message_en' => 'Specialist Required',
            ]);
        }

        # get doctor fees
        $doctorFees = DB::table('user_personal')->where('user_id', $req->doctor_id)->select('fees')->first();
        $bookingId = Carbon::now();
        $date = Carbon::create($req->start_time);
        $inserted = DB::table('appointments_booking')
        ->insert([
            'doctor_id' => $req->doctor_id,
            'user_id' => $req->user_id,
            'date' => $date->toDateString(),
            'Join_url' => $req->Join_url,
            'start_meeting_url' => $req->start_meeting_url,
            'duration' => $req->duration,
            'start_time' => $req->start_time,
            'meeting_status' => $req->meeting_status,
            'age' => $req->age,
            'specialist_id' =>$req->specialist_id,
            'case' => $req->case,
            'schedule_code' => $req->created,
            'day_index' =>$req->day_index,
            'booking_type' => $req->booking_type,
            'fees' => $doctorFees->fees,
            'created' => $bookingId->getTimestampMs(),
            'booking_status' => 0, // confirmed
            'user_status' => 0,
            'paid' => 0,
            'tmp' => 1 // temporary
        ]);

        $created = Carbon::now();
        # db notification user
        $notified = DB::table('user_notifications')->insert([
            'user_id' =>  $req->user_id,
            "title_ar" => 'اشعار الحجوزات',
            "title_en" => 'Bookings Notification',
            "content_ar" => 'تم الحجز قم بسداد الحجز للتأكيد',
            "content_en" => 'Booked and pay to confirm',
            'created' => $created->getTimestampMs().'-'.$req->user_id,
            'read' => 0,
            'link' => 'payment'
        ]);

        # send firebase push notification
        $userDeviceToken = DB::table('users')->where('id', $req->user_id)->select('device_token')->first();
        $SERVER_API_KEY = 'AAAAqICtRL8:APA91bEouQSzCcYpDHy3Sec3xYsNfQVQHOj2VxBFON6PuBC1Rqga2ycgfq6YRpNLablpBrjVmd1YII7tejs_u_KkU8d_8pMzXcjmh8gM3QUSmqA0AjQ9iRCr1Ml9GUR5QIMGUhGf102G';//'AAAAj5ate9k:APA91bFg7DXD_RjKee3xbK1sVFdC87cg-bZbSwio8qmRnEMwWMS50LmILAy9Ot5NJZn3Kj8IEaO6lufZN5UPYanS7VATLhsumKq4_7vBv04IS-_YXAi8RIEZkRCfVLXUgA5qQgI8ktln';
        $data = [
            "registration_ids" => [
                $userDeviceToken->device_token
            ],
            "notification" => [
                "title" => 'اشعار الحجوزات',
                "body" => 'تم الحجز قم بسداد الحجز للتأكيد '. $created->toDateTimeString(). '',
                "sound"=> "default" // required for sound on ios
            ],
            "data" => [
                "type" => 'payment'
            ]
        ];

        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);

        # get appointment id 
        $booking_id = DB::table('appointments_booking')
        ->where('doctor_id', $req->doctor_id)
        ->where('user_id', $req->user_id)
        ->where('date',$date->toDateString())
        ->where('schedule_code', $req->created)
        ->orderBy('created_at','desc')
        ->select('created')
        ->first();


        return response()->json([
            'status' => 'success',
            'message_ar' => 'تم حجز الموعد وبانتظار السداد للتأكيد',
            'message_en' => 'Booked Success and Pay To Confirm',
            'booking_id' => $booking_id->created
        ]);
    }

    public function confirmBookingChat(Request $req){
        # validations
        if(is_null($req->doctor_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'كود الاستشارى مطلوب',
                'message_en' => 'Consultant ID Required',
            ]);
        }

        if(is_null($req->user_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'كود المستخدم مطلوب',
                'message_en' => 'User ID Required',
            ]);
        }

        if(is_null($req->created)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'كود الحجز مطلوب',
                'message_en' => 'Booking Code Required',
            ]);
        }

        if(is_null($req->specialist_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'التخصص مطلوب',
                'message_en' => 'Specialist Required',
            ]);
        }

        # get doctor fees
        $doctorFees = DB::table('user_personal')->where('user_id', $req->doctor_id)->select('fees')->first();
        $bookingId = Carbon::now();
        $date = Carbon::create($req->start_time);
        $chatID = 'U-'.$req->user_id.'-D-'.$req->doctor_id.'-ID-'.$bookingId->getTimestampMs().'';
        $inserted = DB::table('appointments_booking')
        ->insert([
            'doctor_id' => $req->doctor_id,
            'user_id' => $req->user_id,
            'date' => $date->toDateString(),
            'chat' => $chatID,
            'Join_url' => $req->Join_url,
            'start_meeting_url' => $req->start_meeting_url,
            'duration' => $req->duration,
            'start_time' => $req->start_time,
            'meeting_status' => $req->meeting_status,
            'age' => $req->age,
            'specialist_id' =>$req->specialist_id,
            'case' => $req->case,
            'schedule_code' => $req->created,
            'day_index' =>$req->day_index,
            'booking_type' => $req->booking_type,
            'fees' => $doctorFees->fees,
            'created' => $bookingId->getTimestampMs(),
            'booking_status' => 0, // confirmed
            'user_status' => 0,
            'paid' => 0,
            'tmp' => 1 // temporary
        ]);

        $created = Carbon::now();
        # db notification user
        $notified = DB::table('user_notifications')->insert([
            'user_id' =>  $req->user_id,
            "title_ar" => 'اشعار الحجوزات',
            "title_en" => 'Bookings Notification',
            "content_ar" => 'تم الحجز قم بسداد الحجز للتأكيد',
            "content_en" => 'Booked and pay to confirm',
            'created' => $created->getTimestampMs().'-'.$req->user_id,
            'read' => 0,
            'link' => 'payment'
        ]);

        $userDeviceToken = DB::table('users')->where('id', $req->user_id)->select('device_token')->first();
        # send firebase push notification
        $SERVER_API_KEY = 'AAAAqICtRL8:APA91bEouQSzCcYpDHy3Sec3xYsNfQVQHOj2VxBFON6PuBC1Rqga2ycgfq6YRpNLablpBrjVmd1YII7tejs_u_KkU8d_8pMzXcjmh8gM3QUSmqA0AjQ9iRCr1Ml9GUR5QIMGUhGf102G';//'AAAAj5ate9k:APA91bFg7DXD_RjKee3xbK1sVFdC87cg-bZbSwio8qmRnEMwWMS50LmILAy9Ot5NJZn3Kj8IEaO6lufZN5UPYanS7VATLhsumKq4_7vBv04IS-_YXAi8RIEZkRCfVLXUgA5qQgI8ktln';
        $data = [
            "registration_ids" => [
                $userDeviceToken->device_token
            ],
            "notification" => [
                "title" => 'اشعار الحجوزات',
                "body" => 'تم الحجز قم بسداد الحجز للتأكيد '. $created->toDateTimeString(). '',
                "sound"=> "default" // required for sound on ios
            ],
            "data" => [
                "type" => 'payment'
            ]
        ];

        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);

        # get appointment id 
        $booking_id = DB::table('appointments_booking')
        ->where('doctor_id', $req->doctor_id)
        ->where('user_id', $req->user_id)
        ->where('date',$date->toDateString())
        ->where('schedule_code', $req->created)
        ->orderBy('created_at','desc')
        ->select('created')
        ->first();


        return response()->json([
            'status' => 'success',
            'message_ar' => 'تم حجز الموعد وبانتظار السداد للتأكيد',
            'message_en' => 'Booked Success and Pay To Confirm',
            'booking_id' => $booking_id->created
        ]);
    }

    public function paymentTransactionStore(Request $req){
        // return response()->json(['status' => 'error', 'booking_id' =>$req->booking_id]);
        $bookingDetails = DB::table('appointments_booking')->where('created', $req->booking_id)->first();
        if(is_null($bookingDetails)){
            $refunded = DB::table('payment_transactions')
            ->where('booking_code', $req->booking_id)
            ->update([
                'refunded' => 1 
            ]);
            # get user wallet
            $userWallet = DB::table('users')->where('id', $bookingDetails->user_id)->first();
            # update user wallet
            $wallet = $userWallet->wallet + $req->PaidCurrencyValue;
            $userWalletUpdated = DB::table('users')->where('id', $bookingDetails->user_id)->update(['wallet' => $wallet]);

            # notify user add refund to wallet
            $created = Carbon::now();
            # db notification user
            $notified = DB::table('user_notifications')->insert([
                'user_id' =>  $bookingDetails->user_id,
                "title_ar" => 'اشعار المدفوعات',
                "content_ar" => 'تم اضافة القيمة فى رصيد محفظتك',
                "title_en" => 'Payments Notification',
                "content_en" => 'Amount added to your wallet',
                'created' => $created->getTimestampMs().'-'.$bookingDetails->user_id,
                'read' => 0,
                'link' => 'wallet'
            ]);

            $userDeviceToken = DB::table('users')->where('id', $bookingDetails->user_id)->select('device_token')->first();
            # send firebase push notification
            $SERVER_API_KEY = 'AAAAqICtRL8:APA91bEouQSzCcYpDHy3Sec3xYsNfQVQHOj2VxBFON6PuBC1Rqga2ycgfq6YRpNLablpBrjVmd1YII7tejs_u_KkU8d_8pMzXcjmh8gM3QUSmqA0AjQ9iRCr1Ml9GUR5QIMGUhGf102G';//'AAAAj5ate9k:APA91bFg7DXD_RjKee3xbK1sVFdC87cg-bZbSwio8qmRnEMwWMS50LmILAy9Ot5NJZn3Kj8IEaO6lufZN5UPYanS7VATLhsumKq4_7vBv04IS-_YXAi8RIEZkRCfVLXUgA5qQgI8ktln';
            $created = Carbon::now();
            $data = [
                "registration_ids" => [
                    $userDeviceToken->device_token
                ],
                "notification" => [
                    "title" => 'اشعار المدفوعات',
                    "body" => 'تم اضافة القيمة فى رصيد محفظتك '.$created->toDateTimeString().'',
                    "sound"=> "default" // required for sound on ios
                ],
                "data" => [
                    "type" => 'wallet'
                ]
            ];

            $dataString = json_encode($data);
            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $response = curl_exec($ch);

            return response()->json([
                'status' => 'error',
                'message_ar' => 'اصبح الحجز مستخدم بالفعل وتم استرداد القيمة بالمحفظة',
                'message_en' => 'This Booking Not Allowed Now , Your Money In Your Wallet'
            ]);
        }
        # check booking paid ?
        if($bookingDetails->tmp == 0){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'مسدد بالفعل'.'  --  ' .$req->booking_id,
                'message_en' => 'Already Paid'
            ]);
        }
        
        if($req->payment_gateway == 'wallet'){

            $commission = DB::table('settings')->where('key', 'revenue_percentage')->select('value')->first();
            $doctorFees = $bookingDetails->fees;
            $commissionValue = ($doctorFees * $commission->value) / 100; // 70 $
            $doctorRevenue = $doctorFees - $commissionValue;

            $created = Carbon::now();
            DB::beginTransaction();
            $inserted = DB::table('payment_transactions')->insert([
                'id' => $created->getTimestampMs(),
                'booking_code' => $req->booking_id,
                'client_id' => $bookingDetails->user_id,
                'doctor_id' => $req->doctor_id,
                'invoice_id'=> $req->InvoiceId,
                'invoice_reference'=> $req->InvoiceReference,
                'transaction_date'=> $created->toDateString(),
                'expiry_date'=> $req->ExpiryDate,
                'track_id'=> $req->TrackId,
                'transaction_value'=> $req->TransactionValue,
                'customer_service_charge'=> $req->CustomerServiceCharge,
                'transaction_status'=> 'Success',
                'paid_currency'=> $req->PaidCurrency,
                'paid_currency_value'=> $req->PaidCurrencyValue,
                'transaction_id'=> $req->TransactionId,
                'payment_url'=> $req->PaymentUrl,
                'payment_gateway' => 'wallet',
                'created_by'=> $bookingDetails->user_id,
                'sys_comm_value' => $commissionValue,
                'dr_comm_value' => $doctorRevenue
            ]);

            if(!$inserted){
                return response()->json([
                    'status' => 'error',
                    'message_ar' => 'خطأ فى العملية',
                    'message_en' => 'Proccess Failed'
                ]);
            }

            # substruct value from wallet
            # uers wallet
            $usertWallet = DB::table('users')->where('id', $bookingDetails->user_id)->select('wallet')->first();
            # transactionValue
            $netBalance = $usertWallet->wallet - $req->PaidCurrencyValue;
            # update net balance
            $userUpdatedWallet = DB::table('users')->where('id', $bookingDetails->user_id)->update(['wallet' => $netBalance]);

            DB::table('appointments_booking')->where('created', $req->booking_id)->update([
                'dr_comm_value' => $doctorRevenue,
                'sys_comm_value' => $commissionValue
            ]);
            DB::commit();
        }else{

            $commission = DB::table('settings')->where('key', 'revenue_percentage')->select('value')->first();
            $doctorFees = $bookingDetails->fees;
            $commissionValue = ($doctorFees * $commission->value) / 100; // 70 $
            $doctorRevenue = $doctorFees - $commissionValue;
            
            $created = Carbon::now();
            $inserted = DB::table('payment_transactions')->insert([
                'id' => $created->getTimestampMs(),
                'booking_code' => $req->booking_id,
                'client_id' => $bookingDetails->user_id,
                'doctor_id' => $req->doctor_id,
                'invoice_status'=> $req->InvoiceStatus,
                'invoice_id'=> $req->InvoiceId,
                'invoice_reference'=> $req->InvoiceReference,
                'transaction_date'=> $req->TransactionDate,
                'expiry_date'=> $req->ExpiryDate,
                'track_id'=> $req->TrackId,
                'transaction_value'=> $req->TransactionValue,
                'customer_service_charge'=> $req->CustomerServiceCharge,
                'transaction_status'=> $req->TransactionStatus,
                'paid_currency'=> $req->PaidCurrency,
                'paid_currency_value'=> $req->PaidCurrencyValue,
                'transaction_id'=> $req->TransactionId,
                'payment_url'=> $req->PaymentUrl,
                'payment_gateway' => $req->payment_gateway,
                'created_by'=> $bookingDetails->user_id,
                'sys_comm_value' => $commissionValue,
                'dr_comm_value' => $doctorRevenue
            ]);
            
            if(!$inserted){
                return response()->json([
                    'status' => 'error',
                    'message_ar' => 'خطأ فى العملية',
                    'message_en' => 'Proccess Failed'
                ]);
            }
            
            # update comm appointment
            $updatedComm = DB::table('appointments_booking')->where('created', $req->booking_id)
            ->update([
                'dr_comm_value' => $doctorRevenue,
                'sys_comm_value' => $commissionValue
            ]);

        }        
   
        # convert tmp to 0
        $confirmed = DB::table('appointments_booking')
        ->where('created', $req->booking_id)
        ->where('user_id', $bookingDetails->user_id)
        ->where('doctor_id', $req->doctor_id)
        ->update([
            'user_status' => 1,
            'paid' => 1,
            'tmp' => 0 // temporary
        ]);

        $created = Carbon::now();
        # db notification user
        $notified = DB::table('user_notifications')->insert([
            'user_id' =>  $bookingDetails->user_id,
            "title_ar" => 'اشعار المدفوعات',
            "content_ar" => 'تم سداد الحجز بنجاح',
            "title_en" => 'Payments Notification',
            "content_en" => 'You Booking Is Paid Success',
            'created' => $created->getTimestampMs().'-'.$bookingDetails->user_id,
            'read' => 0,
            'link' => 'booking'
        ]);

        $userDeviceToken = DB::table('users')->where('id', $bookingDetails->user_id)->select('device_token')->first();
        # send firebase push notification
        $SERVER_API_KEY = 'AAAAqICtRL8:APA91bEouQSzCcYpDHy3Sec3xYsNfQVQHOj2VxBFON6PuBC1Rqga2ycgfq6YRpNLablpBrjVmd1YII7tejs_u_KkU8d_8pMzXcjmh8gM3QUSmqA0AjQ9iRCr1Ml9GUR5QIMGUhGf102G';//'AAAAj5ate9k:APA91bFg7DXD_RjKee3xbK1sVFdC87cg-bZbSwio8qmRnEMwWMS50LmILAy9Ot5NJZn3Kj8IEaO6lufZN5UPYanS7VATLhsumKq4_7vBv04IS-_YXAi8RIEZkRCfVLXUgA5qQgI8ktln';
        $created = Carbon::now();
        $data = [
            "registration_ids" => [
                $userDeviceToken->device_token
            ],
            "notification" => [
                "title" => 'اشعار المدفوعات',
                "body" => 'تم سداد الحجز بنجاح '.$created->toDateTimeString().'',
                "sound"=> "default" // required for sound on ios
            ],
            "data" => [
                "type" => 'booking'
            ]
        ];

        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
        
        $created = Carbon::now();
        # db notification user
        $notified = DB::table('user_notifications')->insert([
            'user_id' =>  $bookingDetails->user_id,
            "title_ar" => 'اشعار الحجوزات',
            "content_ar" => 'تم تأكيد حجزك بنجاح',
            "title_en" => 'Bookings Notification',
            "content_en" => 'You Booking Is Confirmed Success',
            'created' => $created->getTimestampMs().'-'.$bookingDetails->user_id,
            'read' => 0,
            'link' => 'booking'
        ]);

        $doctorDeviceToken = DB::table('users')->where('id', $bookingDetails->doctor_id)->select('device_token')->first();
        # send firebase push notification
        $SERVER_API_KEY = 'AAAAqICtRL8:APA91bEouQSzCcYpDHy3Sec3xYsNfQVQHOj2VxBFON6PuBC1Rqga2ycgfq6YRpNLablpBrjVmd1YII7tejs_u_KkU8d_8pMzXcjmh8gM3QUSmqA0AjQ9iRCr1Ml9GUR5QIMGUhGf102G';//'AAAAj5ate9k:APA91bFg7DXD_RjKee3xbK1sVFdC87cg-bZbSwio8qmRnEMwWMS50LmILAy9Ot5NJZn3Kj8IEaO6lufZN5UPYanS7VATLhsumKq4_7vBv04IS-_YXAi8RIEZkRCfVLXUgA5qQgI8ktln';
        $created = Carbon::now();
        $data = [
            "registration_ids" => [
                $doctorDeviceToken->device_token
            ],
            "notification" => [
                "title" => 'اشعار الحجوزات',
                "body" => 'لديك حجز جديد '.$created->toDateTimeString().'',
                "sound"=> "default" // required for sound on ios
            ],
            "data" => [
                "type" => 'booking'
            ]
        ];

        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);
        $created = Carbon::now();
        # doctor notification
        $notified = DB::table('user_notifications')->insert([
            'user_id' =>  $req->doctor_id,
            "title_ar" => 'اشعار الحجوزات',
            "content_ar" => 'لديك حجز جديد',
            "title_en" => 'Bookings Notification',
            "content_en" => 'You Have New Booking',
            'created' => $created->getTimestampMs().'-'.$req->doctor_id,
            'read' => 0,
            'link' => 'booking'
        ]);

        $userDeviceToken = DB::table('users')->where('id', $bookingDetails->user_id)->select('device_token')->first();
        # send firebase push notification
        $created = Carbon::now();
        $SERVER_API_KEY = 'AAAAqICtRL8:APA91bEouQSzCcYpDHy3Sec3xYsNfQVQHOj2VxBFON6PuBC1Rqga2ycgfq6YRpNLablpBrjVmd1YII7tejs_u_KkU8d_8pMzXcjmh8gM3QUSmqA0AjQ9iRCr1Ml9GUR5QIMGUhGf102G';//'AAAAj5ate9k:APA91bFg7DXD_RjKee3xbK1sVFdC87cg-bZbSwio8qmRnEMwWMS50LmILAy9Ot5NJZn3Kj8IEaO6lufZN5UPYanS7VATLhsumKq4_7vBv04IS-_YXAi8RIEZkRCfVLXUgA5qQgI8ktln';
        $data = [
            "registration_ids" => [
                $userDeviceToken->device_token
            ],
            "notification" => [
                "title" => 'اشعار الحجوزات',
                "body" => 'تم تأكيد حجزك بنجاح '.$created->toDateTimeString().'',
                "sound"=> "default" // required for sound on ios
            ],
            "data" => [
                "type" => 'booking'
            ]
        ];

        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);

        
        
        return response()->json([
            'status' => 'success',
            'message_ar' => 'العملية تمت بنجاح',
            'message_en' => 'Process Done Success'
        ]);
    }

    public function userJoinSession(Request $req){
        # check if have already session
        $appointment = DB::table('appointments_booking')->where('user_id', $req->user_id)->where('created',$req->created)->first();
        if(is_null($appointment)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'عفواً هذا الحجز غير موجود',
                'message_en' => 'This Booking Not Found'
            ]);
        }

        DB::beginTransaction();
        # temp only before make schedule
        # update status
        $udated = DB::table('appointments_booking')
        ->where('user_id', $req->user_id)
        ->where('created', $req->created)
        ->update([
            'booking_status' => 1 // completed
        ]);
        
        # calculating commission and revenue
        $commission = DB::table('settings')->where('key', 'revenue_percentage')->select('value')->first();
        $doctorFees = $appointment->fees;
        $commissionValue = ($doctorFees * $commission->value) / 100; // 70 $
        $doctorRevenue = $doctorFees - $commissionValue;
        
        # update doctor wallet
        $oldWallet = DB::table('users')->where('id', $appointment->doctor_id)->select('wallet')->first();
        $oldWallet = $oldWallet->wallet;
        $doctorTotalWallet = $doctorRevenue + $oldWallet;
        $updatedDoctorWallet = DB::table('users')->where('id', $appointment->doctor_id)->update(['wallet' => $doctorTotalWallet]);

        # update system balance
        $payment = DB::table('payment_transactions')->where('booking_code', $req->created)->first();
        DB::commit();
        # return message
        return response()->json([
            'status' => 'success',
            'message_ar' => 'جارى التحويل للجلسة',
            'message_en' => 'Session Loading...'
        ]);
    }

    public function doctorJoinSession(Request $req){
        # check if have already session
        $checkExist = DB::table('appointments_booking')->where('user_id', $req->user_id)->where('created',$req->created)->count();
        if($checkExist < 1){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'عفواً هذا الحجز غير موجود',
                'message_en' => 'This Booking Not Found'
            ]);
        }
        # update status
        $udated = DB::table('appointments_booking')
        ->where('doctor_id', $req->doctor_id)
        ->where('created', $req->created)
        ->update([
            'booking_status' => 1 // completed
        ]);
        # return message
        return response()->json([
            'status' => 'success',
            'message_ar' => 'جارى التحويل للجلسة',
            'message_en' => 'Session Loading...'
        ]);
    }

    public function userChatJoinSession(Request $req){
        # check if have already session
        $checkExist = DB::table('appointments_booking')->where('user_id', $req->user_id)->where('created',$req->created)->count();
        if($checkExist < 1){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'عفواً هذا الحجز غير موجود',
                'message_en' => 'This Booking Not Found'
            ]);
        }
        # update status to completed by cronJob
        // $udated = DB::table('appointments_booking')
        // ->where('user_id', $req->user_id)
        // ->where('created', $req->created)
        // ->update([
        //     'booking_status' => 1 // completed
        // ]);
        # return message
        return response()->json([
            'status' => 'success',
            'message_ar' => 'جارى التحويل للجلسة',
            'message_en' => 'Session Loading...'
        ]);
    }

    public function doctorChatJoinSession(Request $req){
        # check if have already session
        $checkExist = DB::table('appointments_booking')->where('user_id', $req->doctor_id)->where('created',$req->created)->count();
        if($checkExist < 1){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'عفواً هذا الحجز غير موجود',
                'message_en' => 'This Booking Not Found'
            ]);
        }
        # update status to completed by cron jobs
        // $udated = DB::table('appointments_booking')
        // ->where('doctor_id', $req->user_id)
        // ->where('created', $req->created)
        // ->update([
        //     'booking_status' => 1 // completed
        // ]);
        # return message
        return response()->json([
            'status' => 'success',
            'message_ar' => 'جارى التحويل للجلسة',
            'message_en' => 'Session Loading...'
        ]);
    }

    public function userCancelSession(Request $req){
        # check canceled before ?
        $checkCanceled = DB::table('appointments_booking')
        ->where('user_id', $req->user_id)
        ->where('created',$req->created)
        ->where('booking_status', 2)
        ->count();

        if($checkCanceled > 0){
            return response()->json([
                'status' => 'success',
                'message_ar' => 'تم الغاؤه بالقعل',
                'message_en' => 'Already Cancled'
            ]);
        }
        
        # check payment canceled
        $checkPaymentCanceled = DB::table('payment_transactions')->where('booking_code', $req->created)->first();
        if($checkPaymentCanceled->refunded == 1 || $checkPaymentCanceled->invoice_status == 'Refunded'){
            return response()->json([
                'status' => 'success',
                'message_ar' => 'تم الغاؤه بالقعل',
                'message_en' => 'Already Cancled'
            ]);
        }
        # check if have already session
        $checkExist = DB::table('appointments_booking')->where('user_id', $req->user_id)->where('created',$req->created)->count();
        if($checkExist < 1){
            return response()->json([
                'status' => 'success',
                'message_ar' => 'عفواً هذا الحجز غير موجود',
                'message_en' => 'This Booking Not Found'
            ]);
        }
        # check remaining hours to cancel (24 hrs)
        $userBooking = DB::table('appointments_booking')->where('user_id', $req->user_id)->where('created', $req->created)->orderBy('created_at','desc')->first();
        // dd($userBooking, $userBooking->doctor_id);
        $doctorFees = DB::table('user_personal')->where('user_id', $userBooking->doctor_id)->select('fees')->first();
        $bookingTime = Carbon::create($userBooking->start_time);
        $todayTimeStamp = Carbon::now();
        $differentHours = $bookingTime->diffInHours($todayTimeStamp);
        // dd($differentHours);
        if($differentHours >= 24){
            # send to wallet
            # current wallet 
            $wallet = DB::table('users')->where('id', Auth::user()->id)->select('wallet')->first();
            $balance = $wallet->wallet + $doctorFees->fees;
            // dd($wallet->wallet, $doctorFees->fees, $balance);
            // dd($wallet->wallet, $userBooking->doctor_id);
            $udated = DB::table('appointments_booking')
            ->where('user_id', $req->user_id)
            ->where('created', $req->created)
            ->update([
                'booking_status' => 2, // canceled,
                'user_canceled' => 1, // user is canceled
                'paid' => 0
            ]);
            DB::beginTransaction();
            // $r = DB::table('payment_transactions')->where('booking_code', $userBooking->created)->get();
            // dd($r, $userBooking->created);
            # payment refund
            DB::table('payment_transactions')->where('booking_code', $userBooking->created)->update([
                'refunded' => 1,
                'transaction_status' => 'Refunded',
                'invoice_status' => 'Refunded'
            ]);
            # update user wallet
            $walletUpdated = DB::table('users')->where('id', $req->user_id)->update(['wallet' => $balance]);

            $created = Carbon::now();
            $notified = DB::table('user_notifications')->insert([
                'user_id' =>  $req->user_id,
                "title_ar" => 'اشعارات الحجوزات',
                "content_ar" => 'تم الغاء الحجز بنجاح',
                "title_en" => 'Booking Notifications',
                "content_en" => 'Booking Canceled Success',
                'created' => $created->getTimestampMs().'-'.$req->user_id,
                'read' => 0
            ]);

            # send firebase push notification
            $userDeviceToken = DB::table('users')->where('id', $req->user_id)->select('device_token')->first();
            $SERVER_API_KEY = 'AAAAqICtRL8:APA91bEouQSzCcYpDHy3Sec3xYsNfQVQHOj2VxBFON6PuBC1Rqga2ycgfq6YRpNLablpBrjVmd1YII7tejs_u_KkU8d_8pMzXcjmh8gM3QUSmqA0AjQ9iRCr1Ml9GUR5QIMGUhGf102G';//'AAAAj5ate9k:APA91bFg7DXD_RjKee3xbK1sVFdC87cg-bZbSwio8qmRnEMwWMS50LmILAy9Ot5NJZn3Kj8IEaO6lufZN5UPYanS7VATLhsumKq4_7vBv04IS-_YXAi8RIEZkRCfVLXUgA5qQgI8ktln';
            $data = [
                "registration_ids" => [
                    $userDeviceToken->device_token
                ],
                "notification" => [
                    "title" => 'اشعار الحجوزات',
                    "body" => 'تم الغاء الحجز  بنجاح '. $created->toDateTimeString(). '',
                    "sound"=> "default" // required for sound on ios
                ],
                "data" => [
                    "type" => 'payment'
                ]
            ];

            $dataString = json_encode($data);
            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $response = curl_exec($ch);
            DB::commit();
            # return message
            return response()->json([
                'status' => 'success',
                'message_ar' => 'تم إلغاء الحجز بنجاح',
                'message_en' => 'Booking Canceled Success'
            ]);
            
        }else{
            return response()->json([
                'status' => 'error',
                'message_ar' => 'لا يمكن الغاء الحجز بأقل من 24 ساعة',
                'message_en' => 'Can Not Cancel Booking Before 24 Hours'
            ]);
        }
        
    }

    public function userChatCancelSession(Request $req){ 
        # only for users
        # check if have already session
        $checkExist = DB::table('appointments_booking')->where('user_id', $req->user_id)->where('created',$req->created)->count();
        if($checkExist < 1){
            return response()->json([
                'status' => 'success',
                'message_ar' => 'عفواً هذا الحجز غير موجود',
                'message_en' => 'This Booking Not Found'
            ]);
        }
        # check remaining hours to cancel (24 hrs)
        $userBooking = DB::table('appointments_booking')->where('user_id', $req->user_id)->where('created', $req->created)->first();
        $doctorFees = DB::table('user_personal')->where('user_id', $userBooking->doctor_id)->select('fees')->first();
        $bookingTime = Carbon::create($userBooking->start_time);
        $todayTimeStamp = Carbon::now();
        $differentHours = $bookingTime->diffInHours($todayTimeStamp);
        if($differentHours >= 24){
            # send to wallet
            # current wallet 
            $wallet = DB::table('users')->where('id', Auth::user()->id)->select('wallet')->first();
            $balance = $wallet->wallet + $doctorFees->fees;
            // dd($wallet->wallet, $userBooking->doctor_id);
            $udated = DB::table('appointments_booking')
            ->where('user_id', $req->user_id)
            ->where('created', $req->created)
            ->update([
                'booking_status' => 2, // canceled,
                'user_canceled' => 1 // user is canceled
            ]);
            DB::beginTransaction();
            # payment refund
            DB::table('payment_transactions')->where('booking_code', $userBooking->created)->update([
                'refunded' => 1,
                'transaction_status' => 'Refunded',
                'invoice_status' => 'Refunded'
            ]);
            # update user wallet
            $walletUpdated = DB::table('users')->where('id', Auth::user()->id)->update(['wallet' => $balance]);

            $created = Carbon::now();
            $notified = DB::table('user_notifications')->insert([
                'user_id' =>  $req->user_id,
                "title_ar" => 'اشعارات الحجوزات',
                "content_ar" => 'تم الغاء الحجز بنجاح',
                "title_en" => 'Booking Notifications',
                "content_en" => 'Booking Canceled Success',
                'created' => $created->getTimestampMs().'-'.$req->user_id,
                'read' => 0
            ]);

            # send firebase push notification
            $userDeviceToken = DB::table('users')->where('id', $req->user_id)->select('device_token')->first();
            $SERVER_API_KEY = 'AAAAqICtRL8:APA91bEouQSzCcYpDHy3Sec3xYsNfQVQHOj2VxBFON6PuBC1Rqga2ycgfq6YRpNLablpBrjVmd1YII7tejs_u_KkU8d_8pMzXcjmh8gM3QUSmqA0AjQ9iRCr1Ml9GUR5QIMGUhGf102G';//'AAAAj5ate9k:APA91bFg7DXD_RjKee3xbK1sVFdC87cg-bZbSwio8qmRnEMwWMS50LmILAy9Ot5NJZn3Kj8IEaO6lufZN5UPYanS7VATLhsumKq4_7vBv04IS-_YXAi8RIEZkRCfVLXUgA5qQgI8ktln';
            $data = [
                "registration_ids" => [
                    $userDeviceToken->device_token
                ],
                "notification" => [
                    "title" => 'اشعار الحجوزات',
                    "body" => 'تم الغاء الحجز  بنجاح '. $created->toDateTimeString(). '',
                    "sound"=> "default" // required for sound on ios
                ],
                "data" => [
                    "type" => 'payment'
                ]
            ];

            $dataString = json_encode($data);
            $headers = [
                'Authorization: key=' . $SERVER_API_KEY,
                'Content-Type: application/json',
            ];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
            $response = curl_exec($ch);
            DB::commit();
            # return message
            return response()->json([
                'status' => 'success',
                'message_ar' => 'تم إلغاء الحجز بنجاح',
                'message_en' => 'Booking Canceled Success'
            ]);
            
        }else{
            return response()->json([
                'status' => 'error',
                'message_ar' => 'لا يمكن الغاء الحجز بأقل من 24 ساعة',
                'message_en' => 'Can Not Cancel Booking Before 24 Hours'
            ]);
        }
    }

    public function doctorCancelSession(Request $req){
        # check if have already session
        $checkExist = DB::table('appointments_booking')
        ->where('user_id', $req->user_id)
        ->where('doctor_id', $req->doctor_id)
        ->where('created',$req->created)
        ->count();
        if($checkExist < 1){
            return response()->json([
                'status' => 'success',
                'message_ar' => 'عفواً هذا الحجز غير موجود',
                'message_en' => 'This Booking Not Found'
            ]);
        }

        DB::beginTransaction();
        # update status
        $udated = DB::table('appointments_booking')
        ->where('doctor_id', $req->doctor_id)
        ->where('created', $req->created)
        ->update([
            'booking_status' => 2, // canceled
            'doctor_canceled' => 1,
            // 'cancel_cause' => $req->cause
        ]);

        # payment transaction update to refunded
        DB::table('payment_transactions')
        ->where('client_id', $req->user_id)
        ->where('docotr_id', $req->doctor_id)
        ->where('booking_code', $req->created)
        ->where('refunded', 0)
        ->update([
            'refunded' => 1,
            'invoice_status' => 'Refunded'
        ]);

        # refund to user wallet
        $fees = DB::table('appointments_booking')
        ->where('user_id', $req->user_id)
        ->where('doctor_id', $req->doctor_id)
        ->where('created',$req->created)
        ->select('fees')
        ->first();
        $userWallet = DB::table('users')->where('id', $req->user_id)->first();
        $updatedWallet = DB::table('users')->where('id', $req->user_id)->update([
            'wallet' => $fees->fees + $userWallet->wallet
        ]);

        # user notification
        $created = Carbon::now();
        $notified = DB::table('user_notifications')->insert([
            'user_id' =>  $req->user_id,
            "title_ar" => 'اشعارات الحجوزات',
            "content_ar" => 'قام الاستشارى بالغاء الموعد لظروف طارئة',
            "title_en" => 'Booking Notifications',
            "content_en" => 'The consultant canceled the appointment due to urgent circumstances',
            'created' => $created->getTimestampMs().'-'.$req->user_id,
            'read' => 0
        ]);
        # user real time notification
        # send firebase push notification
        $userDeviceToken = DB::table('users')->where('id', $req->user_id)->select('device_token')->first();
        $SERVER_API_KEY = 'AAAAqICtRL8:APA91bEouQSzCcYpDHy3Sec3xYsNfQVQHOj2VxBFON6PuBC1Rqga2ycgfq6YRpNLablpBrjVmd1YII7tejs_u_KkU8d_8pMzXcjmh8gM3QUSmqA0AjQ9iRCr1Ml9GUR5QIMGUhGf102G';//'AAAAj5ate9k:APA91bFg7DXD_RjKee3xbK1sVFdC87cg-bZbSwio8qmRnEMwWMS50LmILAy9Ot5NJZn3Kj8IEaO6lufZN5UPYanS7VATLhsumKq4_7vBv04IS-_YXAi8RIEZkRCfVLXUgA5qQgI8ktln';
        $data = [
            "registration_ids" => [
                $userDeviceToken->device_token
            ],
            "notification" => [
                "title" => 'اشعارات الحجوزات',
                "body" => 'قام الاستشارى بالغاء الموعد لظروف طارئة'. $created->toDateTimeString(). '',
                "sound"=> "default" // required for sound on ios
            ],
            "data" => [
                "type" => 'booking'
            ]
        ];

        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);

        # user notification
        $created = Carbon::now();
        $notified = DB::table('user_notifications')->insert([
            'user_id' =>  $req->user_id,
            "title_ar" => 'اشعارات المحفظة',
            "content_ar" => 'تم استرجاع قيمة الحجز بمحفظتك',
            "title_en" => 'Wallet Notifications',
            "content_en" => 'The Booking Fees Refunded To Your Wallet',
            'created' => $created->getTimestampMs().'-'.$req->user_id,
            'read' => 0
        ]);
        # user real time notification
        # send firebase push notification
        $userDeviceToken = DB::table('users')->where('id', $req->user_id)->select('device_token')->first();
        $SERVER_API_KEY = 'AAAAqICtRL8:APA91bEouQSzCcYpDHy3Sec3xYsNfQVQHOj2VxBFON6PuBC1Rqga2ycgfq6YRpNLablpBrjVmd1YII7tejs_u_KkU8d_8pMzXcjmh8gM3QUSmqA0AjQ9iRCr1Ml9GUR5QIMGUhGf102G';//'AAAAj5ate9k:APA91bFg7DXD_RjKee3xbK1sVFdC87cg-bZbSwio8qmRnEMwWMS50LmILAy9Ot5NJZn3Kj8IEaO6lufZN5UPYanS7VATLhsumKq4_7vBv04IS-_YXAi8RIEZkRCfVLXUgA5qQgI8ktln';
        $data = [
            "registration_ids" => [
                $userDeviceToken->device_token
            ],
            "notification" => [
                "title" => 'اشعارات المحفظة',
                "body" => 'تم استرجاع قيمة الحجز بمحفظتك'. $created->toDateTimeString(). '',
                "sound"=> "default" // required for sound on ios
            ],
            "data" => [
                "type" => 'wallet'
            ]
        ];

        $dataString = json_encode($data);
        $headers = [
            'Authorization: key=' . $SERVER_API_KEY,
            'Content-Type: application/json',
        ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        $response = curl_exec($ch);


        # return message
        return response()->json([
            'status' => 'success',
            'message_ar' => 'تم إلغاء الحجز بنجاح',
            'message_en' => 'Booking Canceled Success'
        ]);

        DB::commit();
    }

    public function doctorChatCancelSession(Request $req){
        # check if have already session
        $checkExist = DB::table('appointments_booking')
        ->where('user_id', $req->doctor_id)
        ->where('created',$req->created)
        ->whereNotIn('booking_status', [2,1]) // canceled or completed before
        ->get();

        if(count($checkExist) < 1){
            return response()->json([
                'status' => 'success',
                'message_ar' => 'عفواً هذا الحجز غير موجود',
                'message_en' => 'This Booking Not Found'
            ]);
        }

        # update status
        $udated = DB::table('appointments_booking')
        ->where('user_id', $req->doctor_id)
        ->where('created', $req->created)
        ->update([
            'booking_status' => 2 // canceled
        ]);

        # re open the appointment to be available

        # update payment transaction to refund

        # refund to user wallet

        # notification

        # return message
        return response()->json([
            'status' => 'success',
            'message_ar' => 'تم إلغاء الحجز بنجاح',
            'message_en' => 'Booking Canceled Success'
        ]);
    }

    public function userWallet(Request $req){
        $wallet = DB::table('users')->where('id', Auth::user()->id)->select('wallet')->first();
        return response()->json([
            'status' => 'success',
            'data' => $wallet->wallet,
        ]);
    }

    public function userBanner(Request $req){
        # get all banners
        $banners = DB::table('banner')->where('type', 'user')->where('active',1)->get();
        return response()->json([
            'status' => 'success',
            'data'=>$banners
        ]);
    }

    public function doctorBanner(Request $req){
        # get all banners
        $banners = DB::table('banner')->where('type', 'doctor')->where('active',1)->get();
        return response()->json([
            'status' => 'success',
            'data'=>$banners
        ]);
    }

    public function userReviews(Request $req){
        $data = [];
        $data['reviews'] = DB::table('users_reviews')
        ->join('users','users.id','users_reviews.doctor_id')
            ->where('doctor_id', $req->doctor_id)
            ->select('users_reviews.doctor_id as doctro_id','users_reviews.user_id as user_id','users_reviews.rate','users_reviews.review','users_reviews.created_at','users.name')->get();
        $data['ratingAvaerage'] = DB::table('users_reviews')->where('doctor_id', $req->doctor_id)->avg('rate');
        return response()->json([
            'status' => 'success',
            'data'=>$data
        ]);
    }

    public function userAddReviews(Request $req){
        # validation
        if(is_null($req->user_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'المستخدم مطلوب',
                'message_en' => 'The User is Required'
            ]);
        }

        if(is_null($req->doctor_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'الاستشاري مطلوب',
                'message_en' => 'The Consultant is Required'
            ]);
        }

        if(is_null($req->booking_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'الحجز مطلوب',
                'message_en' => 'The Booking is Required'
            ]);
        }

        if(is_null($req->review)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'التقييم مطلوب',
                'message_en' => 'The Review is Required'
            ]);
        }

        $checkExist = DB::table('users_reviews')
        ->where('user_id', $req->user_id)
        ->where('doctor_id', $req->doctor_id)
        ->where('booking_id', $req->booking_id)
        ->count();

        if($checkExist > 0){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'لا يمكن اضافة اكثر من تقييم لطبيب واحد بحجز واحد',
                'message_en' => 'You can not add more than Review on one booking'
            ]);
        }

        # add review
        $created = Carbon::now();
        $addReview = DB::table('users_reviews')
        ->insert([
            'user_id' => $req->user_id,
            'doctor_id' => $req->doctor_id,
            'booking_id' => $req->booking_id,
            'review' => $req->review,
            'rate' => $req->rate,
            'ip_address' => $req->ip(),
            'created' => $created->getTimestampMs()
        ]);

        if(!$addReview){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'خطأ فى اضافة التقييم',
                'message_en' => 'Error Add Review'
            ]);
        }

        # return message
        return response()->json([
            'status' => 'success',
            'message_ar' => 'تم اضافة التقييم بنجاح',
            'message_en' => 'Review Added Success'
        ]);
    }

    public function userUpdateReview(Request $req){
        # validation
        if(is_null($req->user_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'المستخدم مطلوب',
                'message_en' => 'The User is Required'
            ]);
        }

        if(is_null($req->doctor_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'الاستشاري مطلوب',
                'message_en' => 'The Consultant is Required'
            ]);
        }

        if(is_null($req->booking_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'الحجز مطلوب',
                'message_en' => 'The Booking is Required'
            ]);
        }

        if(is_null($req->review)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'التقييم مطلوب',
                'message_en' => 'The Review is Required'
            ]);
        }

        if(is_null($req->rate)){
            return response()->json([
                'status' => 'error',
                'message_ar' => ' درجة التقييم نطلوبة',
                'message_en' => 'Rate Value Required'
            ]);
        }

        # add review
        $created = Carbon::now();
        $updated = DB::table('users_reviews')
        ->where('doctor_id', $req->doctor_id)
        ->where('user_id', $req->user_id)
        ->where('booking_id', $req->booking_id)
        ->where('created', $req->created)
        ->update([
            'review' => $req->review,
            'rate' => $req->rate,
            'ip_address' => $req->ip()
        ]);

        if(!$updated){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'خطأ فى تعديل التقييم',
                'message_en' => 'Error Updating Review'
            ]);
        }

        # return message
        return response()->json([
            'status' => 'success',
            'message_ar' => 'تم تعديل التقييم بنجاح',
            'message_en' => 'Review Updated Success'
        ]);
    }

    public function userDeleteReview(Request $req){
        # check exist review before deleting
        $exist = DB::table('users_reviews')
        ->where('user_id', $req->user_id)
        ->where('doctor_id', $req->doctor_id)
        ->where('booking_id', $req->booking_id)
        ->where('created', $req->created)
        ->count();

        if($exist > 0){
            # delete
            $deleted = DB::table('users_reviews')
            ->where('created', $req->created)
            ->where('booking_id', $req->booking_id)
            ->where('doctor_id', $req->doctor_id)
            ->where('user_id', $req->user_id_id)
            ->delete();
            if(!$deleted){
                # return message
                return response()->json([
                    'status' => 'success',
                    'message_ar' => 'خطأ فى حذف التقييم بنجاح',
                    'message_en' => 'Error Review Deleted Success'
                ]);
            }
            # return message
            return response()->json([
                'status' => 'success',
                'message_ar' => 'تم حذف التقييم بنجاح',
                'message_en' => 'Review Deleted Success'
            ]);
        }else{
            # return message
            return response()->json([
                'status' => 'success',
                'message_ar' => 'خطأ فى  حذف التقييم',
                'message_en' => 'Error Review Deleted Success'
            ]);
        }
    }



    public function addDoctorReview(){

    }

    public function doctorReviews(Request $req){
        $data = [];
        $data['reviews'] = DB::table('users_reviews')
            ->join('users', 'users.id','users_reviews.user_id')
            ->where('users_reviews.doctor_id', $req->doctor_id)
        ->select('users.id','users_reviews.rate','users_reviews.review','users_reviews.created_at','users.name')->get();

        $data['totalReviews'] = DB::table('users_reviews')->where('doctor_id', $req->doctor_id)->count();
        $rateSum = DB::table('users_reviews')->where('doctor_id', $req->doctor_id)->sum('rate');
        if($rateSum > 0){
            // $rateCount = DB::table('users_reviews')->where('doctor_id', $req->doctor_id)->count();
            $rating = round($rateSum / $data['totalReviews'], 1);
        }else{
            $rateCount = 0;
            $rating = 0;
        }
        $data['rating'] = $rating;
        return response()->json([
            'status' => 'success',
            'data'=>$data
        ]);
    }

    public function doctorHomePage(Request $req){ 
        $data = [];
        # get doctor specialists
        $data['doctorSpecialists'] = DB::table('specialists')
            ->join('doctor_specialists','doctor_specialists.specialist_id','=','specialists.id')
            ->select('specialists.*','specialists.*')
            ->where('doctor_specialists.doctor_id', Auth::user()->id)->get();
        # get doctor rate
        $rateSum = DB::table('users_reviews')->where('doctor_id', Auth::user()->id)->sum('rate');
        if($rateSum > 0){
            $rateCount = DB::table('users_reviews')->where('doctor_id', Auth::user()->id)->count();
            $data['rating'] = round($rateSum / $rateCount);
        }else{
            $rateCount = 0;
            $data['rating'] = 0;
        }
        // dd($data, $rateSum, $rateCount);
        # get reviews total
        $data['totalReviews'] = DB::table('users_reviews')->where('doctor_id', Auth::user()->id)->count();
        # get doctor bookings with categories
        $todayDate = Carbon::now()->toDateString();
        
        // dd($todayDate);
        $data['upCommingTotal'] = DB::table('appointments_booking')
        ->where('doctor_id', Auth::user()->id)
        ->where('booking_status', 0)
        ->where('paid', 1)
        // ->where('date', $todayDate)
        ->count();
            
        $data['completedTotal'] =  DB::table('appointments_booking')
        ->where('doctor_id', Auth::user()->id)->where('booking_status', 1)->where('paid', 1)
        ->count();

        $data['canceledTotal']  =  DB::table('appointments_booking')
        ->where('doctor_id', Auth::user()->id)->where('booking_status', 2)->where('paid', 1)
        ->count();
           

        # get today appointments upcomming
        $data['upCommingToday'] = DB::table('appointments_booking')
            ->join('users','users.id','=','appointments_booking.user_id')
            ->join('doctor_schedule','doctor_schedule.created','=','appointments_booking.schedule_code')
            ->join('specialists','specialists.id','appointments_booking.specialist_id')
            ->where('appointments_booking.doctor_id', Auth::user()->id)
            ->where('booking_status',0)
            ->where('paid', 1)
            ->where('tmp', 0)
            ->where('date', $todayDate)
            ->select(
                'users.name',
                'doctor_schedule.from_hour',
                'appointments_booking.*',
                'specialists.title_en as specialist_title_en',
                'specialists.title_ar as specialist_title_ar'
                )
            ->get();
            
        # return
        return response()->json([
            'status' => 'success',
            'data'=>$data
        ]);
    }

    public function rate(Request $req){
        # check exist 
        $checkExist = DB::table('rating')->where('user_id', $req->user_id)->where('doctor_id', $req->doctor_id)->count();
        if($checkExist > 0){
            # update
            $updated = DB::table('rating')->where('user_id', $req->user_id)->where('doctor_id', $req->doctor_id)->update(['rate' => $req->rate]);
        }else{
            # insert
            $created = Carbon::now();
            $inserted = DB::table('rating')->insert([
                'user_id' => $req->user_id, 
                'doctor_id' => $req->doctor_id, 
                'rate' => $req->rate,
                'ip_address' =>$req->ip(),
                'created' => $created->getTimestampMs()
            ]);
        }
        return response()->json([
            'status' => 'success',
            'message_ar' => 'تم التقييم بنجاح',
            'message_en' => 'Rated Success'
        ]);
    }

    public function bookingFilter(Request $req){
        # type and sorting and doctor_id
        if($req->type == 'all'){
            # all prev booking
            $data = DB::table('appointments_booking')
            ->join('users','users.id','appointments_booking.doctor_id')
            ->join('specialists','specialists.id','appointments_booking.specialist_id')
                ->where('doctor_id', Auth::user()->id)
                ->where('appointments_booking.tmp', 0)
                ->whereIn('booking_status', [0,1,2])
                ->orderBy('start_time', $req->order)
                ->select('appointments_booking.*','users.name','specialists.title_en as specialist_title_en','specialists.title_ar as specialist_title_ar')
                ->get();

            return response()->json([
                'status' => 'success',
                'data' =>$data
            ]);
            
        }elseif($req->type == 'done'){ // completed
            $data = DB::table('appointments_booking')
            ->join('users','users.id','appointments_booking.user_id')
            ->join('specialists','specialists.id','appointments_booking.specialist_id')
            ->where('doctor_id', Auth::user()->id)
            ->where('appointments_booking.tmp', 0)
            ->where('booking_status',1)
            ->orderBy('start_time', $req->order)
            ->select('appointments_booking.*','users.name','specialists.title_en as specialist_title_en','specialists.title_ar as specialist_title_ar')
            ->get();

            return response()->json([
                'status' => 'success',
                'data' =>$data
            ]);

        }elseif($req->type == 'upcoming'){
            $data = DB::table('appointments_booking')
            ->join('users','users.id','appointments_booking.user_id')
            ->join('specialists','specialists.id','appointments_booking.specialist_id')
            ->where('doctor_id', Auth::user()->id)
            ->where('appointments_booking.tmp', 0)
            ->where('booking_status',0)
            ->orderBy('start_time', $req->order)
            ->select('appointments_booking.*','users.name','specialists.title_en as specialist_title_en','specialists.title_ar as specialist_title_ar')
            ->get();

            return response()->json([
                'status' => 'success',
                'data' =>$data
            ]);

        }elseif($req->type == 'canceled'){
            $data = DB::table('appointments_booking')
            ->join('users','users.id','appointments_booking.user_id')
            // ->join('specialists','specialists.id','appointments_booking.specialist_id')
            ->where('doctor_id', Auth::user()->id)
            ->where('appointments_booking.tmp', 1)
            ->where('booking_status',2)
            ->orderBy('start_time', $req->order)
            // ->select('appointments_booking.*','users.name','specialists.title_en as specialist_title_en','specialists.title_ar as specialist_title_ar')
            ->get();

            return response()->json([
                'status' => 'success',
                'data' =>$data
            ]);
        }else{
            return 'error filter type';
        }
    }

    public function userPaymentSavedCards(Request $req){
        # validation

        # exist
        $checkExist = DB::table('usersPaymentCards')
        ->where('user_id', Auth::user()->id)
        ->where('card_type', $req->card_type)
        ->where('card_no', $req->card_no)
        ->where('expired_month', $req->expired_month)
        ->where('expired_year', $req->expired_year)
        ->where('ccv', $req->ccv)
        ->count();
        if($checkExist > 0){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'موجود بالفعل',
                'message_en' => 'Already Exist'
            ]);
        }
        # db
        # insert
        $created = Carbon::now();
        $inserted = DB::table('usersPaymentCards')
            ->insert([
                'user_id' => Auth::user()->id,
                'card_type' => $req->card_type,
                'card_no' => $req->card_no,
                'expired_month' => $req->expired_month,
                'expired_year' => $req->expired_year,
                'ccv' => $req->ccv,
                'created' => $created->getTimestampMs()
            ]);
        # return
        return response()->json([
            'status' => 'success',
            'message_ar' => 'تمت الاضافة بنجاح',
            'message_en' => 'Added Success'
        ]);
    }

    public function listUserPaymentCards(Request $req){
        dd('ss');
    }

    public function userUpdate(Request $req){
        // dd($req);
        # get auth email
        $currEmail = DB::table('users')->where('id', $req->user_id)->select('email')->first();
        if(!is_null($req->email)){
            if($req->email != $currEmail->email){
                # check exist used data on another user
                $checkEmail = DB::table('users')->where('email', $currEmail->email)->whereNotIn('id', [$req->user_id])->count();
                // dd($checkEmail, $req->email, $currEmail->email);
                if($checkEmail > 0){
                    return response()->json([
                        'status' => 'error',
                        'message_ar' => 'هذا البريد موجود مسبقاً',
                        'message_en' => 'Email Already Used!'
                    ]);
                }
                # updated
                $updated = DB::table('users')->where('id', $req->user_id)->update(['email' => $req->email]);
                
            }
        }
        

        if(!is_null($req->name)){
            # updated
            $updated = DB::table('users')->where('id', $req->user_id)->update(['name' => $req->name]);
        }
            
        # upload user photo if have
        if(!is_null($req->photo)){
            # delete old photo
            $deleted = DB::table('users')->where('id', $req->user_id)->first();
            // dd($deleted);
            $photo = $req->user_id.'.'.$req->photo->extension();
            $req->photo->move(public_path('uploads/photos/users/'), $photo);
            // dd(url('/'));
            $url = url('/public/uploads/photos/users/'.$photo);
            # update photo db
            $updated = DB::table('users')->where('id', $req->user_id)->update(['photo' => $url]);
        }
        # return
        $photoUrl = DB::table('user_personal')->where('user_id', $req->user_id)->select('photo')->first();
        // dd($req, $photo, $photoUrl);
        return response()->json([
            'status' => 'success',
            'photo' => $url,
            'message_ar' => 'تم حفظ التعديل بنجاح',
            'message_en' => 'Updated Success'
        ]);
    }

    public function doctorRevenue(Request $req){
        $doctorWallet = DB::table('users')->where('id', Auth::user()->id)->first();
        # min limit collect
        $minCollect = DB::table('settings')->where('key','min_collect_value')->first();
        # calculating dr transactions
        $drTransactions = DB::table('payment_transactions')
        ->where('doctor_id', Auth::user()->id)
        ->where('invoice_status', 'Paid')
        ->where('payment_gateway','!=', 'wallet')
        ->where('transaction_status', 'Success')
        ->where('dr_completed', 1)
        ->where('dr_comm_collected', 0)
        ->where('refunded',0)
        ->sum('dr_comm_value');
        return response()->json([
            'status' => 'success',
            'revenue' => $drTransactions,
            'minCollect' => $minCollect->value,
            'doctorWallet' => (double) $doctorWallet->wallet
        ]);
    }

    public function doctorRevenueDurations(Request $req){
        // dd(Auth::user()->id,$req);
        # calculating dr transactions
        $drTransactions = DB::table('payment_transactions')
        ->where('doctor_id', Auth::user()->id)
        ->where('transaction_date','>=', ''.$req->date_from.'')
        ->where('transaction_date','<=', ''.$req->date_to.'')
        ->where('dr_completed', 1)
        ->where('refunded',0)
        ->sum('dr_comm_value');

        $bookingsTotal = DB::table('payment_transactions')
        ->where('doctor_id', Auth::user()->id)
        ->where('transaction_date','>=', ''.$req->date_from.'')
        ->where('transaction_date','<=', ''.$req->date_to.'')
        ->where('dr_completed', 1)
        ->where('refunded',0)
        ->count();

        return response()->json([
            'status' => 'success',
            'revenue' => $drTransactions,
            'bookingsTotal' => $bookingsTotal
        ]);
    }

    public function doctorSessionNote(Request $req){
        # booking code, doctor_id, age , note

    }

    public function updateBio(Request $req){
        # validation
        if(is_null($req->doctor_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'الاستشارى مطلوب',
                'message_en' => 'Consultant Required'
            ]);
        }

        #update
        $updated = DB::table('user_personal')->where('user_id', $req->doctor_id)->update(['bio' => $req->bio]);
        if(!$updated){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'لا يوجد تغييرات لحفظها',
                'message_en' => 'No Change For UPdating'
            ]);    
        }
        return response()->json([
            'status' => 'success',
            'message_ar' => 'تم حفظ التعديل بنجاح',
            'message_en' => 'Updated Success'
        ]);

    }


    public function updateDoctorFees(Request $req){
        # validation
        if(is_null($req->doctor_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'الاستشارى مطلوب',
                'message_en' => 'Consultant Required'
            ]);
        }

        if(is_null($req->fees) || $req->fees < 1){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'التكلفة مطلوبة',
                'message_en' => 'Fees Required'
            ]);
        }

        #update
        $updated = DB::table('user_personal')->where('user_id', $req->doctor_id)->update(['fees' => $req->fees]);
        if(!$updated){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'لا يوجد تغييرات لحفظها',
                'message_en' => 'No Change For UPdating'
            ]);    
        }
        return response()->json([
            'status' => 'success',
            'message_ar' => 'تم حفظ التعديل بنجاح وبانتظار الموافقة',
            'message_en' => 'Updated Success and waiting approval'
        ]);

    }

    public function updateDoctorExperienceYrs(Request $req){
        # validation
        if(is_null($req->doctor_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'الاستشارى مطلوب',
                'message_en' => 'Consultant Required'
            ]);
        }

        if(is_null($req->experience_years) || $req->experience_years == ''){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'عدد سنوات الخبرة مطلوبة',
                'message_en' => 'Experince Years Required'
            ]);
        }

        #update
        $updated = DB::table('user_personal')->where('user_id', $req->doctor_id)->update(['experience_yrs' => $req->experience_years]);
        if(!$updated){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'لا يوجد تغييرات لحفظها',
                'message_en' => 'No Change For UPdating'
            ]);    
        }
        return response()->json([
            'status' => 'success',
            'message_ar' => 'تم حفظ التعديل بنجاح',
            'message_en' => 'Updated Success'
        ]);

    }

    public function doctorBookingNotes(Request $req){
        $updated = DB::table('appointments_booking')->where('created', $req->created)->update(['doctor_notes' => $req->notes, 'doctor_description' => $req->description]);
        if(!$updated){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'لا يوجد تغيرات لحفظها',
                'message_en' => 'No Changes'
            ]);
        }else{
            return response()->json([
                'status' => 'success',
                'message_ar' => 'تم الحفظ بنجاح',
                'message_en' => 'Saved Success'
            ]);
        }
    }

    public function doctorBookingCancelNotes(Request $req){
        $canceled = DB::table('appointments_booking')->where('created', $req->created)->update(['doctor_canceled'=> 1, 'cancel_cause' => $req->cancel_cause,'booking_status' => 2]);
        if(!$canceled){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'خطأ فى الغاء الحجز',
                'message_en' => 'Cancel Booking Error'
            ]);
        }else{
            return response()->json([
                'status' => 'success',
                'message_ar' => 'تم إلغاء الحجز بنجاح',
                'message_en' => 'Booking Canceled Success'
            ]);
        }
    }

    public function userInfo(Request $req){
        $user = DB::table('users')->where('id', Auth::user()->id)->first();
        $user_personal = DB::table('user_personal')
        ->join('specialists','specialists.id','=','user_personal.specialist_id')
        ->where('user_id', Auth::user()->id)
        ->first();
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'user_personal' =>$user_personal
        ]);
    }

    public function doctorSpecialistsSelector(Request $req){
        $data = DB::table('specialists')->where('active', 1)->get()
        ->map(function($record) use($req){
            $selected = DB::table('doctor_specialists')->where('specialist_id', $record->id)->where('doctor_id', $req->doctor_id)->first();
            if(!is_null($selected)){
                $record->selected = true;
                return $record;
            }else{
                $record->selecetd = false;
                return $record;
            }
            // dd($record, $selected);
        });
        
        // $selected = [];
        // $data['doctorSpecialistsSelected'] = DB::table('doctor_specialists')
        // ->join('specialists','specialists.id','doctor_specialists.specialist_id')
        // ->where('doctor_specialists.doctor_id', $req->doctor_id)
        // ->where('specialists.active', 1)
        // ->get();

        // $data['doctorSpecialistsNotSelected'] = DB::table('doctor_specialists')
        // ->leftJoin('specialists','specialists.id','doctor_specialists.specialist_id')
        // ->where('doctor_specialists.doctor_id', '=' ,$req->doctor_id)
        // // ->where('specialists.active', 1)
        // ->map(function($record){
        //     $checked = DB::table('doctor_specialists')->where('doctor_id', $req->doctor_id)->where();
        //     return $record;
        // })->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function doctorSpecialistsUpdate(Request $req){
        // $arr = [];
        // $arr = ['1','2','4'];
        // dd(gettype($req->specialist_id), gettype($arr));
        if(is_null($req->doctor_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'الاستشارى مطلوب',
                'message_en' => 'Doctor Required'
            ]);
        }

        if(is_null($req->specialist_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'التخصصات مطلوبة',
                'message_en' => 'Specialists Required'
            ]);
        }

        # remvove doctor specialists
        $deleted = DB::table('doctor_specialists')->where('doctor_id', $req->doctor_id)->delete();
        # insert doctor specialists
        foreach ($req->specialist_id as $key => $value) {
            // sleep(1);
            $created = Carbon::now();
            $inserted = DB::table('doctor_specialists')
            ->insert([
                'doctor_id' => $req->doctor_id, 
                'specialist_id' => $value,
                'active' => 1,
                'created' => $created->getTimestampMs().'-'.$key.''
            ]);
            if(!$inserted){
                return response()->json([
                    'status' => 'error',
                    'message_ar' => 'خطأ فى اضافة التخصصات للاستشارى',
                    'message_en' => 'Adding Specialists Error'
                ]);
            }
        }

        # return 
        return response()->json([
            'status' => 'success',
            'message_ar' => 'تم اضافة التخصصات للاستشارى بنجاح',
            'message_en' => 'Specialists Was Added Success'
        ]);
    }


    public function doctorPhotoUpdate(Request $req){
        # upload user photo if have
        if(!is_null($req->photo)){
            # delete old photo
            // $deleted = DB::table('users')->where('id', $req->doctor_id)->first();
            // dd($deleted);
            $photo = $req->doctor_id.'.'.$req->photo->extension();
            $req->photo->move(public_path('uploads/cv/doctors/'), $photo);
            // dd(url('/'));
            $url = url('/public/uploads/cv/doctors/'.$photo);
            # update photo db
            $updated = DB::table('user_personal')->where('user_id', $req->doctor_id)->update(['photo' => $url]);
        }
        # return
        $photoUrl = DB::table('user_personal')
        ->where('user_id', $req->doctor_id)
        ->select('photo')->first();
        // dd($req, $photo, $photoUrl);
        return response()->json([
            'status' => 'success',
            'photo' => $url,
            'message_ar' => 'تم حفظ التعديل بنجاح',
            'message_en' => 'Updated Success'
        ]);
    }

    public function updateID(Request $req){
        $date = Carbon::now()->toDateString();
        if(is_null($req->expired_date)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'انتهاء الصلاحية مطلوب',
                'message_en' => 'Expired Date Required'
            ]);
        }

        if(!is_null($req->expired_date) && $req->expired_date <= $date){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'انتهاء الصلاحية غير صحيح',
                'message_en' => 'Expired Date Not Valid'
            ]);
        }

        if(is_null($req->id_face_1)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'تحقيق الشخصية الوجه الامامى مطلوب',
                'message_en' => 'ID Face 1 Required'
            ]);
        }

        if(is_null($req->id_face_2)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'تحقيق الشخصية الوجه الخلفي مطلوب',
                'message_en' => 'ID Face 2 Required'
            ]);
        }
        # upload user photo if have
        if(!is_null($req->id_face_1)){
            # delete old photo
            // $deleted = DB::table('users')->where('id', $req->doctor_id)->first();
            // dd($deleted);
            $photo = $req->doctor_id.'.'.$req->id_face_1->extension();
            $req->id_face_1->move(public_path('uploads/IDs/'), 'id_face_1-'.$photo);
            // dd(url('/'));
            $url = url('/public/uploads/IDs/'.'id_face_1-'.$photo);
            # update photo db
            $updated = DB::table('user_personal')->where('user_id', $req->doctor_id)->update(['id_face_1' => $url]);
        }

        if(!is_null($req->id_face_2)){
            # delete old photo
            // $deleted = DB::table('users')->where('id', $req->doctor_id)->first();
            // dd($deleted);
            $photo = $req->doctor_id.'.'.$req->id_face_2->extension();
            $req->id_face_2->move(public_path('uploads/IDs/'), 'id_face_2-'.$photo);
            // dd(url('/'));
            $url = url('/public/uploads/IDs/'.'id_face_2-'.$photo);
            # update photo db
            $updated = DB::table('user_personal')->where('user_id', $req->doctor_id)->update(['id_face_2' => $url]);
        }

        # update expired date
        $updated = DB::table('user_personal')->where('user_id', $req->doctor_id)->update(['id_expired_date' => $req->expired_date]);
        # return
        // $photoUrl = DB::table('user_personal')->where('user_id', $req->doctor_id)->select('photo')->first();
        // dd($req, $photo, $photoUrl);
        return response()->json([
            'status' => 'success',
            'message_ar' => 'تم حفظ التعديل وبانتظار الموافقة',
            'message_en' => 'Updated Success and waiting approving'
        ]);
    }

    public function doctorOffDurations(Request $req){
        $todayDate = Carbon::now()->toDateString();
        # validations
        if(is_null($req->from_date) || empty($req->from_date)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'تاريخ البداية مطلوب',
                'message_en' => 'start date is required'
            ]);
        }

        if(is_null($req->to_date) || empty($req->to_date)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'تاريخ النهاية مطلوب',
                'message_en' => 'end date is required'
            ]);
        }

        if($req->from_date > $req->to_date){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'تاريخ البداية اكبر من النهاية',
                'message_en' => 'Start Date > End Date'
            ]);
        }

        # check exist
        // $checkExist = DB::table('');
        # duration > current date
        
        if($todayDate >= $req->from_date){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'هذه الفترة اقدم من تاريخ اليوم',
                'message_en' => 'This Duration Old Than Today'
            ]);
        }

        # check exist
        $checkExist = DB::table('doctor_off_durations')
        ->where('doctor_id', $req->doctor_id)
        ->where('date_from', $req->from_date)
        ->where('date_to', $req->to_date)
        ->count();

        if($checkExist > 0){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'موجود بالفعل',
                'message_en' => 'Already Exist'
            ]);
        }

        # add db 
        $created = Carbon::now();

        $inserted = DB::table('doctor_off_durations')
        ->insert([
            'doctor_id' => $req->doctor_id,
            'date_from' => $req->from_date,
            'date_to' => $req->to_date,
            'active' => 1,
            'id' => $created->getTimestampMs()
        ]);

        return response()->json([
            'status' => 'success',
            'message_ar' => 'تم اضافة الفترة بنجاح',
            'message_en' => 'Duration Added Success'
        ]);

    }

    public function listDoctorOffDurations(Request $req){
        $data = DB::table('doctor_off_durations')->where('doctor_id', $req->doctor_id)->orderBy('date_from', 'asc')->get();
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function deleteDoctorOffDurations(Request $req){
        if(empty($req->id) || is_null($req->id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'كود الفترة مطلوب',
                'message_en' => 'Duration Code is Required'
            ]);
        }

        # check founded
        $checkExist = DB::table('doctor_off_durations')->where('id', $req->id)->count();

        if($checkExist < 1){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'لا يوجد بيانات لحذفها',
                'message_en' => 'No Data Fro Deleting it'
            ]);
        }
        $deleted = DB::table('doctor_off_durations')->where('id', $req->id)->delete();

        if(!$deleted){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'خطأ فى عملية الحذف',
                'message_en' => 'Deleting Error'
            ]);
        }
        
        return response()->json([
            'status' => 'success',
            'message_ar' => 'تم حذف الفترة بنجاح',
            'message_en' => 'Duration is Deleted Success'
        ]);
    }

    public function doctorVisitorsAdd(Request $req){
        $visitors = DB::table('user_personal')->where('user_id', $req->doctor_id)->select('visitors')->first();
        $added = $visitors->visitors + 1;

        $updated = DB::table('user_personal')->where('user_id', $req->doctor_id)->update(['visitors' => $added]);
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function userNotificationOpened(Request $req){
        DB::table('user_notifications')->where('user_id', $req->user_id)->where('created', $req->created)->update(['read' => 1]);
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function userAllNotificationsOpened(Request $req){
        DB::table('user_notifications')->where('user_id', $req->user_id)->update(['read' => 1]);
        return response()->json([
            'status' => 'success'
        ]);
    }

    public function checkBookingAvailableTest(Request $req){
        $exist = DB::table('appointments_booking')
        ->where('doctor_id', $req->doctor_id)
        ->where('schedule_code', $req->created)
        ->where('day_index', $req->day_index)
        ->where('date', ''.$req->date.'')
        ->where('booking_status', 0)
        ->count();

        if($exist > 0){
            return response()->json(['status' => 'error']);
        }else{
            return response()->json(['status' => 'success']);
        }
    }

    public function userCollectsList(Request $erq){
        $data = DB::table('user_collect_requests')->where('user_id', $req->user_id)->get();
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function userCollectRequest(Request $req){
        if(is_null($req->user_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'كود المستخدم مطلوب',
                'message_en' => 'User Id Required'
            ]);
        }

        if(is_null($req->mount) || $req->mount < 1){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'مبلغ المحفظة مطلوب',
                'message_en' => 'Wallet Balance Required'
            ]);
        }
        # 0 new , 1 waiting, 2 approved , 3 canceled
        $checkPrevRequests = DB::table('user_collect_requests')
        ->where('user_id', $req->user_id)
        ->where('status', 0)
        ->count();

        if($checkPrevRequests > 0){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'لديك طلب قيد التنفيذ',
                'message_en' => 'You Have Pending Requests'
            ]);
        } 
        $created = Carbon::now();
        $inserted = DB::table('user_collect_requests')->insert([
            'id' => $created->getTimestampMs().'-'.$req->user_id,
            'user_id' => $req->user_id,
            'amount' =>$req->mount,
            'created_by' =>$req->user_id
        ]);
        $userFcmToken = DB::table('users')->where('id', $req->user_id)->first();
        # send notification db
        
        $created = Carbon::now();
        $notified = DB::table('user_notifications')->insert([
            'user_id' =>  $req->user_id,
            "title_ar" => 'اشعارات التحصيل',
            "content_ar" => 'تم ارسال طلب التحصيل الخص بك بنجاح',
            "title_en" => 'Collecting Notifications',
            "content_en" => 'Your Collect Request Was Sent Success',
            'created' => $created->getTimestampMs().'-'.$req->user_id,
            'read' => 0
        ]);
        # send notification realtime
        # send firebase push notification
        // $SERVER_API_KEY = 'AAAAqICtRL8:APA91bEouQSzCcYpDHy3Sec3xYsNfQVQHOj2VxBFON6PuBC1Rqga2ycgfq6YRpNLablpBrjVmd1YII7tejs_u_KkU8d_8pMzXcjmh8gM3QUSmqA0AjQ9iRCr1Ml9GUR5QIMGUhGf102G';//'AAAAj5ate9k:APA91bFg7DXD_RjKee3xbK1sVFdC87cg-bZbSwio8qmRnEMwWMS50LmILAy9Ot5NJZn3Kj8IEaO6lufZN5UPYanS7VATLhsumKq4_7vBv04IS-_YXAi8RIEZkRCfVLXUgA5qQgI8ktln';
        // $data = [
        //     "registration_ids" => [
        //         $userFcmToken->device_token//$token_1
        //     ],
        //     "notification" => [
        //         "title" => 'عملية دخول جديدة',
        //         "body" => 'تم تسجيل عملية دخول جديدة لحسابك',
        //         "sound"=> "default" // required for sound on ios
        //     ],
        //     "data" => [
        //         "type" => 'payment'
        //     ]
        // ];

        // $dataString = json_encode($data);
        // $headers = [
        //     'Authorization: key=' . $SERVER_API_KEY,
        //     'Content-Type: application/json',
        // ];

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        // $response = curl_exec($ch);
        return response()->json([
            'status' => 'success',
            'message_ar' => 'تم ارسال طلبك بنجاح',
            'message_en' => 'Request Sent Success'
        ]);
    }

    // public function userCollectRequests(Request $req){
    //     if(is_null($req->user_id)){
    //         return response()->json([
    //             'status' => 'error',
    //             'message_ar' => 'كود المستخدم مطلوب',
    //             'message_en' => 'User Id Required'
    //         ]);
    //     }

       
    //     $data = DB::table('doctor_collect_requests')->where('user_id', $req->user_id)->get();
       
       
    //     return response()->json([
    //         'status' => 'success',
    //         'data' => $data
    //     ]);
    // }

    public function userCollectRequests(Request $req){
        if(is_null($req->user_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'كود المستخدم مطلوب',
                'message_en' => 'User Id Required'
            ]);
        }

       
        $data = DB::table('user_collect_requests')->where('user_id', $req->user_id)->get();
       
       
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function doctorCollectRequest(Request $req){
        if(is_null($req->doctor_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'كود المستخدم مطلوب',
                'message_en' => 'User Id Required'
            ]);
        }

        if(is_null($req->mount) || $req->mount < 1){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'مبلغ المحفظة مطلوب',
                'message_en' => 'Wallet Balance Required'
            ]);
        }
        # 0 new , 1 waiting, 2 approved , 3 canceled
        $checkPrevRequests = DB::table('doctor_collect_requests')
        ->where('doctor_id', $req->doctor_id)
        ->where('status', 0)
        ->count();

        if($checkPrevRequests > 0){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'لديك طلب قيد التنفيذ',
                'message_en' => 'You Have Pending Requests'
            ]);
        } 
        $created = Carbon::now();
        $inserted = DB::table('doctor_collect_requests')->insert([
            'id' => $created->getTimestampMs().'-'.$req->doctor_id,
            'doctor_id' => $req->doctor_id,
            'amount' =>$req->mount,
            'created_by' =>$req->doctor_id
        ]);
        if($inserted){
            # lock the waller
            $doctorWallet = DB::table('users')->where('id', $req->doctor_id)->select('wallet')->first();
            $net = $doctorWallet->wallet - $req->mount;
            DB::table('users')->where('id', $req->doctor_id)->update(['wallet' => $net]);
        }
        # send notification db
        $created = Carbon::now();
        $notified = DB::table('user_notifications')->insert([
            'user_id' =>  $req->doctor_id,
            "title_ar" => 'اشعارات التحصيل',
            "content_ar" => 'تم ارسال طلب التحصيل الخص بك بنجاح',
            "title_en" => 'Collecting Notifications',
            "content_en" => 'Your Collect Request Was Sent Success',
            'created' => $created->getTimestampMs().'-'.$req->doctor_id,
            'read' => 0
        ]);
        
        return response()->json([
            'status' => 'success',
            'message_ar' => 'تم ارسال طلبك بنجاح',
            'message_en' => 'Request Sent Success'
        ]);
    }

    public function doctorCollectRequests(Request $req){
        if(is_null($req->doctor_id)){
            return response()->json([
                'status' => 'error',
                'message_ar' => 'كود الاستئارى مطلوب',
                'message_en' => 'Doctor Id Required'
            ]);
        }

       
        $data = DB::table('doctor_collect_requests')->where('doctor_id', $req->doctor_id)->get();
       
       
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function doctorInfo(Request $request){
        # user data
        // $user = DB::table('users')->where('mobile', $request->mobile)->where('user_type', $request->user_type)->first();
        
        $user = User::where('id',$request->user_id)->first();//->where('user_type', $request->user_type)->first();
        // dd($user, $request);
        if(is_null($user)){
            return response()->json([
                'status' => 'error',
                'message_en' => 'Not Found',
                'message_ar' => 'غير موجود'
            ]);
        }
        # check otp 
        // if($user->otp_confirmed == 0){
        //     return response()->json([
        //         'status' => 'error',
        //         'message_en' => 'The Mobile Not Activated Yet',
        //         'message_ar' => 'هذا الهاتف غير مفعل بعد'
        //     ]);
        // }
        
        $user_personal = DB::table('user_personal')->where('user_id', $user->id)
        ->join('specialists','specialists.id','=','user_personal.specialist_id')
        ->select('user_personal.*','specialists.title_ar as specialist_title_ar','specialists.title_en as specialist_title_en')
        ->first();
        // if(!$user || !Hash::check($request['password'],$user->password)){
        //     return response()->json([
        //         'status' => 'error',
        //         'message_en' => 'Invalid Credentials',
        //         'message_ar' => 'البيانات غير صحيحة'
        //     ],401);
        // }
        // $token = $user->createToken($user->name.'-AuthToken')->plainTextToken;
        # update device_token
        // $updateDeviceToken = DB::table('users')->where('id', $user->id)->update(['device_token' => $request->device_token]);

        # send push notification test
        # send firebase push notification
        // $SERVER_API_KEY = 'AAAAqICtRL8:APA91bEouQSzCcYpDHy3Sec3xYsNfQVQHOj2VxBFON6PuBC1Rqga2ycgfq6YRpNLablpBrjVmd1YII7tejs_u_KkU8d_8pMzXcjmh8gM3QUSmqA0AjQ9iRCr1Ml9GUR5QIMGUhGf102G';//'AAAAj5ate9k:APA91bFg7DXD_RjKee3xbK1sVFdC87cg-bZbSwio8qmRnEMwWMS50LmILAy9Ot5NJZn3Kj8IEaO6lufZN5UPYanS7VATLhsumKq4_7vBv04IS-_YXAi8RIEZkRCfVLXUgA5qQgI8ktln';
        // $token_1 = 'ekGDNzZtS-mgE5S9BicTly:APA91bGKheG-0AABfNtIYOJRAHYTu34oOQOaW9HCrcjP-N4Jt8e82YJGnS71MnxARQmRquBou_JRFEGCwTBYGZ1XGz50GPrzhG4W5_3o0IvBqKfqFijPRET22uJr7ZzxLhI2JWb5qawS';
        // $data = [
        //     "registration_ids" => [
        //         $request->device_token//$token_1
        //     ],
        //     "notification" => [
        //         "title" => 'عملية دخول جديدة',
        //         "body" => 'تم تسجيل عملية دخول جديدة لحسابك',
        //         "sound"=> "default" // required for sound on ios
        //     ],
        //     "data" => [
        //         "type" => 'payment'
        //     ]
        // ];

        // $dataString = json_encode($data);
        // $headers = [
        //     'Authorization: key=' . $SERVER_API_KEY,
        //     'Content-Type: application/json',
        // ];

        // $ch = curl_init();
        // curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $dataString);
        // $response = curl_exec($ch);

        return response()->json([
            // 'access_token' => $token,
            // 'message_en' => 'logged Success',
            // 'message_ar' => 'تم الدخول بنجاح',
            'status' => 'success',
            'user' => $user,
            'user_personal' =>$user_personal
        ]);
    }

    public function customerSupport(Request $req){
        $data = DB::table('settings')->where('key', 'customer_support')->first();
        return response()->json([
            'status' => 'success',
            'data' => $data->value,
        ]);
    }
    
    public function scheduleGenerate(Request $req){
        $today = Carbon::now();
        $start = Carbon::create(2024,04,20,$req->start_hour,$req->start_minute,00);
        $end = Carbon::create(2024,04,20,$req->end_hour,$req->end_minute,00);
        $hours = $start->diffInHours($end);
        // $finish = $req->start_hour + $hours;
        // dd((int)$req->start_hour, $finish);
        $times = $hours * 2;
        $currEnd = 0;
        $newStart = Carbon::create($start,00,00);
        for ($i= 0; $i <= $times ; $i++) {
            // echo ''.$i.' \n \r';
            // dd($i, $newStart->hour); 
            
            // $currEnd = $i->addMinutes(30);
            $checkExist = DB::table('doctor_schedule')
            ->where('doctor_id', $req->doctor_id)
            ->where('day_index', $req->day_index)
            ->where('from_hour' , $newStart->toTimeString())
            ->count();
            if($checkExist > 0){
                # delete
                DB::table('doctor_schedule')
                ->where('doctor_id', $req->doctor_id)
                ->where('day_index', $req->day_index)
                ->where('from_hour' , $newStart->toTimeString())
                ->delete();
            }
            # insert
            $today = Carbon::now();
            DB::table('doctor_schedule')
            ->insert([
                'doctor_id' => $req->doctor_id,
                'day_index' => $req->day_index,
                'from_hour' => $newStart->toTimeString(),
                'to_hour' => $newStart->addMinutes(30)->toTimeString(),
                'created_by' => $req->doctor_id,
                'created' => $today->getTimestampMs().'-'.$req->doctor_id.'-'.$i
            ]);

               
            
            # add 30 minutes
            // $newStart->addMinutes(30);
            // echo "counter: ".$i." => ".$newStart->toTimeString()." \n \r";
        }
        // $data = DB::table('doctor_schedule')->where('doctor_id', $req->doctor_id)->orderBy('created','asc')->get();
        // dd($start, $end, $start->diffInHours($end));
        // dd(count($hours));
        return response()->json([
            'status' => 'success',
            'message_ar' => 'تم اضافة المواعيد بنجاح',
            'message_en' => 'Added Success',
            ]);
    }


    
    
    
}

