@extends('layouts.admin')

@section('title', 'Monitor Stok & Kadaluarsa')

@section('content')
<!-- VIEW: STOK & KADALUARSA -->
<div id="view-stok" class="max-w-7xl mx-auto space-y-6">
    
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Stok & Kadaluarsa</h2>
            <p class="text-sm text-slate-500 mt-1">Pantau ketersediaan, pergerakan batch, dan peringatan masa berlaku obat.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button class="px-4 py-2.5 bg-emerald-50 text-emerald-600 hover:bg-emerald-100 text-sm font-semibold rounded-xl transition-colors flex items-center gap-2">
                <i class="ph ph-arrows-clockwise text-lg"></i> Sinkronisasi
            </button>
            <button class="px-4 py-2.5 bg-white border border-slate-200 hover:bg-slate-50 text-slate-600 text-sm font-semibold rounded-xl shadow-sm transition-colors flex items-center gap-2">
                <i class="ph ph-download-simple text-lg text-emerald-600"></i> Export Laporan
            </button>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Low Stock -->
        <a href="{{ route('admin.stok', ['filter' => 'low_stock']) }}" class="bg-white p-6 rounded-2xl border-2 {{ $filter == 'low_stock' ? 'border-amber-500 shadow-amber-100' : 'border-transparent shadow-sm' }} hover:shadow-md transition-all group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Stok Menipis</p>
                    <h3 class="text-3xl font-bold text-amber-600">{{ $stats['low_stock'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ph ph-warning-diamond text-2xl"></i>
                </div>
            </div>
            <p class="text-xs text-slate-400 mt-4">Stok kurang dari 20 unit</p>
        </a>

        <!-- Expiring Soon -->
        <a href="{{ route('admin.stok', ['filter' => 'expiring_soon']) }}" class="bg-white p-6 rounded-2xl border-2 {{ $filter == 'expiring_soon' ? 'border-indigo-500 shadow-indigo-100' : 'border-transparent shadow-sm' }} hover:shadow-md transition-all group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Hampir Kadaluarsa</p>
                    <h3 class="text-3xl font-bold text-indigo-600">{{ $stats['expiring_soon'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-indigo-50 text-indigo-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ph ph-hourglass text-2xl"></i>
                </div>
            </div>
            <p class="text-xs text-slate-400 mt-4">Dalam 30 hari ke depan</p>
        </a>

        <!-- Expired -->
        <a href="{{ route('admin.stok', ['filter' => 'expired']) }}" class="bg-white p-6 rounded-2xl border-2 {{ $filter == 'expired' ? 'border-rose-500 shadow-rose-100' : 'border-transparent shadow-sm' }} hover:shadow-md transition-all group">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-sm font-medium text-slate-500 mb-1">Sudah Kadaluarsa</p>
                    <h3 class="text-3xl font-bold text-rose-600">{{ $stats['expired'] }}</h3>
                </div>
                <div class="w-12 h-12 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="ph ph-skull text-2xl"></i>
                </div>
            </div>
            <p class="text-xs text-slate-400 mt-4">Sudah melewati masa berlaku</p>
        </a>
    </div>

    <!-- Tabel Container -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        
        <!-- Toolbars (Search & Filter) -->
        <div class="p-5 border-b border-slate-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
            <!-- Search -->
            <div class="relative max-w-md w-full">
                <i class="ph ph-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 text-lg"></i>
                <input type="text" placeholder="Cari nama obat atau no. batch..." class="w-full pl-10 pr-4 py-2 bg-slate-50 border border-slate-200 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 transition-all placeholder:text-slate-400">
            </div>
            
            <!-- Filters -->
            <div class="flex flex-wrap items-center gap-3">
                <div class="relative">
                    <select onchange="window.location.href=this.value" class="appearance-none pl-4 pr-10 py-2 bg-white border border-slate-200 rounded-lg text-sm font-medium text-slate-600 focus:outline-none focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500 cursor-pointer">
                        <option value="{{ route('admin.stok') }}">Semua Status</option>
                        <option value="{{ route('admin.stok', ['filter' => 'expired']) }}" {{ $filter == 'expired' ? 'selected' : '' }}>⚠️ Expired</option>
                        <option value="{{ route('admin.stok', ['filter' => 'low_stock']) }}" {{ $filter == 'low_stock' ? 'selected' : '' }}>🟡 Hampir Habis</option>
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
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">No. Batch</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Sisa Stok</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal Kadaluarsa</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($medicines as $medicine)
                        @php
                            $expiryDate = \Carbon\Carbon::parse($medicine->expiry_date);
                            $isExpired = $expiryDate->isPast();
                            $isNearExp = $expiryDate->diffInDays(now()) <= 30 && !$isExpired;
                            $isLowStock = $medicine->stock < 20;
                            $isOutOfStock = $medicine->stock == 0;

                            $rowClass = $isExpired ? 'bg-rose-50/20 hover:bg-rose-50/40' : ($isLowStock ? 'bg-amber-50/20 hover:bg-amber-50/40' : 'hover:bg-slate-50');
                        @endphp
                        <tr class="{{ $rowClass }} transition-colors group">
                            <td class="px-6 py-4">
                                <div>
                                    <p class="text-sm font-semibold text-slate-800">{{ $medicine->name }}</p>
                                    <p class="text-xs text-slate-500">SKU: {{ $medicine->sku }}</p>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-mono font-medium text-slate-600">{{ strtoupper(substr($medicine->sku, -4)) }}-{{ rand(1000, 9999) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium {{ $isOutOfStock || $isLowStock ? 'text-rose-600 font-bold' : 'text-slate-800' }}">
                                    {{ $medicine->stock }} <span class="text-xs text-slate-500 font-normal">{{ $medicine->unit }}</span>
                                </p>
                            </td>
                            <td class="px-6 py-4">
                                <p class="text-sm font-bold {{ $isExpired ? 'text-rose-600' : ($isNearExp ? 'text-amber-600' : 'text-slate-800') }}">
                                    {{ $expiryDate->format('d M Y') }}
                                </p>
                                @if($isExpired)
                                    <p class="text-xs text-rose-500 font-medium">Telah lewat {{ $expiryDate->diffInDays(now()) }} Hari</p>
                                @elseif($isNearExp)
                                    <p class="text-xs text-amber-500 font-medium">{{ $expiryDate->diffInDays(now()) }} Hari lagi</p>
                                @else
                                    <p class="text-xs text-slate-500 font-medium">{{ $expiryDate->diffInMonths(now()) }} Bulan lagi</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                @if($isExpired)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-bold bg-slate-800 text-white shadow-sm">
                                        <i class="ph-fill ph-warning-circle text-rose-400 text-sm"></i> Expired
                                    </span>
                                @elseif($isOutOfStock)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-rose-50 border border-rose-200 text-rose-600 shadow-sm">
                                        <span class="w-2 h-2 rounded-full bg-rose-500"></span> Habis
                                    </span>
                                @elseif($isLowStock)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-amber-50 border border-amber-200 text-amber-700 shadow-sm">
                                        <span class="w-2 h-2 rounded-full bg-amber-500"></span> Hampir Habis
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-emerald-50 border border-emerald-200 text-emerald-700 shadow-sm">
                                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span> Aman
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if($isExpired)
                                    <button class="px-3 py-1.5 text-xs font-semibold text-rose-600 border border-rose-200 bg-white hover:bg-rose-50 rounded-lg shadow-sm transition-colors opacity-0 group-hover:opacity-100">
                                        Buang / Retur
                                    </button>
                                @else
                                    <a href="{{ route('admin.obat') }}" class="px-3 py-1.5 text-xs font-semibold text-emerald-600 border border-emerald-200 bg-white hover:bg-emerald-50 rounded-lg shadow-sm transition-colors opacity-0 group-hover:opacity-100 inline-block">
                                        Restock
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-slate-500">Data tidak ditemukan.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-slate-100 bg-slate-50/30">
            {{ $medicines->appends(['filter' => $filter])->links() }}
        </div>
    </div>
</div>
@endsection
