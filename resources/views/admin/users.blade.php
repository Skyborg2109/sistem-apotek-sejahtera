@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<!-- VIEW: USER MANAGEMENT -->
<div id="view-users" class="max-w-7xl mx-auto space-y-6">
    
    <!-- Header View -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Manajemen Pengguna</h2>
            <p class="text-sm text-slate-500 mt-1">Kelola akses, role, dan status akun staf apotek.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            @if(session('success'))
                <div class="px-4 py-2 bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm font-medium rounded-xl flex items-center gap-2 animate-bounce">
                    <i class="ph ph-check-circle text-lg"></i> {{ session('success') }}
                </div>
            @endif
            <button onclick="openAddModal()" class="px-4 py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white text-sm font-semibold rounded-xl shadow-sm shadow-emerald-200 transition-colors flex items-center gap-2">
                <i class="ph ph-user-plus text-lg"></i> Tambah Pengguna
            </button>
        </div>
    </div>

    <!-- Tabel Container -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        
        <!-- Toolbars (Search & Filter) -->
        <div class="p-5 border-b border-slate-100 flex items-center justify-between gap-4">
            <!-- Search -->
            <div class="relative max-w-md w-full">
                <i class="ph ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                <input type="text" placeholder="Cari nama atau username..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all placeholder:text-slate-400">
            </div>
            
            <div class="flex items-center gap-2 text-sm font-medium text-slate-500">
                <i class="ph ph-users"></i> Total: {{ $users->count() }} Staf
            </div>
        </div>

        <!-- Responsive Table -->
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pengguna</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Role Hak Akses</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Terakhir Login</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status Akun</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($users as $user)
                        <tr class="hover:bg-slate-50/50 transition-colors group {{ !$user->is_active ? 'opacity-75' : '' }}">
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-full {{ $user->role == 'Administrator' ? 'bg-emerald-100 border-emerald-200' : 'bg-blue-100 border-blue-200' }} border flex items-center justify-center overflow-hidden shrink-0">
                                        <img src="https://api.dicebear.com/7.x/notionists/svg?seed={{ $user->username }}&backgroundColor={{ $user->role == 'Administrator' ? 'd1fae5' : 'dbeafe' }}" alt="Avatar" class="w-full h-full object-cover" />
                                    </div>
                                    <div>
                                        <p class="text-sm font-semibold text-slate-800 {{ !$user->is_active ? 'line-through decoration-slate-400' : '' }}">
                                            {{ $user->name }} {{ $user->id == 1 ? '(Anda)' : '' }}
                                        </p>
                                        <p class="text-xs text-slate-500">@ {{ $user->username }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @if($user->role == 'Administrator')
                                    <span class="inline-flex items-center gap-1.5 text-xs font-bold bg-purple-50 text-purple-700 px-2.5 py-1 rounded-md border border-purple-100">
                                        <i class="ph-fill ph-shield-check"></i> Administrator
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold bg-blue-50 text-blue-700 px-2.5 py-1 rounded-md border border-blue-100">
                                        <i class="ph-fill ph-calculator"></i> Kasir
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($user->last_login_at)
                                    <p class="text-sm font-medium text-slate-800">{{ \Carbon\Carbon::parse($user->last_login_at)->diffForHumans() }}</p>
                                    <p class="text-xs text-slate-500">IP: {{ $user->last_login_ip }}</p>
                                @else
                                    <p class="text-xs text-slate-400 italic">Belum pernah login</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" class="sr-only peer" {{ $user->is_active ? 'checked' : '' }} onchange="toggleUserStatus({{ $user->id }})" {{ $user->id == 1 ? 'disabled' : '' }}>
                                        <div class="w-9 h-5 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-slate-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:bg-emerald-500 {{ $user->id == 1 ? 'opacity-60' : '' }}"></div>
                                    </label>
                                    <span class="text-xs font-semibold {{ $user->is_active ? 'text-emerald-600' : 'text-slate-400' }}">
                                        {{ $user->is_active ? 'Aktif' : 'Nonaktif' }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($user->id == 1)
                                    <span class="text-xs font-medium text-slate-400 italic">Akun Anda</span>
                                @else
                                    <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <button onclick='openEditModal(@json($user))' class="p-1.5 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="Edit Data"><i class="ph ph-pencil-simple text-lg"></i></button>
                                        
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors" title="Hapus"><i class="ph ph-trash text-lg"></i></button>
                                        </form>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

    </div>
</div>
@endsection

@push('modals')
<!-- Modal Tambah/Edit User -->
<div id="modal-user" class="fixed inset-0 z-50 {{ $errors->any() ? '' : 'hidden' }}">
    <div class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" onclick="toggleModal('modal-user')"></div>
    <div class="fixed inset-0 flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl w-full max-w-md overflow-hidden shadow-2xl transform transition-all">
            <!-- Header -->
            <div class="px-6 py-4 border-b flex justify-between items-center bg-white">
                <h3 class="font-bold text-xl text-slate-800" id="modal-title">Tambah Pengguna</h3>
                <button onclick="toggleModal('modal-user')" class="p-2 text-slate-400 hover:text-slate-600 rounded-lg transition-colors">
                    <i class="ph ph-x text-xl"></i>
                </button>
            </div>
            
            <form id="form-user" action="{{ route('admin.users.store') }}" method="POST">
                @csrf
                <div id="method-field"></div>
                <div class="p-6 space-y-4">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Nama Lengkap <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" id="field-name" placeholder="Nama staf..." class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Username <span class="text-rose-500">*</span></label>
                            <input type="text" name="username" id="field-username" placeholder="username" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all" required>
                        </div>
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-slate-700">Role <span class="text-rose-500">*</span></label>
                            <select name="role" id="field-role" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all" required>
                                <option value="Kasir">Kasir</option>
                                <option value="Administrator">Administrator</option>
                            </select>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700">Email <span class="text-rose-500">*</span></label>
                        <input type="email" name="email" id="field-email" placeholder="email@apotek.com" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all" required>
                    </div>

                    <div class="space-y-2">
                        <label class="text-sm font-bold text-slate-700" id="label-password">Password <span class="text-rose-500">*</span></label>
                        <input type="password" name="password" id="field-password" placeholder="••••••••" class="w-full px-4 py-3 bg-white border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-4 focus:ring-emerald-500/10 focus:border-emerald-500 transition-all">
                        <p class="text-[10px] text-slate-400" id="hint-password"></p>
                    </div>

                    <div class="flex items-center gap-2 py-2">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" id="field-active" value="1" checked class="w-4 h-4 text-emerald-600 rounded border-slate-300 focus:ring-emerald-500">
                        <label for="field-active" class="text-sm font-medium text-slate-700">Akun Aktif</label>
                    </div>
                </div>

                <div class="px-6 py-5 bg-slate-50 border-t flex justify-end gap-3">
                    <button type="button" onclick="toggleModal('modal-user')" class="px-6 py-2.5 bg-white border border-slate-200 text-slate-600 text-sm font-bold rounded-xl hover:bg-slate-100 transition-colors">
                        Batal
                    </button>
                    <button type="submit" class="px-6 py-2.5 bg-emerald-500 text-white text-sm font-bold rounded-xl hover:bg-emerald-600 shadow-sm shadow-emerald-200 transition-all active:scale-95">
                        Simpan Pengguna
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
        document.getElementById('modal-title').innerText = 'Tambah Pengguna';
        document.getElementById('form-user').action = "{{ route('admin.users.store') }}";
        document.getElementById('method-field').innerHTML = '';
        document.getElementById('form-user').reset();
        document.getElementById('field-password').required = true;
        document.getElementById('hint-password').innerText = '';
        toggleModal('modal-user');
    }

    function openEditModal(user) {
        document.getElementById('modal-title').innerText = 'Edit Data Pengguna';
        document.getElementById('form-user').action = `/admin/users/${user.id}`;
        document.getElementById('method-field').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        
        // Fill fields
        document.getElementById('field-name').value = user.name;
        document.getElementById('field-username').value = user.username;
        document.getElementById('field-email').value = user.email;
        document.getElementById('field-role').value = user.role;
        document.getElementById('field-active').checked = user.is_active;
        
        document.getElementById('field-password').required = false;
        document.getElementById('hint-password').innerText = '*Kosongkan jika tidak ingin mengubah password';

        toggleModal('modal-user');
    }

    async function toggleUserStatus(userId) {
        try {
            const response = await fetch(`/admin/users/${userId}/toggle`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                }
            });
            const data = await response.json();
            if (!data.success) {
                alert(data.message);
                window.location.reload();
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
</script>
@endpush
