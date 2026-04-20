<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ optional($document->judul)->judul ?? 'Document' }}</title>

    <style>
        /* Basic A4 margins */
       /* =========================
        GLOBAL DOCUMENT STYLE
        ========================= */
        body {
            font-family: "Times New Roman", serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #000;
            text-align: justify;
        }

        /* =========================
        HEADINGS
        ========================= */
        h1 {
            font-size: 16pt;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            text-transform: uppercase;
        }

        h2 {
            font-size: 13pt;
            font-weight: bold;
            margin-top: 24px;
            margin-bottom: 12px;
            text-transform: uppercase;
            text-align: center;
        }

        h3 {
            font-size: 12pt;
            font-weight: bold;
            margin-top: 16px;
            margin-bottom: 8px;
        }

        /* =========================
        PARAGRAPH
        ========================= */
        p {
            margin: 0 0 10px 0;
        }

        /* =========================
        LIST STYLE
        ========================= */
        ol {
            margin-left: 20px;
            margin-bottom: 12px;
        }

        ol li {
            margin-bottom: 6px;
        }

        ul {
            margin-left: 20px;
            margin-bottom: 12px;
        }

        ul li {
            margin-bottom: 6px;
        }

        /* =========================
        PASAL TITLE
        ========================= */
        .pasal {
            font-weight: bold;
            text-transform: uppercase;
            text-align: center;
            margin-top: 30px;
            margin-bottom: 12px;
            text-align: center;

        }

        /* =========================
        PIHAK INFO (IDENTITAS)
        ========================= */
        .identitas {
            margin-bottom: 16px;
        }

        .identitas strong {
            display: inline-block;
            width: 100px;
        }

        /* =========================
        PAYMENT SECTION
        ========================= */
        .payment-block {
            margin-left: 20px;
        }

        .payment-block p {
            margin-bottom: 6px;
        }

        /* =========================
        BANK INFO
        ========================= */
        .bank-info {
            margin-top: 10px;
            margin-left: 20px;
        }

        /* =========================
        SIGNATURE AREA
        ========================= */
        .signature {
            margin-top: 50px;
            width: 100%;
        }

        .signature table {
            width: 100%;
            border: none;
        }

        .signature td {
            width: 50%;
            text-align: center;
            vertical-align: top;
            padding-top: 40px;
        }

        /* =========================
        PAGE BREAK (FOR PDF)
        ========================= */
        .page-break {
            page-break-before: always;
        }

        /* =========================
        SMALL TEXT
        ========================= */
        .small-text {
            font-size: 10pt;
        }

        /* Avoid flex, grid, absolute positioning */
    </style>
</head>
<body>
<div class="cover">
    <img src="{{ asset('images/asset_dokumen/cover_atas.png') }}" height=100% width=100% alt="Logo" />

    <h1>{{ optional($document->judul)->judul ?? '—' }}</h1>
</div>


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