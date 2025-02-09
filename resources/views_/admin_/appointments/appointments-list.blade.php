@extends('admin.layout')
@section('content')

<div>
    <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-2xl">
      <div class="flex items-center justify-between px-5 py-4">
        <span class="text-darkBlue text-[1.3125rem]">Appointments</span>
      </div>
      <div class="overflow-x-auto">
        <table class="w-full text-center">
          <thead>
            <tr class="text-darkGray font-bold">
              <th class="border-t border-b border-light-blue-200 px-5 py-4">No</th>
              <th class="border-t border-b border-light-blue-200 px-5 py-4">User</th>
              <th class="border-t border-b border-light-blue-200 px-5 py-4">Consultant</th>
              <th class="border-t border-b border-light-blue-200 px-5 py-4">Method</th>
              <th class="border-t border-b border-light-blue-200 px-5 py-4">Date and Time</th>
              <th class="border-t border-b border-light-blue-200 px-5 py-4">Counseling type</th>
              <th class="border-t border-b border-light-blue-200 px-5 py-4">Status</th>
              <th class="border-t border-b border-light-blue-200 px-5 py-4">Price</th>
              <th class="border-t border-b border-light-blue-200 px-5 py-4">Payment</th>
              <th class="border-t border-b border-light-blue-200 px-5 py-4">Action</th>
            </tr>
          </thead>
          <tbody>
            @foreach ($rows as $row)
            <tr class="text-[#363636] font-semibold hover:bg-[#EFF8FA]">
                <td class="border-b border-light-blue-200 px-5 py-4">{{$loop->iteration}}</td>
                <td class="border-b border-light-blue-200 px-5 py-4">{{ $row->user?->name }}</td>
                <td class="border-b border-light-blue-200 px-5 py-4">{{ $row->doctor?->name }}</td>
                <td class="border-b border-light-blue-200 px-5 py-4">{{ $row->method }}</td>
                <td class="border-b border-light-blue-200 px-5 py-4">{{ \Carbon\Carbon::parse($row->start_time)->format('j M | h:i A') }}</td>
                <td class="border-b border-light-blue-200 px-5 py-4">{{$row->booking_type}}</td>
                <td class="border-b border-light-blue-200 px-5 py-3">
                  <!-- Upcoming Status Color -->
                     @switch($row->booking_status)
                            @case(0)
                            {{-- upcomming --}}
                            <span class="bg-[#FFF4D3] rounded-[0.4375rem] text-[#FFAB07] py-1 px-3">
                                @break
                            @case(1)
                            {{-- compleated --}}
                            <span class="bg-[#DAF7DF] rounded-[0.4375rem] text-[#14C31B] py-1 px-3">
                                @break
                            @default
                            {{-- canceled --}}
                            <span class="bg-[#FFE1E1] rounded-[0.4375rem] text-solidRed py-1 px-3">
                                @endswitch


                        {{$row->status}}</span>
                </td>
                <td class="border-b border-light-blue-200 px-5 py-4">{{$row->doctor?->personal?->fees}}</td>
                <td class="text-main border-b border-light-blue-200 px-5 py-4">{{$row->paid ? __('lang.Paid') : __('lang.Unpaid')}}</td>
                <td class="border-b border-light-blue-200 px-5 py-4">
                  <div class="flex gap-3 items-center">
                    <a href="{{ route('admin.appointments.show', $row->created) }}">
                      <img src="{{asset('images/images/actions-view.svg')}}" alt="actions view icon">
                    </a>
                    <a href="{{ route('admin.appointments.edit', $row->created) }}">
                        <img src="{{ asset('images/images/actions-edit.svg') }}" alt="actions edit icon">
                    </a>
                    {{-- <form id="{{$row->created}}" action="{{ route('appointments-booking/delete', ['id' => $row->created]) }}" method="POST">
                        @csrf
                        @method('POST')
                        <button type="submit" class="btn btn-danger" onclick="confirmDelete({{$row->created}})"><img src="{{ asset('images/images/actions-delete.svg') }}"
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
    <div class="flex justify-between items-center py-8">
      <div class="flex items-center gap-3">
        <p class="text-darkGray">Items Per Page</p>
        <div>
            <button id="dropdown-button" data-dropdown-toggle="dropdown"
                class="text-darkGray bg-secondary border border-light-blue-50 px-3 py-1 w-[4.5rem] rounded-lg flex justify-between items-center"
                type="button">
                <span>{{ request()->get('pagination') ?? 10 }}</span>
                <img src="{{ asset('images/images/arrow-down.svg') }}" alt="arrow down icon">
            </button>
            <!-- Dropdown menu -->
            <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44">
                <ul class="py-2 text-sm text-gray-700" aria-labelledby="dropdown-button">
                    <li>
                        <a href="{{ app('request')->fullUrlWithQuery(['pagination' => 10]) }}"
                            class="block px-4 py-2 hover:bg-gray-100">10</a>
                    </li>
                    <li>
                        <a href="{{ app('request')->fullUrlWithQuery(['pagination' => 25]) }}"
                            class="block px-4 py-2 hover:bg-gray-100">25</a>
                    </li>
                    <li>
                        <a href="{{ app('request')->fullUrlWithQuery(['pagination' => 50]) }}"
                            class="block px-4 py-2 hover:bg-gray-100">50</a>
                    </li>
                </ul>
            </div>
        </div>
      </div>
      <div class="flex justify-center items-center gap-8 py-2 px-4 bg-white rounded-[1.125rem] shadow-[0px_5px_20px_0px_rgba(98,_202,_217,_0.05)]">
        {{-- <button>
          <img src="{{asset('images/images/table-page-prev.svg')}}" alt="page prev">
        </button> --}}
        <ul class="flex items-center gap-3">
          <!-- Active PAge -->
          {{$rows->links()}}
          {{-- <li>
            <a class="bg-main w-8 h-8 rounded-[0.375rem] flex justify-center items-center text-white font-semibold text-xl" href="#">1</a>
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
          <img src="{{asset('images/images/table-page-next.svg')}}" alt="page next">
        </button> --}}
      </div>
      <div></div>
    </div>
  </div>

@endsection
