<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 leading-tight">
            {{ __('Topup Requests') }}
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-xl p-8">

                <!-- Total Approved Balance -->
                @role('buyer')
                <div class="mb-6 border-b pb-4">
                    <h3 class="text-xl font-semibold text-gray-700">{{ __('Total Approved Balance:') }}</h3>
                    <p class="text-2xl font-bold text-gray-800">
                        Rp {{ number_format($saldo->where('sudah_dibayar', true)->sum('saldo'), 0, ',', '.') }}
                    </p>
                </div>
                @endrole

                <!-- Topup List Title -->
                <div class="mb-6 border-b pb-4">
                    <h3 class="text-2xl font-bold text-gray-800">{{ __('Your Topup Requests') }}</h3>
                </div>

                <!-- Table to display the topup requests -->
                <table class="min-w-full table-auto">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">{{ __('Amount') }}</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">{{ __('Status') }}</th>
                            <th class="py-3 px-4 text-left text-sm font-medium text-gray-600">{{ __('Requested at') }}</th>
                            <th class="py-3 px-4 text-center text-sm font-medium text-gray-600">{{ __('Actions') }}</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach ($saldo as $topup)
                            <tr>
                                <td class="py-3 px-4 text-sm text-gray-800">Rp {{ number_format($topup->saldo, 0, ',', '.') }}</td>
                                <td class="py-3 px-4 text-sm">
                                    <span class="py-2 px-5 rounded-full text-white {{ $topup->sudah_dibayar ? 'bg-green-500' : 'bg-orange-500' }} text-sm font-semibold">
                                        {{ $topup->sudah_dibayar ? __('SUCCESS') : __('Pending') }}
                                    </span>
                                </td>
                                <td class="py-3 px-4 text-sm text-gray-700">{{ $topup->created_at->format('d M Y H:i') }}</td>
                                <td class="py-3 px-4 text-center text-sm">
                                    <a href="{{ route('user.saldo.show', $topup) }}" class="text-blue-500 hover:underline">{{ __('View') }}</a>
                                    @role('owner')
                                        @if(!$topup->sudah_dibayar)
                                            | <a href="{{ route('user.saldo.update', $topup) }}" class="text-green-500 hover:underline">{{ __('Approve') }}</a>
                                        @endif
                                    @endrole
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- No Topup Requests Message -->
                @if($saldo->isEmpty())
                    <div class="mt-6 text-center text-sm text-gray-500">
                        {{ __('You have not made any topup requests yet.') }}
                    </div>
                @endif

                <!-- Add New Topup Button -->
                @role('buyer')
                    <div class="mt-6 text-right">
                        <a href="{{ route('user.saldo.create') }}" class="py-2 px-6 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300">
                            {{ __('Request New Topup') }}
                        </a>
                    </div>
                @endrole
            </div>
        </div>
    </div>
</x-app-layout>
