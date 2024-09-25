<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <style>
        .loader {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #3498db;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 10px;
            vertical-align: middle;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
        <h1 class="text-2xl font-bold mb-6 text-center">QR Code Generator</h1>
        
        <form id="qrForm" action="{{ route('qrcode.generate') }}" method="POST" class="mb-6">
            @csrf
            <div class="mb-4">
                <label for="phone_number" class="block text-gray-700 text-sm font-bold mb-2">Phone Number:</label>
                <input type="tel" name="phone_number" id="phone_number" required 
                       class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                       placeholder="Enter phone number">
            </div>
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                Generate QR Code
            </button>
        </form>

        @if(isset($qrCode))
            <div class="text-center">
                <h2 class="text-xl font-semibold mb-2">Generated QR Code</h2>
                <p class="mb-4">Phone number: {{ $phoneNumber }}</p>
                <div class="flex justify-center mb-4">
                    {!! $qrCode !!}
                </div>
                <form action="{{ route('qrcode.download-pdf') }}" method="POST" class="mb-4">
                    @csrf
                    <input type="hidden" name="phone_number" value="{{ $phoneNumber }}">
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                        Download PDF
                    </button>
                </form>
                <div class="mb-4">
                    <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email Address:</label>
                    <input type="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter email address">
                </div>
                <button onclick="sendEmail()" id="sendEmailBtn" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                    Send PDF via Email
                </button>
            </div>
        @endif
    </div>

    <script>
        function sendEmail() {
            const email = document.getElementById('email').value;
            const phoneNumber = '{{ $phoneNumber ?? '' }}';
            const button = document.getElementById('sendEmailBtn');

            if (!email) {
                showNotification('Please enter an email address', 'error');
                return;
            }

            // Show loader
            button.innerHTML = '<span class="loader"></span>Sending...';
            button.disabled = true;

            axios.post('{{ route('qrcode.send-email') }}', {
                email: email,
                phone_number: phoneNumber
            })
            .then(function (response) {
                showNotification(response.data.message, 'success');
            })
            .catch(function (error) {
                console.error('Error:', error.response.data);
                showNotification('An error occurred while sending the email: ' + error.response.data.message, 'error');
            })
            .finally(function () {
                // Hide loader and restore button text
                button.innerHTML = 'Send PDF via Email';
                button.disabled = false;
            });
        }

        function showNotification(message, type) {
            Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: type === 'success' ? "#4CAF50" : "#F44336",
            }).showToast();
        }
    </script>
</body>
</html>