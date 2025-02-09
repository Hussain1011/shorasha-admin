@extends('admin.layout')
@section('content')
    <div class="flex flex-wrap lg:flex-nowrap gap-6">
        <!-- Consultant Information & Personal Information -->
        <div class="w-full lg:w-1/2 flex flex-col gap-6">
            <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-[1.375rem]">
                <div class="py-4 border-b border-light-blue-200">
                    <div class="flex justify-center items-center flex-col text-center gap-2">
                        {{-- {{dd($user->personal->photo)}} --}}
                        <img class="w-20 h-20 rounded-full object-cover" src="{{url('/')}}/{{ $user->personal?->photo }}"
                            alt="consultant image">
                        <p class="text-darkGray font-semibold">{{ $user->name }}</p>
                        <p class="text-[#75B3BB] text-sm">
                            {{ implode(' | ', $user->specialists->pluck('title_' . app()->getLocale())->toArray()) }}</p>
                        <p class="text-[#808080] text-sm">{{ $user->personal?->address }},
                            {{ $user->personal?->nationality?->{'title_' . App::getLocale()} }}</p>
                    </div>
                </div>
                <div class="p-3 flex flex-col justify-center items-center gap-7">
                    <div class="text-center flex flex-col gap-2">
                        <p class="text-[#90B5BA]">{{ __('lang.fees') }}</p>
                        <span class="text-main font-bold text-xl">{{ $user->personal?->fees }} QAR</span>
                    </div>

                    @if ($user->personal?->doctor_verified == 0)
                        <div class="flex gap-4">
                            <button
                                class="bg-main rounded-[0.8125rem] py-1.5 w-32 text-white shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)] text-lg"><a
                                    href="{{ route('/consultant/action', ['id' => $user->id, 'doctor_verified' => 1]) }}">
                                    Assign</a></button>
                            <button class="border border-main text-main rounded-[0.8125rem] py-1.5 w-32 text-lg"
                                data-modal-target="default-modal" data-modal-toggle="default-modal">{{trans('lang.reject')}}</button>
                        </div>
                        @else
                        <form id="{{ $user->id }}"
                            action="{{ route('user/delete', ['id' => $user->id]) }}" method="POST">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="inside" value="{{ true}}">
                            <button type="button" class="btn btn-danger"
                                onclick="confirmDelete({{ $user->id }})">
                                <img src="{{ asset('images/images/actions-delete.svg') }}"
                                    alt="actions delete icon"></i></button>
                        </form>
                    @endif
                </div>

            </div>
            <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-[1.375rem]">
                <div class="py-4 px-5 flex justify-between items-center border-b border-light-blue-200">
                    <p class="text-darkBlue font-semibold text-[1.375rem]">{{ __('lang.personal_information') }}</p>
                </div>
                <div class="flex flex-col gap-4 p-6">
                    <div class="flex flex-col gap-4">
                        <div class="flex">
                            <span class="block w-1/4 text-[#808080]">{{ __('lang.name') }}</span>
                            <span class="text-darkGray">{{ $user->name }}</span>
                        </div>
                        <div class="flex">
                            <span class="block w-1/4 text-[#808080]">{{ __('lang.phone') }}</span>
                            <span class="text-darkGray">{{ $user->mobile }}</span>
                        </div>
                    </div>
                    <div class="bg-light-blue-200 h-[1px]"></div>
                    <div class="flex flex-col gap-4">
                        <div class="flex">
                            <span class="block w-1/4 text-[#808080]">{{ __('lang.location') }}</span>
                            <span class="text-darkGray">{{ $user->personal?->address }}</span>
                        </div>
                        <div class="flex">
                            <span class="block w-1/4 text-[#808080]">{{ __('lang.nationality') }}</span>
                            <span
                                class="text-darkGray">{{ $user->personal?->nationality?->{'title_' . App::getLocale()} ?? 'Default Title' }}
                            </span>
                        </div>
                        {{-- <div class="flex">
                            <span class="block w-1/4 text-[#808080]">{{ __('lang.age') }}</span>
                            <span
                                class="text-darkGray">{{ now()->diff($user->personal?->date_birth)->y ? now()->diff($user->personal?->date_birth)->y : 'AN' }}
                                {{ __('lang.years old') }} </span>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
        <!-- Documents & Specializations -->
        <div class="w-full lg:w-1/2 flex flex-col gap-6">
            <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-[1.375rem]">
                <p class="text-darkBlue text-[1.375rem] p-5 border-b border-light-blue-200">{{ __('lang.documents') }}</p>
                <div class="px-5">
                    <div class="flex justify-between py-4 border-b border-light-blue-200">
                        <div class="flex gap-2 items-center">
                            <img src="{{ asset('images/images/document-profile.svg') }}" alt="profile icon">

                            <p class="text-darkGray text-lg">{{ __('lang.profile') }}</p>
                        </div>
                        <div class="flex items-center gap-5">

                            <a href="{{ $user->personal?->profile }}" download>
                                <img src="{{ asset('images/images/download-icon.svg') }}" alt="download icon">
                            </a>
                        </div>
                    </div>
                    <div class="flex justify-between py-4 border-b border-light-blue-200">
                        <div class="flex gap-2 items-center">
                            <img src="{{ asset('images/images/document-passport.svg') }}" alt="passport icon">
                            <p class="text-darkGray text-lg">{{ __('lang.front_id_passport') }}</p>
                        </div>
                        <div class="flex items-center gap-5">
                            <a href="{{ $user->personal?->doctor_id_1 }}" download>
                                <img src="{{ asset('images/images/download-icon.svg') }}" alt="download icon">
                            </a>
                        </div>
                    </div>
                    <div class="flex justify-between py-4 border-b border-light-blue-200">
                        <div class="flex gap-2 items-center">
                            <img src="{{ asset('images/images/document-passport.svg') }}" alt="passport icon">
                            <p class="text-darkGray text-lg">{{ __('lang.back_id_passport') }}</p>
                        </div>
                        <div class="flex items-center gap-5">
                            <a href="{{ $user->personal?->doctor_id_2 }}" download>
                                <img src="{{ asset('images/images/download-icon.svg') }}" alt="download icon">
                            </a>
                        </div>
                    </div>
                    <div class="flex justify-between py-4 border-b border-light-blue-200">
                        <div class="flex gap-2 items-center">
                            <img src="{{ asset('images/images/document-photo.svg') }}" alt="profile photo">
                            <p class="text-darkGray text-lg">{{ __('lang.photo') }}</p>
                        </div>
                        <div class="flex items-center gap-5">
                            <a href="{{ $user->personal?->photo }}" download>
                                <img src="{{ asset('images/images/download-icon.svg') }}" alt="download icon">
                            </a>
                        </div>
                    </div>
                    <div class="flex justify-between py-4">
                        <div class="flex gap-2 items-center">
                            <img src="{{ asset('images/images/document-certificate.svg') }}" alt="certificate icon">
                            <p class="text-darkGray text-lg">{{ __('lang.certificate') }}</p>
                        </div>
                        <div class="flex items-center gap-5">
                            <a href="{{ $user->personal?->certificate }}" download>
                                <img src="{{ asset('images/images/download-icon.svg') }}" alt="download icon">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-[1.375rem]">
                <p class="text-darkBlue text-[1.375rem] p-5 border-b border-light-blue-200">{{ __('lang.speciality') }}</p>
                <div class="px-5">
                    @foreach ($user->specialists as $item)
                        <div class="flex gap-4 py-4 border-b border-light-blue-200 items-center">
                            <img class="w-10 h-10 rounded-full object-cover" src="{{ $item?->photo }}" alt="profile icon">
                            <div class="flex flex-col gap-2">
                                <p class="text-darkGray">{{ $item?->{'title_' . App::getLocale()} }}</p>
                                {{-- <p class="text-[#808080] text-sm">Certified in family mental health</p> --}}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    {{-- <form action="{{ route('admin.doctor.transferred_amount') }}" method="POST" class="space-y-6">
        @csrf
        @method('post')
        <!-- Personal Information -->
        <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.048)] rounded-[1.375rem]">
        <input name="id" value="{{$user->id}}" hidden>
            <div class="flex flex-col gap-4 p-6">
                <div class="flex flex-col gap-4">
                    <div class="flex">
                        <label for="transferred_amount" class="block w-1/4 text-[#808080]">{{__('lang.transferred_amount')}}</label>
                        <input type="number" id="transferred_amount" name="transferred_amount" value="{{ old('transferred_amount') }}"
                            class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">
                    </div>
                    @error('transferred_amount')
                        <span class="text-tiny+ text-error" style="color:red">{{ $message }}</span>
                    @enderror
                    <!-- Other fields can be added here -->
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save
            </button>
        </div>
    </form> --}}
        <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-2xl">
          <div class="flex items-center justify-between px-5 py-4">


            <span class="text-darkBlue text-[1.3125rem]">{{ __('lang.details') }}</span>
            <div class="flex items-center gap-4">
              {{-- <p class="text-[1.1875rem] text-darkGray">{{ __('lang.total') }} <span class="font-bold">{{$user->doctor_booking->count()}}</span></p> --}}
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-center">
              <thead>
                <tr class="text-darkGray font-bold">
                  <th class="border-y border-light-blue-200 px-5 py-4">{{ trans('lang.completed') }}</th>
                  <th class="border-y border-light-blue-200 px-5 py-4">{{ trans('lang.fees') }}</th>
                  <th class="border-y border-light-blue-200 px-5 py-4">{{ trans('lang.share') }}</th>
                  <th class="border-y border-light-blue-200 px-5 py-4">{{ trans('lang.debit') }}</th>
                  <th class="border-y border-light-blue-200 px-5 py-4">{{ trans('lang.transferred_amount') }}</th>
                  {{-- <th class="border-y border-light-blue-200 px-5 py-4">{{ trans('lang.price') }}</th> --}}
                </tr>
              </thead>
              <tbody>
                  <tr class="text-[#363636] hover:bg-[#EFF8FA]">
                      <td class="border-b border-light-blue-200 px-5 py-4">{{$bookings}}</td>
                      <td class="border-b border-light-blue-200 px-5 py-4">{{$total_fees}}</td>
                      <td class="border-b border-light-blue-200 px-5 py-4">{{$setShare}}</td>
                      <td class="border-b border-light-blue-200 px-5 py-4">{{$doctor_debit}}</td>
                      <td class="border-b border-light-blue-200 px-5 py-4">{{$transferred_amount??0}}</td>
                      {{-- <td class="border-b border-light-blue-200 px-5 py-4"></td> --}}

                    </tr>
              </tbody>
            </table>
          </div>
        </div>
        <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-2xl">
          <div class="flex items-center justify-between px-5 py-4">
            <span class="text-darkBlue text-[1.3125rem]">{{trans('lang.bookings')}}</span>
            <div class="flex items-center gap-4">
              <p class="text-[1.1875rem] text-darkGray">{{trans('lang.total')}}: <span class="font-bold">{{$user->doctor_booking->count()}}</span></p>
            </div>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-center">
              <thead>
                <tr class="text-darkGray font-bold">
                  <th class="border-y border-light-blue-200 px-5 py-4">{{ trans('lang.date') }}</th>
                  <th class="border-y border-light-blue-200 px-5 py-4">{{ trans('lang.counselor') }}</th>
                  <th class="border-y border-light-blue-200 px-5 py-4">{{ trans('lang.type') }}</th>
                  <th class="border-y border-light-blue-200 px-5 py-4">{{ trans('lang.method') }}</th>
                  <th class="border-y border-light-blue-200 px-5 py-4">{{ trans('lang.price') }}</th>
                  <th class="border-y border-light-blue-200 px-5 py-4">{{ trans('lang.status') }}</th>
                  <th class="border-y border-light-blue-200 px-5 py-4">{{ trans('lang.options') }}</th>
                </tr>
              </thead>
              <tbody>
                {{-- $user->doctor_booking --}}
                <!-- mfb updated -->
                  @foreach ($doctorBookings as $booking)
                  <tr class="text-[#363636] hover:bg-[#EFF8FA]">
                    {{--
                      <td class="border-b border-light-blue-200 px-5 py-4">{{$booking->date?? $booking->start_time?->format('j M | h:i A')}}</td>
                      --}}
                      <!-- mfb updated -->
                      <td class="border-b border-light-blue-200 px-5 py-4">{{$booking->start_time}}</td>
                      <td class="border-b border-light-blue-200 px-5 py-4"><a href="{{route('user/show', $booking->user_id)}}" class="text-[#6EABB4] hover:underline"> {{$booking->name}} </a></td>

                      {{-- <td class="border-b border-light-blue-200 px-5 py-4">{{$booking->doctor->name}}</td> --}}
                      <td class="border-b border-light-blue-200 px-5 py-4">{{$booking->booking_type}}</td>

                    {{--
                      <td class="border-b border-light-blue-200 px-5 py-4">{{$booking->method}}</td>
                    --}}
                    <!--  mfb updated -->
                      <td class="border-b border-light-blue-200 px-5 py-4">{{$booking->type}}</td>
                      <td class="border-b border-light-blue-200 px-5 py-4">{{$booking->fees}}</td>
                      <td class="border-b border-light-blue-200 px-5 py-4">
                        {{-- $booking->booking_status --}}
                        <!-- mfb updated -->
                          @switch($booking->status)
                              @case(0)
                              {{-- upcomming --}}
                              <span class="bg-[#FFF4D3] rounded-[0.4375rem] text-[#FFAB07] py-1 px-3">
                                @lang('lang.new')
                                  @break
                              @case(1)
                              {{-- compleated --}}
                              <span class="bg-[#DAF7DF] text-[#14C31B] py-1 px-2 rounded-[0.4375rem]">
                              @lang('lang.completed')
                                  @break
                              @default
                              {{-- canceled --}}
                              <span class="bg-[#FFE1E1] text-solidRed py-1 px-2 rounded-[0.4375rem]">
                              @lang('lang.canceled')
                                  @endswitch

                            {{--
                          {{$booking->status}}
                        --}}
                    </span>
                          {{-- {{ $booking->created}} --}}
                      </td>
                      <td class="border-b border-light-blue-200 px-5 py-4">
                        <div class="flex gap-3 justify-center items-center">
                           <a href="{{url('/appointments')}}/{{$booking->created}}">
                          <img src="{{asset('images/images/actions-view.svg')}}" alt="actions view icon">
                        </a>
                          {{-- <form id="deleteForm" action="{{ route('appointments-booking/delete', ['id' => $booking->created]) }}" method="POST">
                              @csrf
                              @method('POST')

                              <button type="submit" class="btn btn-danger"><img src="{{ asset('images/images/actions-delete.svg') }}"
                                  alt="actions delete icon"></i></button>
                          </form> --}}
                          {{-- <a href="#">
                            <img src="{{asset('images/images/actions-delete.svg')}}" alt="actions delete icon">
                          </a> --}}
                        </div>
                      </td>
                    </tr>
                  @endforeach
              </tbody>
            </table>
          </div>
        </div>
    </div>
    </div>
    <div id="default-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">

        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow ">
                <!-- Modal header -->
                <div class="flex items-center gap-4 p-4 border-b border-light-blue-200">
                    <img src="{{ asset('images/images/danger-icon.svg') }}" alt="danger icon">
                    <h3 class="text-darkGray font-semibold text-xl">{{ __('lang.Rejection Reason') }}</h3>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <form action="{{ route('/consultant/action', ['id' => $user->id, 'doctor_verified' => 0]) }}"
                        method="get">
                        @csrf
                        <input type="hidden" name="doctor_verified" value="0">
                        <input type="hidden" name="id" value="{{ $user->id }}">
                        <label for="message" class="block mb-2 text-darkGray">{{ __('lang.Reason') }}</label>
                        <textarea id="message" name="rejaction_reason" rows="4"
                            class="block p-4 w-full resize-none text-gray-900 bg-[#EAF8FA] rounded-[0.8125rem] border-none focus:ring-0 placeholder:text-[#91BBC1]"
                            placeholder="Type reason here"></textarea>
                        <!-- Add any additional fields if necessary -->
                        <div class="flex p-4">
                            <button type="submit"
                                class="w-full py-3 bg-main rounded-[0.8125rem] text-white font-bold shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)]">{{ __('lang.Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
