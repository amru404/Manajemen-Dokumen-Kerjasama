@extends('/layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Detail Judul Kerjasama"/>

    <div class="space-y-6 md:space-y-7 mt-4">
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
@endsection