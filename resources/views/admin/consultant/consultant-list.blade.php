@extends('admin.layout')
@section('content')
    <!-- Current Consultants Header -->
    <header class="bg-[#E8EBEB] flex justify-between items-center">
        <div>

            <!-- Border-Bottom Made with Before (to give it rounded effect) -->
            @if ($deleted == false)
                <button
                    class="text-main text-xl p-4 w-32 relative before:content-[''] before:absolute before:bottom-0 before:start-0 before:h-[4px] before:w-full before:bg-main before:rounded-full">{{ __('lang.current') }}</button>
                <a href="{{ route('admin.consultant.index.new') }}"
                    class="text-[#5B99A2] text-xl p-4 w-32">{{ __('lang.new') }}</a>
                    <a href="{{ route('admin.consultant.index.deleted') }}"
                    class="text-[#5B99A2] text-xl p-4 w-32">{{ __('lang.deleted') }}</a>
                    <a href="{{ route('admin.consultant.change.list') }}"
                    class="text-[#5B99A2] text-xl p-4 w-32">{{ __('lang.change_request') }}</a>
            @else
                <a href="{{ route('/consultant/list') }}" class="text-[#5B99A2] text-xl p-4 w-32">{{ __('lang.current') }}</a>
                <a href="{{ route('admin.consultant.index.new') }}"
                    class="text-[#5B99A2] text-xl p-4 w-32">{{ __('lang.new') }}</a>
                <button
                    class="text-main text-xl p-4 w-32 relative before:content-[''] before:absolute before:bottom-0 before:start-0 before:h-[4px] before:w-full before:bg-main before:rounded-full">{{ __('lang.deleted') }}</button>
                    <a href="{{ route('admin.consultant.change.list') }}"
                    class="text-[#5B99A2] text-xl p-4 w-32">{{ __('lang.change_request') }}</a>
                    @endif
        </div>
        <div class="flex gap-4 m-4">
            <a class="w-10 h-10 bg-main rounded-xl flex justify-center items-center"
                href="{{ route('admin.consultant.create') }}">
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
                        <img class="w-20 h-20 rounded-full object-cover" src="{{url('/')}}/public/{{ $user->personal->photo }}"
                            alt="consultant image">
                        <p class="text-darkGray font-semibold">{{ $user->name }}</p>
                        <p class="text-[#75B3BB] text-sm">
                            {{ implode(' | ', $user->specialists->pluck('title_' . app()->getLocale())->toArray()) }}</p>
                        <!--<p class="text-[#808080] text-sm">{{ $user->personal?->bio }}</p>-->
                        <div class="row">
                           
                                <a href="{{ route('admin.consultant.show', $user->id) }}"
                                    class="text-white bg-main text-sm font-semibold rounded-[0.8125rem] shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)] py-2 px-5 mt-4">
                                    {{ __('lang.view') }}</a>
                                <!-- Add edit button here -->
                                <a href="{{ route('admin.consultant.edit', $user->id) }}"
                                    class="text-white bg-[#566E71] text-sm font-semibold rounded-[0.8125rem] shadow-[0px_4px_13px_0px_rgba(86,_110,_113,_0.3)] py-2 px-5 mt-2">
                                    {{ __('lang.edit') }}</a>

                        </div>
                    </div>
                </div>
            </div>


            {{-- <div class="p-4 w-1/4">
            <div class="bg-white rounded-2xl shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)]">
                <div class="p-6 flex justify-center items-center flex-col text-center gap-2">
                    <img class="w-20 h-20 rounded-full object-cover" src="{{url('/'}}/{{$user->personal?->photo}}" alt="consultant image">
                    <p class="text-darkGray font-semibold">{{$user->name}}</p>
                    <p class="text-[#75B3BB] text-sm"> {{ implode(' | ',  $user->specialists->pluck('title_'.app()->getLocale())->toArray()) }}</p>
                    <!--<p class="text-[#808080] text-sm">{{ $user->personal?->bio }}</p>-->
                    <div class="row">

                        <a href="{{ route('admin.consultant.show', $user->id) }}"
                            class="text-white bg-main text-sm font-semibold rounded-[0.8125rem] shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)] py-2 px-5 mt-4">
                            {{__('lang.View Profile')}}</a>

                        </div>

                </div>
            </div>
        </div> --}}
        @endforeach


    </div>
    <!-- PAgination -->
    <div class="flex justify-center">
        <div
            class="flex justify-center items-center gap-8 py-2 px-4 bg-white rounded-[1.125rem] shadow-[0px_5px_20px_0px_rgba(98,_202,_217,_0.05)]">

            <ul class="flex items-center gap-3">
                <!-- Active PAge -->
    
                
            </ul>
        </div>
    </div>
@endsection
