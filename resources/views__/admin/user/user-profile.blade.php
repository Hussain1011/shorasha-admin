@extends('admin.layout')
@section('content')
<div class="bg-secondary w-full rounded-s-[2rem] p-6 flex flex-col gap-6">
    <!-- Breadcrumb -->
    <nav>
      <ol class="flex gap-1">
        <li class="inline-flex items-center">
          <a href="{{url('/users/list')}}" class="text-[#566E71] text-lg font-light">
            {{trans('lang.users')}}
          </a>
        </li>
        <img src="{{asset('images/images/arrow-right-dark.svg')}}" alt="arrow right">
        <li aria-current="page">
          <div class="flex items-center">
            <span class="text-main text-lg">{{trans('lang.user_info')}}</span>
          </div>
        </li>
      </ol>
    </nav>
    <!-- Personal Information & Reviews -->
    <div class="flex flex-wrap lg:flex-nowrap gap-6">
      <!-- Personal Information -->
      <div class="w-full overflow-hidden lg:w-1/2 bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.048)] rounded-[1.375rem]">
        <div class="h-20 bg-gradient-to-b from-main from-40% to-main/25 flex justify-center">
            {{-- {{dd($user->personal?->photo)}} --}}
            
          <img class="h-20 w-20 object-cover rounded-full border-2 border-white top-10 relative" src="{{url('/')}}/public/{{$user->photo}}" alt="user image">
        </div>
        <div class="pt-16 pb-4 px-5 flex justify-between items-center border-b border-light-blue-200">
          <p class="text-darkBlue font-semibold text-[1.375rem]">{{trans('lang.personal_information')}}</p>
          <div class="flex items-center">
            <button id="dropdown-button" data-dropdown-toggle="dropdown-personal-information" class="" type="button">
              <img src="{{asset('images/images/three-dots-icon.svg')}}" alt="personal information icon">
            </button>
            <!-- Dropdown menu -->
            <div id="dropdown-personal-information" class="z-10 hidden rounded-2xl overflow-hidden bg-white divide-y divide-gray-100 shadow-[0px_0px_18px_0px_rgba(0,_0,_0,_0.07)]">
              <ul>
                <li class="border-b border-light-blue-200">
                  <label class="inline-flex items-center cursor-pointer py-4 px-5 -ms-2">
                    <input type="checkbox" value="" class="sr-only peer">
                    <div class="relative w-9 h-5 bg-[#6EA7AE] rounded-full peer peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-[#6EA7AE]"></div>
                    <span class="ms-3 text-[#6EA7AE]">{{trans('lang.deactive')}}</span>
                  </label>
                </li>
                <li>
                  <button class="flex gap-4 items-center py-4 px-5">
                    <img src="{{asset('images/images/delete-icon.svg')}}" alt="delete icon">
                    <span class="text-solidRed">{{trans('lang.delete')}}</span>
                  </button>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="flex flex-col gap-4 p-6">
          <div class="flex flex-col gap-4">
            <div class="flex">
              <span class="block w-1/4 text-[#808080]">{{trans('lang.name')}}</span>
              <span class="text-darkGray">{{$user->name}}</span>
            </div>
            <div class="flex">
              <span class="block w-1/4 text-[#808080]">{{trans('lang.age')}}</span>
                {{-- {{$user->personal?->date_birth}} --}}
              <span class="text-darkGray"> {{ now()->diff( $user->personal?->date_birth)->y ? now()->diff( $user->personal?->date_birth)->y  : "AN"}} {{trans('lang.years_old')}} </span>
            </div>
            <div class="flex">
              <span class="block w-1/4 text-[#808080]">{{trans('lang.email')}}</span>
              <span class="text-darkGray">{{$user->email}}</span>
            </div>
            <div class="flex">
              <span class="block w-1/4 text-[#808080]">{{trans('lang.phone')}}</span>
              <span class="text-darkGray">{{$user->mobile}}</span>
            </div>
          </div>
          <div class="bg-light-blue-200 h-[1px]"></div>
          <div class="flex flex-col gap-4">
            <div class="flex">
              <span class="block w-1/4 text-[#808080]">{{trans('lang.case')}}</span>
              <span class="w-3/4 text-darkGray">{{ $user->personal?->case }}</span>
            </div>
          </div>
        </div>
      </div>
      <!-- Reviews -->
      <div class="w-full overflow-hidden lg:w-1/2 bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.048)] rounded-[1.375rem]">
        <p class="text-darkBlue text-[1.375rem] p-5 border-b border-light-blue-200">{{trans('lang.reviews')}}</p>
        <div class="px-5">
            @foreach ($user->review as $review)
            <div class="flex flex-col gap-3 py-4 border-b border-light-blue-200">
                <div class="flex justify-between items-baseline">
                  <p class="text-darkGray w-3/4">
                    {{$review->review}}
                </p>
                  <p class="text-[#808080] text-xs">
                    {{$review->created_at->diffForHumans()}}
                </p>
                </div>
                <div class="flex gap-4 items-center">
                  <div class="flex items-center">
                    @php

                        $rate = $review->rating ;
                        $diff = 5 - $rate ;
                    @endphp

                    @for ($i = 0; $i < $rate; $i++)
                        <img src="{{asset('images/images/star-fill-icon.svg')}}" alt="star fill icon">
                    @endfor
                    @for ($i = 0; $i < $diff; $i++)
                        <img src="{{asset('images/images/star-empty-icon.svg')}}" alt="star empty icon">
                    @endfor
                    {{-- <img src="{{asset('images/images/star-fill-icon.svg')}}" alt="star fill icon">
                    <img src="{{asset('images/images/star-fill-icon.svg')}}" alt="star fill icon">
                    <img src="{{asset('images/images/star-fill-icon.svg')}}" alt="star fill icon">
                    <img src="{{asset('images/images/star-fill-icon.svg')}}" alt="star fill icon">
                    <img src="{{asset('images/images/star-empty-icon.svg')}}" alt="star empty icon"> --}}
                  </div>
                  <p class="text-[#6EABB4] text-sm">{{$review->doctor?->name}}</p>
                </div>
              </div>
            @endforeach

        </div>
      </div>
    </div>
    <!-- Bookings Table -->
    <div>
      <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-2xl">
        <div class="flex items-center justify-between px-5 py-4">
          <span class="text-darkBlue text-[1.3125rem]">{{trans('lang.bookings')}}</span>
          <div class="flex items-center gap-4">
            <p class="text-[1.1875rem] text-darkGray">{{trans('lang.total')}}: <span class="font-bold">{{$user->user_booking->count()}}</span></p>
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
                @foreach ($user->user_booking as $booking)
                <tr class="text-[#363636] hover:bg-[#EFF8FA]">
                    <td class="border-b border-light-blue-200 px-5 py-4">{{$booking->start_time?? $booking->start_time?->format('j M | h:i A')}}</td>
                    <td class="border-b border-light-blue-200 px-5 py-4"><a href="{{route('admin.consultant.show', $booking->doctor->id)}}" class="text-[#6EABB4] hover:underline"> {{$booking->doctor->name}} </a></td>
                    <td class="border-b border-light-blue-200 px-5 py-4">{{$booking->type}}</td>


                    <td class="border-b border-light-blue-200 px-5 py-4">{{$booking->method}} </td>
                    <td class="border-b border-light-blue-200 px-5 py-4">{{$booking->fees}}</td>
                    <td class="border-b border-light-blue-200 px-5 py-4">
                        @switch($booking->booking_status)
                            @case(0)
                            {{-- upcomming --}}
                            <span class="bg-[#FFF4D3] rounded-[0.4375rem] text-[#FFAB07] py-1 px-3">
                                @break
                            @case(1)
                            {{-- compleated --}}
                            <span class="bg-[#DAF7DF] text-[#14C31B] py-1 px-2 rounded-[0.4375rem]">
                                @break
                            @default
                            {{-- canceled --}}
                            <span class="bg-[#FFE1E1] text-solidRed py-1 px-2 rounded-[0.4375rem]">
                                @endswitch


                        {{$booking->status}}</span>
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
  <script>
    function confirmDelete() {
        if (confirm('Are you sure you want to delete this row?')) {
            document.getElementById('deleteForm').submit();
        }
    }
</script>
@endsection
