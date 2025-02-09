@extends('admin.layout')

@section('content')
    <!-- Total (Users, Consultants & Appointments) -->
    <div class="flex gap-6">
        <div
            class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-2xl w-1/3 p-3 flex items-center gap-4">
            <div class="w-[11px] rounded-full h-full bg-lightBlue flex flex-col justify-end">
                <!-- Hight Percentage Dynamic -->
                <div class="w-full h-1/2 bg-gradient-to-t from-main via-main/50 to-main/5 rounded-full"></div>
            </div>
            <div class="flex flex-col gap-2">
                {{-- <span class="text-mainLight">22 Agu, 2023</span> --}}
                <p class="text-darkGray text-2xl font-bold">{{ $users }}</p>
                <span class="text-normalGray">{{__('lang.total_users')}}</span>
            </div>
        </div>
        <div
            class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-2xl w-1/3 p-3 flex items-center gap-4">
            <div class="w-[11px] rounded-full h-full bg-lightBlue flex flex-col justify-end">
                <!-- Hight Percentage Dynamic -->
                <div
                    class="w-full h-3/4 bg-gradient-to-t from-neutralBlue via-neutralBlue/50 to-light-blue-100 rounded-full">
                </div>
            </div>
            <div class="flex flex-col gap-2">
                {{-- <span class="text-mainLight">22 Agu, 2023</span> --}}
                <p class="text-darkGray text-2xl font-bold">{{ $doctors }}</p>
                <span class="text-normalGray">{{__('lang.total_consultants')}}</span>
            </div>
        </div>
        <div
            class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-2xl w-1/3 p-3 flex items-center gap-4">
            <div class="w-[11px] rounded-full h-full bg-lightBlue flex flex-col justify-end">
                <!-- Hight Percentage Dynamic -->
                <div
                    class="w-full h-3/4 bg-gradient-to-t from-neutral-green-100 via-neutral-green-100/75 to-light-green-100/5 rounded-full">
                </div>
            </div>
            <div class="flex flex-col gap-2">
                {{-- <span class="text-mainLight">22 Agu, 2023</span> --}}
                <p class="text-darkGray text-2xl font-bold">{{ $bookings }}</p>
                <span class="text-normalGray">{{__('lang.appointments')}}</span>
            </div>
        </div>
    </div>

    <div class="flex gap-6">
        <div
            class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-2xl w-1/3 p-3 flex items-center gap-4">
            <div class="w-[11px] rounded-full h-full bg-lightBlue flex flex-col justify-end">
                <!-- Hight Percentage Dynamic -->
                <div class="w-full h-2/3 bg-gradient-to-t from-green-500 via-green-300 to-green-100 rounded-full"></div>
            </div>
            <div class="flex flex-col gap-2">
                {{-- <span class="text-mainLight">22 Agu, 2023</span> --}}
                <p class="text-darkGray text-2xl font-bold">{{ $completed_appointments }}</p>
                <span class="text-normalGray">{{__('lang.completed_appointments')}}</span>
            </div>
        </div>
        <div
            class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-2xl w-1/3 p-3 flex items-center gap-4">
            <div class="w-[11px] rounded-full h-full bg-lightBlue flex flex-col justify-end">
                <!-- Hight Percentage Dynamic -->
                <div
                    class="w-full h-3/4 bg-gradient-to-t from-neutralBlue via-neutralBlue/50 to-light-blue-100 rounded-full">
                </div>
            </div>
            <div class="flex flex-col gap-2">
                {{-- <span class="text-mainLight">22 Agu, 2023</span> --}}
                <p class="text-darkGray text-2xl font-bold">{{ $upcoming_appointments }}</p>
                <span class="text-normalGray">{{__('lang.upcoming_appointments')}}</span>
            </div>
        </div>
        <div
            class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-2xl w-1/3 p-3 flex items-center gap-4">
            <div class="w-[11px] rounded-full h-full bg-lightBlue flex flex-col justify-end">
                <!-- Hight Percentage Dynamic -->
                <div class="w-full h-3/4 bg-gradient-to-t from-red-500 via-red-300 to-red-100 rounded-full"></div>

            </div>
            <div class="flex flex-col gap-2">
                {{-- <span class="text-mainLight">22 Agu, 2023</span> --}}
                <p class="text-darkGray text-2xl font-bold">{{ $canceled_appointments }}</p>
                <span class="text-normalGray">{{__('lang.canceled_appointments')}}</span>
            </div>
        </div>
    </div>
    
    <!-- Total Revenue -->
    <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-[1.375rem] overflow-x-auto">
        <div class="flex justify-between items-center p-6 pb-0">
            <p class="text-darkBlue text-xl font-bold">{{trans('lang.total_revenue')}}</p>
            <div>
                <button id="dropdown-button" data-dropdown-toggle="dropdown"
                    class="text-light-blue-150 bg-white border border-light-blue-50 px-3 py-1 w-[6.5rem] rounded-lg flex justify-between items-center"
                    type="button">
                    <span>{{trans('lang.monthly')}}</span>
                    {{-- <img src="{{ asset('images/images/arrow-down.svg') }}" alt="arrow down icon"> --}}
                </button>
                <!-- Dropdown menu -->
                <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                    {{-- <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdown-button">
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">Monthly</a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">Yearly</a>
                        </li>
                    </ul> --}}
                </div>
            </div>
        </div>
        <div class="h-[1px] w-full bg-light-blue-200 my-5"></div>
        <div class="px-6">
            <p class="text-[#808080]">{{trans('lang.total_revenue_since_start')}}</p>
            <span class="text-main font-bold text-2xl">{{ $total_revenue }}</span>
        </div>
        {{-- {{dd($monthle_revenue)}} --}}

        <div class="h-[3px] w-full bg-[#EFEFEF] my-4"></div>
        <div class="flex gap-5 justify-between items-center px-6 pb-6">
            @php
                $last_month=0;
            @endphp
            @foreach ($monthle_revenue as $item)
                @if ($item['revenue'] > 0)
                    <div class="flex flex-col justify-between items-center gap-3">
                        <span class="text-darkGray text-xl font-bold">{{ $item['revenue'] }}</span>
                        @if ($item['revenue'] > $last_month)
                        <img src="{{ asset('images/images/arrow-chart-up.svg') }}" alt="arrow chart up">
                            @else
                            <img src="{{ asset('images/images/arrow-chart-down.svg') }}" alt="arrow chart down">

                        @endif
                        <span class="text-darkGray text-[1.375rem]">{{ $item['month'] }}</span>
                    </div>
                    @php
                        $last_month = $item['revenue'];
                    @endphp
                @endif
            @endforeach
            {{-- <div class="flex flex-col justify-between items-center gap-3">
                <span class="text-darkGray text-xl font-bold">100 QR</span>
                <img src="{{ asset('images/images/arrow-chart-down.svg') }}" alt="arrow chart down">
                <span class="text-darkGray text-[1.375rem]">Feb</span>
            </div>
            <div class="flex flex-col justify-between items-center gap-3">
                <span class="text-darkGray text-xl font-bold">500 QR</span>
                <img src="{{ asset('images/images/arrow-chart-up.svg') }}" alt="arrow chart up">
                <span class="text-darkGray text-[1.375rem]">Jan</span>
            </div>
            <div class="flex flex-col justify-between items-center gap-3">
                <span class="text-darkGray text-xl font-bold">100 QR</span>
                <img src="{{ asset('images/images/arrow-chart-down.svg') }}" alt="arrow chart down">
                <span class="text-darkGray text-[1.375rem]">Feb</span>
            </div>
            <div class="flex flex-col justify-between items-center gap-3">
                <span class="text-darkGray text-xl font-bold">500 QR</span>
                <img src="{{ asset('images/images/arrow-chart-up.svg') }}" alt="arrow chart up">
                <span class="text-darkGray text-[1.375rem]">Jan</span>
            </div>
            <div class="flex flex-col justify-between items-center gap-3">
                <span class="text-darkGray text-xl font-bold">100 QR</span>
                <img src="{{ asset('images/images/arrow-chart-down.svg') }}" alt="arrow chart down">
                <span class="text-darkGray text-[1.375rem]">Feb</span>
            </div>
            <div class="flex flex-col justify-between items-center gap-3">
                <span class="text-darkGray text-xl font-bold">500 QR</span>
                <img src="{{ asset('images/images/arrow-chart-up.svg') }}" alt="arrow chart up">
                <span class="text-darkGray text-[1.375rem]">Jan</span>
            </div>
            <div class="flex flex-col justify-between items-center gap-3">
                <span class="text-darkGray text-xl font-bold">100 QR</span>
                <img src="{{ asset('images/images/arrow-chart-down.svg') }}" alt="arrow chart down">
                <span class="text-darkGray text-[1.375rem]">Feb</span>
            </div>
            <div class="flex flex-col justify-between items-center gap-3">
                <span class="text-darkGray text-xl font-bold">500 QR</span>
                <img src="{{ asset('images/images/arrow-chart-up.svg') }}" alt="arrow chart up">
                <span class="text-darkGray text-[1.375rem]">Jan</span>
            </div>
            <div class="flex flex-col justify-between items-center gap-3">
                <span class="text-darkGray text-xl font-bold">100 QR</span>
                <img src="{{ asset('images/images/arrow-chart-down.svg') }}" alt="arrow chart down">
                <span class="text-darkGray text-[1.375rem]">Feb</span>
            </div>
            <div class="flex flex-col justify-between items-center gap-3">
                <span class="text-darkGray text-xl font-bold">500 QR</span>
                <img src="{{ asset('images/images/arrow-chart-up.svg') }}" alt="arrow chart up">
                <span class="text-darkGray text-[1.375rem]">Jan</span>
            </div>
            <div class="flex flex-col justify-between items-center gap-3">
                <span class="text-darkGray text-xl font-bold">100 QR</span>
                <img src="{{ asset('images/images/arrow-chart-down.svg') }}" alt="arrow chart down">
                <span class="text-darkGray text-[1.375rem]">Feb</span>
            </div> --}}
        </div>
    </div>
    <!-- New Users, Weekly Consultations & New Appointments -->
    <div class="flex gap-6">
        <div class="flex flex-col w-1/3 gap-6">
            <!-- New Users -->
            <div class="p-5 bg-white rounded-2xl shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] flex flex-col gap-6">
                <span class="text-normalGray text-xl">{{trans('lang.new_users')}}</span>
                <div class="flex gap-5 items-center">
                    <img src="{{ asset('images/images/new-users-chart.svg') }}" alt="new users chart">
                    <div>
                        <p class="text-darkGray text-2xl font-bold">{{$new_user}}</p>
                        <p class="text-[#808080]">{{trans('lang.visitors_this_month')}}</p>
                    </div>
                </div>
            </div>
            <!-- Weekly Consultations -->
            <div class="bg-white rounded-2xl shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)]">
                <p class="text-darkBlue text-[1.375rem] font-bold px-5 py-3">{{trans('lang.weekly_consultants')}}</p>
                <div class="bg-light-blue-200 h-[1px]"></div>
                <div>
                    @foreach ($days as $day)
                    <div class="py-4 mx-5 flex justify-between items-center border-b border-light-blue-200">
                        <span class="text-darkGray">{{ $day->{'title_'.App::getLocale()} }}</span>
                        <span class="text-[#0D4249] text-[1.375rem]">{{ $day->slots->count() }}</span>
                    </div>
                    @endforeach
                    {{-- <div class="py-4 mx-5 flex justify-between items-center border-b border-light-blue-200">
                        <span class="text-darkGray">Sunday</span>
                        <span class="text-[#0D4249] text-[1.375rem]">10</span>
                    </div>
                    <div class="py-4 mx-5 flex justify-between items-center border-b border-light-blue-200">
                        <span class="text-darkGray">Monday</span>
                        <span class="text-[#0D4249] text-[1.375rem]">10</span>
                    </div>
                    <div class="py-4 mx-5 flex justify-between items-center border-b border-light-blue-200">
                        <span class="text-darkGray">Tuesday</span>
                        <span class="text-[#0D4249] text-[1.375rem]">10</span>
                    </div>
                    <div class="py-4 mx-5 flex justify-between items-center border-b border-light-blue-200">
                        <span class="text-darkGray">Wednesday</span>
                        <span class="text-[#0D4249] text-[1.375rem]">10</span>
                    </div>
                    <div class="py-4 mx-5 flex justify-between items-center border-b border-light-blue-200">
                        <span class="text-darkGray">Thursday</span>
                        <span class="text-[#0D4249] text-[1.375rem]">10</span>
                    </div>
                    <div class="py-4 mx-5 flex justify-between items-center">
                        <span class="text-darkGray">Friday</span>
                        <span class="text-[#0D4249] text-[1.375rem]">10</span>
                    </div> --}}
                </div>
            </div>
        </div>
        <!-- New Appointments -->
        <div class="bg-white rounded-2xl shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] w-2/3">
            <p class="p-5 text-darkBlue text-[1.375rem] font-bold border-b border-light-blue-200">{{trans('lang.new_appointments')}}</p>
            <table class="w-full text-center">
                <thead>
                    <tr class="text-darkGray font-bold">
                        <th class="p-3 border-b border-light-blue-200">{{trans('lang.user')}}</th>
                        <th class="p-3 border-b border-light-blue-200">{{trans('lang.consultant')}}</th>
                        <th class="p-3 border-b border-light-blue-200">{{trans('lang.type')}}</th>
                        <th class="p-3 border-b border-light-blue-200">{{trans('lang.date_time')}}</th>
                        <th class="p-3 border-b border-light-blue-200">{{trans('lang.options')}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($new_booking as $item)
                    <tr class="text-darkGray text[0.937rem] hover:bg-[#EFF8FA]">
                        <td class="p-3 border-b border-light-blue-200"><a href="{{route('user/show', $item->user_id)}}" class="text-[#6EABB4] hover:underline"> {{$item->user?->name}}</td>
                        <td class="p-3 border-b border-light-blue-200"><a href="{{route('admin.consultant.show', $item->doctor_id)}}" class="text-[#6EABB4] hover:underline"> {{$item->doctor?->name}}</a> </td>
                        <td class="p-3 border-b border-light-blue-200">{{$item->method}}</td>
                        <td class="p-3 border-b border-light-blue-200">{{\Carbon\Carbon::parse($item->start_time)->format('j M | h:i A')}}</td>
                        <td class="p-3 border-b border-light-blue-200">
                            <div class="flex gap-3 justify-center items-center">
                                <a href="{{ route('admin.appointments.show', $item->created) }}">
                                    <img src="{{ asset('images/images/actions-view.svg') }}" alt="actions view icon">
                                </a>
                                <a href="{{ route('admin.appointments.edit', $item->created) }}">
                                    <img src="{{ asset('images/images/actions-edit.svg') }}" alt="actions edit icon">
                                </a>
                                {{-- <a href="{{ route('appointments-booking/delete', $item->created) }}">
                                    <img src="{{ asset('images/images/actions-delete.svg') }}" alt="actions delete icon">
                                </a> --}}
                            </div>
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
    </div>
@endsection
