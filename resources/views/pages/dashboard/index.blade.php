@extends('layouts.app')

@php
 $getStatusClasses = function($status) {
        $baseClasses = 'rounded-full px-2 py-0.5 text-theme-xs font-medium';

        return match($status) {
            'draft' => $baseClasses . ' bg-gray-50 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400',
            'submitted' => $baseClasses . ' bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
            'approved' => $baseClasses . ' bg-indigo-50 text-indigo-600 dark:bg-indigo-500/15 dark:text-indigo-400',
            'published' => $baseClasses . ' bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
            'aktif' => $baseClasses . ' bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-400',
            'selesai' => $baseClasses . ' bg-emerald-50 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-400',
            'denied' => $baseClasses . ' bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
            'akan_expired' => $baseClasses . ' bg-yellow-50 text-yellow-600 dark:bg-yellow-500/15 dark:text-yellow-400',
            'expired' => $baseClasses . ' bg-gray-100 text-gray-500 dark:bg-gray-600/20 dark:text-gray-400',
            default => $baseClasses . ' bg-gray-50 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400',
        };
    };
    

$getStatusClassesActivity = function($status) {
        $baseClasses = 'rounded-full px-2 py-0.5 text-theme-xs font-medium';

        return match($status) {
            'draft' => $baseClasses . ' bg-gray-50 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400',
            'submitted' => $baseClasses . ' bg-blue-50 text-blue-600 dark:bg-blue-500/15 dark:text-blue-400',
            'approved' => $baseClasses . ' bg-indigo-50 text-indigo-600 dark:bg-indigo-500/15 dark:text-indigo-400',
            'published' => $baseClasses . ' bg-success-50 text-success-600 dark:bg-success-500/15 dark:text-success-500',
            'created' => $baseClasses . ' bg-green-50 text-green-600 dark:bg-green-500/15 dark:text-green-400',
            'selesai' => $baseClasses . ' bg-emerald-50 text-emerald-600 dark:bg-emerald-500/15 dark:text-emerald-400',
            'denied' => $baseClasses . ' bg-error-50 text-error-600 dark:bg-error-500/15 dark:text-error-500',
            'akan_expired' => $baseClasses . ' bg-yellow-50 text-yellow-600 dark:bg-yellow-500/15 dark:text-yellow-400',
            'expired' => $baseClasses . ' bg-gray-100 text-gray-500 dark:bg-gray-600/20 dark:text-gray-400',
            default => $baseClasses . ' bg-gray-50 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400',
        };
    };
