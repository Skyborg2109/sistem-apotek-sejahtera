<header class="h-20 bg-white border-b border-slate-200 flex items-center justify-between px-4 md:px-8 z-10 sticky top-0 shrink-0">
    <div class="flex items-center gap-4 flex-1">
        <!-- Mobile Menu Toggle -->
        <button id="btn-sidebar-mobile" class="md:hidden p-2 text-slate-500 hover:bg-slate-100 rounded-lg">
            <i class="ph ph-list text-2xl"></i>
        </button>

        <div class="relative w-full max-w-md hidden sm:block">
            <i class="ph ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
            <input 
                type="text" 
                placeholder="Cari obat, resep, atau supplier..." 
                class="w-full pl-10 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all placeholder:text-slate-400"
            />
        </div>
    </div>

    <div class="flex items-center gap-4 md:gap-6">
        <!-- Mobile Search Toggle -->
        <button class="sm:hidden relative p-2 text-slate-400 hover:text-slate-600 transition-colors">
            <i class="ph ph-magnifying-glass text-xl"></i>
        </button>

        <!-- NOTIFICATION WRAPPER -->
        <div class="relative" id="dropdown-wrapper-notif">
            <button id="btn-notif" class="relative p-2 text-slate-400 hover:text-slate-600 transition-colors focus:outline-none rounded-lg hover:bg-slate-50">
                <i class="ph ph-bell text-xl"></i>
                <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-rose-500 rounded-full border-2 border-white"></span>
            </button>
            
            <!-- Notif Dropdown Panel -->
            <div id="panel-notif" class="absolute right-0 mt-3 w-80 sm:w-96 bg-white rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-slate-100 hidden z-50 overflow-hidden transform origin-top-right transition-all">
                <div class="px-4 py-3 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="text-sm font-bold text-slate-800">Notifikasi</h3>
                    <button class="text-xs font-semibold text-emerald-600 hover:text-emerald-700">Tandai sudah dibaca</button>
                </div>
                <div class="max-h-[320px] overflow-y-auto no-scrollbar">
                    <a href="#" class="flex gap-3 p-4 border-b border-slate-50 hover:bg-slate-50 transition-colors bg-blue-50/30">
                        <div class="w-8 h-8 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center shrink-0 mt-0.5">
                            <i class="ph ph-warning"></i>
                        </div>
                        <div>
                            <p class="text-sm font-semibold text-slate-800">Stok Paracetamol Menipis</p>
                            <p class="text-xs text-slate-500 mt-0.5">Sisa stok 12 botol. Segera lakukan restock ulang.</p>
                            <p class="text-[10px] font-medium text-slate-400 mt-1.5">10 menit yang lalu</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>

        <div class="w-px h-8 bg-slate-200 hidden md:block"></div>
        
        <!-- PROFILE WRAPPER -->
        <div class="relative" id="dropdown-wrapper-profile">
            <button id="btn-profile" class="flex items-center gap-3 text-left hover:opacity-80 transition-opacity focus:outline-none">
                <div class="w-10 h-10 rounded-full bg-emerald-100 border border-emerald-200 flex items-center justify-center overflow-hidden shrink-0">
                    @if(Auth::user() && Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Admin" class="w-full h-full object-cover" />
                    @else
                        <img src="https://api.dicebear.com/7.x/notionists/svg?seed={{ Auth::user()->username ?? 'Guest' }}&backgroundColor=d1fae5" alt="Admin" class="w-full h-full object-cover" />
                    @endif
                </div>
                <div class="hidden md:block">
                    <p class="text-sm font-semibold text-slate-800">{{ Auth::user()->name ?? 'Guest User' }}</p>
                    <p class="text-xs text-slate-500">{{ Auth::user()->role ?? 'Visitor' }}</p>
                </div>
                <i class="ph ph-caret-down text-sm text-slate-400 hidden md:block"></i>
            </button>
            
            <!-- Profil Dropdown Panel -->
            <div id="panel-profile" class="absolute right-0 mt-3 w-56 bg-white rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-slate-100 hidden z-50 overflow-hidden transform origin-top-right transition-all">
                <div class="p-3 border-b border-slate-50">
                    <p class="text-sm font-bold text-slate-800">{{ Auth::user()->name ?? 'Guest User' }}</p>
                    <p class="text-xs text-slate-500">{{ Auth::user()->email ?? 'guest@example.com' }}</p>
                </div>
                <div class="p-1">
                    <a href="{{ route('admin.profil') }}" class="w-full text-left px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-emerald-600 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ph ph-user text-lg"></i> Profil Saya
                    </a>
                    <a href="{{ route('admin.pengaturan') }}" class="w-full text-left px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-emerald-600 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ph ph-gear text-lg"></i> Pengaturan
                    </a>
                </div>
                <div class="p-1 border-t border-slate-50">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-sm text-rose-600 hover:bg-rose-50 rounded-lg transition-colors flex items-center gap-2">
                            <i class="ph ph-sign-out text-lg"></i> Keluar
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>
