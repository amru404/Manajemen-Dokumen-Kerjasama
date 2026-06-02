<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ optional($document->judul)->judul ?? 'Document' }}
            -
            {{$document->mitra}}</title>

        <style>
            .document-body p{
                margin:0 0 6px !important;
            }

            .document-body ul{
                margin-top:0 !important;
                margin-bottom:6px !important;
            }

            .document-body li p{
                margin:0 !important;
            }

            .document-body p:empty{
                display:none !important;
            }
            
            body {
                font-family: "Times New Roman", serif;
                font-size: 14pt;
                line-height: 1;
                color: #000;
                text-align: justify;
                padding-top: 95px;
            }

            
            h1 {
                font-size: 21pt;
                font-weight: bold;
                text-align: center;
                margin-bottom: 10px;
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

            
            p {
                margin: 0 0 10px;
            }

            
            ol {
                margin-left: -10px;
                margin-bottom: 12px;
            }

            ol li {
                margin-bottom: 6px;
            }

           ol {
                list-style-type: decimal !important;
                list-style: decimal outside !important;
                padding-left: 20px;
                margin: 0 0 12px 0;
            }

            ul {
                list-style-type: disc !important;
                list-style: disc outside !important;
                padding-left: 20px;
                margin: 0 0 12px 0;
            }

            ol[type="a"],
            ol[type="A"] {
                list-style-type: lower-alpha !important;
            }

            ol[type="1"] {
                list-style-type: decimal !important;
            }

            li {
                display: list-item !important;
                margin-bottom: 6px;
            }

            ul li {
                margin-bottom: 6px;
            }

        
            table {
                width: 120%;
                border-collapse: collapse;
                margin-left:-40px;
                font-size: 10pt;
            }

            table th,
            table td {
                border: 1px solid #000;
                padding: 6px 8px;
                vertical-align: top;
                min-width: 50px;
            }

           table th {
                text-align: center !important;
                vertical-align: middle;
                min-width: 50px;

            }

            table p {
                margin: 0;
            }

            table ul,
            table ol {
                margin: 0;
                padding-left: 18px;
            }

            table li {
                margin-bottom: 2px;
            }

            .pasal {
                font-weight: bold;
                text-transform: uppercase;
                text-align: center;
                margin-top: 30px;
                margin-bottom: 12px;
                text-align: center;

            }

            
            .identitas {
                margin-bottom: 16px;
            }

            .identitas strong {
                display: inline-block;
                width: 100px;
            }

            .payment-block {
                margin-left: 20px;
            }

            .payment-block p {
                margin-bottom: 6px;
            }

            
            .bank-info {
                margin-top: 10px;
                margin-left: 20px;
            }


            .signature {
                margin-top: 50px;
                width: 100%;
            }

            .pihak1-nama, .pihak2-nama {
                font-size:11pt;
                
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

            
            .page-break {
                page-break-before: always;
            }

            
            .small-text {
                font-size: 10pt;
            }

            .cover {
                padding: 100px;
                position: relative;
            }
            .cover-sidebar-mask{
                position: absolute;
                top: 10;
                left: -63px;
                width: 90px;
                height: 700px;
                background: white;
                z-index: 10000;
            }

            .coveratas {
                position: fixed;
                top: -44px;
                margin-bottom: 100px;
                left: -44px;
                height: 180.5px;
                width: 790px;
            }
            .coverbawah {
                position: fixed;
                bottom: 0;
                left: -33;
                top: 805px;
                height: 270px;
                width: 790px;
            }
            
            .desc{
                color: #2f6e85; 
                margin-left: -67px;
                margin-top:60px;
                padding: 0 -20px;
                text-transform: none;
                text-align: justify; 
                font-size: 25px; 
                font-weight:500; 
                line-height: 1.5;
                width: 650px;
            }
            
            .atas{
                position: fixed;
                top:-43px;
                left:-34;
                right:0;
                height:105px;
                width:790px;
            }

            .samping{
                position: fixed;
                top:48;
                left:-47px;
                width:73px;
                bottom:0;
                height: 900px;
            }

            
            .bawah{
                position: fixed;
                bottom:-20;
                left:-45px;
                right:0;
                height:90px;
                width: 790px;
            }

            .document-wrapper{
                padding: 0px 60px 60px 60px;
                margin-top: -20px;
                overflow-wrap: break-word;
                word-break: break-word;
            }

             .doc-header h1{
                font-size: 18pt;
                font-weight: bold;
                text-align: center;
                margin-bottom: 20px;
                text-transform: uppercase;
            }

            .document-body > *:first-child{
                margin-top:0 !important;
                padding-top:0 !important;
            }
            .document-body{
                width: 100%;
            }

            .ttd{
                max-width: 180px;   
                max-height: 80px;
                object-fit: contain;
                margin: 10px 0;
            }
            /* Avoid flex, grid, absolute positioning */
        </style>
    </head>
    <body>
        <div class="cover">
            <div class="cover-sidebar-mask"></div>
            <img class="coveratas" src="{{ $images['coverAtas'] }}" alt="Logo"/>
            <h1 style="margin-top:80px; white-space: nowrap;">
                {{$document->template->document_type ?? '—'}} 
                <br>
                Buku Tahunan Sekolah
                <br>
                Nomor:{{ $document->nomor_document ?? '—' }}
            </h1>
            <h1 style="margin-top:35px; white-space: nowrap;  text-transform: none;">
                Antara
                <br>
                    {{$document->pihak1->nama}}
                <br>
                    Dengan
                <br>
                <span style="font-size:22px">
                    {{$document->pihak2->nama}}
                </span>
            </h1>

            <div class="desc">
                Strategy and Consulting, Branding and Design, Creative Content Production,
                Digital Marketing, Web and App Development, Emerging Technologies, Experiential
                Marketing, Data and Analytics, E-commerce Solutions, Public Relations and
                Influencer Marketing, Media Printing.
            </div>
            
            <img class="coverbawah" src="{{ $images['coverBawah'] }}"alt="Logo"/>
        </div>
        
        
        <img class="atas" src="{{$images['atas']}}"alt="Logo"/>
        <img class="samping" src="{{ $images['samping'] }}"alt="Logo"/>
        <img class="bawah" src="{{ $images['bawah'] }}"alt="Logo"/>
        
        <div class="page-break"></div>

        <div class="document-wrapper">
             <div class="doc-header">
            <div class="document-body">
                {!! $htmlTemplate !!}
            </div>

            
          <div class="signature-block">
            @php
                $hasPihak1 = !empty(optional($document->pihak1)->nama);
                $hasPihak2 = !empty(optional($document->pihak2)->nama);
                $jumlahPihak = ($hasPihak1 ? 1 : 0) + ($hasPihak2 ? 1 : 0);
            @endphp

            @if($jumlahPihak > 0)
                <div style="margin-top:36pt; text-align:right;">

                    @if($jumlahPihak == 1)

                        @if($hasPihak1)
                            <div style="display:inline-block; width:45%; text-align:center;">
                                <div>Pihak Pertama</div>
                                <img class="ttd" src="{{ $images['logoPihak1'] }}" alt="ttd">
                                <div>
                                    <strong>{{ $document->pihak1->nama }}</strong>
                                </div>
                            </div>
                        @endif

                        @if($hasPihak2)
                            <div style="display:inline-block; width:45%; text-align:center;">
                                <div>Pihak Kedua</div>
                                <img class="ttd" src="{{ $images['logoPihak2'] }}" alt="ttd">
                                <div>
                                    <strong>{{ $document->pihak2->nama }}</strong>
                                </div>
                            </div>
                        @endif

                    @else

                        <div style="display:inline-block; width:45%; text-align:center; vertical-align:top;">
                            <div>Pihak Pertama</div>
                            <img class="ttd" src="{{ $images['logoPihak1'] }}" alt="ttd">
                            <div class="pihak1-nama">
                                <strong>{{ $document->pihak1->nama }}</strong>
                            </div>
                        </div>

                        <div style="display:inline-block; width:5%;"></div>

                        <div style="display:inline-block; width:45%; text-align:center; vertical-align:top;">
                            <div>Pihak Kedua</div>
                            <img class="ttd" src="{{ $images['logoPihak2'] }}" alt="ttd">
                            <div class="pihak2-nama">
                                <strong>{{ $document->pihak2->nama }}</strong>
                            </div>
                        </div>

                    @endif

                </div>
            @endif
        </div>
        </div>
    </body>
</html>