@endphp

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-12">
    <div class="col-span-12 space-y-12 xl:col-span-12">
        <x-ecommerce.ecommerce-metrics
            :mou-count="$mouCount"
            :pks-count="$pksCount"
            :berita-acara-count="$beritaAcaraCount"/>
    </div>
 
    <div class="col-span-12 xl:col-span-12">
        <div
            class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
            <div
                class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Total Document</h3>
                </div>

                <div class="flex items-center gap-3">
                    <a
                        href="{{ route('documents.mou') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                        See all
                    </a>
                </div>
            </div>
            <!-- chart total dokumen by status -->
                <div id="chart" class="w-full"></div>
        </div>

    <div class="col-span-12 xl:col-span-12">
        <div
            class="overflow-hidden rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6 mt-12">
            <div
                class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Dokumen Activity</h3>
                </div>

                <div class="flex items-center gap-3">
                    <a
                        href="{{ route('document-activities') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                        See all
                    </a>
                </div>
            </div>

            <div
                class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
                <table
                    class="w-full min-w-max text-sm text-left text-body table-auto rounded-base">
                    <thead
                        class="text-sm text-body bg-neutral-secondary-soft border-b rounded-base border-default">
                        <tr class="border-t border-gray-100 dark:border-gray-800">
                            <th
                                class="px-6 py-3 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">Judul Dokumen</th>
                            <th
                                class="px-6 py-3 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">Tipe</th>
                            <th
                                class="px-6 py-3 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">Mitra</th>
                            <th
                                class="px-6 py-3 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">Waktu</th>
                            <th
                                class="px-6 py-3 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">Dibuat Oleh</th>
                            <th
                                class="px-6 py-3 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($documentActivity as $document)
                        <tr class="bg-neutral-primary border-b border-default">
                            <td class="px-6 py-4 whitespace-nowrap align-top">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-[40px] w-[40px] shrink-0 overflow-hidden rounded-md bg-gray-100 flex items-center justify-center">
                                        <i class="fa-regular fa-file text-gray-600"></i>
                                    </div>
                                    <p class="font-medium text-heading text-gray-800 dark:text-white/90">
                                        @if(isset($document->document->judul->judul))
                                        {{ $document->document->judul->judul ?? 'N/A' }}
                                        @else
                                        {{ $document['title'] ?? 'N/A' }}
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap align-top text-gray-500 dark:text-gray-400">
                                @if(isset($document->document->template))
                                {{ $document->document->template->document_type ?? 'N/A' }}
                                @else
                                {{ $document['type'] ?? 'N/A' }}
                                @endif
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap align-top text-gray-500 dark:text-gray-400">
                                @if(isset($document->document->pihak1))
                                {{ $document->document->pihak1->nama ?? 'N/A' }}
                                @else
                                {{ $document['pihak1'] ?? 'N/A' }}
                                @endif
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap align-top text-gray-500 dark:text-gray-400">
                                @if(isset($document->created_at))
                                {{ $document->created_at->format('d M Y, H:i') ?? 'N/A' }}
                                @else
                                {{ $document['created_at'] ?? 'N/A' }}
                                @endif
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap align-top text-gray-500 dark:text-gray-400">
                                @if(isset($document->user->name))
                                {{ $document->user->name ?? 'N/A' }}
                                @else
                                {{ $document['user'] ?? 'N/A' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap align-top">
                                <span
                                    class="inline-flex items-center justify-center rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-400 {{ $getStatusClassesActivity($document->activity_type ?? 'Aktif') }}">
                                    @if(isset($document->activity_type))
                                    {{ $document->activity_type ?? 'Aktif' }}
                                    @else
                                    {{ $document['activity_type'] ?? 'Aktif' }}
                                    @endif
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div
            class="overflow-hidden mt-12 rounded-2xl border border-gray-200 bg-white px-4 pb-3 pt-4 dark:border-gray-800 dark:bg-white/[0.03] sm:px-6">
            <div
                class="flex flex-col gap-2 mb-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-white/90">Dokumen Akan Expired</h3>
                </div>

                <div class="flex items-center gap-3">
                    <a
                        href="{{ route('judul-kerjasama') }}"
                        class="inline-flex items-center gap-2 rounded-lg border border-gray-300 bg-white px-4 py-2.5 text-theme-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 hover:text-gray-800 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400 dark:hover:bg-white/[0.03] dark:hover:text-gray-200">
                        See all
                    </a>
                </div>
            </div>

            <div
                class="relative overflow-x-auto bg-neutral-primary-soft shadow-xs rounded-base border border-default">
                <table class="w-full min-w-max text-sm text-left text-body table-auto">
                    <thead
                        class="text-sm text-body bg-neutral-secondary-soft border-b rounded-base border-default">
                        <tr class="border-t border-gray-100 dark:border-gray-800">
                            <th
                                class="px-6 py-3 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">Judul Dokumen</th>
                            <th
                                class="px-6 py-3 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">Tipe</th>
                            <th
                                class="px-6 py-3 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">Mitra</th>
                            <th
                                class="px-6 py-3 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">Tanggal Expired</th>
                            <th
                                class="px-6 py-3 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">Dibuat Oleh</th>
                            <th
                                class="px-6 py-3 font-medium whitespace-nowrap text-gray-500 dark:text-gray-400">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($akanExpired as $document)
                        <tr class="bg-neutral-primary border-b border-default">
                            <td class="px-6 py-4 whitespace-nowrap align-top">
                                <div class="flex items-center gap-3">
                                    <div
                                        class="h-[40px] w-[40px] shrink-0 overflow-hidden rounded-md bg-gray-100 flex items-center justify-center">
                                        <i class="fa-regular fa-file text-gray-600"></i>
                                    </div>
                                    <p class="font-medium text-heading text-gray-800 dark:text-white/90">
                                        @if(isset($document->judul))
                                        {{ $document->judul->judul ?? 'N/A' }}
                                        @else
                                        {{ $document['title'] ?? 'N/A' }}
                                        @endif
                                    </p>
                                </div>
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap align-top text-gray-500 dark:text-gray-400">
                                @if(isset($document->template))
                                {{ $document->template->document_type ?? 'N/A' }}
                                @else
                                {{ $document['type'] ?? 'N/A' }}
                                @endif
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap align-top text-gray-500 dark:text-gray-400">
                                @if(isset($document->pihak1))
                                {{ $document->pihak1->nama ?? 'N/A' }}
                                @else
                                {{ $document['pihak1'] ?? 'N/A' }}
                                @endif
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap align-top text-gray-500 dark:text-gray-400">
                                @if(isset($document->end_date))
                                {{ $document->end_date ?? 'N/A' }}
                                @else
                                {{ $document['end_date'] ?? 'N/A' }}
                                @endif
                            </td>
                            <td
                                class="px-6 py-4 whitespace-nowrap align-top text-gray-500 dark:text-gray-400">
                                @if(isset($document->user->name))
                                {{ $document->user->name ?? 'N/A' }}
                                @else
                                {{ $document['user'] ?? 'N/A' }}
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap align-top">
                                <span
                                    class="inline-flex items-center justify-center rounded-full bg-gray-100 px-2 py-1 text-xs font-semibold text-gray-700 dark:bg-gray-800 dark:text-gray-400 {{ $getStatusClasses($document->status ?? 'Aktif') }}">
                                    @if(isset($document->status))
                                    {{ $document->status }}
                                    @else
                                    {{ $document['status'] ?? 'Aktif' }}
                                    @endif
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    var options = {
    series: [
        {
        name: 'Total Dokumen',
        data: [
            {{ $chartData['draft'] ?? 0 }},
            {{ $chartData['submitted'] ?? 0 }},
            {{ $chartData['approved'] ?? 0 }},
            {{ $chartData['published'] ?? 0 }},
            {{ $activeMouCount ?? 0 }},
            {{ $activePksCount ?? 0 }},
            {{ $chartData['denied'] ?? 0 }},
            {{ $chartData['akan_expired'] ?? 0 }},
            {{ $chartData['expired'] ?? 0 }}
        ]
        },
    ],
    chart: {
        height: 350,
        type: 'bar',
    },
    plotOptions: {
        bar: {
        borderRadius: 10,
        columnWidth: '50%',
        },
    },
    dataLabels: {
        enabled: false,
    },
    stroke: {
        width: 0,
    },
    grid: {
        row: {
        colors: ['#fff', '#f2f2f2'],
        },
    },
    xaxis: {
        labels: {
        rotate: -45,
        },
        categories: [
        'draft',
        'submitted',
        'approved',
        'published',
        'aktif MoU',
        'aktif PKS',
        'denied',
        'akan_expired',
        'expired',
        ],
        tickPlacement: 'on',
    },
    yaxis: {
        title: {
        text: '',
        },
    },
    fill: {
        type: 'gradient',
        gradient: {
        shade: 'light',
        type: 'horizontal',
        shadeIntensity: 0.25,
        gradientToColors: undefined,
        inverseColors: true,
        opacityFrom: 0.85,
        opacityTo: 0.85,
        stops: [50, 0, 100],
        },
    },
    }

    var chart = new ApexCharts(document.querySelector('#chart'), options)
    chart.render()
     setTimeout(() => {
        window.dispatchEvent(new Event('resize'));
    }, 100);
    
</script>
@endsection
