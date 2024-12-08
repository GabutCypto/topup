<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Daftar Transaksi Pembelian Produk') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-xl p-8">

                @if(Auth::user()->hasRole('owner'))
                    <div class="mb-6">
                        <a href="{{ route('ofline.create') }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">
                            {{ __('Buat Transaksi Pembelian') }}
                        </a>
                    </div>
                @endif

                <div class="mb-6 border-b pb-4">
                    <h3 class="text-2xl font-bold text-gray-800">{{ __('Transaksi Anda') }}</h3>
                </div>

                <table class="min-w-full table-auto">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">{{ __('Produk') }}</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">{{ __('Jumlah') }}</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">{{ __('Total Harga') }}</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">{{ __('Status') }}</th>
                            <th class="py-3 px-4 text-center text-sm font-medium text-gray-600">{{ __('Tanggal Transaksi') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($transactions as $transaction)
                            <tr>
                                <td class="py-3 px-4 text-sm">{{ $transaction->produk->name }}</td>
                                <td class="py-3 px-4 text-sm">{{ $transaction->quantity }}</td>
                                <td class="py-3 px-4 text-sm">Rp. {{ number_format($transaction->total_price, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-sm">
                                    <span class="px-2 py-1 text-white bg-green-500 rounded-full">
                                        {{ $transaction->status }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm">{{ $transaction->created_at->format('d-m-Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>
