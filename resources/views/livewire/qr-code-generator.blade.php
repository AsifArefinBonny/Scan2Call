<div class="bg-white p-8 rounded-lg shadow-md w-full max-w-md">
    <h1 class="text-2xl font-bold mb-6 text-center">QR Code Generator</h1>
    
    <form wire:submit.prevent="generateQrCode" class="mb-6">
        <div class="mb-4">
            <label for="phoneNumber" class="block text-gray-700 text-sm font-bold mb-2">Phone Number:</label>
            <input type="tel" wire:model.defer="phoneNumber" id="phoneNumber" required 
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                   placeholder="Enter phone number">
            @error('phoneNumber') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>
        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
            Generate QR Code
        </button>
    </form>

    @if($qrCode)
        <div class="text-center">
            <h2 class="text-xl font-semibold mb-2">Generated QR Code</h2>
            <p class="mb-4">Phone number: {{ $phoneNumber }}</p>
            <div class="flex justify-center mb-4">
                {!! $qrCode !!}
            </div>
            <button wire:click="downloadPdf" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full mb-4">
                Download PDF
            </button>
            <div class="mb-4">
                <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email Address:</label>
                <input type="email" wire:model.defer="email" id="email" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" placeholder="Enter email address">
                @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <button wire:click="sendEmail" class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                Send PDF via Email
            </button>
        </div>
    @endif

    @if($message)
        <div class="mt-4 p-4 bg-blue-100 text-blue-700 rounded">
            {{ $message }}
        </div>
    @endif
</div>