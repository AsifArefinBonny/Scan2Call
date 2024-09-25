<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use PDF;
use Illuminate\Support\Facades\Mail;
use App\Mail\QrCodePdf;

class QrCodeController extends Controller
{
    public function index()
    {
        return view('qrcode');
    }

    public function generate(Request $request)
    {
        $request->validate([
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ]);

        $phoneNumber = $request->input('phone_number');
        $qrCode = QrCode::size(300)->generate("tel:$phoneNumber");

        return view('qrcode', compact('qrCode', 'phoneNumber'));
    }

    public function downloadPdf(Request $request)
    {
        $phoneNumber = $request->input('phone_number');

        // Generate the QR code image
        $qrCodeImage = QrCode::format('png')
                            ->size(300)
                            ->generate("tel:$phoneNumber");

        // Create the PDF with the QR code image
        $pdf = PDF::loadView('pdf.qrcode', compact('qrCodeImage', 'phoneNumber'));

        return $pdf->download('qrcode.pdf');
    }

    public function sendEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ]);

        $email = $request->input('email');
        $phoneNumber = $request->input('phone_number');

        // Generate the QR code image
        $qrCodeImage = QrCode::format('png')
                            ->size(300)
                            ->generate("tel:$phoneNumber");

        $pdf = PDF::loadView('pdf.qrcode', compact('qrCodeImage', 'phoneNumber'));

        try {
            Mail::to($email)->send(new QrCodePdf($pdf, $phoneNumber));
            return response()->json(['message' => 'Email sent successfully']);
        } catch (\Exception $e) {
            \Log::error('Email sending failed: ' . $e->getMessage());
            return response()->json(['message' => 'An error occurred while sending the email: ' . $e->getMessage()], 500);
        }
    }
}