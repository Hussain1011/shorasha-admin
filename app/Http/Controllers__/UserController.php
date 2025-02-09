<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Models\AppointmentsBooking;
use App\Models\DoctorCollectRequest;
use App\Models\Setting;
use App\Models\User;
use App\Models\UserPersonal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use DB;
use Carbon\Carbon;
use App;

class UserController extends Controller
{
    public function index(Request $request)
    {
        // dd($request);
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $users = User::where('user_type', 1)->when($request->search, function ($q) use ($request) {
            return $q->where('name', $request->search)->orWhere('email', $request->search);
        })->latest()->paginate($request->pagination ?? 10);
        return view('admin/user/user-list')
        ->with('deleted',false)

            ->with('routePrefix', $routePrefix)
            ->with('users', $users);
    }
    public function deletedUsers(Request $request)
    {
        // dd($request);
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $users = User::where('user_type', 1)->when($request->search, function ($q) use ($request) {
            return $q->where('name', $request->search)->orWhere('email', $request->search);
        })->onlyTrashed()->paginate($request->pagination ?? 10);
        return view('admin/user/user-list')
            ->with('deleted',true)
            ->with('routePrefix', $routePrefix)
            ->with('users', $users);
    }

    public function restoreUser($id)
    {
        $user = User::withTrashed()->where('id', $id)->first();
        $user->restore();
        return back()->with('message', 'success');
    }


    public function consultantIndex(Request $request)
    {
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $users = User::where('user_type', 2)->whereHas('personal', function ($q) {
            $q->where('doctor_verified', 1);
        })
            ->latest()->paginate($request->pagination ?? 10);
        //     $users = DB::table('users')
        //     ->join('user_personal','user_personal.user_id','=','users.id')
        //     ->join('doctor_specialists','doctor_specialists.doctor_id','=','user_personal.user_id')
        //     ->join('specialists_departments','specialists_departments.id','=','doctor_specialists.specialist_id')
        //     ->select('users.name','users.created_at','user_personal.*','specialists_departments.title_en','specialists_departments.title_ar')
        //     ->where('user_personal.doctor_verified', 1)
        //    ->where('users.user_type', 2)
        //    ->latest()->paginate($request->pagination ?? 10);
        return view('admin/consultant/consultant-list')
            ->with('deleted',false)
            ->with('routePrefix', $routePrefix)
            ->with('users', $users);
    }
    public function ConsultantChangeRequestIndex(Request $request)
    {
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $users = User::where('user_type', 2)->whereHas('personal', function ($q) {
            $q->where('doctor_verified', 1)->whereNotNull('new_fees');
        })
            ->latest()->paginate($request->pagination ?? 10);
        return view('admin/consultant/consultant-change-list')
            ->with('deleted',false)
            ->with('routePrefix', $routePrefix)
            ->with('users', $users);
    }
    public function deletedConsultantIndex(Request $request)
    {
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $users = User::where('user_type', 2)->whereHas('personal', function ($q) {
            $q->where('doctor_verified', 1);
        })
        ->onlyTrashed()->paginate($request->pagination ?? 10);
        return view('admin/consultant/consultant-list')
        ->with('deleted',true)

            ->with('routePrefix', $routePrefix)
            ->with('users', $users);
    }
    public function newConsultantIndex(Request $request)
    {
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $users = User::where('user_type', 2)->whereHas('personal', function ($q) {
            $q->where('doctor_verified', 0)->whereNull('rejection_reason');
        })
            ->latest()->paginate($request->pagination ?? 10);
        return view('admin/consultant/new-consultant')
            ->with('routePrefix', $routePrefix)
            ->with('users', $users);
    }


/*
    public function consultantShow($id)
    {
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $user = User::where('id', $id)->first();
        $transferred_amount = DoctorCollectRequest::where('status',1)->whereNotNull('reference_no')
        ->where('doctor_id',$id)->sum('amount');
        $bookings = AppointmentsBooking::where('doctor_id',$id)->where('booking_status', 1)->count();
        $fees = $user->personal?->fees;
        $total_fees = $fees*$bookings;
        $set_value = Setting::where('key',Setting::APP_COMMISSION)->first()?->value ?? 20;

        $intValue = intval($set_value);
        $intValue = ( ($intValue / 100));
        $setShare = $total_fees*$intValue;
        // dd($total_fees,$intValue);
        $doctor_debit = $total_fees-$setShare-$transferred_amount;
        return view('admin/consultant/consultant-profile')
            ->with('routePrefix', $routePrefix)
            ->with('user', $user)
            ->with('bookings', $bookings)
            ->with('total_fees', $total_fees)
            ->with('setShare', $setShare)
            ->with('doctor_debit', $doctor_debit)
            ->with('transferred_amount', $transferred_amount);
    }
*/

