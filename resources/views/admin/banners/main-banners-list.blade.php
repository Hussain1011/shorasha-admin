@extends('admin.layout')
@section('content')
    <div>
        <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-2xl">
            <div class="flex items-center justify-between px-5 py-4">
                <span class="text-darkBlue text-[1.3125rem]">{{ trans('lang.users') }}</span>
                <div class="flex items-center gap-4">
                    {{-- @if ($deleted == false)
                        <a class="bg-red-500 text-white px-4 py-2 rounded hover:bg-green-600"
                            style="margin-left:10px"
                            href="{{ route('admin.deleted.users') }}">{{ __('lang.deleted_users') }}</a>
                    @else
                    <a class="bg-green-500 text-white px-4 py-2 rounded hover:bg-blue-600"
                    style="margin-left:10px
                 " href="{{ route('/users/list') }}">{{__('lang.main_banners')}}</a>
                    @endif --}}

                    <!-- Add User -->
                    {{-- <a class="w-10 h-10 bg-main rounded-xl flex justify-center items-center"
                        href="{{ route('users.create') }}">
                        <img src="{{ asset('images/images/action-add.svg') }}" alt="action add">
                    </a> --}}

                    <!-- Search -->
                    <form class="flex items-center">
                        <div class="relative w-full">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <img src="{{ asset('images/images/search-icon.svg') }}" alt="search icon">
                            </div>
                            <input type="text" id="simple-search" name="search"
                                class="bg-[#EAF8FA] ps-10 rounded-[0.8125rem] border border-[#BFD5D8] focus:shadow-none focus:border-[#BFD5D8] focus:ring-0"
                                placeholder="Search" required="">
                        </div>
                    </form>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-start">
                    <thead>
                        <tr class="text-darkGray font-bold">
                            <th class="border-b border-light-blue-200 px-5 py-4">
                                <div class="flex justify-between items-center">
                                    <span>{{__('lang.name')}}</span>
                                    <div class="w-4 flex flex-col">
                                        <img class="-mb-1" src="{{ asset('images/images/table-sort-asc') }}.svg"
                                            alt="sort asc">
                                        <img src="{{ asset('images/images/table-sort-desc') }}-active.svg" alt="sort desc">
                                    </div>
                                </div>
                            </th>
                            <th class="border-b border-light-blue-200 px-5 py-4">
                                <div class="flex justify-between items-center">
                                    <span>{{__('lang.amount')}}
                                       </span>
                                    <div class="w-4 flex flex-col">
                                        <img class="-mb-1" src="{{ asset('images/images/table-sort-asc') }}.svg"
                                            alt="sort asc">
                                        <img src="{{ asset('images/images/table-sort-desc') }}-active.svg" alt="sort desc">
                                    </div>
                                </div>
                            </th>
                            <th class="border-b border-light-blue-200 px-5 py-4">
                                <div class="flex justify-between items-center">
                                    <span>
                                        Status
                                        </span>
                                    <div class="w-4 flex flex-col">
                                        <img class="-mb-1" src="{{ asset('images/images/table-sort-asc') }}.svg"
                                            alt="sort asc">
                                        <img src="{{ asset('images/images/table-sort-desc') }}-active.svg" alt="sort desc">
                                    </div>
                                </div>
                            </th>
                            <th class="border-b border-light-blue-200 px-5 py-4">
                                <div class="flex justify-between items-center">
                                    <span>is Collected</span>
                                    <div class="w-4 flex flex-col">
                                        <img class="-mb-1" src="{{ asset('images/images/table-sort-asc') }}.svg"
                                            alt="sort asc">
                                        <img src="{{ asset('images/images/table-sort-desc') }}-active.svg" alt="sort desc">
                                    </div>
                                </div>
                            </th>
                            <th class="border-b border-light-blue-200 px-5 py-4">
                                <div class="flex justify-between items-center">
                                    <span>{{__('lang_created')}}</span>
                                    <div class="w-4 flex flex-col">
                                        <img class="-mb-1" src="{{ asset('images/images/table-sort-asc') }}.svg"
                                            alt="sort asc">
                                        <img src="{{ asset('images/images/table-sort-desc') }}-active.svg" alt="sort desc">
                                    </div>
                                </div>
                            </th>
                            <th class="border-b border-light-blue-200 px-5 py-4">
                                <div class="flex justify-between items-center">
                                    <span>{{__('lang.reference_number')}}</span>
                                    <div class="w-4 flex flex-col">
                                        <img class="-mb-1" src="{{ asset('images/images/table-sort-asc') }}.svg"
                                            alt="sort asc">
                                        <img src="{{ asset('images/images/table-sort-desc') }}-active.svg" alt="sort desc">
                                    </div>
                                </div>
                            </th>
                            <th class="text-start border-b border-light-blue-200 px-5 py-4">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($main_banners as $row)
                            <tr class="text-[#363636] hover:bg-[#EFF8FA]">
                                {{-- 'doctor_id',
                                'amount',
                                'status',
                                'collected',
                                'reference_no',
                                'created_by',
                                'updated_by', --}}
                                {{-- <td class="border-b border-light-blue-200 px-5 py-4">{{ $row->doctor?->name }}</td> --}}
                                <td class="border-b border-light-blue-200 px-5 py-4">{{ $row->doctor?->name }}</td>
                                <td class="border-b border-light-blue-200 px-5 py-4">{{ $row->amount }}</td>
                                <td class="border-b border-light-blue-200 px-5 py-4">{{ $row->statust }}</td>
                                <td class="border-b border-light-blue-200 px-5 py-4">{{ $row->collected?"True":"False" }}</td>
                                <td class="border-b border-light-blue-200 px-5 py-4"> {{ $row->created_at?->format('j M | h:i A') }}
                                <td class="border-b border-light-blue-200 px-5 py-4"> {{ $row->reference_no }}
                                </td>
                                @if ($row->status==0)

                                <td class="border-b border-light-blue-200 px-5 py-4">

                                        <div class="flex gap-3 items-center">
                                            {{-- <form id="{{ $row->id }}"
                                                action="{{ route('user/delete', ['id' => $row->id]) }}" method="POST">
                                                @csrf
                                                @method('POST') --}}
                                                <button type="button" class="btn btn-danger"
                                                    {{-- onclick="confirmDelete({{ $row->id }})" --}}
                                                    data-modal-target="approve-modal{{$row->id}}" data-modal-toggle="approve-modal{{$row->id}}" >
                                                    <img src="{{ asset('images/images/approve.png') }}"
                                                      style="height: 20px"   alt="actions delete icon" title="approve"></button>
                                            {{-- </form> --}}
                                            <form id="{{ $row->id }}"
                                                action="{{ route('/collect/reject', ['id' => $row->id]) }}" method="POST">
                                                @csrf
                                                @method('POST')
                                                <button type="button" class="btn btn-danger"
                                                    onclick="confirmReject({{ $row->id }})">
                                                    <img src="{{ asset('images/images/reject.png') }}"
                                                        style="height: 20px" alt="actions delete icon" title="reject"></button>
                                            </form>
                                        </div>

                                </td>
                                @endif
                            </tr>
                            <div id="approve-modal{{$row->id}}" tabindex="-1" aria-hidden="true"
                            class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">

                            <div class="relative p-4 w-full max-w-2xl max-h-full">
                                <!-- Modal content -->
                                <div class="relative bg-white rounded-lg shadow ">
                                    <!-- Modal header -->
                                    <div class="flex items-center gap-4 p-4 border-b border-light-blue-200">
                                        <img src="{{ asset('images/images/danger-icon.svg') }}" alt="danger icon">
                                        <h3 class="text-darkGray font-semibold text-xl">{{ __('lang.Referance_number') }}</h3>
                                    </div>
                                    <!-- Modal body -->
                                    <div class="p-4 md:p-5 space-y-4">
                                        <form action="{{ route('/collect/approve', ['id' => $row->id, 'doctor_verified' => 0]) }}"
                                            method="post">
                                            @method('POST')
                                            @csrf
                                            <input type="hidden" name="doctor_verified" value="0">
                                            <input type="hidden" name="id" value="{{ $row->id }}">
                                            <label for="message" class="block mb-2 text-darkGray">{{ __('lang.Referance_number') }}</label>
                                            <input type="number" id="message" name="referance_number" rows="4"
                                                class="block p-4 w-full resize-none text-gray-900 bg-[#EAF8FA] rounded-[0.8125rem] border-none focus:ring-0 placeholder:text-[#91BBC1]"
                                                placeholder="Type Referance number here"></input>
                                            <!-- Add any additional fields if necessary -->
                                            <div class="flex p-4">
                                                <button type="submit"
                                                    class="w-full py-3 bg-main rounded-[0.8125rem] text-white font-bold shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)]">{{ __('lang.submit') }}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
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
            {{--
            <div
                class="flex justify-center items-center gap-8 py-2 px-4 bg-white rounded-[1.125rem] shadow-[0px_5px_20px_0px_rgba(98,_202,_217,_0.05)]">

                <ul class="flex items-center gap-3">
                    <!-- Active PAge -->
                    {{ $collect_requests->links() }}
                </ul>
            </div>
            --}}
        </div>
    </div>
    {{-- <div id="approve-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">

        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow ">
                <!-- Modal header -->
                <div class="flex items-center gap-4 p-4 border-b border-light-blue-200">
                    <img src="{{ asset('images/images/danger-icon.svg') }}" alt="danger icon">
                    <h3 class="text-darkGray font-semibold text-xl">{{ __('lang.Rejection Reason') }}</h3>
                </div>
                <!-- Modal body -->
                <div class="p-4 md:p-5 space-y-4">
                    <form action="{{ route('/consultant/action', ['id' => 3, 'doctor_verified' => 0]) }}"
                        method="get">
                        @csrf
                        <input type="hidden" name="doctor_verified" value="0">
                        <input type="hidden" name="id" value="{{ 3 }}">
                        <label for="message" class="block mb-2 text-darkGray">{{ __('lang.Reason') }}</label>
                        <textarea id="message" name="rejaction_reason" rows="4"
                            class="block p-4 w-full resize-none text-gray-900 bg-[#EAF8FA] rounded-[0.8125rem] border-none focus:ring-0 placeholder:text-[#91BBC1]"
                            placeholder="Type reason here"></textarea>
                        <!-- Add any additional fields if necessary -->
                        <div class="flex p-4">
                            <button type="submit"
                                class="w-full py-3 bg-main rounded-[0.8125rem] text-white font-bold shadow-[0px_4px_13px_0px_rgba(48,_185,_204,_0.30)]">{{ __('lang.Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}
    <script>
        function confirmReject(id) {
            if (confirm('Are you sure you want to Reject  this Collection Request?')) {
                console.log(id);
                document.getElementById(id).submit();
            }
        }
    </script>
@endsection
