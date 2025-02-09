@extends('admin.layout')
@section('content')

<div class="flex flex-col justify-between h-full">
    <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-[1.375rem] overflow-x-auto">
        <div class="flex justify-between items-center p-6 pb-0">
            <p class="text-darkBlue text-xl font-bold">Total Revenue</p>
            <div>
              <label>@lang('lang.from_date')</label>
              <input class="text-light-blue-150 bg-white border border-light-blue-50 px-3 py-1 rounded-lg" type="date" name="" id="from_date">
                <!-- <button id="dropdown-button" data-dropdown-toggle="dropdown" class="text-light-blue-150 bg-white border border-light-blue-50 px-3 py-1 w-[6.5rem] rounded-lg flex justify-between items-center"
                    type="button">
                    <span>Monthly</span>
                    {{-- <img src="{{ asset('images/images/arrow-down.svg') }}" alt="arrow down icon"> --}}
                </button> -->
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

              <label>@lang('lang.to_date')</label>
              <input class="text-light-blue-150 bg-white border border-light-blue-50 px-3 py-1 rounded-lg" type="date" id="to_date">
                <!-- <button id="dropdown-button" data-dropdown-toggle="dropdown" class="text-light-blue-150 bg-white border border-light-blue-50 px-3 py-1 w-[6.5rem] rounded-lg flex justify-between items-center"
                    type="button">
                    <span>Monthly</span>
                    {{-- <img src="{{ asset('images/images/arrow-down.svg') }}" alt="arrow down icon"> --}}
                </button> -->
                <!-- Dropdown menu -->
                <div id="searchBtnId" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                    {{-- <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdown-button">
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">Monthly</a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-gray-100">Yearly</a>
                        </li>
                    </ul> --}}
                </div>
              <button class="text-white bg-main text-sm font-semibold rounded-[0.8125rem] shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)] py-2 px-5 mt-4" id="getDuration">@lang('lang.search')</button>
            </div>
           
            
        </div>

        <!-- mfb updated -->
        <div>
        <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-2xl">
          <div class="flex items-center justify-between px-5 py-4">
            <span class="text-darkBlue text-[1.3125rem]">@lang('lang.bookings')</span>
          </div>
          <div class="overflow-x-auto">
            <table class="w-full text-center">
              <thead>
                <tr class="text-darkGray font-bold">
                  <th class="border-t border-b border-light-blue-200 px-5 py-4">@lang('lang.bookings')</th>
                  <th class="border-t border-b border-light-blue-200 px-5 py-4">@lang('lang.total_paid')</th>
                  <th class="border-t border-b border-light-blue-200 px-5 py-4">@lang('lang.total_refund')</th>
                  <th class="border-t border-b border-light-blue-200 px-5 py-4">@lang('lang.general_net')</th>
                  <th class="border-t border-b border-light-blue-200 px-5 py-4">@lang('lang.dr_revenue')</th>
                  <th class="border-t border-b border-light-blue-200 px-5 py-4">@lang('lang.net_revenue')</th>
                </tr>
              </thead>
              <tbody>
                  <tr>
                    <td class="border-t border-b border-light-blue-200 px-5 py-4">{{$revenue['totalCount']}}</td>
                    <td class="border-t border-b border-light-blue-200 px-5 py-4">{{$revenue['totalCost']}}</td>
                    <td class="border-t border-b border-light-blue-200 px-5 py-4">{{$revenue['totalRefund']}}</td>
                    <td class="border-t border-b border-light-blue-200 px-5 py-4">{{$revenue['totalCost']-$revenue['totalRefund']}}</td>
                    <td class="border-t border-b border-light-blue-200 px-5 py-4">{{$revenue['drRevenue']}}</td>
                    <td class="border-t border-b border-light-blue-200 px-5 py-4">{{$revenue['netRevenue']}}</td>
                  </tr>
              </tbody>
            </table>
          </div>
        </div>
       
      </div>
        <!-- end mfb updated -->
        {{--
        <div class="h-[1px] w-full bg-light-blue-200 my-5"></div>
        <div class="px-6">
            <p class="text-[#808080]">Total Revenue since start</p>
            <span class="text-main font-bold text-2xl">{{ $total_revenue }}</span>
        </div>
        --}}

    </div>

    <!-- © Naraakom. All Rights Reserved. -->

  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>
    $('#getDuration').click(function(){
      var from_date = $('#from_date').val();
      var to_date = $('#to_date').val();
      window.location= '{{url("/revenue")}}/'+from_date+'/'+to_date+'';
    });
  </script>
@endsection
