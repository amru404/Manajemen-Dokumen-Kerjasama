@extends('/layouts.app')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/@tailwindplus/elements@1" type="module"></script>
    <x-common.page-breadcrumb pageTitle="Dokumen - {{ $type }}" />

    <a href="{{ route('documents.' . $slug . '.create') }}" class="bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white p-2 text-sm">Add Document</a>

    <div class="space-y-6 md:space-y-7 mt-6">
        <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-gray-700 dark:bg-white/[0.03]">
          <div id="statusFilterContainer" class="inline-block">
            <el-dropdown class="inline-block">
                <button id="statusDropdownBtn" type="button" class="inline-flex w-full items-center justify-center gap-x-1.5 rounded-md bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-xs inset-ring-1 inset-ring-gray-300 hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-200 dark:inset-ring-gray-600">
                    <span id="selectedStatusText">Semua Status</span>
                    <svg viewBox="0 0 20 20" fill="currentColor" data-slot="icon" aria-hidden="true" class="-mr-1 size-5 text-gray-400">
                        <path d="M5.22 8.22a.75.75 0 0 1 1.06 0L10 11.94l3.72-3.72a.75.75 0 1 1 1.06 1.06l-4.25 4.25a.75.75 0 0 1-1.06 0L5.22 9.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" fill-rule="evenodd" />
                    </svg>
                </button>

                <el-menu anchor="bottom end" popover class="w-56 origin-top-right rounded-md bg-white shadow-lg outline-1 outline-black/5 transition transition-discrete [--anchor-gap:--spacing(2)] data-closed:scale-95 data-closed:transform data-closed:opacity-0 data-enter:duration-100 data-enter:ease-out data-leave:duration-75 data-leave:ease-in dark:bg-gray-800 dark:outline-gray-700">
                    <div class="py-1 space-y-1 px-1" id="statusDropdownItems">
                        <button type="button" data-value="" class="block w-full text-left px-4 py-2 text-sm rounded-md text-gray-700 hover:bg-gray-100 dark:text-gray-200 dark:hover:bg-gray-700">Semua Status</button>
                        
                        <button type="button" data-value="denied" class="block w-full text-left px-4 py-2 text-sm rounded-md text-gray-700 dark:text-gray-400 hover:bg-gray-100 text-gray-900">Denied</button>
                        
                        <button type="button" data-value="draft" class="block w-full text-left px-4 py-2 text-sm rounded-md text-gray-700 dark:text-gray-400 hover:bg-gray-100 text-gray-900">Draft</button>
                        
                        <button type="button" data-value="submitted" class="block w-full text-left px-4 py-2 text-sm rounded-md text-gray-700 dark:text-gray-400 hover:bg-gray-100 text-gray-900">Submitted</button>
                        
                        <button type="button" data-value="approved" class="block w-full text-left px-4 py-2 text-sm rounded-md text-gray-700 dark:text-gray-400 hover:bg-gray-100 text-gray-900">Approved</button>
                        
                        <button type="button" data-value="published" class="block w-full text-left px-4 py-2 text-sm rounded-md text-gray-700 dark:text-gray-400 hover:bg-gray-100 text-gray-900">Published</button>
                        
                        <button type="button" data-value="akan_expired" class="block w-full text-left px-4 py-2 text-sm rounded-md text-gray-700 dark:text-gray-400 hover:bg-gray-100 text-gray-900">Akan Expired</button>
                        
                        <button type="button" data-value="expired" class="block w-full text-left px-4 py-2 text-sm rounded-md text-gray-700 dark:text-gray-400 hover:bg-gray-100 text-gray-900">Expired</button>
                    </div>
                </el-menu>
            </el-dropdown>
        </div>

            <div class="max-w-full overflow-x-auto">
                <table id="tableDocuments" class="min-w-full whitespace-normal divide-y divide-gray-200 stripe hover w-full text-theme-xs dark:text-gray-400 text-start text-center">
                    <thead class="px-6 py-3.5 border-t border-gray-100 border-y bg-gray-50 dark:border-gray-700 dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">No</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Judul</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Mitra Pihak 1</th>
                             <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Mitra Pihak 2</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Start</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">End</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Document</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Send Email</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Status</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $d)
                            <tr class="border-b border-gray-100 dark:border-gray-700">
                                <td class="px-4 sm:px-6 py-3.5">
                                    <span class="block font-medium text-gray-700 break-words text-theme-sm dark:text-gray-400">{{ $loop->iteration + ($documents->currentPage()-1) * $documents->perPage() }}</span>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5 whitespace-normal break-words">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ optional($d->judul)->judul ?? '—' }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5 whitespace-normal break-words">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ optional($pihak1)->nama ?? '—' }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5 whitespace-normal break-words">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ optional($pihak2)->nama ?? '—' }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5 whitespace-normal break-words">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $d->start_date ?? '—' }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5 whitespace-normal break-words">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $d->end_date ?? '—' }}</p>
                                </td>
                                
                                <td class="px-4 sm:px-6 py-3.5 text-center whitespace-normal break-words">
                                    @if($d->source === 'upload' || $d->content_html)
                                        <a href="{{ route('documents.pdf', $d->id) }}" class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400" title="Open Document"><i class="fa-solid fa-file-pdf ml-1"></i></a>
                                    @else
                                        <span class="text-gray-500">—</span>
                                    @endif
                                </td>
                                <td class="px-4 sm:px-6 py-3.5 whitespace-normal break-words">
                                    @if($d->status == 'approved' || $d->status == 'published')
                                        <a href="javascript:void(0)" 
                                           class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400 cursor-pointer" 
                                           title="Send Email"
                                           onclick="confirmSendEmail('{{ $d->id }}', '{{ $d->status }}')">
                                            <i class="fa-solid fa-paper-plane ml-1"></i>
                                        </a>
                                    @else
                                    -
                                    @endif
                                </td>

                                <td class="px-4 sm:px-6 py-3.5 text-center whitespace-normal break-words">
                                    <span class="inline-block px-2 py-1 rounded text-xs font-semibold {{ match($d->status) {'draft' => 'bg-gray-50 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400',
                                    'submitted' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                                    'approved' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/15 dark:text-indigo-400',
                                    'published' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                                    'aktif' => 'bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-400',
                                    'selesai' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-400',
                                    'denied' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                                    'akan_expired' => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-500/15 dark:text-yellow-400',
                                    'expired' => 'bg-gray-100 text-gray-500 dark:bg-gray-600/20 dark:text-gray-400',
                                    } }}">
                                        
                                        {{ $d->status }}
                                    </span>
                                </td>
                              
                                <td class="px-4 sm:px-6 py-3.5 whitespace-normal break-words">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('documents.show', $d->id) }}" class="flex justify-center text-gray-700 size-5 hover:text-indigo-500" title="View">
                                            <svg class="inline h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                        </a>
                                        <a href="{{ route('documents.edit', $d->id) }}" class="flex justify-center text-gray-700 size-5 hover:text-green-500" title="Edit">
                                            <svg class="inline h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        </a>

                                        <form id="deleteForm-{{ $d->id }}" action="{{ route('documents.destroy', $d->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="flex justify-center text-gray-700 size-5 hover:text-red-500" title="Delete" onclick="confirmDelete('{{ $d->id }}');">
                                                <svg class="inline h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m2 0V5a2 2 0 00-2-2h-3.5"/></svg>
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
                {{ $documents->links() }}
            </div>
        </div>
    </div>

    <script>
        function confirmSendEmail(docId, status) {
            const isPublished = status === 'published';
            
            Swal.fire({
                title: isPublished ? 'Kirim Ulang Email?' : 'Kirim Email?',
                text: isPublished ? 'Dokumen akan dikirim ulang ke pihak terkait' : 'Dokumen akan dikirim ke pihak terkait',
                icon: 'question',
                showCancelButton: true,
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, kirim',
                reverseButtons: false
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `/documents/${docId}/send-email`;
                }
            });
        }

        function confirmDelete(docId) {
            Swal.fire({
                title: 'Hapus Dokumen?',
                text: 'Dokumen ini akan dihapus secara permanen',
                icon: 'warning',
                showCancelButton: true,
                cancelButtonText: 'Batal',
                confirmButtonText: 'Ya, hapus',
                confirmButtonColor: '#ef4444',
                reverseButtons: false
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm-' + docId).submit();
                }
            });
        }

        document.addEventListener('DOMContentLoaded', function () {
        const table = new DataTable('#tableDocuments', {
            responsive: true,
            autoWidth: false,
            pageLength: 10,
            lengthMenu: [5, 10, 25, 50],
            order: [[0, 'asc']],
            columnDefs: [
                { orderable: false, targets: [-1, 6, 7] },
                { responsivePriority: 1, targets: [0, 1, 8, 9] },
                { responsivePriority: 2, targets: [2, 3] },
                { responsivePriority: 3, targets: [4, 5] },
                { responsivePriority: 4, targets: [6, 7] }
            ],
            dom: "<'flex flex-wrap items-center gap-3 justify-between mb-4 dark:text-gray-200 dark:border-gray-200'<'flex flex-wrap items-center gap-3 dark:text-gray-200 dark:border-gray-200'l><'flex flex-wrap items-center gap-3 dark:text-gray-200 dark:border-gray-200'f>><'w-full overflow-x-auto dark:text-gray-200 dark:border-gray-200't><'mt-4 flex items-center justify-between dark:text-gray-200 dark:border-gray-200'ip >",
            language: {
                search: "",
                searchPlaceholder: "Cari...",
                lengthMenu: "Tampilkan _MENU_ entri",
                paginate: { previous: "Sebelumnya", next: "Berikutnya" }
            },
            initComplete: function () {
                // Ambil container filter milikmu
                const filterContainer = document.getElementById('statusFilterContainer');
                // Cari input search bawaan DataTables
                const searchInput = document.querySelector('input[type="search"]');
                
                if (filterContainer && searchInput) {
                    // Cari wrapper flex terdekat dari input search (efek dari dom <'flex...f>)
                    const searchWrapper = searchInput.closest('.flex');
                    
                    if (searchWrapper) {
                        // Pindahkan seluruh container ke samping input search
                        searchWrapper.prepend(filterContainer);
                    }
                }
            }
        });

       // Status Filter Handler (Untuk custom el-dropdown)
        const dropdownItems = document.querySelectorAll('#statusDropdownItems button');
        const selectedStatusText = document.getElementById('selectedStatusText');

        dropdownItems.forEach(item => {
            item.addEventListener('click', function() {
                const filterValue = this.getAttribute('data-value');
                const textLabel = this.innerText;
                
                if (selectedStatusText) {
                    selectedStatusText.innerText = textLabel;
                }
                
                table.column(8).search(filterValue).draw();
                
                const parentMenu = this.closest('el-menu');
                if (parentMenu && typeof parentMenu.hidePopover === 'function') {
                    parentMenu.hidePopover();
                }
            });
        });

        // Styling input search agar tingginya sama (py-2) dan support dark mode
        const searchInput = document.querySelector('input[type="search"]');
        if (searchInput) {
            searchInput.classList.add(
                'rounded-md', 'border', 'border-gray-300', 'px-3', 'py-2', 'text-sm',
                'dark:bg-gray-800', 'dark:border-gray-700', 'dark:text-gray-200', 'focus:outline-none'
            );
        }
        
        const lengthSelect = document.querySelector('#tableDocuments_length select');
        if (lengthSelect) {
            lengthSelect.classList.add(
                'rounded-md', 'border', 'border-gray-300', 'px-2', 'py-1',
                'dark:bg-gray-800', 'dark:border-gray-700', 'dark:text-gray-200'
            );
        }

        
        const observer = new MutationObserver(() => {
            document.querySelectorAll('#tableDocuments_paginate .paginate_button').forEach(btn => {
                btn.classList.add(
                    'rounded-md', 'border', 'px-3', 'py-1', 'mx-1', 'text-sm', 
                    'dark:border-gray-700', 'dark:text-gray-200', 'hover:bg-gray-100', 'dark:hover:bg-gray-700'
                );
            });
        });
        const paginateElem = document.querySelector('#tableDocuments_paginate');
        if (paginateElem) observer.observe(paginateElem, { childList: true, subtree: true });
    });
</script>

    
@endsection

