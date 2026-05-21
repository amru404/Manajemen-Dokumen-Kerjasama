@extends('/layouts.app')


@section('content')
    <x-common.page-breadcrumb pageTitle="Templates" />

    <a href="{{ route('templates.create') }}" class="mb-4 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white p-2">Add Template</a>

    <div class="space-y-6 md:space-y-7 mt-4">
        <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <!-- Header -->
            <!-- Table -->
            <div class="max-w-full overflow-x-auto">
                <table id="tableTemplates" class="table-fixed min-w-full divide-y divide-gray-200 stripe hover w-full text-theme-xs dark:text-gray-400 text-start">
                    <thead class="px-6 py-3.5 border-t border-gray-100 border-y bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">
                                <div class="flex items-center gap-3">
                                    <span class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No</span>
                                </div>
                            </th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Name</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Type</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Created By</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Description</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($templates as $t)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                                <td class="px-4 sm:px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <span class="block font-medium text-gray-700 break-words text-theme-sm dark:text-gray-400">{{ $loop->iteration + ($templates->currentPage()-1) * $templates->perPage() }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $t->name }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $t->document_type }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ optional($t->user)->name ?? '—' }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ Str::limit($t->description, 120) }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('templates.show', $t->id) }}" class="flex justify-center text-gray-700 cursor-pointer size-5 hover:text-indigo-500 dark:text-gray-400 dark:hover:text-indigo-400 text-sm" title="Lihat detail">
                                            <svg class="inline h-5 w-5 flex justify-center" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                        </a>

                                        <a href="{{ route('templates.edit', $t->id) }}" class="flex justify-center text-gray-700 cursor-pointer size-5 hover:text-green-500 dark:text-gray-400 dark:hover:text-green-400 text-sm mt-1" title="Edit Template">
                                            <i class="fa-regular fa-pen-to-square"></i>
                                        </a>

                                        <form action="{{ route('templates.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Hapus template ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex justify-center text-gray-700 cursor-pointer size-5 hover:text-red-500 dark:text-gray-400 dark:hover:text-red-400 text-sm" title="Hapus Template">
                                                <svg class="inline h-5 w-5 flex justify-center" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0V5a2 2 0 00-2-2h-3.5"/>
                                                </svg>
                                            </button>
                                        </form>

                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $templates->links() }}
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = new DataTable('#tableTemplates', {
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
            const searchInput = document.querySelector('#tableTemplates_filter input');
            if (searchInput) {
                searchInput.classList.add('rounded-md', 'border', 'px-3', 'py-2');
            }
            const lengthSelect = document.querySelector('#tableTemplates_length select');
            if (lengthSelect) {
                lengthSelect.classList.add('rounded-md', 'border', 'px-2', 'py-1');
            }

            // style pagination buttons
            const observer = new MutationObserver(() => {
                document.querySelectorAll('#tableTemplates_paginate button').forEach(btn => {
                    btn.classList.add('rounded-md', 'border', 'px-2', 'py-1', 'mx-1');
                });
            });
            const paginateElem = document.querySelector('#tableTemplates_paginate');
            if (paginateElem) observer.observe(paginateElem, { childList: true, subtree: true });
        });
    </script>

@endsection
