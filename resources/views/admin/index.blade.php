@extends('layouts.admin')

@section('title', 'Overview Dashboard')

@section('content')
<!-- VIEW: DASHBOARD -->
<div id="view-dashboard" class="max-w-7xl mx-auto space-y-8">
    
    <!-- Header Title -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Overview Hari Ini</h2>
            <p class="text-sm text-slate-500 mt-1">Pantau aktivitas dan performa apotek secara real-time.</p>
        </div>
        
        <!-- DATE WRAPPER -->
        <div class="relative" id="dropdown-wrapper-date">
            <button id="btn-date" class="px-4 py-2 bg-white border border-slate-200 rounded-lg shadow-sm text-sm font-medium text-slate-600 flex items-center gap-2 hover:bg-slate-50 transition-colors focus:outline-none w-full md:w-auto justify-between md:justify-start">
                <i class="ph ph-calendar-blank text-slate-400"></i>
                <span>{{ now()->format('d F Y') }}</span>
                <i class="ph ph-caret-down text-slate-400 ml-1"></i>
            </button>
            
            <!-- Date Dropdown Panel -->
            <div id="panel-date" class="absolute right-0 mt-2 w-52 bg-white rounded-xl shadow-[0_10px_40px_-10px_rgba(0,0,0,0.1)] border border-slate-100 hidden z-50 overflow-hidden">
                <div class="p-1">
                    <button class="w-full text-left px-3 py-2 text-sm text-emerald-700 bg-emerald-50 font-semibold rounded-lg transition-colors flex items-center justify-between">
                        Hari Ini <i class="ph ph-check text-emerald-600"></i>
                    </button>
                    <button class="w-full text-left px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-emerald-600 rounded-lg transition-colors">
                        Kemarin
                    </button>
                    <button class="w-full text-left px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-emerald-600 rounded-lg transition-colors">
                        7 Hari Terakhir
                    </button>
                    <button class="w-full text-left px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-emerald-600 rounded-lg transition-colors">
                        30 Hari Terakhir
                    </button>
                </div>
                <div class="p-1 border-t border-slate-50">
                    <button class="w-full text-left px-3 py-2 text-sm text-slate-600 hover:bg-slate-50 hover:text-emerald-600 rounded-lg transition-colors flex items-center gap-2">
                        <i class="ph ph-calendar-plus text-lg"></i> Pilih Kustom...
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- 1. STATISTIK CARDS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Card 1 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total Obat Aktif</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ number_format($stats['total_medicines']) }}</h3>
                </div>
                <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ph ph-pill text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-xs">
                <span class="flex items-center text-emerald-600 font-medium bg-emerald-50 px-2 py-0.5 rounded-md">
                    <i class="ph ph-trend-up mr-1"></i> +12%
                </span>
                <span class="text-slate-400">dari bulan lalu</span>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Total Supplier</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ $stats['total_suppliers'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ph ph-truck text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-xs">
                <span class="text-slate-500">Pemasok obat terdaftar</span>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Penjualan Hari Ini</p>
                    <h3 class="text-3xl font-bold text-slate-800">{{ $stats['today_sales'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ph ph-shopping-cart text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-xs">
                <span class="flex items-center text-emerald-600 font-medium bg-emerald-50 px-2 py-0.5 rounded-md">
                    <i class="ph ph-trend-up mr-1"></i> +5.2%
                </span>
                <span class="text-slate-400">vs kemarin</span>
            </div>
        </div>

        <!-- Card 4 -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm hover:shadow-md transition-shadow group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Pendapatan Bulanan</p>
                    <h3 class="text-3xl font-bold text-slate-800 tracking-tight">Rp {{ $stats['monthly_revenue'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ph ph-wallet text-2xl"></i>
                </div>
            </div>
            <div class="mt-4 flex items-center gap-2 text-xs">
                <div class="w-full bg-slate-100 h-1.5 rounded-full overflow-hidden">
                    <div class="bg-amber-500 h-full rounded-full" style="width: {{ $stats['revenue_target_percent'] }}%"></div>
                </div>
                <span class="text-slate-500 whitespace-nowrap">{{ $stats['revenue_target_percent'] }}% Target</span>
            </div>
        </div>
    </div>

    <!-- MAIN CHARTS AND ALERTS ROW -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- 2. GRAFIK PENJUALAN (Line Chart) -->
        <div class="lg:col-span-2 bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Tren Penjualan</h3>
                    <p class="text-sm text-slate-500">Statistik transaksi 7 hari terakhir</p>
                </div>
                <button class="p-2 hover:bg-slate-50 rounded-lg transition-colors">
                    <i class="ph ph-dots-three-vertical text-xl text-slate-400"></i>
                </button>
            </div>
            
            <!-- Mockup Line Chart using SVG -->
            <div class="relative w-full h-64 mt-4">
                <svg class="w-full h-full overflow-visible" preserveAspectRatio="none" viewBox="0 0 500 150">
                    <defs>
                        <linearGradient id="gradientLine" x1="0%" y1="0%" x2="0%" y2="100%">
                            <stop offset="0%" stop-color="#10b981" stop-opacity="0.4" />
                            <stop offset="100%" stop-color="#10b981" stop-opacity="0" />
                        </linearGradient>
                    </defs>
                    <!-- Grid lines -->
                    <path d="M0,30 L500,30" stroke="#f1f5f9" stroke-width="1" />
                    <path d="M0,70 L500,70" stroke="#f1f5f9" stroke-width="1" />
                    <path d="M0,110 L500,110" stroke="#f1f5f9" stroke-width="1" />
                    <path d="M0,150 L500,150" stroke="#f1f5f9" stroke-width="1" />
                    
                    <!-- Gradient Area -->
                    <path d="M0,120 C50,110 100,140 150,90 C200,40 250,80 300,60 C350,40 400,70 450,30 L500,20 L500,150 L0,150 Z" fill="url(#gradientLine)" />
                    
                    <!-- Line -->
                    <path d="M0,120 C50,110 100,140 150,90 C200,40 250,80 300,60 C350,40 400,70 450,30 L500,20" fill="none" stroke="#10b981" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                    
                    <!-- Data Points -->
                    <circle cx="150" cy="90" r="4" fill="#fff" stroke="#10b981" stroke-width="2" class="cursor-pointer hover:r-[6px] transition-all duration-200" />
                    <circle cx="300" cy="60" r="4" fill="#fff" stroke="#10b981" stroke-width="2" class="cursor-pointer hover:r-[6px] transition-all duration-200" />
                    <circle cx="450" cy="30" r="4" fill="#fff" stroke="#10b981" stroke-width="2" class="cursor-pointer hover:r-[6px] transition-all duration-200" />
                </svg>
                
                <!-- X-Axis Labels -->
                <div class="absolute -bottom-6 left-0 right-0 flex justify-between text-xs text-slate-400 px-2">
                    <span class="hidden sm:inline">17 Apr</span>
                    <span>18 Apr</span>
                    <span class="hidden sm:inline">19 Apr</span>
                    <span>20 Apr</span>
                    <span class="hidden sm:inline">21 Apr</span>
                    <span>22 Apr</span>
                    <span>Hari Ini</span>
                </div>
            </div>
        </div>

        <!-- 3. ALERTS & NOTIFICATIONS -->
        <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm flex flex-col">
            <div class="flex justify-between items-center mb-6">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Peringatan Sistem</h3>
                    <p class="text-sm text-slate-500">Butuh perhatian segera</p>
                </div>
                <div class="bg-rose-100 text-rose-600 text-xs font-bold px-2 py-1 rounded-md">{{ $alerts->count() }} Alert</div>
            </div>
            
            <div class="flex-1 space-y-4 overflow-y-auto pr-2 no-scrollbar max-h-[400px]">
                @forelse($alerts as $alert)
                    @php
                        $isExpiredSoon = $alert->expiry_date < now()->addDays(30);
                        $isLowStock = $alert->stock < 20;
                    @endphp
                    <div class="flex gap-4 p-3 rounded-xl border {{ $isExpiredSoon ? 'border-rose-100 bg-rose-50/50' : 'border-amber-100 bg-amber-50/50' }} hover:shadow-sm transition-all group">
                        <div class="mt-0.5">
                            @if($isExpiredSoon)
                                <i class="ph ph-clock text-xl text-rose-500"></i>
                            @else
                                <i class="ph ph-warning text-xl text-amber-500"></i>
                            @endif
                        </div>
                        <div class="w-full">
                            <h4 class="text-sm font-semibold text-slate-800">{{ $alert->name }}</h4>
                            <p class="text-xs text-slate-500 mt-0.5">@if($isLowStock) Sisa: {{ $alert->stock }} unit @else Batch #{{ strtoupper(Str::random(5)) }} @endif</p>
                            <div class="mt-2 flex items-center justify-between w-full">
                                <span class="inline-flex text-[11px] font-medium bg-white border {{ $isExpiredSoon ? 'border-rose-200 text-rose-600' : 'border-amber-200 text-amber-600' }} px-2 py-0.5 rounded shadow-sm">
                                    {{ $isExpiredSoon ? 'Kadaluarsa ' . \Carbon\Carbon::parse($alert->expiry_date)->diffForHumans() : 'Stok Menipis' }}
                                </span>
                                <button class="text-xs {{ $isExpiredSoon ? 'text-rose-600' : 'text-amber-600' }} font-semibold hover:underline">Detail</button>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-10">
                        <i class="ph ph-check-circle text-4xl text-emerald-500 mb-2"></i>
                        <p class="text-sm text-slate-500">Sistem berjalan optimal.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- 4. OBAT TERLARIS -->
    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h3 class="text-lg font-bold text-slate-800">Obat Terlaris Bulan Ini</h3>
                <p class="text-sm text-slate-500">Berdasarkan volume keluar</p>
            </div>
            <a href="{{ route('admin.laporan') }}" class="text-sm text-emerald-600 font-semibold hover:text-emerald-700 transition-colors">Lihat Laporan</a>
        </div>

        <div class="space-y-5">
            @foreach($best_sellers as $index => $bs)
                @php
                    $percentage = 100 - ($index * 15);
                    $colors = ['bg-emerald-500', 'bg-emerald-400', 'bg-teal-400', 'bg-cyan-400', 'bg-sky-400'];
                @endphp
                <div class="flex items-center gap-4 group">
                    <div class="w-8 text-sm font-bold text-slate-400 text-right">#{{ $index + 1 }}</div>
                    <div class="flex-1">
                        <div class="flex justify-between text-sm mb-1.5">
                            <span class="font-semibold text-slate-700">{{ $bs->name }}</span>
                            <span class="font-medium text-slate-500">{{ rand(500, 1500) }} Terjual</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $colors[$index] ?? 'bg-emerald-500' }} group-hover:opacity-80 transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Toggle Date Dropdown
    const btnDate = document.getElementById('btn-date');
    const panelDate = document.getElementById('panel-date');
    
    if(btnDate && panelDate) {
        btnDate.addEventListener('click', (e) => {
            e.stopPropagation();
            panelDate.classList.toggle('hidden');
        });

        document.addEventListener('click', (e) => {
            if (!panelDate.contains(e.target) && !btnDate.contains(e.target)) {
                panelDate.classList.add('hidden');
            }
        });
    }
</script>
@endpush
