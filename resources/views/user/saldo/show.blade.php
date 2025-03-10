<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Topup Details') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-xl p-8">

                <!-- Topup Details Header -->
                <div class="mb-6 border-b pb-4">
                    <h3 class="text-2xl font-bold text-gray-800">{{ __('Topup Details') }}</h3>
                </div>

                <!-- Amount and Date Details -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Amount') }}</p>
                        <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($saldo->saldo, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">{{ __('Requested at') }}</p>
                        <p class="text-lg text-gray-700">{{ $saldo->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                <!-- Status of Topup -->
                <div class="mb-6">
                    <p class="text-sm text-gray-500">{{ __('Status') }}</p>
                    <span class="py-2 px-5 rounded-full text-white {{ $saldo->sudah_dibayar ? 'bg-green-500' : 'bg-orange-500' }} text-sm font-semibold">
                        {{ $saldo->sudah_dibayar ? __('SUCCESS') : __('Pending') }}
                    </span>
                </div>

                <!-- Payment Proof Section -->
                @if($saldo->proof)
                    <div class="mb-6">
                        <p class="text-sm text-gray-500">{{ __('Payment Proof') }}</p>
                        @php
                            $extension = pathinfo($saldo->proof, PATHINFO_EXTENSION);
                        @endphp
                        <!-- If payment proof is an image -->
                        @if(in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp']))
                            <img src="{{ asset('storage/' . $saldo->proof) }}" alt="Payment Proof" class="w-full max-w-md mx-auto rounded-md shadow-md">
                        @elseif($extension === 'pdf')
                            <!-- If payment proof is a PDF -->
                            <object data="{{ asset('storage/' . $saldo->proof) }}" type="application/pdf" class="w-full max-w-md h-96 mx-auto border rounded-md shadow-md">
                                <p>Your browser does not support PDFs. <a href="{{ asset('storage/' . $saldo->proof) }}" class="text-blue-500 hover:underline">Download the PDF</a>.</p>
                            </object>
                        @else
                            <p class="text-sm text-gray-500">{{ __('Unsupported file type for payment proof.') }}</p>
                        @endif
                    </div>
                @else
                    <p class="text-sm text-gray-500">{{ __('No payment proof uploaded.') }}</p>
                @endif

                <!-- Approve Button for Owner -->
                @role('owner')
                    @if(!$saldo->sudah_dibayar)
                        <div class="mt-6">
                            <form method="POST" action="{{ route('user.saldo.update', $saldo) }}">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="py-2 px-6 rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none transition duration-300">
                                    {{ __('Approve Payment') }}
                                </button>
                            </form>
                        </div>
                    @endif
                @endrole

                <!-- Back Button -->
                <div class="mt-6">
                    <a href="{{ route('user.saldo.index') }}" class="text-blue-500 hover:underline">{{ __('Back to Topup Requests') }}</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
