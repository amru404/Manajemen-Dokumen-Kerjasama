<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ optional($document->judul)->judul ?? 'Document' }}
            -
            {{$document->mitra}}</title>

        <style>
            
            body {
                font-family: "Times New Roman", serif;
                font-size: 12pt;
                line-height: 1.6;
                color: #000;
                text-align: justify;
            }

            
            h1 {
                font-size: 21pt;
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

            
            p {
                margin: 0 0 10px;
            }

            
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
                top: 100;
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
                margin-left: -20px;
                margin-top:60px;
                padding: 0 -20px;
                text-transform: none;
                text-align: justify; 
                font-size: 22px; 
                font-weight:500; 
                line-height: 1.5;
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
                padding: 50px;
                margin-top: 60px;
                padding-bottom:50px
            }

             .doc-header h1{
                font-size: 18pt;
                font-weight: bold;
                text-align: center;
                margin-bottom: 20px;
                text-transform: uppercase;
            }
            /* Avoid flex, grid, absolute positioning */
        </style>
    </head>
    <body>
        <div class="cover">
            <div class="cover-sidebar-mask"></div>
            <img class="coveratas" src="{{ $coverAtas }}" alt="Logo"/>
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
            
            <img class="coverbawah" src="{{ $coverBawah }}"alt="Logo"/>
        </div>


            <img class="atas" src="{{$atas}}"alt="Logo"/>
            <img class="samping" src="{{ $samping }}"alt="Logo"/>
            <img class="bawah" src="{{ $bawah }}"alt="Logo"/>


        <div class="document-wrapper" style="margin-top: 60px; padding-top: 50px;">
             <div class="doc-header
            <div class="document-body">
                {!! $document->content_html !!}
            </div>

            
            <div class="signature-block">
                @if(optional($document->pihak1)->nama || optional($document->pihak2)->nama)
                <div style="display:block; margin-top:36pt;">
                    <div
                        style="display:inline-block; width:45%; text-align:center; vertical-align:top;">
                        <div class="muted">Pihak Pertama</div>
                        <div style="height:60px;"></div>
                        <div>
                            <strong>{{ optional($document->pihak1)->nama ?? '—' }}</strong>
                        </div>
                    </div>
                    <div style="display:inline-block; width:5%;"></div>
                    <div
                        style="display:inline-block; width:45%; text-align:center; vertical-align:top;">
                        <div class="muted">Pihak Kedua</div>
                        <div style="height:60px;"></div>
                        <div>
                            <strong>{{ optional($document->pihak2)->nama ?? '—' }}</strong>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </body>
</html>