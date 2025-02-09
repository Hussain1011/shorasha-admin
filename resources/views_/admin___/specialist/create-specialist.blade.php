@extends('admin.layout')

@section('content')
        <div class="grid sm:gap-5">
            <h2 class="text-xl font-medium text-slate-800 dark:text-navy-50 lg:text-2xl">
                {{trans('lang.create_specialist')}}
            </h2>
            <div class="hidden h-full py-1 sm:flex">
                <div class="h-full w-px bg-slate-300 dark:bg-navy-600"></div>
            </div>
        </div>


        <div class="grid grid-cols-1 gap-4 sm:gap-5 lg:gap-6">
            <!-- Input Mask -->
            <div class="card px-4 pb-4 sm:px-5">
                <div class="my-3 flex h-6 items-center justify-between">
                </div>
                <form id="deleteForm" action="{{ route('specialist.store') }}" method="POST">
                    @csrf
                    @method('POST')

                <div class="row">

                    <div class="max-w-m form-group">

                        <span>{{trans('lang.arabic_title')}}</span>
                        <label>
                            <input name="title_ar"
                                class="form-control mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                placeholder="Arabic Title" type="text" />
                        </label>
                    </div>

                    <div class="max-w-m form-group" style="padding-top: 2%">

                        <span>{{trans('lang.english_title')}}</span>
                        <label>
                            <input name="title_en"
                                class="form-control mt-1.5 w-full rounded-lg border border-slate-300 bg-transparent px-3 py-2 placeholder:text-slate-400/70 hover:border-slate-400 focus:border-primary dark:border-navy-450 dark:hover:border-navy-400 dark:focus:border-accent"
                                placeholder="English Title" type="text" />
                        </label>
                    </div>
                </div>


                {{-- <div class="max-w-m  " style="padding-top: 2%  ;float-right">
                    <a href="{{ route('/specialist/create') }}" style="color: green"

                    </a>

                </div> --}}
            </div>
            <button type="submit" style="background-color: green ;  color: white"  class="btn w-full space-x-2 rounded-full border border-slate-200 py-2 font-medium text-slate-800 hover:bg-slate-150 focus:bg-slate-150 active:bg-slate-150/80 dark:border-navy-500 dark:text-navy-50 dark:hover:bg-navy-500 dark:focus:bg-navy-500 dark:active:bg-navy-500/90" >

            <span> {{trans('lang.submit')}} </span>
        </button>
        </form>

        </div>
@endsection
