<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Transaksi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tampilkan pesan sukses atau error -->
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @elseif(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <!-- Form untuk membuat transaksi -->
                    <form action="{{ route('ofline.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <!-- Pilih Buyer -->
                        <div class="form-group mb-4">
                            <label for="buyer_id" class="block text-gray-700 font-semibold">Pilih Buyer</label>
                            <select name="buyer_id" id="buyer_id" class="form-control mt-2 w-full p-2 border border-gray-300 rounded" required>
                                <option value="">--Pilih Buyer--</option>
                                @foreach($buyers as $buyer)
                                    <option value="{{ $buyer->id }}">{{ $buyer->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Pilih Produk dan Kuantitas -->
                        <div class="form-group mb-4">
                            <label for="produk" class="block text-gray-700 font-semibold">Pilih Produk</label>
                            <div class="produk-container">
                                <div class="produk-item mb-3">
                                    <select name="produk[0][id]" class="form-control produk-id mt-2 w-full p-2 border border-gray-300 rounded" required>
                                        <option value="">--Pilih Produk--</option>
                                        @foreach($produk as $item)
                                            <option value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach
                                    </select>

                                    <input type="number" name="produk[0][quantity]" class="form-control produk-quantity mt-2 w-full p-2 border border-gray-300 rounded" placeholder="Kuantitas" min="1" required>
                                    
                                    <button type="button" class="btn btn-danger remove-produk mt-2">Hapus Produk</button>
                                </div>
                            </div>
                            <button type="button" class="btn btn-primary add-produk mt-4">Tambah Produk</button>
                        </div>

                        <!-- Upload Foto -->
                        <div class="form-group mb-4">
                            <label for="photo" class="block text-gray-700 font-semibold">Foto Produk (Opsional)</label>
                            <input type="file" name="photo" id="photo" class="form-control mt-2 w-full p-2 border border-gray-300 rounded">
                        </div>

                        <!-- Submit -->
                        <button type="submit" class="btn btn-success mt-4 px-6 py-2 bg-green-500 text-white rounded">Simpan Transaksi</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <x-slot name="scripts">
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Menambahkan baris produk baru
                document.querySelector('.add-produk').addEventListener('click', function() {
                    const produkContainer = document.querySelector('.produk-container');
                    const produkCount = produkContainer.getElementsByClassName('produk-item').length;
                    const newProdukItem = document.createElement('div');
                    newProdukItem.classList.add('produk-item', 'mb-3');
                    newProdukItem.innerHTML = `
                        <select name="produk[${produkCount}][id]" class="form-control produk-id mt-2 w-full p-2 border border-gray-300 rounded" required>
                            <option value="">--Pilih Produk--</option>
                            @foreach($produk as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                        <input type="number" name="produk[${produkCount}][quantity]" class="form-control produk-quantity mt-2 w-full p-2 border border-gray-300 rounded" placeholder="Kuantitas" min="1" required>
                        <button type="button" class="btn btn-danger remove-produk mt-2">Hapus Produk</button>
                    `;
                    produkContainer.appendChild(newProdukItem);
                });

                // Menghapus baris produk
                document.querySelector('.produk-container').addEventListener('click', function(e) {
                    if (e.target && e.target.classList.contains('remove-produk')) {
                        e.target.closest('.produk-item').remove();
                    }
                });
            });
        </script>
    </x-slot>
</x-app-layout>