   public function consultantShow($id)
    {
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $user = User::where('id', $id)->first();
        $transferred_amount = DoctorCollectRequest::where('status',1)->whereNotNull('reference_no')
        ->where('doctor_id',$id)->sum('amount');
        $bookings = AppointmentsBooking::where('doctor_id',$id)->where('booking_status', 1)->count();
        // $fees = $user->personal?->fees;
        //  $fees*$bookings;
        $set_value = Setting::where('key',Setting::APP_COMMISSION)->first()?->value ?? 20;

        // $intValue = intval($set_value);
        // $intValue = ( ($intValue / 100));
        $setShare =AppointmentsBooking::where('doctor_id',$id)->where('booking_status', 1)->sum('sys_comm_value');
        //  $total_fees*$intValue;
        $doctor_debit_with_transfared=AppointmentsBooking::where('doctor_id',$id)->where('booking_status', 1)->sum('dr_comm_value');
	   	$doctor_debit = $doctor_debit_with_transfared - $transferred_amount;
        $total_fees = $setShare + $doctor_debit;
        // mfb updated
        $doctorBookings = DB::table('appointments_booking')
            ->join('users','users.id','=','appointments_booking.user_id')
            ->join('payment_transactions','payment_transactions.booking_code','=','appointments_booking.created')
            ->where('appointments_booking.doctor_id', $id)
            ->select(
                'appointments_booking.created','users.name','users.id as user_id','appointments_booking.start_time','appointments_booking.booking_type',
                'appointments_booking.booking_status as status','appointments_booking.fees','payment_transactions.payment_gateway as type'
                )
            ->get();
            // dd($doctorBookings);
        // $total_fees-$setShare-$transferred_amount;
        return view('admin/consultant/consultant-profile')
            ->with('routePrefix', $routePrefix)
            ->with('user', $user)
            ->with('bookings', $bookings)
            ->with('total_fees', $total_fees)
            ->with('setShare', $setShare)
            ->with('doctor_debit', $doctor_debit)
            ->with('transferred_amount', $transferred_amount)
            ->with('doctorBookings', $doctorBookings);
    }


    public function show($id)
    {
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $user = User::where('id', $id)->first();
        return view('admin/user/user-profile')
            ->with('routePrefix', $routePrefix)
            ->with('user', $user);
    }


    public function edit($id)
    {
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $user = User::where('id', $id)->first();
        return view('admin/user/user-edit')
            ->with('routePrefix', $routePrefix)
            ->with('user', $user);
    }

