<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Kategori') }}
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

                <form method="POST" action="{{ route('admin.kategori.store') }}" enctype="multipart/form-data">
                    @csrf
            
                    <!-- Nama Kategori -->
                    <div class="mb-6">
                        <x-input-label for="nama" :value="__('Nama Kategori')" />
                        <x-text-input id="nama" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 focus:ring-indigo-500 focus:border-indigo-500" type="text" name="nama" :value="old('nama')" required autofocus autocomplete="nama" />
                        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
                    </div>

                    <!-- Pilih Toko -->
                    <div class="mb-6">
                        <x-input-label for="toko_id" :value="__('Toko')" />
                        <select id="toko_id" name="toko_id" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 focus:ring-indigo-500 focus:border-indigo-500" required>
                            <option value="" disabled selected>{{ __('Pilih Toko') }}</option>
                            @foreach($toko as $t)
                                <option value="{{ $t->id }}" {{ old('toko_id') == $t->id ? 'selected' : '' }}>{{ $t->nama }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('toko_id')" class="mt-2" />
                    </div>
            
                    <!-- Ikon Kategori -->
                    <div class="mt-4 mb-6">
                        <x-input-label for="ikon" :value="__('Ikon')" />
                        <x-text-input id="ikon" class="block mt-1 w-full border border-gray-300 rounded-lg p-3 focus:ring-indigo-500 focus:border-indigo-500" type="file" name="ikon" required autocomplete="ikon" />
                        <x-input-error :messages="$errors->get('ikon')" class="mt-2" />
                    </div>
            
                    <!-- Buttons -->
                    <div class="flex items-center justify-between mt-4">
                        <!-- Submit Button -->
                        <x-primary-button class="py-3 px-6 rounded-full text-white bg-indigo-700 hover:bg-indigo-800 transition duration-300 ease-in-out shadow-md hover:shadow-lg">
                            {{ __('Buat Kategori') }}
                        </x-primary-button>

                        <!-- Cancel Button -->
                        <a href="{{ route('admin.kategori.index') }}" class="py-3 px-6 rounded-full text-gray-700 bg-gray-200 hover:bg-gray-300 transition duration-300 ease-in-out shadow-md hover:shadow-lg">
                            {{ __('Balik') }}
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
