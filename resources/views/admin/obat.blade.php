@extends('layouts.admin')

@section('title', 'Manajemen Obat')

@section('content')
<!-- VIEW: MANAJEMEN OBAT -->
<div id="view-obat" class="max-w-7xl mx-auto space-y-6">
    
    <!-- Header View -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Obat</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola data inventaris obat, harga, dan stok.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            @if(session('success'))
                <div class="px-4 py-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-xl flex items-center gap-2 animate-bounce">
                    <i class="ph ph-check-circle text-lg"></i> {{ session('success') }}
                </div>
            @endif
            <button onclick="toggleModal('modal-import')" class="px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-semibold rounded-xl shadow-sm transition-colors flex items-center gap-2">
                <i class="ph ph-file-xls text-lg text-emerald-600"></i> Import CSV
            </button>
            <button onclick="openAddModal()" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-xl shadow-sm shadow-emerald-200 transition-colors flex items-center gap-2">
                <i class="ph ph-plus text-lg"></i> Tambah Obat
            </button>
        </div>
    </div>

    <!-- Error Validation -->
    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-sm mb-6 space-y-1">
            <p class="font-bold flex items-center gap-2"><i class="ph ph-warning-circle text-lg"></i> Mohon periksa kesalahan berikut:</p>
            <ul class="list-disc list-inside ml-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tabel Container -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        
        <!-- Toolbars (Search & Filter) -->
        <div class="p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <!-- Search -->
            <div class="relative max-w-md w-full">
                <i class="ph ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                <input type="text" placeholder="Cari nama obat atau SKU..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all placeholder:text-slate-400">
            </div>
            
            <!-- Filters -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <select class="appearance-none pl-4 pr-10 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 cursor-pointer">
                        <option value="">Semua Kategori</option>
                        <option value="antibiotik">Antibiotik</option>
                        <option value="vitamin">Vitamin & Suplemen</option>
                        <option value="bebas">Obat Bebas</option>
                        <option value="resep">Obat Resep</option>
                        <option value="alkes">Alat Kesehatan</option>
                    </select>
                    <i class="ph ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                </div>
                
                <div class="relative">
                    <select class="appearance-none pl-4 pr-10 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 cursor-pointer">
                        <option value="">Urutkan: Terbaru</option>
                        <option value="harga_asc">Harga Terendah</option>
                        <option value="harga_desc">Harga Tertinggi</option>
                        <option value="stok_asc">Stok Paling Sedikit</option>
                    </select>
                    <i class="ph ph-caret-down absolute right-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                </div>
            </div>
        </div>

        <!-- Responsive Table -->
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Info Obat</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Harga (Beli/Jual)</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Stok</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($medicines as $medicine)
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg border border-slate-200 bg-slate-100 overflow-hidden shrink-0 flex items-center justify-center">
                                        @if($medicine->image)
                                            <img src="{{ asset('storage/' . $medicine->image) }}" class="w-full h-full object-cover">
                                        @else
                                            <i class="ph ph-pill text-xl text-emerald-500"></i>
                                        @endif
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">{{ $medicine->name }}</p>
                                        <p class="text-xs text-slate-500">SKU: {{ $medicine->sku }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex text-xs font-medium bg-slate-100 text-slate-600 px-2 py-1 rounded-md">{{ $medicine->category }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-xs text-slate-500 line-through">Rp {{ number_format($medicine->purchase_price) }}</p>
                                <p class="text-sm font-semibold text-emerald-600">Rp {{ number_format($medicine->selling_price) }}</p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium {{ $medicine->stock < 20 ? 'text-amber-600' : 'text-slate-800' }}">
                                    {{ $medicine->stock }} <span class="text-xs text-slate-500 font-normal">{{ $medicine->unit }}</span>
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                @if($medicine->stock > 50)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-emerald-50 border border-emerald-200 text-emerald-700 px-2 py-1 rounded-md">
                                        <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div> Stok Aman
                                    </span>
                                @elseif($medicine->stock > 0)
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-amber-50 border border-amber-200 text-amber-700 px-2 py-1 rounded-md">
                                        <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div> Menipis
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium bg-rose-50 border border-rose-200 text-rose-700 px-2 py-1 rounded-md">
                                        <div class="w-1.5 h-1.5 rounded-full bg-rose-500"></div> Habis / Expired
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick='openEditModal(@json($medicine))' class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors"><i class="ph ph-pencil-simple text-lg"></i></button>
                                    
                                    <form action="{{ route('admin.obat.destroy', $medicine->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus obat ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors"><i class="ph ph-trash text-lg"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">Belum ada data obat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-slate-100">
            {{ $medicines->links() }}
        </div>
    </div>
</div>
@endsection

@push('modals')
<!-- Modal Tambah/Edit Obat -->
<div id="modal-obat" class="fixed inset-0 z-50 {{ $errors->any() ? '' : 'hidden' }}">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="toggleModal('modal-obat')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-2xl overflow-hidden shadow-2xl transform transition-all">
            <!-- Header -->
            <div class="px-6 py-4 border-b flex justify-between items-center bg-white">
                <h3 class="font-bold text-xl text-slate-800" id="modal-title">Tambah Obat Baru</h3>
                <button onclick="toggleModal('modal-obat')" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg transition-colors">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>
            
            <form id="form-obat" action="{{ route('admin.obat.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div id="method-field"></div>
                <div class="p-6 space-y-6 max-h-[70vh] overflow-y-auto no-scrollbar">
                    
                    <!-- Upload Foto -->
                    <div class="w-full space-y-4">
                        <div id="image-preview-container" class="hidden">
                            <p class="text-xs font-bold text-slate-500 uppercase mb-2">Foto Saat Ini</p>
                            <div class="relative w-32 h-32 rounded-2xl overflow-hidden border-2 border-slate-100">
                                <img id="image-preview" src="" class="w-full h-full object-cover">
                            </div>
                        </div>

                        <label class="relative flex flex-col items-center justify-center w-full h-36 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50 hover:bg-slate-100 transition-all cursor-pointer group">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                                    <i class="ph ph-image text-2xl text-slate-400"></i>
                                </div>
                                <p class="text-sm font-semibold text-slate-600" id="upload-label">Klik untuk upload foto obat</p>
                                <p class="text-xs text-slate-400 mt-1">PNG, JPG up to 5MB</p>
                            </div>
                            <input type="file" class="hidden" name="image" accept="image/*" />
                        </label>
                    </div>

                    <!-- Grid Fields -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Nama Obat <span class="text-rose-500">*</span></label>
                            <input type="text" name="name" id="field-name" placeholder="Masukkan nama obat..." class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder:text-slate-400" required>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Kategori <span class="text-rose-500">*</span></label>
                            <select name="category" id="field-category" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all appearance-none cursor-pointer" required>
                                <option value="" disabled selected>Pilih kategori</option>
                                <option value="Antibiotik">Antibiotik</option>
                                <option value="Obat Bebas">Obat Bebas</option>
                                <option value="Vitamin & Suplemen">Vitamin & Suplemen</option>
                                <option value="Obat Resep">Obat Resep</option>
                                <option value="Alat Kesehatan">Alat Kesehatan</option>
                            </select>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Harga Beli</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-400">Rp</span>
                                <input type="number" name="purchase_price" id="field-purchase_price" placeholder="0" class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder:text-slate-400">
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Harga Jual <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm font-semibold text-slate-400">Rp</span>
                                <input type="number" name="selling_price" id="field-selling_price" placeholder="0" class="w-full pl-11 pr-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder:text-slate-400" required>
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Stok Awal</label>
                            <input type="number" name="stock" id="field-stock" placeholder="Contoh: 100" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all placeholder:text-slate-400">
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Expired Date <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <input type="date" name="expiry_date" id="field-expiry_date" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all cursor-pointer" required>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Supplier</label>
                        <select name="supplier_id" id="field-supplier_id" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all appearance-none cursor-pointer">
                            <option value="" disabled selected>Pilih supplier utama...</option>
                            @foreach($suppliers as $supplier)
                                <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="px-6 py-5 bg-slate-50 border-t flex justify-end gap-3">
                    <button type="button" onclick="toggleModal('modal-obat')" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-500 text-white text-sm font-bold rounded-xl hover:bg-emerald-600 shadow-sm shadow-emerald-200 transition-all active:scale-95">
                        Simpan Obat
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Import CSV -->
<div id="modal-import" class="fixed inset-0 z-50 hidden">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="toggleModal('modal-import')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-2xl">
            <div class="px-6 py-4 border-b flex justify-between items-center">
                <h3 class="font-bold text-lg">Import Data Obat</h3>
                <button onclick="toggleModal('modal-import')" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg"><i class="ph ph-x text-lg"></i></button>
            </div>
            <form action="{{ route('admin.obat.import') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
                @csrf
                <div class="space-y-1">
                    <label class="text-sm font-semibold">Pilih File CSV</label>
                    <input type="file" name="file" accept=".csv" class="w-full border rounded-lg p-2 text-sm" required>
                </div>
                <p class="text-xs text-slate-500">Format kolom: Nama, Kategori, Harga Beli, Harga Jual, Stok, Expired (YYYY-MM-DD), Supplier ID</p>
                <button type="submit" class="w-full bg-emerald-500 text-white py-2.5 rounded-xl font-bold">Mulai Import</button>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function openAddModal() {
        document.getElementById('modal-title').innerText = 'Tambah Obat Baru';
        document.getElementById('form-obat').action = "{{ route('admin.obat.store') }}";
        document.getElementById('method-field').innerHTML = '';
        document.getElementById('form-obat').reset();
        document.getElementById('image-preview-container').classList.add('hidden');
        document.getElementById('upload-label').innerText = 'Klik untuk upload foto obat';
        toggleModal('modal-obat');
    }

    function openEditModal(medicine) {
        document.getElementById('modal-title').innerText = 'Edit Data Obat';
        document.getElementById('form-obat').action = `/admin/obat/${medicine.id}`;
        document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        // Image preview
        if(medicine.image) {
            document.getElementById('image-preview-container').classList.remove('hidden');
            document.getElementById('image-preview').src = `/storage/${medicine.image}`;
            document.getElementById('upload-label').innerText = 'Klik untuk ganti foto obat';
        } else {
            document.getElementById('image-preview-container').classList.add('hidden');
            document.getElementById('upload-label').innerText = 'Klik untuk upload foto obat';
        }
        
        // Fill fields
        document.getElementById('field-name').value = medicine.name;
        document.getElementById('field-category').value = medicine.category;
        document.getElementById('field-purchase_price').value = medicine.purchase_price;
        document.getElementById('field-selling_price').value = medicine.selling_price;
        document.getElementById('field-stock').value = medicine.stock;
        document.getElementById('field-expiry_date').value = medicine.expiry_date;
        document.getElementById('field-supplier_id').value = medicine.supplier_id;

        toggleModal('modal-obat');
    }
</script>
@endpush
