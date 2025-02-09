@extends('admin.layout')
@section('content')
      <!-- Breadcrumb -->
      <nav class="border-b border-light-blue-200 pb-5 mb-1">
        <ol class="flex gap-1">
          <li class="inline-flex items-center">
            <a href="{{url('/admin')}}" class="text-[#566E71] text-lg font-light">
              {{trans('lang.appointments')}}
            </a>
          </li>
          <img src="{{asset('images/images/arrow-right-dark.svg')}}" alt="arrow right">
          <li aria-current="page">
            <div class="flex items-center">
              <span class="text-main text-lg">{{$row->user?->name}}</span>
            </div>
          </li>
        </ol>
      </nav>
      <!-- User Info, Consultant Info & Booking Details -->
      <div class="flex gap-6 flex-wrap lg:flex-nowrap">
        <!-- User Info, Consultant Info -->
        <div class="flex flex-col gap-6 w-full lg:w-1/3">
          <!-- User Info -->
          <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-[1.375rem]">
            <p class="text-darkBlue text-xl font-semibold px-4 py-5 border-b border-light-blue-200">{{__("lang.user_info")}}</p>
            <div class="flex flex-col gap-4 p-4">
              <div class="flex flex-col gap-4">
                <div class="flex">
                  <span class="block w-1/4 text-[#808080]">{{__('lang.email')}}</span>
                  <span class="text-darkGray">{{$row->user?->email}}</span>
                </div>
                <div class="flex">
                  <span class="block w-1/4 text-[#808080]">{{__("lang.phone")}}</span>
                  <span class="text-darkGray">{{$row->user?->mobile}}</span>
                </div>
              </div>
              <div class="bg-light-blue-200 h-[1px]"></div>
              <div class="flex flex-col gap-4">
                <div class="flex">
                  <span class="block w-1/4 text-[#808080]">{{__("lang.location")}}</span>
                  <span class="text-darkGray">{{$row->user?->personal?->address}}</span>
                </div>
                <div class="flex">
                  <span class="block w-1/4 text-[#808080]">{{__('lang.nationality')}}</span>
                  <span class="text-darkGray">{{$row->user?->personal?->nationality?->{"title_".App::getLocale()} }}</span>
                </div>
                <div class="flex">
                  <span class="block w-1/4 text-[#808080]">{{__('lang.age')}}</span>
                  <span class="text-darkGray">{{ now()->diff( $row->user?->personal?->date_birth)->y ? now()->diff( $row?->user->personal?->date_birth)->y  : "AN"}}  {{ __('lang.years old') }} </span>
                </div>
              </div>
            </div>
          </div>
          <!-- Consultant Info -->
          <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-[1.375rem]">
            <p class="text-darkBlue text-xl font-semibold px-4 py-5 border-b border-light-blue-200">{{__("lang.consultant_info")}}</p>
            <div class="flex flex-col gap-4 p-4">
              <div class="flex flex-col gap-4">
                <div class="flex">
                  <span class="block w-1/4 text-[#808080]">{{__('lang.name')}}</span>
                  <span class="text-darkGray">{{$row->doctor?->name}}</span>
                </div>
                <div class="flex">
                  <span class="block w-1/4 text-[#808080]">{{__('lang.phone')}}</span>
                  <span class="text-darkGray">{{$row->doctor?->mobile}}</span>
                </div>
              </div>
              <div class="bg-light-blue-200 h-[1px]"></div>
              <div class="flex flex-col gap-4">
                <div class="flex">
                  <span class="block w-1/4 text-[#808080]">{{__('lang.location')}}</span>
                  <span class="text-darkGray">{{$row->doctor?->personal?->address}}</span>
                </div>
                <div class="flex">
                  <span class="block w-1/4 text-[#808080]">{{__('lang.nationality')}}</span>
                  <span class="text-darkGray">{{$row->doctor?->personal?->nationality?->{"title_".App::getLocale()} }}</span>
                </div>
                <div class="flex">
                  <span class="block w-1/4 text-[#808080]">{{__('lang.Age')}}</span>
                  <span class="text-darkGray">{{ now()->diff( $row->doctor?->personal?->date_birth)->y ? now()->diff( $row?->doctor->personal?->date_birth)->y  : "AN"}}  {{ __('lang.years old') }} </span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="w-full lg:w-2/3">
          <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-[1.375rem]">
            <p class="text-darkBlue text-xl font-semibold px-4 py-5 border-b border-light-blue-200">{{__("lang.Booking Details")}}</p>
            <div class="flex flex-col gap-4 p-4">
              <div class="flex flex-col gap-4">
                <div class="flex">
                  <span class="block w-1/4 text-[#808080]">{{__('lang.type')}}</span>
                  <span class="text-darkGray">{{$row->speciality?->{"title_".App::getLocale()} }}  </span>
                </div>
                <div class="flex">
                  <span class="block w-1/4 text-[#808080]">{{__('lang.date_time')}}</span>
                  <span class="text-darkGray">{{ \Carbon\Carbon::parse($row->start_time)->format('j M | h:i A') }}</span>
                </div>
                <div class="flex">
                  <span class="block w-1/4 text-[#808080]">{{__('lang.method')}}</span>
                  <span class="text-darkGray">{{$row->method}}</span>
                </div>
                <div class="flex">
                  <span class="block w-1/4 text-[#808080]">{{__('lang.status')}}</span>
                  <span class="text-[#06BA0D]">{{$row->status}}</span>
                </div>
              </div>
              <div class="bg-light-blue-200 h-[1px]"></div>
              <div class="flex flex-col gap-4">
                <p class="font-semibold text-lg text-darkGray">{{__("lang.details")}}</p>
                <div class="flex">
                  <span class="block w-1/4 text-[#808080]">{{__('lang.price')}}</span>
                  <span class="text-darkGray font-semibold text-xl">{{$row?->doctor?->personal?->fee}}</span>
                </div>
                <div class="flex">
                  <span class="block w-1/4 text-[#808080]">{{__('lang.status')}}</span>
                  <span class="text-main font-semibold">{{$row->paid ? __('lang.paid') : __('lang.unpaid')}}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

@endSection
