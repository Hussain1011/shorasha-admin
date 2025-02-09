@extends('admin.layout')
@section('content')
    <div>
        <div class="bg-white shadow-[0px_2px_13px_0px_rgba(202,_229,_233,_0.48)] rounded-2xl">
            <div class="flex items-center justify-between px-5 py-4">
                <span class="text-darkBlue text-[1.3125rem]">{{ trans('lang.users') }}</span>
                <div class="flex items-center gap-4">
                    <a class="w-10 h-10 bg-main rounded-xl flex justify-center items-center" href="{{ route('admin.specialist.create')  }}">
                        <img src="{{ asset('images/images/action-add.svg') }}" alt="action add">
                    </a>
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
                                    <span>#</span>
                                    <div class="w-4 flex flex-col">
                                        <img class="-mb-1" src="{{ asset('images/images/table-sort-asc') }}.svg"
                                            alt="sort asc">
                                        <img src="{{ asset('images/images/table-sort-desc') }}-active.svg" alt="sort desc">
                                    </div>
                                </div>
                            </th>
                            <th class="border-b border-light-blue-200 px-5 py-4">
                                <div class="flex justify-between items-center">
                                    <span>{{ trans('lang.title_en') }}</span>
                                    <div class="w-4 flex flex-col">
                                        <img class="-mb-1" src="{{ asset('images/images/table-sort-asc') }}.svg"
                                            alt="sort asc">
                                        <img src="{{ asset('images/images/table-sort-desc') }}-active.svg" alt="sort desc">
                                    </div>
                                </div>
                            </th>
                            <th class="border-b border-light-blue-200 px-5 py-4">
                                <div class="flex justify-between items-center">
                                    <span>{{ trans('lang.title_ar') }}</span>
                                    <div class="w-4 flex flex-col">
                                        <img class="-mb-1" src="{{ asset('images/images/table-sort-asc') }}.svg"
                                            alt="sort asc">
                                        <img src="{{ asset('images/images/table-sort-desc') }}-active.svg" alt="sort desc">
                                    </div>
                                </div>
                            </th>

                            <th class="border-b border-light-blue-200 px-5 py-4">
                                <div class="flex justify-between items-center">
                                    <span>{{ trans('lang.photo') }}</span>
                                    <div class="w-4 flex flex-col">
                                        <img class="-mb-1" src="{{ asset('images/images/table-sort-asc') }}.svg"
                                            alt="sort asc">
                                        <img src="{{ asset('images/images/table-sort-desc') }}-active.svg" alt="sort desc">
                                    </div>
                                </div>
                            </th>
                            {{-- <th class="border-b border-light-blue-200 px-5 py-4">
                                <div class="flex justify-between items-center">
                                    <span>Email</span>
                                    <div class="w-4 flex flex-col">
                                        <img class="-mb-1" src="{{ asset('images/images/table-sort-asc') }}.svg"
                                            alt="sort asc">
                                        <img src="{{ asset('images/images/table-sort-desc') }}-active.svg" alt="sort desc">
                                    </div>
                                </div>
                            </th>
                            <th class="border-b border-light-blue-200 px-5 py-4">
                                <div class="flex justify-between items-center">
                                    <span>Age</span>
                                    <div class="w-4 flex flex-col">
                                        <img class="-mb-1" src="{{ asset('images/images/table-sort-asc') }}.svg"
                                            alt="sort asc">
                                        <img src="{{ asset('images/images/table-sort-desc') }}-active.svg" alt="sort desc">
                                    </div>
                                </div>
                            </th> --}}
                            <th class="text-start border-b border-light-blue-200 px-5 py-4">{{trans('lang.options')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($specialists as $row)
                        
                            <tr class="text-[#363636] hover:bg-[#EFF8FA]">
                                <td class="border-b border-light-blue-200 px-5 py-4">{{ $row->id }}</td>
                                <td class="border-b border-light-blue-200 px-5 py-4">{{ $row->title_en }}</td>
                                <td class="border-b border-light-blue-200 px-5 py-4">{{ $row->title_ar }}</td>
                                <td class="border-b border-light-blue-200 px-5 py-4"><img src="{{ Url('/').'/public/'.$row->photo }}" width=200></td>
                                {{-- <td class="border-b border-light-blue-200 px-5 py-4">{{ $row->email }}</td>
                                <td class="border-b border-light-blue-200 px-5 py-4"> {{ $row->personal?->date_birth }} --}}
                                </td>
                                <td class="border-b border-light-blue-200 px-5 py-4">
                                    <div class="flex gap-3 items-center">
                                        <a href="{{ url("admin/specialist/edit/{$row->id}") }}">
                                            <img src="{{asset('images/images/actions-edit.svg')}}"
                                            alt="actions edit icon">

                                        </a>
                                        <form id="{{$row->id}}" action="{{ route('specialist/delete', ['id' => $row->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('POST')

                                            <button type="button" class="btn btn-danger" onclick="confirmDelete({{$row->id}})"><img
                                                    src="{{ asset('images/images/actions-delete.svg') }}"
                                                    alt="actions delete icon"></i></button>
                                        </form>
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
                <p class="text-darkGray">{{trans('lang.items_per_page')}}</p>
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
            <div
                class="flex justify-center items-center gap-8 py-2 px-4 bg-white rounded-[1.125rem] shadow-[0px_5px_20px_0px_rgba(98,_202,_217,_0.05)]">
                {{-- <button>
                    <img src="{{ asset('images/images/table-page-prev') }}.svg" alt="page prev">
                </button> --}}
                <ul class="flex items-center gap-3">
                    <!-- Active PAge -->
                    {{ $specialists->links() }}
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
                    <img src="{{ asset('images/images/table-page-next') }}.svg" alt="page next">
                </button> --}}
            </div>
            <div></div>
        </div>
    </div>
    {{-- <script>
        function confirmDelete() {
            if (confirm('Are you sure you want to delete this row?')) {
                document.getElementById('deleteForm').submit();
            }
        }
    </script> --}}
@endsection
