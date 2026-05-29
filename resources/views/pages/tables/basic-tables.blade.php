@extends('layouts.app')


@section('content')
    <x-common.page-breadcrumb pageTitle="Data Mitra"/>
    <a href="{{route('dashboard')}}" class="mb-4 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white p-2">Add Mitra</a>
    <div class="space-y-6 md:space-y-7 mt-6">
        <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <!-- Header -->
            <!-- Table -->
            <div class="max-w-full overflow-x-auto">
                <table id="example1" class="min-w-full whitespace-normal divide-y divide-gray-200 stripe hover w-full text-theme-xs dark:text-gray-400 text-start text-center">
                    <thead class="px-6 py-3.5 border-t border-gray-100 border-y bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">
                                <div class="flex items-center gap-3">
                                 
                                    <span class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No</span>
                                </div>
                            </th>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Nama</th>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Penanggun Jawab</th>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Jabatan</th>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Alamat</th>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">No Telepon</th>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach($mitra as $m)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                                <td class="px-4 sm:px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <span class="block font-medium text-gray-700 text-theme-sm dark:text-gray-400">{{ $loop->iteration + ($mitra->currentPage()-1) * $mitra->perPage() }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $m->nama }}</p>
                                </td>
                                 <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $m->penanggung_jawab }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $m->jabatan }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $m->alamat }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 text-theme-sm dark:text-gray-400">{{ $m->no_telp }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <button type="button" class="text-gray-700 cursor-pointer size-5 hover:text-red-500 dark:text-gray-400 dark:hover:text-red-500">
                                        <svg class="inline" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
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
            const table = new DataTable('#example1', {
                responsive: true,
                autoWidth: false,
                pageLength: 10,
                lengthMenu: [5, 10, 25, 50],
                order: [[0, 'asc']],
                columnDefs: [{ orderable: false, targets: -1 }],
                dom: "<'flex flex-wrap items-center gap-3 justify-between mb-4 dark:text-gray-200 dark:border-gray-200'<'flex flex-wrap items-center gap-3 dark:text-gray-200 dark:border-gray-200'l><'flex flex-wrap items-center gap-3 dark:text-gray-200 dark:border-gray-200'f>><'w-full overflow-x-auto dark:text-gray-200 dark:border-gray-200't><'mt-4 flex items-center justify-between dark:text-gray-200 dark:border-gray-200'ip >",
                language: {
                    search: "",
                    searchPlaceholder: "Cari...",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    paginate: { previous: "Sebelumnya", next: "Berikutnya" }
                }
            });

            // style search input and length select
            const searchInput = document.querySelector('#example1_filter input');
            if (searchInput) {
                searchInput.classList.add('rounded-md', 'border', 'px-3', 'py-2');
            }
            const lengthSelect = document.querySelector('#example1_length select');
            if (lengthSelect) {
                lengthSelect.classList.add('rounded-md', 'border', 'px-2', 'py-1');
            }

            // style pagination buttons
            const observer = new MutationObserver(() => {
                document.querySelectorAll('#example1_paginate button').forEach(btn => {
                    btn.classList.add('rounded-md', 'border', 'px-2', 'py-1', 'mx-1');
                });
            });
            const paginateElem = document.querySelector('#example1_paginate');
            if (paginateElem) observer.observe(paginateElem, { childList: true, subtree: true });
        });
    </script>

@endsection

