<!-- Mobile Overlay -->
<div id="sidebar-overlay" class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm z-40 hidden md:hidden"></div>

<aside id="sidebar-admin" class="fixed inset-y-0 left-0 w-72 bg-white border-r border-slate-200 flex flex-col z-50 transform -translate-x-full transition-transform duration-300 ease-in-out md:relative md:translate-x-0 md:flex shadow-[4px_0_24px_rgba(0,0,0,0.02)]">
    <!-- Logo Area -->
    <div class="h-20 flex items-center px-8 border-b border-slate-100 shrink-0">
        <div class="flex items-center gap-3">
            @php
                $logo = \App\Models\Setting::getValue('app_logo');
                $fullName = \App\Models\Setting::getValue('app_name', 'Apotek Sejahtera');
                $nameParts = explode(' ', $fullName, 2);
                $firstName = $nameParts[0] ?? 'Apotek';
                $lastName = $nameParts[1] ?? 'Sejahtera';
            @endphp
            
            @if($logo)
                <div class="w-10 h-10 rounded-xl overflow-hidden shadow-sm shadow-emerald-200">
                    <img src="{{ asset('storage/' . $logo) }}" alt="Logo" class="w-full h-full object-cover">
                </div>
            @else
                <div class="w-10 h-10 bg-emerald-500 rounded-xl flex items-center justify-center shadow-sm shadow-emerald-200">
                    <i class="ph ph-activity text-white text-2xl"></i>
                </div>
            @endif

            <div>
                <h1 class="text-lg font-bold text-slate-800 tracking-tight leading-none">{{ strtoupper($firstName) }}</h1>
                <p class="text-sm font-medium text-emerald-600 tracking-wide uppercase">{{ $lastName }}</p>
            </div>
        </div>
    </div>

    <!-- Menu Items -->
    <div class="flex-1 overflow-y-auto py-6 px-4 space-y-1.5" id="sidebar-menu">
        <p class="px-4 text-xs font-semibold text-slate-400 uppercase tracking-wider mb-4">Main Menu</p>
        
        @php
            $menus = [
                ['route' => 'admin.dashboard', 'icon' => 'ph-squares-four', 'label' => 'Dashboard'],
                ['route' => 'admin.obat', 'icon' => 'ph-pill', 'label' => 'Manajemen Obat'],
                ['route' => 'admin.stok', 'icon' => 'ph-archive', 'label' => 'Stok & Kadaluarsa'],
                ['route' => 'admin.supplier', 'icon' => 'ph-truck', 'label' => 'Supplier'],
                ['route' => 'admin.laporan', 'icon' => 'ph-chart-bar', 'label' => 'Laporan'],
                ['route' => 'admin.users', 'icon' => 'ph-users', 'label' => 'User Management'],
                ['route' => 'admin.pengaturan', 'icon' => 'ph-gear', 'label' => 'Pengaturan'],
            ];
        @endphp

        @foreach($menus as $menu)
            @php
                $isActive = request()->routeIs($menu['route']);
            @endphp
            <a href="{{ route($menu['route']) }}" 
               class="w-full flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ $isActive ? 'bg-emerald-50 text-emerald-700 font-medium shadow-sm' : 'text-slate-500 hover:bg-slate-50 hover:text-slate-700' }}">
                <i class="ph {{ $menu['icon'] }} text-xl {{ $isActive ? 'text-emerald-600' : 'text-slate-400' }}"></i>
                <span class="text-sm">{{ $menu['label'] }}</span>
                @if($isActive)
                    <div class="active-dot ml-auto w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                @endif
            </a>
        @endforeach
    </div>
    
    <!-- Help/Support Card -->
    <div class="p-6 mt-auto shrink-0">
        <div class="bg-slate-900 rounded-2xl p-5 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-24 h-24 bg-white opacity-5 rounded-full blur-xl -mr-10 -mt-10"></div>
            <p class="text-white text-sm font-medium mb-1">Butuh Bantuan?</p>
            <p class="text-slate-400 text-xs mb-4">Kontak tim IT support</p>
            <button class="w-full bg-emerald-500 hover:bg-emerald-400 text-white text-xs font-medium py-2 rounded-lg transition-colors">
                Hubungi Support
            </button>
        </div>
    </div>
</aside>
