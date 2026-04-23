@extends('layouts.admin')

@section('title', 'Laporan & Analitik')

@section('content')
<!-- VIEW: LAPORAN -->
<div id="view-laporan" class="max-w-7xl mx-auto space-y-6">
    
    <!-- Header View & Exports -->
    <div class="flex flex-col md:flex-row md:justify-between md:items-end gap-4">
        <div>
            <h2 class="text-2xl font-bold text-slate-800">Laporan & Analitik</h2>
            <p class="text-sm text-slate-500 mt-1">Analisis penjualan, performa obat, dan ringkasan finansial.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <button class="px-4 py-2.5 bg-white border border-rose-200 hover:bg-rose-50 text-rose-600 text-sm font-semibold rounded-xl shadow-sm transition-colors flex items-center gap-2">
                <i class="ph ph-file-pdf text-lg"></i> Export PDF
            </button>
            <button class="px-4 py-2.5 bg-white border border-emerald-200 hover:bg-emerald-50 text-emerald-600 text-sm font-semibold rounded-xl shadow-sm transition-colors flex items-center gap-2">
                <i class="ph ph-file-xls text-lg"></i> Export Excel
            </button>
        </div>
    </div>

    <!-- Filter Bar -->
    <form action="{{ route('admin.laporan') }}" method="GET" class="bg-white p-4 border border-slate-200 rounded-2xl shadow-sm flex flex-col md:flex-row items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
            <!-- Range Picker Custom -->
            <div class="flex items-center gap-2 bg-white border border-slate-200 rounded-lg px-3 py-1.5 w-full md:w-auto">
                <i class="ph ph-calendar-blank text-slate-400"></i>
                <input type="date" name="start_date" value="{{ $startDate }}" class="text-sm font-medium text-slate-600 bg-transparent outline-none cursor-pointer">
                <span class="text-slate-400 text-sm">-</span>
                <input type="date" name="end_date" value="{{ $endDate }}" class="text-sm font-medium text-slate-600 bg-transparent outline-none cursor-pointer">
            </div>
        </div>
        
        <button type="submit" class="w-full md:w-auto px-5 py-2 bg-slate-800 hover:bg-slate-700 text-white text-sm font-semibold rounded-lg shadow-sm transition-colors">
            Terapkan Filter
        </button>
    </form>

    <!-- 3 Quick Stats untuk Laporan -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 shrink-0">
                <i class="ph-fill ph-receipt text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Transaksi</p>
                <h4 class="text-2xl font-bold text-slate-800">{{ number_format($stats['total_transactions']) }}</h4>
            </div>
        </div>
        <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 shrink-0">
                <i class="ph-fill ph-money text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Total Pendapatan</p>
                <h4 class="text-2xl font-bold text-slate-800">Rp {{ number_format($stats['total_revenue'] / 1000000, 1) }}M</h4>
            </div>
        </div>
        <div class="bg-white p-5 border border-slate-200 rounded-2xl shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 bg-purple-50 rounded-xl flex items-center justify-center text-purple-600 shrink-0">
                <i class="ph-fill ph-chart-line-up text-2xl"></i>
            </div>
            <div>
                <p class="text-sm font-medium text-slate-500">Estimasi Laba Kotor</p>
                <h4 class="text-2xl font-bold text-slate-800">Rp {{ number_format($stats['gross_profit'] / 1000000, 1) }}M</h4>
            </div>
        </div>
    </div>

    <!-- Content Split: Chart & Data -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        
        <!-- Laporan Grafik (Bar Chart css) -->
        <div class="xl:col-span-2 bg-white border border-slate-200 rounded-2xl shadow-sm p-6">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h3 class="text-lg font-bold text-slate-800">Grafik Pendapatan</h3>
                    <p class="text-sm text-slate-500">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d M') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</p>
                </div>
            </div>

            <!-- CSS Bar Chart -->
            <div class="h-64 flex items-end justify-between gap-2 px-2 relative">
                <div class="absolute inset-0 flex flex-col justify-between z-0">
                    @for($i=0; $i<5; $i++)
                        <div class="w-full h-px bg-slate-100"></div>
                    @endfor
                </div>

                @php
                    $maxVal = $chartData->max('total') ?: 1;
                @endphp
                @foreach($chartData as $index => $data)
                    @php
                        $height = ($data->total / $maxVal) * 100;
                    @endphp
                    <div class="w-full flex flex-col items-center gap-2 z-10 group cursor-pointer">
                        <div class="w-full max-w-[40px] {{ $index == count($chartData)-1 ? 'bg-emerald-500 shadow-[0_0_15px_rgba(16,185,129,0.3)]' : 'bg-emerald-100 hover:bg-emerald-500' }} transition-colors rounded-t-md relative" style="height: {{ max($height, 5) }}%;">
                            <div class="absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] py-1 px-2 rounded opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap pointer-events-none">Rp {{ number_format($data->total / 1000000, 1) }}M</div>
                        </div>
                        <span class="text-xs font-medium text-slate-400">Minggu {{ $index + 1 }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Laporan Kategori (Pie Chart Info) -->
        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm p-6 flex flex-col">
            <h3 class="text-lg font-bold text-slate-800 mb-6">Produk per Kategori</h3>
            
            <div class="flex-1 flex flex-col justify-center space-y-5">
                @php
                    $colors = ['emerald', 'blue', 'purple', 'amber', 'rose', 'indigo'];
                    $total = $categories->sum('count');
                @endphp
                @foreach($categories as $index => $category)
                    @php 
                        $percentage = $total > 0 ? round(($category->count / $total) * 100) : 0;
                        $color = $colors[$index % count($colors)];
                    @endphp
                    <div class="flex items-center gap-4">
                        <div class="w-3 h-3 rounded-full bg-{{ $color }}-500"></div>
                        <div class="flex-1">
                            <div class="flex justify-between text-sm mb-1">
                                <span class="font-semibold text-slate-700">{{ $category->category }}</span>
                                <span class="font-bold text-slate-800">{{ $percentage }}%</span>
                            </div>
                            <p class="text-xs text-slate-500">{{ $category->count }} Produk</p>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <a href="{{ route('admin.obat') }}" class="mt-6 w-full py-2.5 bg-slate-50 hover:bg-slate-100 text-slate-600 text-sm font-semibold rounded-lg transition-colors text-center">
                Lihat Detail Inventaris
            </a>
        </div>

    </div>

    <!-- Tabel Rincian Data (Report Table) -->
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-lg font-bold text-slate-800">Rincian Transaksi Terbaru</h3>
        </div>
        
        <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">No. Invoice</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Kasir</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Metode Bayar</th>
                        <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Total Transaksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($latestSales as $sale)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-sm font-medium text-slate-800">{{ $sale->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-slate-500">{{ $sale->created_at->format('H:i') }} WIB</p>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-sm font-mono text-emerald-600 font-semibold cursor-pointer hover:underline">{{ $sale->invoice_number }}</span>
                            </td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $sale->cashier_name }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $methodColor = str_contains($sale->payment_method, 'QRIS') ? 'blue' : (str_contains($sale->payment_method, 'Tunai') ? 'emerald' : 'purple');
                                @endphp
                                <span class="inline-flex text-[11px] font-semibold bg-{{ $methodColor }}-50 text-{{ $methodColor }}-600 px-2.5 py-1 rounded-md border border-{{ $methodColor }}-100">{{ $sale->payment_method }}</span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <p class="text-sm font-bold text-slate-800">Rp {{ number_format($sale->total_amount) }}</p>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-500">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
