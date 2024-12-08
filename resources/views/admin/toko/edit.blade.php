<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Category') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-lg rounded-lg">

                @if($errors->any())
                    @foreach ($errors->all() as $error)
                        <div class="py-3 w-full rounded-3xl bg-red-500 text-white mb-4">
                            {{ $error }}
                        </div>
                    @endforeach
                @endif
                
                <form method="POST" action="{{ route('admin.toko.update', $toko) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Name -->
                    <div class="mb-6">
                        <x-input-label for="nama" :value="__('Nama')" class="text-lg font-semibold text-gray-700" />
                        <x-text-input 
                            id="nama" 
                            class="block mt-1 w-full border-2 border-gray-300 rounded-lg p-4 text-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300" 
                            type="text" 
                            name="nama" 
                            value="{{ $toko->nama }}" 
                            required 
                            autofocus 
                            autocomplete="nama" 
                        />
                        <x-input-error :messages="$errors->get('nama')" class="mt-2 text-sm text-red-500" />
                    </div>

                    <!-- Icon -->
                    <div class="mb-6">
                        <x-input-label for="ikon" :value="__('Ikon')" class="text-lg font-semibold text-gray-700" />
                        <div class="mb-4">
                            <!-- Display current icon -->
                            <img src="{{ Storage::url($toko->ikon) }}" alt="{{ $toko->nama }}" class="w-32 h-32 object-cover rounded-lg">
                        </div>
                        <x-text-input 
                            id="ikon" 
                            class="block w-full mt-1 border-2 border-gray-300 rounded-lg p-4 text-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300" 
                            type="file" 
                            name="ikon" 
                            autocomplete="ikon" 
                        />
                        <x-input-error :messages="$errors->get('ikon')" class="mt-2 text-sm text-red-500" />
                    </div>

                    <!-- Buttons -->
                    <div class="flex items-center justify-between mt-4">
                        <!-- Update Button -->
                        <x-primary-button class="py-3 px-6 rounded-full text-white bg-indigo-700 hover:bg-indigo-800 transition duration-300 ease-in-out shadow-md hover:shadow-lg">
                            {{ __('Perbarui Toko') }}
                        </x-primary-button>

                        <!-- Cancel Button -->
                        <a href="{{ route('admin.toko.index') }}" class="py-3 px-6 rounded-full text-gray-700 bg-gray-200 hover:bg-gray-300 transition duration-300 ease-in-out shadow-md hover:shadow-lg">
                            {{ __('Kembali') }}
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>