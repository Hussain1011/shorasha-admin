<x-app-layout title="Table Advanced Component">
    <main class="main-content w-full " style="padding: 5% ">

        <div class="grid sm:gap-5">
            <!-- Users Table -->
            <div>
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-medium tracking-wide text-slate-700 line-clamp-1 dark:text-navy-100">
                        Users Table
                    </h2>


                    {{-- </div> --}}
                </div>
                <div class="card mt-3">
                    <div class="is-scrollbar-hidden min-w-full overflow-x-auto">
                        <table class="is-hoverable w-full text-left">
                            <thead>
                                <tr>
                                    <th
                                        class="whitespace-nowrap rounded-tl-lg bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                                        #
                                    </th>
                                    <th
                                        class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                                        Name
                                    </th>
                                    <th
                                        class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                                        Email
                                    </th>
                                    <th
                                        class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                                        Phone
                                    </th>
                                    <th
                                        class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                                        BirthDate
                                    </th>
                                    {{-- <th
                                        class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                                        Role
                                    </th> --}}
                                    {{-- <th
                                        class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                                        Status
                                    </th> --}}
                                    <th
                                    class="whitespace-nowrap bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                                    Show
                                    </th>
                                    <th
                                        class="whitespace-nowrap rounded-tr-lg bg-slate-200 px-4 py-3 font-semibold uppercase text-slate-800 dark:bg-navy-800 dark:text-navy-100 lg:px-5">
                                        Delete
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <template> --}}
                                @foreach ($users as $user)
                                    {{-- {{ dd($user)}} --}}
                                    <tr class="border-y border-transparent border-b-slate-200 dark:border-b-navy-500">
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                                            {{ $user->id }}
                                        </td>
                                        <td
                                            class="whitespace-nowrap px-3 py-3 font-medium text-slate-700 dark:text-navy-100 lg:px-5">
                                            {{ $user->name }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                                            {{ $user->email }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                                            {{ $user->mobile }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                                            {{ $user->personal?->date_birth }}
                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                                            <a data-toggle="tooltip" data-placement="top" title="View" href="{{ url("users/{$user->id}") }}" class="btn btn-icon btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                        </td>
                                        <td class="whitespace-nowrap px-4 py-3 sm:px-5">
                                            <form id="deleteForm" action="{{ route('user/delete', ['id' => $user->id]) }}" method="POST">
                                                @csrf
                                                @method('POST')
                                                <button type="button" class="btn btn-danger" onclick="confirmDelete()"><i class="fas fa-trash" style="color: red" ></i></button>
                                            </form>
                                        </td>


                                    </tr>
                                @endforeach

                                {{-- </template> --}}
                            </tbody>
                        </table>
                    </div>
                    <div
                        class="flex flex-col justify-between space-y-4 px-4 py-4 sm:flex-row sm:items-center sm:space-y-0 sm:px-5">


                        <ol class="pagination">
                            {{-- <li class="rounded-l-lg bg-slate-150 dark:bg-navy-500"> --}}
                            <div style="padding: 2%">
                                {{ $users->links() }}

                            </div>
                            {{-- </li> --}}
                        </ol>

                        <div class="text-xs+">1 - 10 of 10 entries</div>
                    </div>
                </div>
            </div>
        </div>
        <script>
            function confirmDelete() {
                if (confirm('Are you sure you want to delete this row?')) {
                    document.getElementById('deleteForm').submit();
                }
            }
        </script>
    </main>
</x-app-layout>
