<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { text-align: center; }
        .qr-code { margin-top: 20px; }
        .qr-code img { max-width: 100%; }
    </style>
</head>
<body>
    <div class="container">
        <h1>QR Code for Phone Number</h1>
        <p>Scan this QR code to call: {{ $phoneNumber }}</p>
        <div class="qr-code">
            <img src="data:image/png;base64,{{ base64_encode($qrCodeImage) }}" alt="QR Code">
        </div>
    </div>
</body>
</html>