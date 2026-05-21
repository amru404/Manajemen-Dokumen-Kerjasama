@extends('/layouts.app')


@section('content')
    <x-common.page-breadcrumb pageTitle="Data Mitra"/>
    <div class="flex flex-wrap items-center gap-3 mb-6">
        <a href="{{ route('mitra.create') }}" class="bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white p-2">Add Mitra</a>
        <a href="{{ route('mitra.trashed') }}" class="bg-red-200 text-red-600 hover:bg-red-300 rounded-lg text-red-900 p-2">Trash</a>
    </div>
    <div class="space-y-6 md:space-y-7 mt-4">
        <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <!-- Header -->
            <!-- Table -->
            <div class="max-w-full overflow-x-auto">
                <table id="tableMitra" class="table-fixed min-w-full divide-y divide-gray-200 stripe hover w-full text-theme-xs dark:text-gray-400 text-start text-gray-700 hover:text-green-500 text-lg dark:text-gray-400 dark:hover:text-indigo-400">
                    <thead class="px-6 py-3.5 border-t border-gray-100 border-y bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">
                                <div class="flex items-center gap-3">
                                 
                                    <span class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No</span>
                                </div>
                            </th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Nama</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Penanggun Jawab</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Jabatan</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Email</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">No Telepon</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach($mitra as $m)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                                <td class="px-4 sm:px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <span class="block font-medium text-gray-700 break-words text-theme-sm dark:text-gray-400">{{ $loop->iteration}}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $m->nama }}</p>
                                </td>
                                 <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $m->penanggung_jawab }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $m->jabatan }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $m->alamat }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $m->no_telp }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <div class="content-center gap-2">
                                        <a href="{{ route('mitra.show', $m->id) }}" class="text-gray-700 hover:text-blue-500 text-lg dark:text-gray-400 dark:hover:text-indigo-400 text-sm" title="Lihat Detail">
                                              <i class="fa-regular fa-eye h-5 w-5"></i>
                                       </a>
                                        <a href="{{ route('mitra.edit', $m->id) }}" class="text-gray-700 hover:text-green-500 text-lg dark:text-gray-400 dark:hover:text-indigo-400 text-sm " title="Edit">
                                             <svg class="inline h-5 w-5 text-sm " fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        </a>
                                        <form action="{{ route('mitra.destroy', $m->id) }}" method="POST" class="inline-block">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-gray-700 hover:text-red-500 text-lg dark:text-red-400 dark:hover:text-indigo-400" title="Hapus" onclick="return confirm('Hapus mitra ini?')">
                                                <svg class="inline h-5 w-5 text-sm" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0V5a2 2 0 00-2-2h-3.5"/></svg>
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
            const table = new DataTable('#tableMitra', {
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
            const searchInput = document.querySelector('#tableMitra_filter input');
            if (searchInput) {
                searchInput.classList.add('rounded-md', 'border', 'px-3', 'py-2');
            }
            const lengthSelect = document.querySelector('#tableMitra_length select');
            if (lengthSelect) {
                lengthSelect.classList.add('rounded-md', 'border', 'px-2', 'py-1');
            }

            // style pagination buttons
            const observer = new MutationObserver(() => {
                document.querySelectorAll('#tableMitra_paginate button').forEach(btn => {
                    btn.classList.add('rounded-md', 'border', 'px-2', 'py-1', 'mx-1');
                });
            });
            const paginateElem = document.querySelector('#tableMitra_paginate');
            if (paginateElem) observer.observe(paginateElem, { childList: true, subtree: true });
        });
    </script>

@endsection

