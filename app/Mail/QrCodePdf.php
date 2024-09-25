<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QrCodePdf extends Mailable
{
    use Queueable, SerializesModels;

    public $pdf;
    public $phoneNumber;

    public function __construct($pdf, $phoneNumber)
    {
        $this->pdf = $pdf;
        $this->phoneNumber = $phoneNumber;
    }

    public function build()
    {
        return $this->subject('Print the PDF')
                    ->view('emails.qrcode')
                    ->attachData($this->pdf->output(), 'qrcode.pdf');
    }
}