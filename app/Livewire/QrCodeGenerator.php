<?php

namespace App\Http\Livewire;

use Livewire\Component;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\QrCodePdf;

class QrCodeGenerator extends Component
{
    public $phoneNumber = '';
    public $email = '';
    public $qrCode = null;
    public $message = '';

    protected $rules = [
        'phoneNumber' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        'email' => 'nullable|email',
    ];

    public function render()
    {
        return view('livewire.qr-code-generator');
    }

    public function generateQrCode()
    {
        $this->validate(['phoneNumber' => $this->rules['phoneNumber']]);

        $this->qrCode = QrCode::size(300)->generate("tel:{$this->phoneNumber}");
    }

    public function downloadPdf()
    {
        $qrCodeImage = QrCode::format('png')
                            ->size(300)
                            ->generate("tel:{$this->phoneNumber}");

        $pdf = PDF::loadView('pdf.qrcode', [
            'qrCodeImage' => $qrCodeImage,
            'phoneNumber' => $this->phoneNumber
        ]);

        return response()->streamDownload(function() use ($pdf) {
            echo $pdf->output();
        }, 'qrcode.pdf');
    }

    public function sendEmail()
    {
        $this->validate();

        $qrCodeImage = QrCode::format('png')
                            ->size(300)
                            ->generate("tel:{$this->phoneNumber}");

        $pdf = PDF::loadView('pdf.qrcode', [
            'qrCodeImage' => $qrCodeImage,
            'phoneNumber' => $this->phoneNumber
        ]);

        try {
            Mail::to($this->email)->send(new QrCodePdf($pdf, $this->phoneNumber));
            $this->message = 'Email sent successfully';
        } catch (\Exception $e) {
            \Log::error('Email sending failed: ' . $e->getMessage());
            $this->message = 'An error occurred while sending the email: ' . $e->getMessage();
        }
    }
}