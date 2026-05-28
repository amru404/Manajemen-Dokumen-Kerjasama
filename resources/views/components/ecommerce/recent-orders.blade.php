@props(['documents' => []])

@php
    $defaultDocuments = [
        [
            'title' => 'Dokumen Kerjasama A',
            'type' => 'MoU',
            'created_at' => '2024-01-15',
            'status' => 'Aktif',
        ],
        [
            'title' => 'Dokumen Kerjasama B',
            'type' => 'PKS',
            'created_at' => '2024-01-10',
            'status' => 'Draft',
        ],
        [
            'title' => 'Dokumen Kerjasama C',
            'type' => 'Berita Acara',
            'created_at' => '2024-01-05',
            'status' => 'Selesai',
        ],
    ];

    $documentsList = !empty($documents) ? $documents : $defaultDocuments;
    
    

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
            'dibatalkan' => $baseClasses . ' bg-red-50 text-red-600 dark:bg-red-500/15 dark:text-red-500',
            'akan_expired' => $baseClasses . ' bg-yellow-50 text-yellow-600 dark:bg-yellow-500/15 dark:text-yellow-400',
            'expired' => $baseClasses . ' bg-gray-100 text-gray-500 dark:bg-gray-600/20 dark:text-gray-400',
            default => $baseClasses . ' bg-gray-50 text-gray-600 dark:bg-gray-500/15 dark:text-gray-400',
        };
    };
    
@endphp

