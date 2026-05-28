@extends('/layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Detail Judul Kerjasama"/>

    <div class="space-y-6 md:space-y-7 mt-6">
        <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-medium dark:text-gray-200">{{ $judul_kerjasama->judul }}</h3>
                <a href="{{ route('judul-kerjasama') }}" class="mb-4 bg-indigo-500 hover:bg-indigo-700 rounded-lg text-white p-2 text-sm">Kembali</a>
            </div>

            <div>
                <dl>
                    <div class="mb-3">
                        <dt class="text-sm font-medium text-gray-500">Mitra</dt>
                        <dd class="mt-1 text-theme-sm text-gray-700 dark:text-gray-400">{{ $judul_kerjasama->mitra ? $judul_kerjasama->mitra->nama : '-' }}</dd>
                    </div>

                    <div class="mb-3">
                        <dt class="text-sm font-medium text-gray-500">Judul</dt>
                        <dd class="mt-1 text-theme-sm text-gray-700 dark:text-gray-400">{{ $judul_kerjasama->judul }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    <div class="space-y-6 md:space-y-7 mt-6">
        <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="max-w-full overflow-x-auto">
                <table id="tableDocuments" class="table-fixed min-w-full divide-y divide-gray-200 stripe hover w-full text-theme-xs dark:text-gray-400 text-start">
                    <thead class="px-6 py-3.5 border-t border-gray-100 border-y bg-gray-50 dark:border-white/[0.05] dark:bg-gray-900">
                        <tr>
                            <th class="px-6 py-3 font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">No</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Judul</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Jenis Dokumen</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Start</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">End</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Document</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Status</th>
                            <th class="px-6 py-3 break-words text-wrap font-medium text-gray-500 sm:px-6 text-theme-xs dark:text-gray-400 text-start">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documents as $d)
                            <tr class="border-b border-gray-100 dark:border-white/[0.05]">
                                <td class="px-4 sm:px-6 py-3.5">
                                    <span class="block font-medium text-gray-700 break-words text-theme-sm dark:text-gray-400">{{ $loop->iteration + ($documents->currentPage()-1) * $documents->perPage() }}</span>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ optional($d->judul)->judul ?? '—' }}</p>
                                </td>
                                <td class="px-4 sm:px-6 py-3.5 text-center">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ optional($d->template)->document_type ?? '—' }}</p>
                                </td>
                                
                                <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $d->start_date ?? '—' }}</p>
                                </td>
                                   <td class="px-4 sm:px-6 py-3.5">
                                    <p class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400">{{ $d->end_date ?? '—' }}</p>
                                </td>
                                
                            <td class="px-4 sm:px-6 py-3.5 text-center">
                                    @if($d->source === 'upload' || $d->content_html)
                                        <a href="{{ route('documents.pdf', $d->id) }}" class="text-gray-700 overflow-hidden text-ellipsis text-theme-sm dark:text-gray-400" title="Open Document"><i class="fa-solid fa-file-pdf ml-1"></i></a>
                                    @else
                                        <span class="text-gray-500">—</span>
                                    @endif
                                </td>
                                 <td class="px-4 sm:px-6 py-3.5">
                                    <span class="inline-block px-2 py-1 rounded text-xs font-semibold  {{ match($d->status) {'draft' => 'bg-gray-50 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400',
                                    'submitted' => 'bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
                                    'approved' => 'bg-indigo-50 text-indigo-600 dark:bg-indigo-500/15 dark:text-indigo-400',
                                    'published' => 'bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
                                    'aktif' => 'bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-400',
                                    'selesai' => 'bg-emerald-50 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-400',
                                    'denied' => 'bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
                                    'akan_expired' => 'bg-yellow-50 text-yellow-600 dark:bg-yellow-500/15 dark:text-yellow-400',
                                    'expired' => 'bg-gray-100 text-gray-500 dark:bg-gray-600/20 dark:text-gray-400',
                                    } }}">{{ $d->status }}</span>
                                </td>

                                <td class="px-4 sm:px-6 py-3.5">
                                    <div class="flex items-center gap-2">
                                        <a href="{{ route('documents.edit', $d->id) }}" class="flex justify-center text-gray-700 size-5 hover:text-green-500" title="Edit">
                                            <svg class="inline h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5M18.5 2.5a2.121 2.121 0 113 3L12 15l-4 1 1-4 9.5-9.5z"/></svg>
                                        </a>

                                        <form action="{{ route('documents.destroy', $d->id) }}" method="POST" onsubmit="return confirm('Hapus dokumen ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="flex justify-center text-gray-700 size-5 hover:text-red-500" title="Delete">
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


@endsection