@extends('layouts.admin')

@section('title', 'Profil Saya')

@section('content')
<!-- VIEW: PROFIL ADMIN -->
<div id="view-profil-admin" class="max-w-7xl mx-auto space-y-6">
    
    @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-4 py-3 rounded-xl flex items-center gap-3 shadow-sm animate-fade-in">
            <i class="ph ph-check-circle text-xl"></i>
            <span class="text-sm font-semibold">{{ session('success') }}</span>
        </div>
    @endif

    @if($errors->any())
        <div class="bg-rose-50 border border-rose-200 text-rose-600 px-4 py-3 rounded-xl shadow-sm animate-fade-in">
            <div class="flex items-center gap-3 mb-1">
                <i class="ph ph-warning-circle text-xl"></i>
                <span class="text-sm font-bold">Terjadi Kesalahan:</span>
            </div>
            <ul class="list-disc list-inside text-xs font-medium ml-6">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <!-- Header View -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
            <div>
                <h2 class="text-2xl font-bold text-slate-800">Profil Saya</h2>
                <p class="text-sm text-slate-500 mt-1">Kelola informasi pribadi dan keamanan akun Anda.</p>
            </div>
            <div class="flex flex-wrap items-center gap-3">
                <button type="submit" class="px-5 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-xl shadow-sm shadow-emerald-200 transition-colors flex items-center gap-2">
                    <i class="ph ph-floppy-disk text-lg"></i> Simpan Profil
                </button>
            </div>
        </div>

        <!-- Content Split -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
            
            <!-- Card Kiri: Avatar & Info Singkat -->
            <div class="lg:col-span-1 space-y-6">
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 flex flex-col items-center text-center">
                    <div class="w-32 h-32 rounded-full bg-emerald-100 border-4 border-white shadow-md overflow-hidden mb-4 relative group cursor-pointer" onclick="document.getElementById('avatar-input').click()">
                        @if($user && $user->avatar)
                            <img id="avatar-preview" src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover" />
                        @else
                            <img id="avatar-preview" src="https://api.dicebear.com/7.x/notionists/svg?seed={{ $user->username ?? 'Guest' }}&backgroundColor=d1fae5" alt="{{ $user->name ?? 'Guest' }}" class="w-full h-full object-cover" />
                        @endif
                        <div class="absolute inset-0 bg-slate-900/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <i class="ph ph-camera text-white text-2xl"></i>
                        </div>
                        <input type="file" name="avatar" id="avatar-input" class="hidden" accept="image/*" onchange="previewAvatar(this)">
                    </div>
                    <h3 class="text-lg font-bold text-slate-800">{{ $user->name ?? 'Guest User' }}</h3>
                    <p class="text-sm font-semibold text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full mt-2 border border-emerald-100">{{ $user->role ?? 'Visitor' }}</p>
                    
                    <div class="w-full mt-6 pt-6 border-t border-slate-100 space-y-3 text-left">
                        <div class="flex items-center gap-3 text-slate-600">
                            <i class="ph ph-envelope-simple text-lg text-slate-400"></i>
                            <span class="text-sm font-medium">{{ $user->email ?? 'guest@example.com' }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-slate-600">
                            <i class="ph ph-phone text-lg text-slate-400"></i>
                            <span class="text-sm font-medium">{{ ($user->phone ?? null) ?? '-' }}</span>
                        </div>
                        <div class="flex items-center gap-3 text-slate-600">
                            <i class="ph ph-map-pin text-lg text-slate-400"></i>
                            <span class="text-sm font-medium">{{ ($user->city ?? null) ?? '-' }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Card Kanan: Form Edit -->
            <div class="lg:col-span-2 space-y-6">
                
                <!-- Informasi Pribadi -->
                <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                    <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                        <h3 class="text-base font-bold text-slate-800">Informasi Pribadi</h3>
                        <p class="text-xs text-slate-500">Perbarui data diri Anda di sini.</p>
                    </div>
                    <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-1.5 sm:col-span-2">
                            <label class="text-sm font-semibold text-slate-700">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name ?? 'Guest User') }}" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-slate-800 font-medium" required>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700">Username</label>
                            <input type="text" value="{{ $user->username ?? 'guest' }}" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-lg text-sm text-slate-500 font-medium cursor-not-allowed" readonly title="Username tidak dapat diubah">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700">Email</label>
                            <input type="email" name="email" value="{{ old('email', $user->email ?? 'guest@example.com') }}" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-slate-800 font-medium" required>
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700">No. Telepon</label>
                            <input type="tel" name="phone" value="{{ old('phone', $user->phone ?? '') }}" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-slate-800 font-medium">
                        </div>
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700">Kota / Wilayah</label>
                            <input type="text" name="city" value="{{ old('city', $user->city ?? '') }}" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all text-slate-800 font-medium">
                        </div>
                    </div>
                </div>
            </form>

            <!-- Keamanan / Ubah Password -->
            <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-base font-bold text-slate-800">Keamanan & Password</h3>
                    <p class="text-xs text-slate-500">Pastikan akun Anda selalu aman dengan password yang kuat.</p>
                </div>
                <form action="{{ route('admin.profil.password') }}" method="POST">
                    @csrf
                    <div class="p-6 space-y-5">
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-slate-700">Password Saat Ini</label>
                            <input type="password" name="current_password" placeholder="••••••••" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all tracking-widest" required>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div class="space-y-1.5">
                                <label class="text-sm font-semibold text-slate-700">Password Baru</label>
                                <input type="password" name="password" placeholder="Minimal 8 karakter" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all tracking-widest" required>
                            </div>
                            <div class="space-y-1.5">
                                <label class="text-sm font-semibold text-slate-700">Konfirmasi Password Baru</label>
                                <input type="password" name="password_confirmation" placeholder="Ulangi password baru" class="w-full px-3 py-2.5 bg-white border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all tracking-widest" required>
                            </div>
                        </div>
                        <div class="pt-3">
                            <button type="submit" class="px-5 py-2.5 bg-slate-800 hover:bg-slate-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors flex items-center gap-2">
                                <i class="ph ph-lock-key"></i> Perbarui Password
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            </div>
        </div>
</div>
@endsection

@push('scripts')
<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('avatar-preview').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endpush
