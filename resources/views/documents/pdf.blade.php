<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ optional($document->judul)->judul ?? 'Document' }}</title>

    <style>
        /* Basic A4 margins */
        @page { margin: 3cm 2.5cm 3cm 2.5cm; }
        body { font-family: "Times New Roman", serif; font-size: 12pt; line-height: 1.6; color: #000; }

        /* Wrapper */
        .document-wrapper { text-align: justify; }

        /* Head / metadata */
        .doc-header { text-align: center; margin-bottom: 18pt; }
        .doc-meta { margin-bottom: 12pt; }
        .doc-meta td { padding: 3pt 6pt; vertical-align: top; }

        /* Title */
        .document-wrapper h1 { font-size: 14pt; font-weight: bold; text-align: center; margin-bottom: 18pt; }
        .document-wrapper h2 { font-size: 12pt; font-weight: bold; margin: 18pt 0 12pt 0; text-transform: uppercase; }

        /* Paragraphs and lists */
        .document-wrapper p { margin: 0 0 12pt 0; }
        .document-wrapper ul, .document-wrapper ol { margin-left: 24pt; margin-bottom: 12pt; }

        /* Table (dompdf friendly) */
        .document-wrapper table { width: 100%; border-collapse: collapse; margin-top: 12pt; }
        .document-wrapper table th, .document-wrapper table td { padding: 6pt; vertical-align: top; border: 1px solid #000; }
        .document-wrapper table th { font-weight: bold; }
        /* Avoid page breaks inside table rows */
        .document-wrapper table, .document-wrapper tr, .document-wrapper td { page-break-inside: avoid; }

        /* Signature block */
        .signature-block { text-align: center; margin-top: 48pt; }
        .signature-block img { height: 80px; margin: 8pt 0; }

        /* Small utility */
        .muted { color: #555; font-size: 10pt; }

        /* Ensure headings don't get orphaned */
        h1, h2 { page-break-after: avoid; page-break-before: avoid; }

        /* Avoid flex, grid, absolute positioning */
    </style>
</head>
<body>
    <div class="document-wrapper">
        <div class="doc-header">
            <h1>{{ optional($document->judul)->judul ?? '—' }}</h1>
            <table class="doc-meta" style="width:auto; margin:0 auto;">
                <tr>
                    <td><strong>Jenis</strong></td>
                    <td>: {{ $document->jenis_dokumen ?? optional($document->template)->document_type ?? '—' }}</td>
                </tr>
                <tr>
                    <td><strong>Nomor</strong></td>
                    <td>: {{ $document->nomor_dokumen ?? '—' }}</td>
                </tr>
                <tr>
                    <td><strong>Tanggal</strong></td>
                    <td>: {{ $document->tanggal_dokumen ? $document->tanggal_dokumen->format('d M Y') : '—' }}</td>
                </tr>
                @if($document->berlaku_mulai || $document->berlaku_selesai)
                    <tr>
                        <td><strong>Berlaku</strong></td>
                        <td>: {{ $document->berlaku_mulai ? $document->berlaku_mulai->format('d M Y') : '—' }} — {{ $document->berlaku_selesai ? $document->berlaku_selesai->format('d M Y') : '—' }}</td>
                    </tr>
                @endif
            </table>
        </div>

        {{-- CKEditor content (render raw HTML) --}}
        <div class="document-body">
            {!! $document->content_html !!}
        </div>

        {{-- Optional signature block --}}
        <div class="signature-block">
            @if(optional($document->pihak1)->nama || optional($document->pihak2)->nama)
                <div style="display:block; margin-top:36pt;">
                    <div style="display:inline-block; width:45%; text-align:center; vertical-align:top;">
                        <div class="muted">Pihak Pertama</div>
                        <div style="height:60px;"></div>
                        <div><strong>{{ optional($document->pihak1)->nama ?? '—' }}</strong></div>
                    </div>
                    <div style="display:inline-block; width:5%;"></div>
                    <div style="display:inline-block; width:45%; text-align:center; vertical-align:top;">
                        <div class="muted">Pihak Kedua</div>
                        <div style="height:60px;"></div>
                        <div><strong>{{ optional($document->pihak2)->nama ?? '—' }}</strong></div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</body>
</html>