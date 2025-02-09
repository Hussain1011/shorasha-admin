<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Nunito+Sans:ital,opsz,wght@0,6..12,200..1000;1,6..12,200..1000&display=swap"
        rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('css/global.css') }}">
</head>

<body>
    <div class="flex bg-main">
        <!-- Dashboard-Header -->
        <div class="w-56 bg-main h-screen sticky top-0 py-10 px-4">
            <!-- Logo -->
            <div class="mb-12">
                <a href="{{ route('index') }}">
                    <img src="{{ asset('images/images/logo.svg') }}" alt="website logo">
                </a>
            </div>
            <!-- Nav-Items -->
            <nav>
                <ul>
                    <!-- Dashboard -->
                    <li class="mb-1">
                        <a class="{{ Route::currentRouteName() === 'index' ? ' bg-white' : 'text-white hover:bg-white/15' }} rounded-[0.8125rem] flex items-center gap-2 px-4 py-2"
                            href="{{ route('index') }}">

                            <img src="{{ asset('images/images/nav-dashboard.svg') }}" alt="dashboard icon">
                            <span
                                class="{{ Route::currentRouteName() === 'index' ? 'text-main font-bold' : 'text-white' }}">
                                {{ trans('lang.dashboard') }}</span>
                        </a>
                    </li>
                    <!-- Users -->
                    <li class="mb-1">
                        <a class="{{ Route::currentRouteName() === '/users/list' ? ' bg-white' : 'text-white hover:bg-white/15' }} rounded-[0.8125rem] flex items-center gap-2 px-4 py-2"
                            href="{{ route('/users/list') }}">
                            <img src="{{ asset('images/images/nav-users-active.svg') }}" alt="users icon">
                            <span
                                class="{{ Route::currentRouteName() === '/users/list' ? 'text-main font-bold' : 'text-white' }}">
                                {{ trans('lang.users') }}</span>
                        </a>
                    </li>
                    <!-- Consultants -->
                    <li class="mb-1">
                        <a class="{{ Route::currentRouteName() === '/consultant/list' ? ' bg-white' : 'text-white hover:bg-white/15' }} rounded-[0.8125rem] flex items-center gap-2 px-4 py-2"
                            href="{{ route('/consultant/list') }}">
                            <img style="background-color:rgb(48 185 204 / var(--tw-bg-opacity))"
                                src="{{ asset('images/images/nav-consultants.svg') }}" alt="consultants icon">
                            <span
                                class="{{ Route::currentRouteName() === '/consultant/list' ? ' text-main font-bold' : 'text-white ' }}">
                                {{ trans('lang.consultants') }}</span>
                        </a>
                    </li>
                    <!-- Appointments -->
                    <li class="mb-1">
                        {{-- {{dd( Route::currentRouteName() === '/appointments/list')}} --}}
                        <a class="{{ Route::currentRouteName() === '/appointments/list' ? ' bg-white' : 'text-white hover:bg-white/15' }} rounded-[0.8125rem] flex items-center gap-2 px-4 py-2"
                            href="{{ route('/appointments/list') }}">
                            <img src="{{ asset('images/images/nav-appointments.svg') }}" alt="appointments icon">
                            <span
                                class="{{ Route::currentRouteName() === '/appointments/list' ? ' text-main font-bold' : 'text-white ' }}">
                                {{ __('lang.appointments') }}</span>
                        </a>
                    </li>
                    <!-- Revenue -->
                    <li class="mb-1">
                        <a class="{{ Route::currentRouteName() === 'revenue' ? ' bg-white' : 'text-white hover:bg-white/15' }} rounded-[0.8125rem] flex items-center gap-2 px-4 py-2"
                            href="{{ route('revenue') }}">
                            <img src="{{ asset('images/images/nav-revenue.svg') }}" alt="revenue icon">
                            <span
                                class="{{ Route::currentRouteName() === 'revenue' ? ' text-main font-bold' : 'text-white ' }}">
                                {{ __('lang.revenue') }}</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a class="{{ Route::currentRouteName() === '/specialist/list' ? ' bg-white' : 'text-white hover:bg-white/15' }} rounded-[0.8125rem] flex items-center gap-2 px-4 py-2"
                            href="{{ route('/specialist/list') }}">
                            <img src="{{ asset('images/images/nav-revenue.svg') }}" alt="revenue icon">
                            <span
                                class="{{ Route::currentRouteName() === '/specialist/list' ? ' text-main font-bold' : 'text-white ' }}">
                                {{ __('lang.specialist') }}</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a class="{{ Route::currentRouteName() === '/settings/list' ? ' bg-white' : 'text-white hover:bg-white/15' }} rounded-[0.8125rem] flex items-center gap-2 px-4 py-2"
                        href="{{ route('admin.settings.list') }}">
                        <div class="flex gap-2 items-center">
                                <img src="{{ asset('images/images/nav-settings.svg') }}" alt="settings icon">
                                <span
                                class="{{ Route::currentRouteName() === '/settings/list' ? ' text-main font-bold' : 'text-white ' }}">
                                {{__('lang.settings')}}</span>
                            </div>
                            <img src="{{ asset('images/images/arrow-right.svg') }}" alt="arrow right icon">
                        </a>
                    </li>
                    <li class="mb-1">
                        <a class="{{ Route::currentRouteName() === '/collect-request/list' ? ' bg-white' : 'text-white hover:bg-white/15' }} rounded-[0.8125rem] flex items-center gap-2 px-4 py-2"
                            href="{{ route('admin.collect.list')}}">
                            <img src="{{ asset('images/images/nav-advertisements.svg') }}" alt="revenue icon">
                            <span
                                class="{{ Route::currentRouteName() === '/collect-request/list' ? ' text-main font-bold' : 'text-white ' }}">
                                {{ __('lang.consaltant_collects') }}</span>
                        </a>
                    </li>
                    <li class="mb-1">
                        <a class="{{ Route::currentRouteName() === '/collect-user-request/list' ? ' bg-white' : 'text-white hover:bg-white/15' }} rounded-[0.8125rem] flex items-center gap-2 px-4 py-2"
                            href="{{ route('admin.collect-user.list')}}">
                            <img src="{{ asset('images/images/nav-advertisements.svg') }}" alt="revenue icon">
                            <span
                                class="{{ Route::currentRouteName() === '/collect-user-request/list' ? ' text-main font-bold' : 'text-white ' }}">
                                {{ __('lang.user_collects') }}</span>
                        </a>
                    </li>

                    <li class="mb-1">
                        <a class="{{ Route::currentRouteName() === 'advs.list' ? ' bg-white' : 'text-white hover:bg-white/15' }} rounded-[0.8125rem] flex items-center gap-2 px-4 py-2"
                            href="{{ route('advs.list')}}">
                            <img src="{{ asset('images/images/nav-advertisements.svg') }}" alt="revenue icon">
                            <span
                                class="{{ Route::currentRouteName() === 'advs.list' ? ' text-main font-bold' : 'text-white ' }}">
                                {{ __('lang.advs') }}</span>
                        </a>
                    </li>

                    <li class="mb-1">
                        <a class="{{ Route::currentRouteName() === 'banners.list' ? ' bg-white' : 'text-white hover:bg-white/15' }} rounded-[0.8125rem] flex items-center gap-2 px-4 py-2"
                            href="{{ route('banners.list')}}">
                            <img src="{{ asset('images/images/nav-advertisements.svg') }}" alt="revenue icon">
                            <span
                                class="{{ Route::currentRouteName() === 'banners.list' ? ' text-main font-bold' : 'text-white ' }}">
                                {{ __('lang.main_banners') }}</span>
                        </a>
                    </li>
                    <!-- Advertisements -->
                    {{-- <li class="mb-1">
                        <a
                        class="{{ Route::currentRouteName() === '/collect-request/list' ? ' bg-white' : 'text-white hover:bg-white/15' }} rounded-[0.8125rem] flex items-center gap-2 px-4 py-2"
                        href="{{ route('admin.collect.list') }}">
                        <img src="{{ asset('images/images/nav-advertisements.svg') }}" alt="advertisements icon">
                        <span
                        class="{{ Route::currentRouteName() === '/collect-request/list' ? ' text-main font-bold' : 'text-white ' }}">
                        {{__('lang.consaltant_collects')}}</span>
                    </class=>
                </li> --}}
                    {{-- <li class="mb-1">
                        <a class="{{ Route::currentRouteName() === '/collect-user-request/list' ? ' bg-white' : 'text-white hover:bg-white/15' }} rounded-[0.8125rem] flex items-center gap-2 px-4 py-2"
                        href="{{ route('admin.collect-user.list') }}">
                        <img src="{{ asset('images/images/nav-advertisements.svg') }}" alt="advertisements icon">
                        <span class="{{ Route::currentRouteName() === '/collect-user-request/list' ? ' text-main font-bold' : 'text-white ' }}">
                        {{__('lang.user_collects')}}</span>
                    </class=>
                </li> --}}
                {{--
                    <!-- Complaints -->
                    <li class="mb-1">
                        <a class="rounded-[0.8125rem] flex items-center gap-2 px-4 py-2 text-white hover:bg-white/15"
                            href="/dashboard.html">
                            <img src="{{ asset('images/images/nav-complaints.svg') }}" alt="complaints icon">
                            <span class="text-white">Complaints</span>
                        </a>
                    </li>
                    <!-- Settings -->
                     --}}

                </ul>
            </nav>
        </div>
        <!-- Dashboard-Main-Body -->
        <div class="bg-secondary w-full rounded-s-[2rem] p-6 flex flex-col gap-6">
            {{-- {{        dd(app()->getLocale() )            }} --}}
            <!-- Profile Header -->
            <div
                class="bg-white rounded-[1.125rem] shadow-[0px_5px_20px_0px_rgba(98,_202,_217,_0.10)] py-4 px-8 flex justify-end">
                <!-- Language -->
                <div class="flex items-center">
                    @if (app()->getLocale() == 'en')
                        <a href="{{ route('setLocale') }}" class="flex gap-3 items-center">
                            <img class="w-6 h-6 rounded-full" src="{{ asset('images/images/english-language.png') }}"
                                alt="english language icon">
                            <span>{{trans('lang.english')}}</span>
                        </a>
                    @else
                        <a href="{{ route('setLocale') }}" class="flex gap-3 items-center">
                            <img class="w-6 h-6 rounded-full" src="{{ asset('images/images/arabic.webp') }}"
                                alt="arabic language icon">
                            <span>{{trans('lang.arabic')}}</span>
                        </a>
                    @endif
                </div>
                <!-- Notification -->
                <div class="flex items-center">
                    <button id="dropdown-button" data-dropdown-toggle="dropdown-notification"
                        class="bg-lightGray rounded-[0.375rem] w-10 h-10 relative ms-16 me-8" type="button">
                        <span
                            class="block w-3 h-3 bg-solidRed rounded-full absolute end-2.5 top-1 border-white border-2"></span>
                        <img src="{{ asset('images/images/notification-icon.svg') }}" alt="notification icon">
                    </button>
                    <!-- Dropdown menu -->
                    <div id="dropdown-notification"
                        @php
                            $notifications = \App\Models\User::where('user_type', 2)
                        ->whereHas('personal', function ($q) {
                            $q->where('doctor_verified', 0)->whereNull('rejection_reason');
                        })
                        ->latest()->get();
                        @endphp
                        class="z-10 hidden rounded-2xl overflow-hidden bg-white divide-y divide-gray-100 shadow w-80">
                        <div class="bg-main p-4 flex justify-between items-center">
                            <span class="text-white text-xl font-semibold">{{ __('lang.Notifications') }}</span>
                            <span class="text-main text-xl font-bold bg-white rounded-lg py-1 px-2">{{ $notifications->count() }}</span>
                        </div>
                        <div class="flex flex-col gap-4 p-4">
                            {{-- <div class="flex justify-between items-center">
                          <span class="text-[#808080]">Today</span>
                          <a class="text-darkGray" href="#">Mark all as read</a>
                        </div> --}}


                            @foreach ($notifications as $notification)
                                <a href="{{  route('admin.consultant.show', $notification->id) }}">
                                 <div class="p-3 bg-[#EFFAFC] rounded-[0.8125rem] flex gap-3">
                                    <img class="w-[3.5rem] h-[3.75rem] rounded-lg"
                                        src="{{ asset('images/images/notification-image.png') }}"
                                        alt="Ahmed Mostafa image">
                                    <div class="flex flex-col gap-1">
                                        <p class="text-sm text-[#808080]">
                                            <span class="text-darkGray">{{ $notification->name }}</span>
                                            {{ __('lang.assigned_you_a_consultation_request') }}
                                        </p>
                                        <div class="flex items-center gap-2">
                                            <img src="{{ asset('images/images/clock-icon.svg') }}" alt="clock icon">
                                            <span
                                                class="text-[#808080] text-sm">{{ $notification->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </div> </a>
                            @endforeach

                        </div>
                    </div>
                </div>
                <!-- Profile-Icon -->
                <div class="flex gap-3 items-center">
                    <div><img src="{{ asset('images/images/profile-icon.svg') }}" alt="profile icon"></div>
                    <div class="font-semibold flex flex-col">
                        <span class="text-darkGray">{{ Auth::user()?->name }}</span>
                        <span class="text-neutralGreen text-xs">{{trans('lang.available')}}</span>
                    </div>
                </div>
                <div class="font-semibold flex flex-col">
                    {{-- <span class="text-darkGray">Ali</span> --}}
                    <a class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-red-600"
                        style="margin-left:10px
                     " href="{{ route('admin.logout') }}">{{ __('lang.Logout') }}</a>
                    {{-- <span class="text-neutralGreen text-xs">Available</span> --}}
                </div>
            </div>
            @if (session('message'))
                <div class="flex justify-center items-center ">
                    <div
                        class="py-3 px-4 rounded-[0.8125rem] bg-green-400 bg-[#EAF8FA] text-darkGray border-none focus:ring-main border-main block w-full">
                        <span class="text-tiny+ text-error" style="color:white; font-weight: bold ; font-size: 18px">
                            {{ session('message') }}</span>
                    </div>
                </div>
            @endif

            <!-- Users Table -->
            @yield('content')
            <!-- © Naraakom. All Rights Reserved. -->
            <div class="bg-white py-4 px-6 shadow-[0px_5px_20px_0px_rgba(98,_202,_217,_0.10)] rounded-[1.125rem]">
                <p class="text-[0.875rem] text-darkGray">© 2023 <a class="text-main" href="#">Shorasha</a>. All
                    Rights Reserved.</p>
            </div>
        </div>
    </div>
    <script>
        function confirmDelete(id) {
            if (confirm('Are you sure you want to delete this row?')) {
                console.log(id);
                document.getElementById(id).submit();
            }
        }
    </script>
    <script src="{{ asset('js/tailwind.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script></script>
</body>

</html>
