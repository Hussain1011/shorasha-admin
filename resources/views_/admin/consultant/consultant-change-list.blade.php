@extends('admin.layout')
@section('content')
    <!-- Current Consultants Header -->
    <header class="bg-[#E8EBEB] flex justify-between items-center">
        <div>

            <!-- Border-Bottom Made with Before (to give it rounded effect) -->
            @if ($deleted == false)
            <a href="{{ route('/consultant/list') }}" class="text-[#5B99A2] text-xl p-4 w-32">{{ __('lang.current') }}</a>

                <a href="{{ route('admin.consultant.index.new') }}"
                    class="text-[#5B99A2] text-xl p-4 w-32">{{ __('lang.new') }}</a>

                    {{-- <a href="{{ route('admin.consultant.change.list') }}"
                    class="text-[#5B99A2] text-xl p-4 w-32">{{  }}</a> --}}
                    <a href="{{ route('admin.consultant.index.deleted') }}"
                    class="text-[#5B99A2] text-xl p-4 w-32">{{ __('lang.deleted') }}</a>
                    <button class="text-main text-l p-4 w-32 relative before:content-[''] before:absolute before:bottom-0 before:start-0 before:h-[4px] before:w-full before:bg-main before:rounded-full">{{__('lang.change_request')}}</button>
            @else
                <a href="{{ route('/consultant/list') }}" class="text-[#5B99A2] text-xl p-4 w-32">{{ __('lang.current') }}</a>
                <a href="{{ route('admin.consultant.index.new') }}"
                    class="text-[#5B99A2] text-xl p-4 w-32">{{ __('lang.new') }}</a>
                <button
                    class="text-main text-xl p-4 w-32 relative before:content-[''] before:absolute before:bottom-0 before:start-0 before:h-[4px] before:w-full before:bg-main before:rounded-full">{{ __('lang.deleted') }}</button>
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
    {{-- <div class="flex flex-wrap"> --}}
        <div class="overflow-x-auto">
            <table class="w-full text-start">
                <thead>
                    <tr class="text-darkGray font-bold">
                        <th class="border-b border-light-blue-200 px-5 py-4">
                            <div class="flex justify-between items-center">
                                <span>{{trans('lang.id')}}</span>
                                <div class="w-4 flex flex-col">
                                    <img class="-mb-1" src="{{ asset('images/images/table-sort-asc') }}.svg"
                                        alt="sort asc">
                                    <img src="{{ asset('images/images/table-sort-desc') }}-active.svg" alt="sort desc">
                                </div>
                            </div>
                        </th>
                        <th class="border-b border-light-blue-200 px-5 py-4">
                            <div class="flex justify-between items-center">
                                <span>{{ __('lang.name') }}</span>
                                <div class="w-4 flex flex-col">
                                    <img class="-mb-1" src="{{ asset('images/images/table-sort-asc') }}.svg"
                                        alt="sort asc">
                                    <img src="{{ asset('images/images/table-sort-desc') }}-active.svg" alt="sort desc">
                                </div>
                            </div>
                        </th>
                        <th class="border-b border-light-blue-200 px-5 py-4">
                            <div class="flex justify-between items-center">
                                <span>{{ __('lang.fees') }}</span>
                                <div class="w-4 flex flex-col">
                                    <img class="-mb-1" src="{{ asset('images/images/table-sort-asc') }}.svg"
                                        alt="sort asc">
                                    <img src="{{ asset('images/images/table-sort-desc') }}-active.svg" alt="sort desc">
                                </div>
                            </div>
                        </th>
                        <th class="border-b border-light-blue-200 px-5 py-4">
                            <div class="flex justify-between items-center">
                                <span>{{ __('lang.old_fees') }}</span>
                                <div class="w-4 flex flex-col">
                                    <img class="-mb-1" src="{{ asset('images/images/table-sort-asc') }}.svg"
                                        alt="sort asc">
                                    <img src="{{ asset('images/images/table-sort-desc') }}-active.svg" alt="sort desc">
                                </div>
                            </div>
                        </th>
                        {{-- <th class="border-b border-light-blue-200 px-5 py-4">
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
                    @foreach ($users as $row)
                        <tr class="text-[#363636] hover:bg-[#EFF8FA]">
                            <td class="border-b border-light-blue-200 px-5 py-4">{{ $row->id }}</td>
                            <td class="border-b border-light-blue-200 px-5 py-4"><a href="{{route('admin.consultant.show', $row->id)}}" class="text-[#6EABB4] hover:underline"> {{$row?->name}}</a></td>
                            <td class="border-b border-light-blue-200 px-5 py-4">{{$row->personal?->new_fees  }}</td>
                            <td class="border-b border-light-blue-200 px-5 py-4">{{  $row->personal?->fees  }}</td>
                            {{-- <td class="border-b border-light-blue-200 px-5 py-4"> {{ $row->personal?->date_birth }} --}}
                            </td>
                            <td class="border-b border-light-blue-200 px-5 py-4">
                                {{-- <td class="border-b border-light-blue-200 px-5 py-4"> --}}
                                    <div class="flex gap-3 items-center">
                                    <form id="{{ $row->id }}"
                                        action="{{ route('/consultant/change-fees/approve', ['id' => $row->id]) }}" method="POST">
                                        @csrf
                                        @method('POST')
                                        <button type="button"       onclick="confirmApprove({{ $row->id }})">
                                        <img src="{{ asset('images/images/approve.png') }}"
                                          style="height: 20px"   alt="actions delete icon" title="approve"></button>
                                    </form>

                                        <form id="{{ $row->id }}"
                                            action="{{ route('/consultant/change-fees/reject', ['id' => $row->id]) }}" method="POST">
                                            @csrf
                                            @method('POST')
                                            <button type="button" class="btn btn-danger"
                                                onclick="confirmReject({{ $row->id }})">
                                                <img src="{{ asset('images/images/reject.png') }}"
                                                    style="height: 20px" alt="actions delete icon" title="reject"></button>
                                        </form>
                                    </div>

                            {{-- </td> --}}

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    {{-- </div> --}}
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

            </ul>

        </div>
    </div>
    <script>
        function confirmReject(id) {
            if (confirm('Are you sure you want to Reject  this Change Request?')) {
                console.log(id);
                document.getElementById(id).submit();
            }
        }
        function confirmApprove(id) {
            if (confirm('Are you sure you want to Approve   this Change Request?')) {
                console.log(id);
                document.getElementById(id).submit();
            }
        }
    </script>
@endsection
