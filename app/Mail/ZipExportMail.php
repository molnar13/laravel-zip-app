<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ZipExportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfContent;

    // Itt kapja meg a generált PDF-et a Controllertől
    public function __construct($pdfContent)
    {
        $this->pdfContent = $pdfContent;
    }

    // Ez a régi, de legmegbízhatóbb módja a csatolásnak Laravelben
    public function build()
    {
        return $this->subject('Irányítószám Szűrt Export (PDF)')
                    ->view('emails.zip_export')
                    ->attachData($this->pdfContent, 'iranyitoszamok_szurt.pdf', [
                        'mime' => 'application/pdf',
                    ]);
    }
}