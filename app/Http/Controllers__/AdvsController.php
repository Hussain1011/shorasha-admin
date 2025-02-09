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
use Storage;

class AdvsController extends Controller
{
    public function index(Request $request)
    {
        // dd($request);
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        // $advs = User::where('user_type', 1)->when($request->search, function ($q) use ($request) {
        //     return $q->where('name', $request->search)->orWhere('email', $request->search);
        // })->latest()->paginate($request->pagination ?? 10);

        $advs = DB::table('advs')
        // ->join('users', 'notices.user_id', '=', 'users.id')
        // ->join('departments', 'users.dpt_id', '=', 'departments.id')
        // ->select('notices.id', 'notices.title', 'notices.body', 'notices.created_at', 'notices.updated_at', 'users.name', 'departments.department_name')
        ->paginate($request->pagination ?? 10);

        return view('admin/advs/adv-list')
        ->with('deleted',false)

            ->with('routePrefix', $routePrefix)
            ->with('advs', $advs);
    }
    public function deletedUsers(Request $request)
    {
        // dd($request);
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $users = User::where('user_type', 1)->when($request->search, function ($q) use ($request) {
            return $q->where('name', $request->search)->orWhere('email', $request->search);
        })->onlyTrashed()->paginate($request->pagination ?? 10);
        return view('admin/user/adv-list')
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
        return view('admin/user/adv-profile')
            ->with('routePrefix', $routePrefix)
            ->with('user', $user);
    }


    public function edit($id)
    {
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        $adv = DB::table('advs')->where('id', $id)->first(); //User::where('id', $id)->first();
        return view('admin/advs/adv-edit')
            ->with('routePrefix', $routePrefix)
            ->with('id', $id)
            ->with('adv', $adv);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'adv_ar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'adv_en' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $details = DB::table('advs')->where('id', $request->id)->first();
        $adv_en_title = explode('advs/', $details->image_en);
        $adv_en = $adv_en_title[1]; //time().'.'.$request->adv_en->extension();  
        $adv_en_path = $request->adv_en->storeAs('public/advs', $adv_en);
        $adv_en_path = str_replace('public','storage', $adv_en_path);

        $adv_ar_title = explode('advs/', $details->image_ar);
        $adv_ar = $adv_ar_title[1]; //time().'.'.$request->adv_ar->extension();  
        $adv_ar_path = $request->adv_ar->storeAs('public/advs', $adv_ar);
        $adv_ar_path = str_replace('public','storage', $adv_ar_path);

        $updated = DB::table('advs')->where('id', $request->id)->update([
            'image_en' => $adv_en_path,
            'image_ar' => $adv_ar_path,
            'updated_at' => now()
        ]);
        return back()->with('message', 'success');
    }

    public function create()
    {
        // $user_type = 1;
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        return view('admin/advs/adv-create')
            // ->with('user_type', $user_type)
            ->with('routePrefix', $routePrefix);
    }
    public function Consultantcreate()
    {
        $user_type = 2;
        $pageName = request()->route()->getName();
        $routePrefix = explode('/', $pageName)[0] ?? '';
        return view('admin/consultant/consultant-create')
            ->with('user_type', $user_type)
            ->with('routePrefix', $routePrefix);
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
            'type' => 'required',
            'adv_ar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'adv_en' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
        ]);


        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();

        $adv_en = time().'-en.'.$request->adv_en->extension();  
        $adv_en_path = $request->adv_en->storeAs('public/advs', $adv_en);
        $adv_en_path = str_replace('public','storage', $adv_en_path);

        $adv_ar = time().'-ar.'.$request->adv_ar->extension();  
        $adv_ar_path = $request->adv_ar->storeAs('public/advs', $adv_ar);
        $adv_ar_path = str_replace('public','storage', $adv_ar_path);

        $inserted = DB::table('advs')->insert([
            'type' => $request->type,
            'image_en' => $adv_en_path,
            'image_ar' => $adv_ar_path,
            'active' => 1
        ]);

        return back()->with('message', 'success');
    }
    public function consultantStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'name' => 'required',
            'mobile' => 'required',
			'fees' => 'required'
            //'date_birth' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $validated = $validator->validated();
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->password = bcrypt($request->password);
        $user->user_type = $request->user_type;
        $user->password = Hash::make($request->password);
        $user->save();

        if ($request->file('profile')) {
            $file = $request->file('profile');
            $profile_path = $file->store('uploads');
        }
        if ($request->file('doctor_id_1')) {
            $file = $request->file('doctor_id_1');
            $doctor_id_1_path = $file->store('uploads');
        }
        if ($request->file('doctor_id_2')) {
            $file = $request->file('doctor_id_2');
            $doctor_id_2_path = $file->store('uploads');
        }
        if ($request->file('photo')) {
            $file = $request->file('photo');
            $photo_path = $file->store('uploads');
        }
        if ($request->file('certificate')) {
            $file = $request->file('certificate');
            $certificate_path = $file->store('uploads');
        }

        $user_personal = new UserPersonal();
        $user_personal->user_id = $user->id;
        $user_personal->cv = isset($profile_path)? url($profile_path):null;
        $user_personal->id_face_1 = isset($doctor_id_1_path)? url($doctor_id_1_path):null;
        $user_personal->id_face_2 = isset($doctor_id_2_path)? url($doctor_id_2_path):null;
        $user_personal->photo = isset($photo_path)? url($photo_path):null;
        $user_personal->certificate = isset($certificate_path)? url($certificate_path):null;
        $user_personal->fees = $request->fees;
		//$user_personal->date_birth = $request->date_birth;
        $user_personal->doctor_verified = 0;
        $user_personal->save();
        // dd($user);
        return back()->with('message', 'success');
    }

    public function delete(Request $request)
    {
        $details = DB::table('advs')->where('id', $request->id)->first();
        

        $deleted = DB::table('advs')->where('id', $request->id)->delete();
        if($deleted){
            $image_en = str_replace('storage','public', $details->image_en);
            if(Storage::exists($image_en)){
                Storage::delete($image_en);
            }

            $image_ar = str_replace('storage','public', $details->image_ar);
            if(Storage::exists($image_ar)){
                Storage::delete($image_ar);
            }
            return back()->with('message', 'success');
        }else{
            return back()->with('message', 'error');
        }
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
