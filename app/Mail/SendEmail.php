<?php

namespace App\Mail;

use App\Models\Document;
use App\Helpers\ReplaceHelper;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $document;

    /**
     * Create a new message instance.
     */
    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        // Jika source upload → attach file asli
        if (
            $this->document->source === 'upload' &&
            $this->document->file_path
        ) {

            $storagePath = storage_path(
                'app/public/' . $this->document->file_path
            );

            if (!file_exists($storagePath)) {
                throw new \Exception('File upload tidak ditemukan.');
            }

            return $this->view('email.document')
                ->subject(
                    optional($this->document->judul)->judul ?? 'Document'
                )
                ->attach($storagePath);
        }

        // Jika source generate → generate PDF dari HTML
        if (
            $this->document->source === 'generate' &&
            $this->document->content_html
        ) {

            try {

                $document = $this->document->load([
                    'judul',
                    'template',
                    'user',
                    'pihak1',
                    'pihak2'
                ]);

                $template = $document->content_html;

                // replace placeholder
                $htmlTemplate = ReplaceHelper::parse(
                    $template,
                    $document
                );

                // parsing image
                $images = collect([
                    'coverAtas'  => public_path('images/asset_dokumen/cover_atas.png'),
                    'coverBawah' => public_path('images/asset_dokumen/cover_bawah.png'),
                    'atas'       => public_path('images/asset_dokumen/atas.png'),
                    'bawah'      => public_path('images/asset_dokumen/bawah.png'),
                    'samping'    => public_path('images/asset_dokumen/samping.png'),
                    'logoPihak1' => $document->pihak1->logo
                        ? storage_path('app/public/' . $document->pihak1->logo)
                        : null,
                    'logoPihak2' => $document->pihak2->logo
                        ? storage_path('app/public/' . $document->pihak2->logo)
                        : null,
                ])->map(function ($path) {

                    if (!$path || !file_exists($path)) {
                        return null;
                    }

                    $mime = mime_content_type($path);

                    return 'data:' . $mime . ';base64,' .
                        base64_encode(file_get_contents($path));
                });

                // render html pdf
                $html = view('documents.pdf', compact(
                    'document',
                    'images',
                    'htmlTemplate'
                ))->render();

                // generate pdf
                $pdf = Pdf::loadHTML($html)
                    ->setPaper('a4', 'portrait')
                    ->setOptions([
                        'isHtml5ParserEnabled' => true,
                        'isRemoteEnabled' => true,
                    ]);

                $pdfContent = $pdf->output();

                if (!$pdfContent) {
                    throw new \Exception('Gagal generate PDF.');
                }

            } catch (\Exception $e) {

                \Log::error(
                    'Gagal generate document PDF ID ' .
                    $this->document->id .
                    ': ' . $e->getMessage()
                );

                throw $e;
            }

            $filename = (
                optional($document->judul)->judul ?? 'document'
            ) . '.pdf';

            $subject = optional($document->judul)->judul ?? 'Document';
            $body = ReplaceHelper::parse($template, $document);

            return $this->view('emails.send_email', [
                'subject' => $subject,
                'body' => $body,
            ])
            ->subject($subject)
            ->attachData($pdfContent, $filename, [
                'mime' => 'application/pdf',
            ]);
        }

        throw new \Exception(
            'Document tidak memiliki file atau content_html.'
        );
    }
}