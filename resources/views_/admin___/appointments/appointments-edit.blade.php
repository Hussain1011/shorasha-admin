@extends('admin.layout')

@section('content')
    <!-- Breadcrumb -->
    <nav class="border-b border-light-blue-200 pb-5 mb-1">
        <ol class="flex gap-1">
            <li class="inline-flex items-center">
                <a href="appointments.html" class="text-[#566E71] text-lg font-light">
                    Appointments
                </a>
            </li>
            <img src="{{ asset('images/images/arrow-right-dark.svg') }}" alt="arrow right">
            <li aria-current="page">
                <div class="flex items-center">
                    <span class="text-main text-lg">{{ $row->user?->name }}</span>
                </div>
            </li>
        </ol>
    </nav>

    <!-- Edit Form -->
    <form action="{{ route('admin.appointments.update',['id'=> $row->created]) }}" method="POST" class="space-y-6">
        @csrf
        @method('Post')

        <!-- User Info -->
        <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-[1.375rem]">
            <p class="text-darkBlue text-xl font-semibold px-4 py-5 border-b border-light-blue-200">
                {{ __('lang.User Info') }}</p>
            <div class="flex flex-col gap-4 p-4">
                <div class="flex flex-col gap-4">
                    <div class="flex">
                        <span class="block w-1/4 text-[#808080]">{{ __('lang.Email') }}</span>
                        <span
                            class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">{{ $row->user?->email }}</span>
                    </div>
                    <div class="flex">
                        <span class="block w-1/4 text-[#808080]">{{ __('lang.Phone') }}</span>
                        <span
                            class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">{{ $row->user?->mobile }}</span>
                    </div>
                    <div class="flex">
                        <span class="block w-1/4 text-[#808080]">{{ __('lang.location') }}</span>
                        <span
                            class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">{{ $row->user?->personal?->address }}</span>
                    </div>
                    <div class="flex">
                        <span class="block w-1/4 text-[#808080]">{{ __('lang.nationality') }}</span>
                        <span
                            class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">{{ $row->user?->personal?->nationality?->{'title_' . App::getLocale()} }}</span>
                    </div>
                    <div class="flex">
                        <span class="block w-1/4 text-[#808080]">{{ __('lang.age') }}</span>
                        <span
                            class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">{{ now()->diff($row->user?->personal?->date_birth)->y ? now()->diff($row?->user->personal?->date_birth)->y : 'AN' }}
                            {{ __('lang.years old') }} </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Consultant Info -->
        <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-[1.375rem]">
            <p class="text-darkBlue text-xl font-semibold px-4 py-5 border-b border-light-blue-200">
                {{ __('lang.Consultant Info') }}</p>
            <div class="flex flex-col gap-4 p-4">
                <div class="flex flex-col gap-4">
                    <div class="flex">
                        <span class="block w-1/4 text-[#808080]">{{ __('lang.name') }}</span>
                        <span
                            class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">{{ $row->doctor?->name }}</span>
                    </div>
                    <div class="flex">
                        <span class="block w-1/4 text-[#808080]">{{ __('lang.phone') }}</span>
                        <span
                            class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">{{ $row->doctor?->mobile }}</span>
                    </div>
                    <div class="flex">
                        <span class="block w-1/4 text-[#808080]">{{ __('lang.location') }}</span>
                        <span
                            class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">{{ $row->doctor?->personal?->address }}</span>
                    </div>
                    <div class="flex">
                        <span class="block w-1/4 text-[#808080]">{{ __('lang.nationality') }}</span>
                        <span
                            class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">{{ $row->doctor?->personal?->nationality?->{'title_' . App::getLocale()} }}</span>
                    </div>
                    <div class="flex">
                        <span class="block w-1/4 text-[#808080]">{{ __('lang.Age') }}</span>
                        <span
                            class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">{{ now()->diff($row->doctor?->personal?->date_birth)->y ? now()->diff($row?->doctor->personal?->date_birth)->y : 'AN' }}
                            {{ __('lang.years old') }} </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-[1.375rem]">
            <p class="text-darkBlue text-xl font-semibold px-4 py-5 border-b border-light-blue-200">
                {{ __('lang.Booking Details') }}</p>
            <div class="flex flex-col gap-4 p-4">
                <div class="flex flex-col gap-4">
                    <div class="flex">
                        <label for="booking_type"
                            class="block w-1/4 text-[#808080]">{{ __('lang.Counseling Type') }}</label>
                        <input type="text" id="booking_type" name="booking_type"
                            value="{{ $row->speciality?->{'title_' . App::getLocale()} }}"
                            class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full"
                            disabled>
                    </div>
                    <div class="flex">
                        <label for="start_time" class="block w-1/4 text-[#808080]">{{ __('lang.Date and Time') }}</label>
                        <input type="datetime-local" id="start_time" name="start_time"
                            value="{{ \Carbon\Carbon::parse($row->start_time)->format('Y-m-d\TH:i') }}"
                            class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">
                    </div>
                    <div class="flex">
                        <label for="method" class="block w-1/4 text-[#808080]">{{ __('lang.method') }}</label>
                        <select name="method" id="method"
                            class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full">
                            <option value="chat" {{ !is_null($row->chat) ? 'selected' : '' }}>{{ __('lang.chat') }}
                            </option>
                            <option value="call" {{ !is_null($row->calling) ? 'selected' : '' }}>
                                {{ __('lang.calling') }}</option>
                            <option value="zoom" {{ !is_null($row->zoom) ? 'selected' : '' }}>{{ __('lang.zoom') }}
                            </option>
                        </select>

                    </div>
                    <div class="flex">
                        <label for="status" class="block w-1/4 text-[#808080]">{{ __('lang.status') }}</label>
                        <input type="text" id="status" name="status" value="{{ $row->status }}"
                            class="py-3 px-4 rounded-[0.8125rem] bg-[#EAF8FA] text-darkGray border-none focus:ring-main focus:border-main block w-full"  disabled>
                    </div>
                </div>
            </div>
        </div>
        <!-- Submit Button -->
        <div class="flex justify-end">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Save Changes</button>
        </div>
    </form>
@endsection
