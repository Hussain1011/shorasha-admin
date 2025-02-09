@extends('admin.layout')

@section('content')
    <div class="bg-secondary w-full rounded-s-[2rem] p-6 flex flex-col gap-6">
        <!-- Breadcrumb -->
        <nav>
            <ol class="flex gap-1">
                <li class="inline-flex items-center">
                    <a href="{{ url('/users/list') }}" class="text-[#566E71] text-lg font-light">
                    {{trans('lang.adv')}}
                    </a>
                </li>
                <img src="{{ asset('images/images/arrow-right-dark.svg') }}" alt="arrow right">
                <li aria-current="page">
                    <div class="flex items-center">
                        <span class="text-main text-lg">{{trans('lang.create')}} </span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Edit Form -->
        <form action="{{ route('admin.adv.update',['id'=> $adv->id]) }}" method="POST" class="space-y-6" enctype="multipart/form-data">
            @csrf
            @method('post')
            <!-- Personal Information -->
            <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.048)] rounded-[1.375rem]">
     
                <div class="pt-16 pb-4 px-5 flex justify-between items-center border-b border-light-blue-200">
                    <p class="text-darkBlue font-semibold text-[1.375rem]">{{trans('lang.adv_information')}}</p>
                </div>
                <div class="flex flex-col gap-4 p-6">
                    <div class="flex flex-col gap-4">
                        <div class="flex">
                            <label for="type" class="block w-1/4 text-[#808080]">{{trans('lang.type')}}</label>
                            <select name='type' class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">
                                @if($adv->type == 'user')
                                <option selected value='user'>{{trans('lang.user')}}</option>
                                <option value='doctor'>{{trans('lang.doctor')}}</option>
                                @else
                                <option value='user'>{{trans('lang.user')}}</option>
                                <option selected value='doctor'>{{trans('lang.doctor')}}</option>
                                @endif
                            </select>
                        </div>
                        @error('type')
                            <span class="text-tiny+ text-error" style="color:red">{{ $message }}</span>
                        @enderror
                        <div class="flex">
                            <label for="image_ar" class="block w-1/4 text-[#808080]">{{trans('lang.image_ar')}}</label>
                            <input type="file" id="image_ar" name="adv_ar" value="{{ old('image_ar') }}"
                            class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block">
                            <!-- <img src="{{$adv->image_ar}}" width=200> -->
                        </div>
                        @error('adv_ar')
                            <span class="text-tiny+ text-error" style="color:red">{{ $message }}</span>
                        @enderror

                        <div class="flex">
                            <label for="image_en" class="block w-1/4 text-[#808080]">{{trans('lang.image_en')}}</label>
                            <input type="file" id="image_en" name="adv_en" value="{{ old('image_en') }}"
                                class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block">
                                <!-- <img src="{{$adv->image_en}}" width=200> -->
                        </div>
                        @error('adv_en')
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
        </form>
    </div>
@endsection
