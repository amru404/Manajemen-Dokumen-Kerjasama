@extends('/layouts.app')


@section('content')
    <x-common.page-breadcrumb pageTitle="Detail Mitra"/>

    <div class="space-y-6 md:space-y-7 mt-6">
        <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium dark:text-gray-200">{{ $mitra->nama }}</h3>
                <a href="{{ route('mitra') }}" " class="mb-4 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white p-2 text-sm">Kembali</a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <dl>
                        <div class="mb-3">
                            <dt class="text-md font-medium text-gray-700">Penanggung Jawab</dt>
                            <dd class="mt-1 text-theme-sm text-gray-700 dark:text-gray-400">{{ $mitra->penanggung_jawab }}</dd>
                        </div>

                        <div class="mb-3">
                            <dt class="text-md font-medium text-gray-700">Jabatan</dt>
                            <dd class="mt-1 text-theme-sm text-gray-700 dark:text-gray-400">{{ $mitra->jabatan }}</dd>
                        </div>

                        <div class="mb-3">
                            <dt class="text-md font-medium text-gray-700">Alamat</dt>
                            <dd class="mt-1 text-theme-sm text-gray-700 dark:text-gray-400">{{ $mitra->alamat }}</dd>
                        </div>

                        <div class="mb-3">
                            <dt class="text-md font-medium text-gray-700">No Telepon</dt>
                            <dd class="mt-1 text-theme-sm text-gray-700 dark:text-gray-400">{{ $mitra->no_telp }}</dd>
                        </div>
                         <div class="mb-3">
                            <dt class="text-md font-medium text-gray-700">Email</dt>
                            <dd class="mt-1 text-theme-sm text-gray-700 dark:text-gray-400">{{ $mitra->email }}</dd>
                        </div>
                    </dl>
                </div>

                <div class="space-y-4">
                    @if($logoUrl)
                        <div>
                            <dt class="text-md font-medium text-gray-700">Logo</dt>
                            <img src="{{ asset('storage/' . $mitra->logo) }}" alt="Logo Mitra" class="mt-2 max-h-40 object-contain>
                        </div>
                    @endif

                    @if($tandaTanganUrl)
                        <div>
                            <dt class="text-md font-medium text-gray-700">Tanda Tangan</dt>
                            <img src="{{ asset('storage/' . $tandaTanganUrl) }}" alt="Tanda Tangan {{ $mitra->nama }}" class="mt-2 max-h-40 object-contain">
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
       <div class="overflow-hidden mt-8 p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <!-- Header -->
            <!-- Table -->
            <div class="max-w-full overflow-x-auto">
                <table id="tableKerjasama" class="min-w-full whitespace-normal divide-y divide-gray-200 stripe hover w-full text-theme-xs dark:text-gray-400 text-start text-center">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-medium dark:text-gray-200">List Kerjasama</h3>
                    <a href="{{ route('judul-kerjasama.create') }}" class="mb-4 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white p-2 text-sm">Add Judul Kerjasama</a>
                </div>
                    <thead class="px-6 py-3.5 border-t border-gray-100 border-y bg-gray-100 dark:border-white/[0.05] dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">
                                <div class="flex items-center gap-3">
                                 
                                    <span class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No</span>
                                </div>
                            </th>
                            <th class="px-6 py-3 text-center font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Judul Kerjasama</th>
                            <th class="px-6 py-3 text-center font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">MoU</th>
                            <th class="px-6 py-3 text-center font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">PKS</th>
                            <th class="px-6 py-3 text-center font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Berita Acara</th>
                            <th class="px-6 py-3 text-center font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                            @foreach($kerjasama as $kj)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                                <td class="px-4 sm:px-6 py-3.5">
                                    <div class="flex items-center gap-3">
                                        <div>
                                            <span class="block font-medium text-gray-700 break-words text-theme-sm dark:text-gray-400">{{ $loop->iteration}}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{$kj->judul}}</p>
                                </td>
                                   <td class="px-4 sm:px-6 py-3.5 text-center">
                                    @php
                                        $mou = $kj->documents->first(fn($d) => optional($d->template)->document_type == 'MoU');
                                    @endphp
                                    @if($mou)
                                        <a href="{{ route('documents.pdf', $mou->id) }}" class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400" title="Open MoU"><i class="fa-solid fa-file-pdf ml-1"></i></a>
                                    @else
                                        <a href="{{ url('/documents/mou/create') }}?judul_id={{ $kj->id }}" class="text-indigo-500" title="Buat MoU">+ Buat</a>
                                    @endif
                                </td>

                                <td class="px-4 sm:px-6 py-3.5 text-center">
                                    @php
                                        $pks = $kj->documents->first(fn($d) => optional($d->template)->document_type == 'PKS');
                                    @endphp
                                    @if($pks)
                                        <a href="{{ route('documents.pdf', $pks->id) }}" class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400" title="Open PKS"><i class="fa-solid fa-file-pdf ml-1"></i></a>
                                    @else
                                        <a href="{{ url('/documents/pks/create') }}?judul_id={{ $kj->id }}" class="text-indigo-500" title="Buat PKS">+ Buat</a>
                                    @endif
                                </td>

                                <td class="px-4 sm:px-6 py-3.5 text-center">
                                    @php
                                        $ba = $kj->documents->first(fn($d) => optional($d->template)->document_type == 'Berita Acara');
                                    @endphp
                                    @if($ba)
                                        <a href="{{ route('documents.pdf', $ba->id) }}" class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400" title="Open Berita Acara">Berita <i class="fa-solid fa-file-pdf ml-1"></i></a>
                                    @else
                                        <a href="{{ url('/documents/berita-acara/create') }}?judul_id={{ $kj->id }}" class="text-indigo-500" title="Buat Berita Acara">+ Buat</a>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <a href="{{ route('judul-kerjasama.show',$kj->id) }}" class="flex justify-center text-center text-gray-700 cursor-pointer size-5 hover:text-indigo-500 dark:text-gray-400 dark:hover:text-indigo-400" title="Lihat detail">
                                        <svg class="inline h-7 w-7 flex justify-center" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function () {
            const table = new DataTable('#tableKerjasama', {
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
            const searchInput = document.querySelector('#tableKerjasama_filter input');
            if (searchInput) {
                searchInput.classList.add('rounded-md', 'border', 'px-3', 'py-2');
            }
            const lengthSelect = document.querySelector('#tableKerjasama_length select');
            if (lengthSelect) {
                lengthSelect.classList.add('rounded-md', 'border', 'px-2', 'py-1');
            }

            // style pagination buttons
            const observer = new MutationObserver(() => {
                document.querySelectorAll('#tableKerjasama_paginate button').forEach(btn => {
                    btn.classList.add('rounded-md', 'border', 'px-2', 'py-1', 'mx-1');
                });
            });
            const paginateElem = document.querySelector('#tableKerjasama_paginate');
            if (paginateElem) observer.observe(paginateElem, { childList: true, subtree: true });
        });
    </script>
@endsection
