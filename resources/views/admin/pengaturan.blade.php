@extends('layouts.admin')

@section('title', 'Pengaturan Sistem')

@section('content')
<!-- VIEW: PENGATURAN -->
<div id="view-pengaturan" class="max-w-7xl mx-auto space-y-6">
    
    <!-- Header View -->
    <form action="{{ route('admin.pengaturan.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4 mb-6">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Pengaturan Sistem</h2>
                <p class="text-sm text-slate-500 mt-1">Konfigurasi profil apotek, preferensi keuangan, dan pajak.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                @if(session('success'))
                    <div class="px-4 py-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-xl flex items-center gap-2">
                        <i class="ph ph-check-circle text-lg"></i> {{ session('success') }}
                    </div>
                @endif
                <button type="submit" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-xl shadow-sm shadow-emerald-200 transition-colors flex items-center gap-2">
                    <i class="ph ph-floppy-disk text-lg"></i> Simpan Perubahan
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            <!-- Kolom Kiri: Profil & Logo -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Card Profil Apotek -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-base font-bold text-slate-800">Profil Apotek</h3>
                        <p class="text-xs text-slate-500">Informasi ini akan ditampilkan pada struk dan invoice.</p>
                    </div>
                    
                    <div class="p-6 space-y-6">
                        <!-- Upload Logo Area -->
                        <div class="flex flex-col sm:flex-row sm:items-center gap-6 border-b border-slate-100 pb-6">
                            <div class="w-24 h-24 rounded-2xl bg-slate-50 border-2 border-dashed border-slate-300 flex items-center justify-center hover:bg-emerald-50 hover:border-emerald-400 cursor-pointer transition-colors group relative overflow-hidden shrink-0">
                                @if($settings['app_logo'])
                                    <img id="logo-preview" src="{{ asset('storage/' . $settings['app_logo']) }}" class="w-full h-full object-cover">
                                @else
                                    <div id="logo-placeholder" class="flex items-center justify-center">
                                        <i class="ph ph-camera text-2xl text-slate-400 group-hover:text-emerald-500 transition-colors z-10"></i>
                                    </div>
                                    <img id="logo-preview" class="w-full h-full object-cover hidden">
                                @endif
                                <input type="file" id="app-logo-input" name="app_logo" class="absolute inset-0 opacity-0 cursor-pointer z-20" onchange="previewLogo(this)">
                                <div class="absolute inset-0 bg-emerald-500/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            </div>
                            <div>
                                <h4 class="text-sm font-semibold text-slate-800 mb-1">Logo Resmi Apotek</h4>
                                <p class="text-xs text-slate-500 mb-3 max-w-xs">Format PNG, JPG, atau SVG dengan ukuran maksimal 2MB. Resolusi disarankan 512x512px.</p>
                                <div class="flex gap-2">
                                    <button type="button" onclick="document.getElementById('app-logo-input').click()" class="px-3 py-1.5 text-xs font-semibold text-emerald-600 bg-emerald-50 border border-emerald-200 rounded-lg hover:bg-emerald-100 transition-colors">
                                        Upload Logo
                                    </button>
                                    @if($settings['app_logo'])
                                        <button type="button" onclick="confirmDeleteLogo()" class="px-3 py-1.5 text-xs font-semibold text-rose-600 bg-white border border-slate-200 rounded-lg hover:bg-rose-50 transition-colors">Hapus</button>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Form Profil -->
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="space-y-1.5 sm:col-span-2">
                                <label class="text-sm font-semibold text-slate-700">Nama Apotek <span class="text-rose-500">*</span></label>
                                <input type="text" name="app_name" value="{{ $settings['app_name'] }}" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all font-medium text-slate-800" required>
                            </div>
                            
                            <div class="space-y-1.5">
                                <label class="text-sm font-semibold text-slate-700">No. Izin Apotek (SIA)</label>
                                <input type="text" name="app_sia" value="{{ $settings['app_sia'] }}" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-slate-600">
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-sm font-semibold text-slate-700">Apoteker Penanggung Jawab</label>
                                <input type="text" name="app_pharmacist" value="{{ $settings['app_pharmacist'] }}" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-slate-600">
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-sm font-semibold text-slate-700">No. Telepon / WhatsApp <span class="text-rose-500">*</span></label>
                                <input type="tel" name="app_phone" value="{{ $settings['app_phone'] }}" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-slate-600" required>
                            </div>

                            <div class="space-y-1.5">
                                <label class="text-sm font-semibold text-slate-700">Email Resmi</label>
                                <input type="email" name="app_email" value="{{ $settings['app_email'] }}" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-slate-600">
                            </div>

                            <div class="space-y-1.5 sm:col-span-2">
                                <label class="text-sm font-semibold text-slate-700">Alamat Lengkap <span class="text-rose-500">*</span></label>
                                <textarea name="app_address" rows="3" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all resize-none text-slate-600" required>{{ $settings['app_address'] }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Kolom Kanan: Keuangan & Diskon -->
            <div class="space-y-6">
                
                <!-- Card Pajak -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-2">
                            <i class="ph-fill ph-receipt text-slate-400 text-lg"></i>
                            <h3 class="text-sm font-bold text-slate-800">Pajak (PPN)</h3>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="tax_enabled" class="sr-only peer" {{ $settings['tax_enabled'] == '1' ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>
                    <div class="p-5">
                        <p class="text-xs text-slate-500 mb-3">Persentase pajak ini akan ditambahkan secara otomatis pada setiap transaksi di kasir jika statusnya aktif.</p>
                        <div class="relative">
                            <input type="number" name="tax_percentage" value="{{ $settings['tax_percentage'] }}" class="w-full pl-4 pr-10 py-2.5 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold">%</span>
                        </div>
                    </div>
                </div>

                <!-- Card Diskon Global -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                        <div class="flex items-center gap-2">
                            <i class="ph-fill ph-tag text-slate-400 text-lg"></i>
                            <h3 class="text-sm font-bold text-slate-800">Diskon Global</h3>
                        </div>
                        <label class="relative inline-flex items-center cursor-pointer">
                            <input type="checkbox" name="discount_enabled" id="toggle-diskon" class="sr-only peer" {{ $settings['discount_enabled'] == '1' ? 'checked' : '' }}>
                            <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500"></div>
                        </label>
                    </div>
                    <div class="p-5 transition-opacity {{ $settings['discount_enabled'] == '1' ? '' : 'opacity-60 pointer-events-none' }}" id="area-diskon">
                        <p class="text-xs text-slate-500 mb-3">Terapkan diskon otomatis untuk semua pelanggan (contoh: Promo Kemerdekaan).</p>
                        <div class="relative">
                            <input type="number" name="discount_percentage" value="{{ $settings['discount_percentage'] }}" placeholder="0" class="w-full pl-4 pr-10 py-2.5 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-800 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all">
                            <span class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-500 font-bold">%</span>
                        </div>
                    </div>
                </div>

                <!-- Hint Box -->
                <div class="bg-amber-50 border border-amber-100 rounded-xl p-4 flex gap-3">
                    <i class="ph-fill ph-warning-circle text-amber-500 text-lg shrink-0 mt-0.5"></i>
                    <p class="text-xs text-amber-700 leading-relaxed font-medium">Perubahan pada pajak dan diskon global akan langsung memengaruhi transaksi kasir yang sedang berjalan. Pastikan untuk memberitahu staf Anda.</p>
                </div>

            </div>
        </div>
    </form>
</div>

<!-- Hidden Delete Form -->
<form id="form-delete-logo" action="{{ route('admin.pengaturan.logo.delete') }}" method="POST" class="hidden">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
    function previewLogo(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('logo-preview');
                const placeholder = document.getElementById('logo-placeholder');
                
                preview.src = e.target.result;
                preview.classList.remove('hidden');
                if (placeholder) placeholder.classList.add('hidden');
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    function confirmDeleteLogo() {
        if (confirm('Apakah Anda yakin ingin menghapus logo apotek?')) {
            document.getElementById('form-delete-logo').submit();
        }
    }

    // Toggle diskon area visibility
    document.getElementById('toggle-diskon').addEventListener('change', function() {
        const area = document.getElementById('area-diskon');
        if (this.checked) {
            area.classList.remove('opacity-60', 'pointer-events-none');
        } else {
            area.classList.add('opacity-60', 'pointer-events-none');
        }
    });
</script>
@endpush
