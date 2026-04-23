@extends('layouts.admin')

@section('title', 'Manajemen Supplier')

@section('content')
<!-- VIEW: SUPPLIER -->
<div id="view-supplier" class="max-w-7xl mx-auto space-y-6">
    
    <!-- Header View -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Supplier</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola data pemasok, kontak, dan riwayat pemesanan.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            @if(session('success'))
                <div class="px-4 py-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-xl flex items-center gap-2 animate-bounce">
                    <i class="ph ph-check-circle text-lg"></i> {{ session('success') }}
                </div>
            @endif
            <button onclick="openAddModal()" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-xl shadow-sm shadow-emerald-200 transition-colors flex items-center gap-2">
                <i class="ph ph-plus text-lg"></i> Tambah Supplier
            </button>
        </div>
    </div>

    <!-- Tabel Container -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        
        <!-- Toolbars (Search) -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between gap-4">
            <!-- Search -->
            <div class="relative max-w-md w-full">
                <i class="ph ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                <input type="text" placeholder="Cari nama perusahaan atau kontak..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all placeholder:text-slate-400">
            </div>
            
            <span class="text-sm font-medium text-slate-500 hidden sm:block">Total: {{ $suppliers->total() }} Supplier Terdaftar</span>
        </div>

        <!-- Responsive Table -->
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Perusahaan</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kontak Person</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($suppliers as $supplier)
                        @php
                            $colors = ['blue', 'purple', 'amber', 'indigo', 'rose'];
                            $color = $colors[$supplier->id % 5];
                        @endphp
                        <tr class="hover:bg-slate-50/50 transition-colors group">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-{{ $color }}-50 text-{{ $color }}-600 border border-{{ $color }}-100 flex items-center justify-center shrink-0">
                                        <i class="ph-fill ph-buildings text-xl"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800">{{ $supplier->name }}</p>
                                        <p class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                                            <i class="ph ph-map-pin text-slate-400"></i> {{ Str::limit($supplier->address, 30) }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-slate-800">{{ $supplier->contact_person }}</p>
                                <p class="text-xs text-slate-500 flex items-center gap-1 mt-0.5">
                                    <i class="ph ph-phone text-slate-400"></i> {{ $supplier->phone }}
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-flex text-xs font-medium bg-slate-100 text-slate-600 px-2 py-1 rounded-md">{{ $supplier->category }}</span>
                            </td>
                            <td class="px-6 py-4">
                                @if($supplier->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-emerald-50 border border-emerald-200 text-emerald-700">
                                        Aktif
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 border border-slate-200 text-slate-600">
                                        Nonaktif
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    <button onclick='openEditModal(@json($supplier))' class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit"><i class="ph ph-pencil-simple text-lg"></i></button>
                                    
                                    <form action="{{ route('admin.supplier.destroy', $supplier->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus supplier ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus"><i class="ph ph-trash text-lg"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">Belum ada data supplier.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-slate-100">
            {{ $suppliers->links() }}
        </div>
    </div>
</div>
@endsection

@push('modals')
<!-- Modal Tambah/Edit Supplier -->
<div id="modal-supplier" class="fixed inset-0 z-50 {{ $errors->any() ? '' : 'hidden' }}">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="toggleModal('modal-supplier')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-lg overflow-hidden shadow-2xl transform transition-all">
            <!-- Header -->
            <div class="px-6 py-4 border-b flex justify-between items-center bg-white">
                <h3 class="font-bold text-xl text-slate-800" id="modal-title">Tambah Supplier</h3>
                <button onclick="toggleModal('modal-supplier')" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg transition-colors">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>
            
            <form id="form-supplier" action="{{ route('admin.supplier.store') }}" method="POST">
                @csrf
                <div id="method-field"></div>
                <div class="p-6 space-y-4">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Nama Perusahaan <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" id="field-name" placeholder="PT. Contoh Jaya" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all" required>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Kontak Person <span class="text-rose-500">*</span></label>
                            <input type="text" name="contact_person" id="field-contact" placeholder="Nama PIC" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Telepon <span class="text-rose-500">*</span></label>
                            <input type="text" name="phone" id="field-phone" placeholder="0812..." class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all" required>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Email</label>
                        <input type="email" name="email" id="field-email" placeholder="kontak@perusahaan.com" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Alamat</label>
                        <textarea name="address" id="field-address" rows="3" placeholder="Alamat lengkap kantor/gudang..." class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all"></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Kategori Supplier</label>
                        <select name="category" id="field-category" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                            <option value="Distributor Utama">Distributor Utama</option>
                            <option value="Pabrik Farmasi">Pabrik Farmasi</option>
                            <option value="Lokal/Retail">Lokal/Retail</option>
                            <option value="Alkes & Suplemen">Alkes & Suplemen</option>
                        </select>
                    </div>

                    <div class="flex items-center gap-2 py-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="field-active" value="1" checked class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                        <label for="field-active" class="text-sm font-medium text-slate-700">Supplier Aktif</label>
                    </div>
                </div>

                <div class="px-6 py-5 bg-slate-50 border-t flex justify-end gap-3">
                    <button type="button" onclick="toggleModal('modal-supplier')" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-500 text-white text-sm font-bold rounded-xl hover:bg-emerald-600 shadow-sm shadow-emerald-200 transition-all active:scale-95">
                        Simpan Supplier
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function openAddModal() {
        document.getElementById('modal-title').innerText = 'Tambah Supplier';
        document.getElementById('form-supplier').action = "{{ route('admin.supplier.store') }}";
        document.getElementById('method-field').innerHTML = '';
        document.getElementById('form-supplier').reset();
        document.getElementById('field-active').checked = true;
        toggleModal('modal-supplier');
    }

    function openEditModal(supplier) {
        document.getElementById('modal-title').innerText = 'Edit Data Supplier';
        document.getElementById('form-supplier').action = `/admin/supplier/${supplier.id}`;
        document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        // Fill fields
        document.getElementById('field-name').value = supplier.name;
        document.getElementById('field-contact').value = supplier.contact_person;
        document.getElementById('field-phone').value = supplier.phone;
        document.getElementById('field-email').value = supplier.email;
        document.getElementById('field-address').value = supplier.address;
        document.getElementById('field-category').value = supplier.category;
        document.getElementById('field-active').checked = supplier.is_active;

        toggleModal('modal-supplier');
    }
</script>
@endpush
