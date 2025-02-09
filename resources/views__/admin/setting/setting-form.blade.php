@extends('admin.layout')

@section('content')
    <div class="bg-secondary w-full rounded-s-[2rem] p-6 flex flex-col gap-6">
        <!-- Breadcrumb -->
        {{-- <nav>
            <ol class="flex gap-1">
                <li class="inline-flex items-center">
                    <a href="{{ url('/users/list') }}" class="text-[#566E71] text-lg font-light">
                        {{ __('lang.settings') }}
                    </a>
                </li>
                <img src="{{ asset('images/images/arrow-right-dark.svg') }}" alt="arrow right">
                <li aria-current="page">
                    <div class="flex items-center">
                        <span class="text-main text-lg">{{trans('lang.create')}} </span>
                    </div>
                </li>
            </ol>
        </nav> --}}

        <!-- Edit Form -->
        <form action="{{ route('admin.setting.update') }}" method="POST" class="space-y-6">
            @csrf
            @method('post')
            {{-- <input type="hidden" name="user_type" value="{{ $user_type }}"> --}}
            <!-- Personal Information -->
            <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.048)] rounded-[1.375rem]">
                {{-- <div class="h-20 bg-gradient-to-b from-main from-40% to-main/25 flex justify-center">
                    <img class="h-20 w-20 object-cover rounded-full border-2 border-white top-10 relative"
                        src="{{asset('images/images/user.png') }}" alt="user image">
                </div> --}}
                <div class="pt-16 pb-4 px-5 flex justify-between items-center border-b border-light-blue-200">
                    <p class="text-darkBlue font-semibold text-[1.375rem]"> {{__('lang.settings')}}</p>
                </div>
                <div class="flex flex-col gap-4 p-6">
                    <div class="flex flex-col gap-4">
                        @forelse ($settings as $setting)

                        <div class="flex">
                            <label for="name" class="block w-1/4 text-[#808080]">{{trans('lang.'.$setting->key.'')}}</label>
                            <input type="text" id="name" name="{{$setting->key}}" value="{{ $setting->value }}"
                                class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">
                        </div>
                        @error($setting->key)
                            <span class="text-tiny+ text-error" style="color:red">{{ $message }}</span>
                        @enderror
                        @empty
                        <div class="flex">
                            <label for="name" class="block w-1/4 text-[#808080]">{{__('lang.commition')}}</label>
                            <input type="text" id="name" name="{{App\Models\Setting::APP_COMMISSION}}" value="{{App\Models\Setting::where('key', 'APP_COMMISSION')->first()->value ?? 0 }}"
                                class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">
                        </div>
                        @endforelse
                        <!-- Other fields can be added here -->
                    </div>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="flex justify-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    {{__('lang.save')}}
                </button>
            </div>
        </form>
    </div>
@endsection
