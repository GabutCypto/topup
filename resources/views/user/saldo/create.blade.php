<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Create Topup Request') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-xl p-8">

                <h3 class="text-2xl font-bold text-gray-800 mb-6">{{ __('Request Topup') }}</h3>

                <form method="POST" action="{{ route('user.saldo.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Saldo Input -->
                    <div class="mb-6">
                        <label for="saldo" class="block text-sm font-medium text-gray-700">{{ __('Amount (in IDR)') }}</label>
                        <input type="number" id="saldo" name="saldo" value="{{ old('saldo') }}" min="1000" required
                            class="mt-2 px-4 py-2 w-full border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        
                        @error('saldo')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Proof Upload -->
                    <div class="mb-6">
                        <label for="proof" class="block text-sm font-medium text-gray-700">{{ __('Upload Payment Proof') }}</label>
                        <input type="file" id="proof" name="proof" accept=".jpeg,.png,.jpg,.pdf"
                            class="mt-2 w-full border border-gray-300 rounded-lg text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                        
                        @error('proof')
                            <p class="text-red-500 text-sm mt-2">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="mt-6">
                        <button type="submit" class="py-2 px-6 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300">
                            {{ __('Submit Topup Request') }}
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <a href="{{ route('user.saldo.index') }}" class="text-blue-500 hover:underline">{{ __('Back to Topups List') }}</a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
