@extends('/layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Data User"/>
    <a href="{{ route('users.create') }}" class="mb-6 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white p-2 text-sm">Add User</a>
    <div class="space-y-6 md:space-y-7 mt-6">
        <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="max-w-full overflow-x-auto">
                <table id="tableUsers" class="table-fixed min-w-full divide-y divide-gray-200 stripe hover w-full text-theme-xs dark:text-gray-400 text-start">
                    <thead class="px-6 py-3.5 border-t border-gray-100 border-y bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">No</th>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Name</th>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Email</th>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Role</th>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $u)
                        <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                            <td class="px-4 sm:px-6 py-3.5"><span class="block font-medium text-gray-700">{{ $loop->iteration + ($users->currentPage()-1) * $users->perPage() }}</span></td>
                            <td class="px-4 sm:px-6 py-3.5"><p class="text-gray-700 text-theme-sm">{{ $u->name }}</p></td>
                            <td class="px-4 sm:px-6 py-3.5"><p class="text-gray-700 text-theme-sm">{{ $u->email }}</p></td>
                            <td class="px-4 sm:px-6 py-3.5"><p class="text-gray-700 text-theme-sm">{{ ucfirst($u->role) }}</p></td>
                            <td class="px-4 sm:px-6 py-3.5">
                                <div class="flex  gap-2">
                                    <a href="{{ route('users.edit', $u) }}" class="text-gray-700 hover:text-green-500 text-lg" title="Edit">
                                       <i class="fa-regular fa-pen-to-square text-sm"></i>
                                    </a>
                                    <form method="POST" action="{{ route('users.destroy', $u) }}" data-swal-confirm data-swal-title="Hapus user ini?" data-swal-text="User akan dihapus permanen">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-700 hover:text-red-500" title="Hapus">
                                            <svg class="inline h-5 w-5 mt-1 text-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0V5a2 2 0 00-2-2h-3.5"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = new DataTable('#tableUsers', {
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                order: [[0, 'asc']],
                columnDefs: [{ orderable: false, targets: -1 }],
                dom: "<'flex items-center justify-between mb-4'lfr>t<'mt-4 flex items-center justify-between'ip>",
                language: {
                    search: "",
                    searchPlaceholder: "Cari...",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    paginate: { previous: "Sebelumnya", next: "Berikutnya" }
                }
            });

            // style search input and length select
            const searchInput = document.querySelector('#tableUsers_filter input');
            if (searchInput) {
                searchInput.classList.add('rounded-md', 'border', 'px-3', 'py-2');
            }
            const lengthSelect = document.querySelector('#tableUsers_length select');
            if (lengthSelect) {
                lengthSelect.classList.add('rounded-md', 'border', 'px-2', 'py-1');
            }

            // style pagination buttons
            const observer = new MutationObserver(() => {
                document.querySelectorAll('#tableUsers_paginate button').forEach(btn => {
                    btn.classList.add('rounded-md', 'border', 'px-2', 'py-1', 'mx-1');
                });
            });
            const paginateElem = document.querySelector('#tableUsers_paginate');
            if (paginateElem) observer.observe(paginateElem, { childList: true, subtree: true });
        });
    </script>
@endsection