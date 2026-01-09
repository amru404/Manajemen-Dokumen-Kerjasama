@extends('/layouts.app')

@section('content')
    <x-common.page-breadcrumb pageTitle="Template Detail" />

    <div class="space-y-6 mt-4">
        <div class="overflow-hidden p-5 rounded-2xl border border-gray-200 bg-white pt-4 dark:border-white/[0.05] dark:bg-white/[0.03]">
            <div class="mb-4">
                <h3 class="text-lg font-semibold">{{ $template->name }}</h3>
                <p class="text-sm text-gray-500">Tipe: {{ $template->document_type }} | Dibuat oleh: {{ optional($template->user)->name }}</p>
            </div>

            <div class="mb-4">
                <h4 class="font-medium">Description</h4>
                <p class="text-gray-700">{{ $template->description ?? '—' }}</p>
            </div>

            <div class="mb-4">
                <h4 class="font-medium">Template Format</h4>
                <div class="prose max-w-none mt-2">
                    {!! $template->content_html !!}
                </div>
            </div>

            <div class="flex gap-2 mt-4">
                <a href="{{ route('templates.edit', $template->id) }}" class="bg-green-500 hover:bg-green-700 text-white px-4 py-2 rounded">Edit</a>
                <a href="{{ route('templates.index') }}" class="inline-block border px-4 py-2 rounded">Back</a>
            </div>
        </div>
    </div>

@endsection
