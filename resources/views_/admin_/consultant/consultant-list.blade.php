@extends('admin.layout')
@section('content')
    <!-- Current Consultants Header -->
    <header class="bg-[#E8EBEB] flex justify-between items-center">
        <div>

            <!-- Border-Bottom Made with Before (to give it rounded effect) -->
            @if ($deleted == false)
                <button
                    class="text-main text-xl p-4 w-32 relative before:content-[''] before:absolute before:bottom-0 before:start-0 before:h-[4px] before:w-full before:bg-main before:rounded-full">{{ __('lang.Current') }}</button>
                <a href="{{ route('admin.consultant.index.new') }}"
                    class="text-[#5B99A2] text-xl p-4 w-32">{{ __('lang.New') }}</a>
                    <a href="{{ route('admin.consultant.index.deleted') }}"
                    class="text-[#5B99A2] text-xl p-4 w-32">{{ __('lang.Deleted') }}</a>
                    <a href="{{ route('admin.consultant.change.list') }}"
                    class="text-[#5B99A2] text-xl p-4 w-32">{{ __('lang.Change_request') }}</a>
            @else
                <a href="{{ route('/consultant/list') }}" class="text-[#5B99A2] text-xl p-4 w-32">{{ __('lang.Current') }}</a>
                <a href="{{ route('admin.consultant.index.new') }}"
                    class="text-[#5B99A2] text-xl p-4 w-32">{{ __('lang.New') }}</a>
                <button
                    class="text-main text-xl p-4 w-32 relative before:content-[''] before:absolute before:bottom-0 before:start-0 before:h-[4px] before:w-full before:bg-main before:rounded-full">{{ __('lang.Deleted') }}</button>
                    <a href="{{ route('admin.consultant.change.list') }}"
                    class="text-[#5B99A2] text-xl p-4 w-32">{{ __('lang.Change_request') }}</a>
                    @endif
        </div>
        <div class="flex gap-4 m-4">
            <a class="w-10 h-10 bg-main rounded-xl flex justify-center items-center"
                href="{{ route('consultant.create') }}">
                <img src="{{ asset('images/images/action-add.svg') }}" alt="action add">
            </a>
        </div>
    </header>
    <!-- Current Consultants Content -->
    <div class="flex flex-wrap">
        @foreach ($users as $user)
            <div class="p-4 w-1/4">
                <div class="bg-white rounded-2xl shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)]">
                    <div class="p-6 flex justify-center items-center flex-col text-center gap-2">
                        <img class="w-20 h-20 rounded-full object-cover" src="{{ $user->personal?->photo }}"
                            alt="consultant image">
                        <p class="text-darkGray font-semibold">{{ $user->name }}</p>
                        <p class="text-[#75B3BB] text-sm">
                            {{ implode(' | ', $user->specialists->pluck('title_' . app()->getLocale())->toArray()) }}</p>
                        <p class="text-[#808080] text-sm">{{ $user->personal?->bio }}</p>
                        <div class="row">
                            @if ($deleted)
                            <a href="{{  route('admin.user.restore', $user->id)  }}"
                                class="text-white bg-main text-sm font-semibold rounded-[0.8125rem] shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)] py-2 px-5 mt-4">
                                {{ __('lang.Restore') }}</a>
                            @else
                                <a href="{{ route('admin.consultant.show', $user->id) }}"
                                    class="text-white bg-main text-sm font-semibold rounded-[0.8125rem] shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)] py-2 px-5 mt-4">
                                    {{ __('lang.View Profile') }}</a>
                                <!-- Add edit button here -->
                                <a href="{{ route('admin.user.edit', $user->id) }}"
                                    class="text-white bg-[#566E71] text-sm font-semibold rounded-[0.8125rem] shadow-[0px_4px_13px_0px_rgba(86,_110,_113,_0.3)] py-2 px-5 mt-2">
                                    {{ __('lang.Edit') }}</a>

                            @endif
                        </div>
                    </div>
                </div>
            </div>


            {{-- <div class="p-4 w-1/4">
            <div class="bg-white rounded-2xl shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)]">
                <div class="p-6 flex justify-center items-center flex-col text-center gap-2">
                    <img class="w-20 h-20 rounded-full object-cover" src="{{$user->personal?->photo}}" alt="consultant image">
                    <p class="text-darkGray font-semibold">{{$user->name}}</p>
                    <p class="text-[#75B3BB] text-sm"> {{ implode(' | ',  $user->specialists->pluck('title_'.app()->getLocale())->toArray()) }}</p>
                    <p class="text-[#808080] text-sm">{{ $user->personal?->bio }}</p>
                    <div class="row">

                        <a href="{{ route('admin.consultant.show', $user->id) }}"
                            class="text-white bg-main text-sm font-semibold rounded-[0.8125rem] shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)] py-2 px-5 mt-4">
                            {{__('lang.View Profile')}}</a>

                        </div>

                </div>
            </div>
        </div> --}}
        @endforeach

        {{-- <div class="p-4 w-1/4">
            <div class="bg-white rounded-2xl shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)]">
                <div class="p-6 flex justify-center items-center flex-col text-center gap-2">
                    <img class="w-20 h-20 rounded-full object-cover" src="images/consultant-image.png"
                        alt="consultant image">
                    <p class="text-darkGray font-semibold">Ahmed Mostafa</p>
                    <p class="text-[#75B3BB] text-sm">Family | Professional Consultant</p>
                    <p class="text-[#808080] text-sm">Lorem Ipsum is simply dummy text of the printing and typesetting.</p>
                    <a href="#"
                        class="text-white bg-main text-sm font-semibold rounded-[0.8125rem] shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)] py-2 px-5 mt-4">View
                        Profile</a>
                </div>
            </div>
        </div>
        <div class="p-4 w-1/4">
            <div class="bg-white rounded-2xl shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)]">
                <div class="p-6 flex justify-center items-center flex-col text-center gap-2">
                    <img class="w-20 h-20 rounded-full object-cover" src="images/consultant-image.png"
                        alt="consultant image">
                    <p class="text-darkGray font-semibold">Ahmed Mostafa</p>
                    <p class="text-[#75B3BB] text-sm">Family | Professional Consultant</p>
                    <p class="text-[#808080] text-sm">Lorem Ipsum is simply dummy text of the printing and typesetting.</p>
                    <a href="#"
                        class="text-white bg-main text-sm font-semibold rounded-[0.8125rem] shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)] py-2 px-5 mt-4">View
                        Profile</a>
                </div>
            </div>
        </div>
        <div class="p-4 w-1/4">
            <div class="bg-white rounded-2xl shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)]">
                <div class="p-6 flex justify-center items-center flex-col text-center gap-2">
                    <img class="w-20 h-20 rounded-full object-cover" src="images/consultant-image.png"
                        alt="consultant image">
                    <p class="text-darkGray font-semibold">Ahmed Mostafa</p>
                    <p class="text-[#75B3BB] text-sm">Family | Professional Consultant</p>
                    <p class="text-[#808080] text-sm">Lorem Ipsum is simply dummy text of the printing and typesetting.</p>
                    <a href="#"
                        class="text-white bg-main text-sm font-semibold rounded-[0.8125rem] shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)] py-2 px-5 mt-4">View
                        Profile</a>
                </div>
            </div>
        </div>
        <div class="p-4 w-1/4">
            <div class="bg-white rounded-2xl shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)]">
                <div class="p-6 flex justify-center items-center flex-col text-center gap-2">
                    <img class="w-20 h-20 rounded-full object-cover" src="images/consultant-image.png"
                        alt="consultant image">
                    <p class="text-darkGray font-semibold">Ahmed Mostafa</p>
                    <p class="text-[#75B3BB] text-sm">Family | Professional Consultant</p>
                    <p class="text-[#808080] text-sm">Lorem Ipsum is simply dummy text of the printing and typesetting.</p>
                    <a href="#"
                        class="text-white bg-main text-sm font-semibold rounded-[0.8125rem] shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)] py-2 px-5 mt-4">View
                        Profile</a>
                </div>
            </div>
        </div>
        <div class="p-4 w-1/4">
            <div class="bg-white rounded-2xl shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)]">
                <div class="p-6 flex justify-center items-center flex-col text-center gap-2">
                    <img class="w-20 h-20 rounded-full object-cover" src="images/consultant-image.png"
                        alt="consultant image">
                    <p class="text-darkGray font-semibold">Ahmed Mostafa</p>
                    <p class="text-[#75B3BB] text-sm">Family | Professional Consultant</p>
                    <p class="text-[#808080] text-sm">Lorem Ipsum is simply dummy text of the printing and typesetting.</p>
                    <a href="#"
                        class="text-white bg-main text-sm font-semibold rounded-[0.8125rem] shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)] py-2 px-5 mt-4">View
                        Profile</a>
                </div>
            </div>
        </div>
        <div class="p-4 w-1/4">
            <div class="bg-white rounded-2xl shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)]">
                <div class="p-6 flex justify-center items-center flex-col text-center gap-2">
                    <img class="w-20 h-20 rounded-full object-cover" src="images/consultant-image.png"
                        alt="consultant image">
                    <p class="text-darkGray font-semibold">Ahmed Mostafa</p>
                    <p class="text-[#75B3BB] text-sm">Family | Professional Consultant</p>
                    <p class="text-[#808080] text-sm">Lorem Ipsum is simply dummy text of the printing and typesetting.</p>
                    <a href="#"
                        class="text-white bg-main text-sm font-semibold rounded-[0.8125rem] shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)] py-2 px-5 mt-4">View
                        Profile</a>
                </div>
            </div>
        </div>
        <div class="p-4 w-1/4">
            <div class="bg-white rounded-2xl shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)]">
                <div class="p-6 flex justify-center items-center flex-col text-center gap-2">
                    <img class="w-20 h-20 rounded-full object-cover" src="images/consultant-image.png"
                        alt="consultant image">
                    <p class="text-darkGray font-semibold">Ahmed Mostafa</p>
                    <p class="text-[#75B3BB] text-sm">Family | Professional Consultant</p>
                    <p class="text-[#808080] text-sm">Lorem Ipsum is simply dummy text of the printing and typesetting.</p>
                    <a href="#"
                        class="text-white bg-main text-sm font-semibold rounded-[0.8125rem] shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)] py-2 px-5 mt-4">View
                        Profile</a>
                </div>
            </div>
        </div>
        <div class="p-4 w-1/4">
            <div class="bg-white rounded-2xl shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)]">
                <div class="p-6 flex justify-center items-center flex-col text-center gap-2">
                    <img class="w-20 h-20 rounded-full object-cover" src="images/consultant-image.png"
                        alt="consultant image">
                    <p class="text-darkGray font-semibold">Ahmed Mostafa</p>
                    <p class="text-[#75B3BB] text-sm">Family | Professional Consultant</p>
                    <p class="text-[#808080] text-sm">Lorem Ipsum is simply dummy text of the printing and typesetting.</p>
                    <a href="#"
                        class="text-white bg-main text-sm font-semibold rounded-[0.8125rem] shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)] py-2 px-5 mt-4">View
                        Profile</a>
                </div>
            </div>
        </div>
        <div class="p-4 w-1/4">
            <div class="bg-white rounded-2xl shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)]">
                <div class="p-6 flex justify-center items-center flex-col text-center gap-2">
                    <img class="w-20 h-20 rounded-full object-cover" src="images/consultant-image.png"
                        alt="consultant image">
                    <p class="text-darkGray font-semibold">Ahmed Mostafa</p>
                    <p class="text-[#75B3BB] text-sm">Family | Professional Consultant</p>
                    <p class="text-[#808080] text-sm">Lorem Ipsum is simply dummy text of the printing and typesetting.</p>
                    <a href="#"
                        class="text-white bg-main text-sm font-semibold rounded-[0.8125rem] shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)] py-2 px-5 mt-4">View
                        Profile</a>
                </div>
            </div>
        </div>
        <div class="p-4 w-1/4">
            <div class="bg-white rounded-2xl shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)]">
                <div class="p-6 flex justify-center items-center flex-col text-center gap-2">
                    <img class="w-20 h-20 rounded-full object-cover" src="images/consultant-image.png"
                        alt="consultant image">
                    <p class="text-darkGray font-semibold">Ahmed Mostafa</p>
                    <p class="text-[#75B3BB] text-sm">Family | Professional Consultant</p>
                    <p class="text-[#808080] text-sm">Lorem Ipsum is simply dummy text of the printing and typesetting.</p>
                    <a href="#"
                        class="text-white bg-main text-sm font-semibold rounded-[0.8125rem] shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)] py-2 px-5 mt-4">View
                        Profile</a>
                </div>
            </div>
        </div>
        <div class="p-4 w-1/4">
            <div class="bg-white rounded-2xl shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)]">
                <div class="p-6 flex justify-center items-center flex-col text-center gap-2">
                    <img class="w-20 h-20 rounded-full object-cover" src="images/consultant-image.png"
                        alt="consultant image">
                    <p class="text-darkGray font-semibold">Ahmed Mostafa</p>
                    <p class="text-[#75B3BB] text-sm">Family | Professional Consultant</p>
                    <p class="text-[#808080] text-sm">Lorem Ipsum is simply dummy text of the printing and typesetting.</p>
                    <a href="#"
                        class="text-white bg-main text-sm font-semibold rounded-[0.8125rem] shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)] py-2 px-5 mt-4">View
                        Profile</a>
                </div>
            </div>
        </div> --}}
    </div>
    <!-- PAgination -->
    <div class="flex justify-center">
        <div
            class="flex justify-center items-center gap-8 py-2 px-4 bg-white rounded-[1.125rem] shadow-[0px_5px_20px_0px_rgba(98,_202,_217,_0.05)]">
            {{-- <button>
                <img src="images/table-page-prev.svg" alt="page prev">
            </button> --}}
            <ul class="flex items-center gap-3">
                <!-- Active PAge -->
                {{ $users->links() }}
                {{-- <li>
                    <a class="bg-main w-8 h-8 rounded-[0.375rem] flex justify-center items-center text-white font-semibold text-xl"
                        href="#">1</a>
                </li>
                <li>
                    <a class="w-8 h-8 flex justify-center items-center text-[#5A5A5A] text-xl" href="#">2</a>
                </li>
                <li>
                    <a class="w-8 h-8 flex justify-center items-center text-[#5A5A5A] text-xl" href="#">3</a>
                </li>
                <li>
                    <span class="w-8 h-8 flex justify-center items-center text-[#5A5A5A] text-xl">...</span>
                </li>
                <li>
                    <a class="w-8 h-8 flex justify-center items-center text-[#5A5A5A] text-xl" href="#">12</a>
                </li> --}}
            </ul>
            {{-- <button>
                <img src="images/table-page-next.svg" alt="page next">
            </button> --}}
        </div>
    </div>
@endsection