    public function update(Request $request)
    {
        $user = User::where('id', $request->id)->first();
        if(is_null($user->photo)){
            $profile_photo = time().'-photo.'.$request->profile_photo->extension();  
            $profile_photo_path = $request->profile_photo->storeAs('public/profile_photos', $profile_photo);
            $profile_photo_path = str_replace('public','storage', $profile_photo_path);
            $user->photo = $profile_photo_path;
        }else{
            // $profile_photo_title = explode('profile_photos/', $user->photo);
            // $profile_photo = $profile_photo_title[1]; //time().'.'.$request->profile_photo->extension();  
            // $profile_photo_path = $request->profile_photo->storeAs('public/profile_photos', $profile_photo);
            // $profile_photo_path = str_replace('public','storage', $profile_photo_path);
            // $user->profile_photo_path;

            $profile_photo = time().'-photo.'.$request->profile_photo->extension();  
            $profile_photo_path = $request->profile_photo->storeAs('public/profile_photos', $profile_photo);
            $profile_photo_path = str_replace('public','storage', $profile_photo_path);
            $user->photo = $profile_photo_path;
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->save();
        // $user_personal = UserPersonal::where('user_id', $request->id)->first();
        return back()->with('message', 'success');
    }

    public function create()
    {
        $user_type = 1;
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        return view('admin/user/user-create')
            ->with('user_type', $user_type)
            ->with('routePrefix', $routePrefix);
    }
    public function Consultantcreate()
    {
        $user_type = 2;
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $cities = DB::table('cities')->where('active', 1)->get();
        $countries = DB::table('countries')->where('active', 1)->get();
        $nationalities = DB::table('nationalities')->where('active', 1)->get();
        return view('admin/consultant/consultant-create')
            ->with('user_type', $user_type)
            ->with('routePrefix', $routePrefix)
            ->with('cities', $cities)
            ->with('countries', $countries)
            ->with('nationalities', $nationalities)
            ->with('lan', App::getLocale());
    }



    public function ConsultantEdit(Request $request, $id)
    {
        $details = [];
        $details['user'] = DB::table('users')->where('id', $id)->first();
        $details['doctor'] = DB::table('user_personal')->where('user_id', $id)->first();
        $details['specialities'] = DB::table('specialists')->get()->map(function($record)use($request, $id){
            $exist = DB::table('doctor_specialists')->where('doctor_id', $id)->where('specialist_id', $record->id)->count();
            // dd($record->specialist_id, $id, $exist);
            if($exist > 0){
                $record->selected = 'yes';
            }else{
                $record->selected = 'no';
            }
            return $record;
        });
        
        $user_type = 2;
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $cities = DB::table('cities')->where('active', 1)->get();
        $countries = DB::table('countries')->where('active', 1)->get();
        $nationalities = DB::table('nationalities')->where('active', 1)->get();
        return view('admin/consultant/consultant-edit')
            ->with('user_type', $user_type)
            ->with('routePrefix', $routePrefix)
            ->with('cities', $cities)
            ->with('countries', $countries)
            ->with('nationalities', $nationalities)
            ->with('details', $details)
            ->with('id', $id)
            ->with('lan', App::getLocale());
    }

    public function action(Request $request)
    {
        // dd($request->all());
        $user = User::find($request->id);
        $user_personal = UserPersonal::where('user_id', $request->id)->first();
        $user_personal->doctor_verified = $request->doctor_verified;
        $user_personal->rejection_reason = $request->rejection_reason;
        $user_personal->save();
        return back()->with('message', 'success');
    }

    public function store(Request $request)
    {


        
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'name' => 'required',
            'mobile' => 'required',
			// 'fees' => 'required',
            'profile_photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            //'date_birth' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // dd($request);
        $profile_photo = time().'-photo.'.$request->profile_photo->extension();  
        $profile_photo_path = $request->profile_photo->storeAs('public/profile_photos', $profile_photo);
        $profile_photo_path = str_replace('public','storage', $profile_photo_path);
        // dd('here');
        $validated = $validator->validated();
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->password = bcrypt($request->password);
        $user->user_type = 1;
        $user->password = Hash::make($request->password);
        $user->photo = $profile_photo_path;
        $user->save();
        // $user_personal = new UserPersonal();
        // $user_personal->user_id = $user->id;
		// $user_personal->fees = $request->fees;
        // $user_personal->photo = $profile_photo_path;
        // $user_personal->save();
        return back()->with('message', 'success');
    }
    public function consultantStore(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'name' => 'required',
            'mobile' => 'required',
			'fees' => 'required',
            'profile' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'doctor_id_1' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'doctor_id_2' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'certificate' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'speciality' => 'required',
            'nationality_id' => 'required',
            'city_id' => 'required',
            'address' => 'required',
            'id_expired_date' => 'required|date',
            'country_id' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        // $user->password = bcrypt($request->password);
        $user->user_type = $request->user_type;
        $user->password = Hash::make($request->password);
        $user->save();

        if ($request->file('profile')) {
            // $file = $request->file('profile');
            // $profile_path = $file->store('uploads');

            $profile = time().'-profile.'.$request->profile->extension();  
            $profile_path = $request->profile->storeAs('public/profiles', $profile);
            $profile_path = str_replace('public','storage', $profile_path);
        }
        if ($request->file('doctor_id_1')) {
            // $file = $request->file('doctor_id_1');
            // $doctor_id_1_path = $file->store('uploads');

            $doctor_id_1 = time().'-doctor_id_1.'.$request->doctor_id_1->extension();  
            $doctor_id_1_path = $request->doctor_id_1->storeAs('public/Ids', $doctor_id_1);
            $doctor_id_1_path = str_replace('public','storage', $doctor_id_1_path);
        }
        if ($request->file('doctor_id_2')) {
            // $file = $request->file('doctor_id_2');
            // $doctor_id_2_path = $file->store('uploads');
            $doctor_id_2 = time().'-doctor_id_2.'.$request->doctor_id_2->extension();  
            $doctor_id_2_path = $request->doctor_id_2->storeAs('public/Ids', $doctor_id_2);
            $doctor_id_2_path = str_replace('public','storage', $doctor_id_2_path);
        }
        if ($request->file('photo')) {
            // $file = $request->file('photo');
            // $photo_path = $file->store('uploads');

            $photo = time().'-photo.'.$request->photo->extension();  
            $photo_path = $request->photo->storeAs('public/photos', $photo);
            $photo_path = str_replace('public','storage', $photo_path);
        }
        if ($request->file('certificate')) {
            // $file = $request->file('certificate');
            // $certificate_path = $file->store('uploads');

            $certificate = time().'-certificate.'.$request->certificate->extension();  
            $certificate_path = $request->certificate->storeAs('public/certificates', $certificate);
            $certificate_path = str_replace('public','storage', $certificate_path);
        }

        $user_personal = new UserPersonal();
        $user_personal->user_id = $user->id;
        $user_personal->cv = isset($profile_path)? $profile_path:null;
        $user_personal->id_face_1 = isset($doctor_id_1_path)? $doctor_id_1_path : null;
        $user_personal->id_face_2 = isset($doctor_id_2_path)? $doctor_id_2_path : null;
        $user_personal->photo = isset($photo_path)? $photo_path : null;
        $user_personal->certificate = isset($certificate_path)? $certificate_path : null;
        $user_personal->fees = $request->fees;
		//$user_personal->date_birth = $request->date_birth;
        $user_personal->doctor_verified = 0;
        $user_personal-> address = $request->address;
        $user_personal-> city_id = $request->city_id;
        $user_personal-> country_id = $request->country_id;
        $user_personal-> nationality_id = $request->nationality_id;
        $user_personal-> id_expired_date = $request->id_expired_date;
        $user_personal->save();

        foreach ($request->speciality as $key => $value) {
            if($key == 0){
                DB::table('user_personal')->where('user_id', $user->id)->update(['specialist_id' => $value]);
                $exist = DB::table('doctor_specialists')->where('doctor_id', $user->id)->where('specialist_id', $value)->count();
                if($exist < 1){
                    $created = Carbon::now();
                    DB::table('doctor_specialists')->insert(['doctor_id' => $user->id,'specialist_id' => $value,'active' => 1, 'created' => $created->getTimestampMs().'-'.$key]);
                }
            }
            $exist = DB::table('doctor_specialists')->where('doctor_id', $user->id)->where('specialist_id', $value)->count();
            if($exist < 1){
                $created = Carbon::now();
                DB::table('doctor_specialists')->insert(['doctor_id' => $user->id,'specialist_id' => $value,'active' => 1, 'created' => $created->getTimestampMs().'-'.$key]);
            }
        }
        
        // dd($user);
        return back()->with('message', 'success');
    }

    public function consultantUpdate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'name' => 'required',
            'mobile' => 'required',
			'fees' => 'required',
            'profile' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'doctor_id_1' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'doctor_id_2' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'photo' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'certificate' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'speciality' => 'required',
            'nationality_id' => 'required',
            'city_id' => 'required',
            'address' => 'required',
            'id_expired_date' => 'required|date',
            'country_id' => 'required'
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        $validated = $validator->validated();

        if ($request->file('profile')) {
            // $file = $request->file('profile');
            // $profile_path = $file->store('uploads');

            $profile = time().'-profile.'.$request->profile->extension();  
            $profile_path = $request->profile->storeAs('public/profiles', $profile);
            $profile_path = str_replace('public','storage', $profile_path);
        }
        if ($request->file('doctor_id_1')) {
            // $file = $request->file('doctor_id_1');
            // $doctor_id_1_path = $file->store('uploads');

            $doctor_id_1 = time().'-doctor_id_1.'.$request->doctor_id_1->extension();  
            $doctor_id_1_path = $request->doctor_id_1->storeAs('public/Ids', $doctor_id_1);
            $doctor_id_1_path = str_replace('public','storage', $doctor_id_1_path);
        }
        if ($request->file('doctor_id_2')) {
            // $file = $request->file('doctor_id_2');
            // $doctor_id_2_path = $file->store('uploads');
            $doctor_id_2 = time().'-doctor_id_2.'.$request->doctor_id_2->extension();  
            $doctor_id_2_path = $request->doctor_id_2->storeAs('public/Ids', $doctor_id_2);
            $doctor_id_2_path = str_replace('public','storage', $doctor_id_2_path);
        }
        if ($request->file('photo')) {
            // $file = $request->file('photo');
            // $photo_path = $file->store('uploads');

            $photo = time().'-photo.'.$request->photo->extension();  
            $photo_path = $request->photo->storeAs('public/photos', $photo);
            $photo_path = str_replace('public','storage', $photo_path);
        }
        if ($request->file('certificate')) {
            // $file = $request->file('certificate');
            // $certificate_path = $file->store('uploads');

            $certificate = time().'-certificate.'.$request->certificate->extension();  
            $certificate_path = $request->certificate->storeAs('public/certificates', $certificate);
            $certificate_path = str_replace('public','storage', $certificate_path);
        }
        
        $user = User::find($request->id);
        $user->name = $request->name;
        // $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->photo = isset($photo_path)? $photo_path : null;
        $user->user_type = $request->user_type;
        $user->password = Hash::make($request->password);
        $user->save();

        
     
        $user_personal = UserPersonal::find($request->id);
        $user_personal->user_id = $user->id;
        $user_personal->cv = isset($profile_path)? $profile_path:null;
        $user_personal->id_face_1 = isset($doctor_id_1_path)? $doctor_id_1_path : null;
        $user_personal->id_face_2 = isset($doctor_id_2_path)? $doctor_id_2_path : null;
        $user_personal->photo = isset($photo_path)? $photo_path : null;
        $user_personal->certificate = isset($certificate_path)? $certificate_path : null;
        $user_personal->fees = $request->fees;
		//$user_personal->date_birth = $request->date_birth;
        $user_personal->doctor_verified = 0;
        $user_personal-> address = $request->address;
        $user_personal-> city_id = $request->city_id;
        $user_personal-> country_id = $request->country_id;
        $user_personal-> nationality_id = $request->nationality_id;
        $user_personal-> id_expired_date = $request->id_expired_date;
        $user_personal->save();

        foreach ($request->speciality as $key => $value) {
            if($key == 0){
                DB::table('user_personal')->where('user_id', $user->id)->update(['specialist_id' => $value]);
                $exist = DB::table('doctor_specialists')->where('doctor_id', $user->id)->where('specialist_id', $value)->count();
                if($exist < 1){
                    $created = Carbon::now();
                    DB::table('doctor_specialists')->insert(['doctor_id' => $user->id,'specialist_id' => $value,'active' => 1, 'created' => $created->getTimestampMs().'-'.$key]);
                }
            }
            $exist = DB::table('doctor_specialists')->where('doctor_id', $user->id)->where('specialist_id', $value)->count();
            if($exist < 1){
                $created = Carbon::now();
                DB::table('doctor_specialists')->insert(['doctor_id' => $user->id,'specialist_id' => $value,'active' => 1, 'created' => $created->getTimestampMs().'-'.$key]);
            }
        }
        
        // dd($user);
        return back()->with('message', 'success');
    }

    public function delete(Request $request)
    {
        // dd($request->id);
        $user = User::find($request->id);
        // dd($user->id);
        $user->delete();
        if ($request->inside) {
           return redirect('/consultant/list')->with('message', 'success');

        }
        return back()->with('message', 'success');
    }


    public function transferAmount(Request $request)
    {
        $user = UserPersonal::find($request->id);
        $user->transferred_amount = ($user->transferred_amount??0)+$request->transferred_amount;
        $user->save();
        return back()->with('message', 'success');

    }


    public function ConsultantChangeRequestApprove(Request $request)
    {
        $change_request = UserPersonal::find($request->id);
        $change_request->fees = $change_request->new_fees ;
        $change_request->new_fees_confirmed = true;
        $change_request->new_fees = null ;
        $change_request->update();
        return back()->with('message', 'success');
    }

    public function ConsultantChangeRequestReject(Request $request)
    {
        $change_request = UserPersonal::find($request->id);
        $change_request->new_fees = null ;
        $change_request->new_fees_confirmed = false;
        $change_request->update();
        return back()->with('message', 'success');
    }
}
