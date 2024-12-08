<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Produk') }}
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
                
                <form method="POST" action="{{ route('admin.produk.update', $produk) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Nama Produk -->
                    <div class="mb-6">
                        <x-input-label for="nama" :value="__('Nama Produk')" class="text-lg font-semibold text-gray-700" />
                        <x-text-input 
                            id="nama" 
                            class="block mt-1 w-full border-2 border-gray-300 rounded-lg p-4 text-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300" 
                            type="text" 
                            name="nama" 
                            value="{{ old('nama', $produk->nama) }}" 
                            required 
                            autofocus 
                            autocomplete="nama" 
                        />
                        <x-input-error :messages="$errors->get('nama')" class="mt-2 text-sm text-red-500" />
                    </div>

                    <!-- Tentang Produk -->
                    <div class="mb-6">
                        <x-input-label for="tentang" :value="__('Tentang Produk')" class="text-lg font-semibold text-gray-700" />
                        <textarea 
                            id="tentang" 
                            class="block mt-1 w-full border-2 border-gray-300 rounded-lg p-4 text-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300" 
                            name="tentang" 
                            required 
                            rows="4" 
                            autofocus>{{ old('tentang', $produk->tentang) }}</textarea>
                        <x-input-error :messages="$errors->get('tentang')" class="mt-2 text-sm text-red-500" />
                    </div>

                    <!-- Pilih Toko -->
                    <div class="mb-6">
                        <x-input-label for="toko_id" :value="__('Toko')" class="text-lg font-semibold text-gray-700" />
                        <select name="toko_id" id="toko_id" class="block mt-1 w-full border-2 border-gray-300 rounded-lg p-4 text-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300">
                            <option value="" disabled>{{ __('Pilih Toko') }}</option>
                            @foreach ($toko as $store)
                                <option value="{{ $store->id }}" {{ old('toko_id', $produk->toko_id) == $store->id ? 'selected' : '' }}>
                                    {{ $store->nama }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('toko_id')" class="mt-2 text-sm text-red-500" />
                    </div>

                    <!-- Pilih Kategori -->
                    <div class="mb-6">
                        <x-input-label for="kategori_id" :value="__('Kategori')" class="text-lg font-semibold text-gray-700" />
                        <select name="kategori_id" id="kategori_id" class="block mt-1 w-full border-2 border-gray-300 rounded-lg p-4 text-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300">
                            <option value="" disabled>{{ __('Pilih Kategori') }}</option>
                            @foreach ($kategori as $cat)
                                <option value="{{ $cat->id }}" {{ old('kategori_id', $produk->kategori_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nama }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('kategori_id')" class="mt-2 text-sm text-red-500" />
                    </div>

                    <!-- Harga Produk -->
                    <div class="mb-6">
                        <x-input-label for="harga" :value="__('Harga')" class="text-lg font-semibold text-gray-700" />
                        <x-text-input 
                            id="harga" 
                            class="block mt-1 w-full border-2 border-gray-300 rounded-lg p-4 text-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300" 
                            type="number" 
                            name="harga" 
                            value="{{ old('harga', $produk->harga) }}" 
                            required 
                            autocomplete="harga" 
                        />
                        <x-input-error :messages="$errors->get('harga')" class="mt-2 text-sm text-red-500" />
                    </div>

                    <!-- Foto Produk -->
                    <div class="mb-6">
                        <x-input-label for="foto" :value="__('Foto Produk')" class="text-lg font-semibold text-gray-700" />
                        <div class="mb-4">
                            <!-- Display current photo -->
                            @if ($produk->foto)
                                <img src="{{ Storage::url($produk->foto) }}" alt="{{ $produk->nama }}" class="w-32 h-32 object-cover rounded-lg">
                            @else
                                <p class="text-sm text-gray-500">{{ __('Tidak ada foto') }}</p>
                            @endif
                        </div>
                        <x-text-input 
                            id="foto" 
                            class="block w-full mt-1 border-2 border-gray-300 rounded-lg p-4 text-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition duration-300" 
                            type="file" 
                            name="foto" 
                            autocomplete="foto" 
                        />
                        <x-input-error :messages="$errors->get('foto')" class="mt-2 text-sm text-red-500" />
                    </div>

                    <!-- Tombol Submit dan Kembali -->
                    <div class="flex items-center justify-between mt-4">
                        <!-- Tombol Update -->
                        <x-primary-button class="py-3 px-6 rounded-full text-white bg-indigo-700 hover:bg-indigo-800 transition duration-300 ease-in-out shadow-md hover:shadow-lg">
                            {{ __('Perbarui Produk') }}
                        </x-primary-button>

                        <!-- Tombol Kembali -->
                        <a href="{{ route('admin.produk.index') }}" class="py-3 px-6 rounded-full text-gray-700 bg-gray-200 hover:bg-gray-300 transition duration-300 ease-in-out shadow-md hover:shadow-lg">
                            {{ __('Kembali') }}
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>
