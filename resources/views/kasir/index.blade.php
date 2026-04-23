<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
  <title>Kasir - {{ \App\Models\Setting::getValue('app_name', 'Apotek Sejahtera') }}</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">
  
  <!-- Tailwind CSS -->
  <script src="https://cdn.tailwindcss.com"></script>
  <!-- Phosphor Icons -->
  <script src="https://unpkg.com/@phosphor-icons/web"></script>

  <style>
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    input[type=number]::-webkit-inner-spin-button, 
    input[type=number]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
    .click-pop:active { transform: scale(0.95); }
    @keyframes fade-in {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
  </style>

  <script>
    tailwind.config = {
      theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'], } } }
    }
  </script>
</head>
<body class="bg-slate-50 font-sans text-slate-800 min-h-screen md:h-screen w-full overflow-x-hidden overflow-y-auto md:overflow-hidden flex flex-col selection:bg-emerald-200">

  <!-- TOP NAVBAR -->
  <header class="bg-white border-b border-slate-200 h-16 shrink-0 flex items-center justify-between px-4 sm:px-6 z-20">
    <div class="flex items-center gap-3">
      @php
          $logo = \App\Models\Setting::getValue('app_logo');
      @endphp
      @if($logo)
        <div class="w-10 h-10 rounded-lg overflow-hidden border border-slate-100">
            <img src="{{ asset('storage/' . $logo) }}" class="w-full h-full object-cover">
        </div>
      @else
        <div class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center shadow-sm">
            <i class="ph ph-activity text-white text-lg"></i>
        </div>
      @endif
      <div>
        <h1 class="text-sm font-bold text-slate-800 leading-tight">{{ \App\Models\Setting::getValue('app_name', 'Apotek Sejahtera') }}</h1>
        <p class="text-[10px] font-semibold text-emerald-600 uppercase tracking-widest">Sistem Kasir</p>
      </div>
    </div>

    <!-- Desktop Nav Links -->
    <nav class="hidden md:flex items-center gap-1 bg-slate-100 p-1 rounded-xl">
      <button class="desk-nav-btn active px-4 py-1.5 text-sm font-bold bg-white text-emerald-600 rounded-lg shadow-sm" data-target="view-transaksi">Transaksi</button>
      <button class="desk-nav-btn px-4 py-1.5 text-sm font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-200 transition-colors rounded-lg" data-target="view-riwayat">Riwayat</button>
      <button class="desk-nav-btn px-4 py-1.5 text-sm font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-200 transition-colors rounded-lg" data-target="view-profil">Shift Kasir</button>
    </nav>

    <div class="flex items-center gap-4">
      <div class="hidden sm:block text-right mr-2">
        <p class="text-sm font-bold text-slate-800" id="clock">00:00 WIB</p>
        <p class="text-[10px] text-slate-500 font-medium uppercase">Kasir: {{ $user->name }}</p>
      </div>
      <button id="btn-top-profile" class="w-9 h-9 rounded-full bg-emerald-100 border border-emerald-200 flex items-center justify-center hover:opacity-80 transition-opacity focus:outline-none focus:ring-2 focus:ring-emerald-500/50">
        @if($user->avatar)
            <img src="{{ asset('storage/' . $user->avatar) }}" alt="Kasir" class="w-full h-full object-cover rounded-full" />
        @else
            <img src="https://api.dicebear.com/7.x/notionists/svg?seed={{ $user->username }}&backgroundColor=dbeafe" alt="Kasir" class="w-full h-full object-cover rounded-full" />
        @endif
      </button>
    </div>
  </header>

  <!-- MAIN POS LAYOUT -->
  <main class="flex-1 flex overflow-hidden relative">
    
    <!-- VIEW 1: TRANSAKSI -->
    <div id="view-transaksi" class="w-full h-full flex relative overflow-hidden">
      <!-- LEFT PANEL: KATALOG OBAT -->
      <section id="panel-produk" class="flex-1 flex flex-col w-full h-full pb-16 md:pb-0 z-10 transition-transform duration-300 overflow-y-auto md:overflow-hidden">
        
        <!-- Search & Filter Bar -->
        <div class="p-4 sm:p-6 pb-2 shrink-0 space-y-4 bg-slate-50">
          <div class="relative w-full">
            <i class="ph ph-magnifying-glass absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-xl"></i>
            <input type="text" id="search-obat" placeholder="Cari obat, scan barcode, atau SKU... (Tekan /)" autofocus autocomplete="off"
              class="w-full pl-12 pr-4 py-3.5 bg-white border-2 border-transparent shadow-[0_2px_15px_-3px_rgba(0,0,0,0.05)] rounded-2xl text-base font-medium focus:outline-none focus:border-emerald-500 focus:ring-0 transition-all placeholder:text-slate-400">
            <button class="absolute right-3 top-1/2 -translate-y-1/2 p-1.5 bg-slate-100 text-slate-500 hover:bg-slate-200 rounded-lg transition-colors">
              <i class="ph ph-barcode text-lg"></i>
            </button>
          </div>
          
          <div class="flex items-center gap-2 overflow-x-auto no-scrollbar pb-2">
            <button class="filter-btn active shrink-0 px-5 py-2 text-sm font-semibold rounded-full bg-slate-800 text-white shadow-sm transition-all" data-filter="all">Semua Obat</button>
            <button class="filter-btn shrink-0 px-5 py-2 text-sm font-medium rounded-full bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 transition-all" data-filter="Bebas">Obat Bebas</button>
            <button class="filter-btn shrink-0 px-5 py-2 text-sm font-medium rounded-full bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 transition-all" data-filter="Resep">Obat Resep</button>
            <button class="filter-btn shrink-0 px-5 py-2 text-sm font-medium rounded-full bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 transition-all" data-filter="Suplemen">Vitamin & Suplemen</button>
            <button class="filter-btn shrink-0 px-5 py-2 text-sm font-medium rounded-full bg-white border border-slate-200 text-slate-600 hover:bg-slate-100 transition-all" data-filter="Alkes">Alat Kesehatan</button>
          </div>
        </div>

        <div class="flex-1 overflow-y-auto p-4 sm:p-6 pt-0 no-scrollbar">
          <div id="product-grid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-5 gap-3 sm:gap-4 pb-4">
            <!-- Injected by JS -->
          </div>
        </div>
      </section>

      <!-- RIGHT PANEL: KERANJANG -->
      <section id="panel-keranjang" class="absolute inset-0 h-full bg-white md:relative md:flex flex-col w-full md:w-[400px] lg:w-[450px] border-l border-slate-200 shadow-[-10px_0_30px_rgba(0,0,0,0.02)] z-20 transform translate-x-full md:translate-x-0 transition-transform duration-300 pb-16 md:pb-0 overflow-y-auto md:overflow-hidden">
        <div class="h-16 px-5 border-b border-slate-100 flex items-center justify-between bg-white shrink-0">
          <div class="flex items-center gap-2">
            <i class="ph-fill ph-shopping-cart text-emerald-500 text-xl"></i>
            <h2 class="text-base font-bold text-slate-800">Pesanan Saat Ini</h2>
          </div>
          <button id="btn-reset" class="text-xs font-semibold text-rose-500 hover:text-rose-600 hover:bg-rose-50 px-3 py-1.5 rounded-lg transition-colors flex items-center gap-1">
            <i class="ph ph-trash"></i> Kosongkan
          </button>
        </div>

        <div id="cart-items" class="flex-1 overflow-y-auto p-4 space-y-3 bg-slate-50/50"></div>

        <div class="bg-white border-t border-slate-200 shrink-0 shadow-[0_-10px_20px_rgba(0,0,0,0.02)]">
          <div class="p-4 sm:p-5 pb-0 space-y-2">
            <div class="flex justify-between text-sm font-medium text-slate-500">
              <span>Subtotal (<span id="cart-count">0</span> Item)</span>
              <span id="subtotal-val">Rp 0</span>
            </div>
            <div class="flex justify-between text-sm font-medium text-slate-500">
              <span>Pajak ({{ \App\Models\Setting::getValue('tax_percentage', 0) }}%)</span>
              <span id="tax-val">Rp 0</span>
            </div>
          </div>

          <div class="px-4 sm:p-5 pt-3">
            <div class="mb-6">
              <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Metode Pembayaran</p>
              <div class="grid grid-cols-2 gap-2">
                <button type="button" class="pay-method-btn active flex flex-col items-center justify-center p-3 rounded-2xl border-2 border-emerald-500 bg-emerald-50 text-emerald-700 transition-all shadow-sm" data-method="Tunai">
                  <i class="ph ph-money text-xl mb-1"></i>
                  <span class="text-[10px] font-bold">Tunai</span>
                </button>
                <button type="button" class="pay-method-btn flex flex-col items-center justify-center p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 text-slate-500 hover:border-slate-200 transition-all" data-method="QRIS">
                  <i class="ph ph-qr-code text-xl mb-1"></i>
                  <span class="text-[10px] font-bold">QRIS</span>
                </button>
                <button type="button" class="pay-method-btn flex flex-col items-center justify-center p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 text-slate-500 hover:border-slate-200 transition-all" data-method="Transfer Bank">
                  <i class="ph ph-bank text-xl mb-1"></i>
                  <span class="text-[10px] font-bold">Transfer</span>
                </button>
                <button type="button" class="pay-method-btn flex flex-col items-center justify-center p-3 rounded-2xl border-2 border-slate-100 bg-slate-50 text-slate-500 hover:border-slate-200 transition-all" data-method="Kartu Debit">
                  <i class="ph ph-credit-card text-xl mb-1"></i>
                  <span class="text-[10px] font-bold">Debit</span>
                </button>
              </div>
            </div>



            <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-1">Total Bayar</p>
            <h2 id="total-val" class="text-4xl font-black text-slate-800 tracking-tight leading-none mb-4">Rp 0</h2>
            
            <div class="grid grid-cols-2 gap-3 mb-4">
              <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-200 relative">
                <label class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block mb-1">Uang Diterima</label>
                <div class="flex items-center">
                  <span class="text-sm font-bold text-slate-400 mr-1">Rp</span>
                  <input type="number" id="input-bayar" placeholder="0" class="w-full bg-transparent text-lg font-bold text-slate-800 focus:outline-none text-right">
                </div>
              </div>
              <div id="box-kembalian" class="bg-blue-50/50 p-2.5 rounded-xl border border-blue-100 flex flex-col items-end justify-center transition-colors">
                <label class="text-[10px] font-bold text-blue-600/70 uppercase tracking-wider block mb-1 w-full text-right">Kembalian</label>
                <p id="kembalian-val" class="text-lg font-bold text-blue-700">Rp 0</p>
              </div>
            </div>

            <button id="btn-proses" disabled class="w-full h-14 bg-emerald-500 disabled:bg-slate-300 disabled:cursor-not-allowed hover:bg-emerald-600 text-white text-lg font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all click-pop flex items-center justify-center gap-2">
              <i class="ph-fill ph-check-circle text-2xl"></i> Proses Pembayaran
            </button>
          </div>
        </div>
      </section>
    </div>

    <!-- VIEW 2: RIWAYAT -->
    <div id="view-riwayat" class="w-full h-full flex-col overflow-y-auto hidden p-4 sm:p-6 lg:p-8 bg-slate-50">
      <div class="max-w-5xl mx-auto w-full space-y-6 pb-20 md:pb-0">
        <div class="flex items-center justify-between">
          <div>
            <h2 class="text-2xl font-bold text-slate-800">Riwayat Transaksi</h2>
            <p class="text-sm text-slate-500 mt-1">Daftar transaksi pada shift Anda hari ini.</p>
          </div>
          <div class="flex gap-2">
            <a href="{{ route('kasir.dashboard', ['filter' => 'today']) }}" class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ ($filter ?? 'today') === 'today' ? 'bg-emerald-500 text-white shadow-md shadow-emerald-200' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">Hari Ini</a>
            <a href="{{ route('kasir.dashboard', ['filter' => 'all']) }}" class="px-4 py-2 rounded-xl text-sm font-bold transition-all {{ ($filter ?? '') === 'all' ? 'bg-emerald-500 text-white shadow-md shadow-emerald-200' : 'bg-white text-slate-600 border border-slate-200 hover:bg-slate-50' }}">Semua</a>
          </div>
        </div>

        <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
          <div class="overflow-x-auto w-full">
            <table class="w-full text-left border-collapse">
              <thead>
                <tr class="bg-slate-50/50 border-b border-slate-100">
                  <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu</th>
                  <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">No. Invoice</th>
                  <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider">Metode</th>
                  <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Total</th>
                  <th class="px-6 py-4 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-slate-100" id="history-table-body">
                @foreach($sales as $sale)
                <tr class="hover:bg-slate-50/50 transition-colors">
                  <td class="px-6 py-4 text-sm font-semibold text-slate-800">
                    {{ $sale->created_at->isToday() ? $sale->created_at->format('H:i') : $sale->created_at->format('d/m H:i') }} WIB
                  </td>
                  <td class="px-6 py-4">
                    <span class="text-sm font-mono text-emerald-600 font-bold">{{ $sale->invoice_number }}</span>
                    <p class="text-[10px] text-slate-400 mt-0.5">{{ $sale->items_count }} Item Obat</p>
                  </td>
                  <td class="px-6 py-4"><span class="inline-flex text-[11px] font-semibold bg-blue-50 text-blue-600 px-2 py-1 rounded-md border border-blue-100">{{ $sale->payment_method }}</span></td>
                  <td class="px-6 py-4 text-right font-bold text-slate-800">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
                  <td class="px-6 py-4 text-right">
                    <button onclick="showDetail({{ $sale->id }})" class="px-4 py-2 text-xs font-bold text-emerald-600 bg-emerald-50 hover:bg-emerald-100 rounded-lg transition-colors click-pop">Detail</button>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- VIEW 3: PROFIL / SHIFT -->
    <div id="view-profil" class="w-full h-full flex-col overflow-y-auto hidden p-4 sm:p-6 lg:p-8 bg-slate-50 items-center justify-center">
      <div class="max-w-sm w-full bg-white rounded-3xl shadow-sm border border-slate-200 p-8 text-center pb-20 md:pb-8">
        <div class="w-24 h-24 bg-blue-100 rounded-full mx-auto mb-4 border-4 border-white shadow-md overflow-hidden">
            @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
            @else
                <img src="https://api.dicebear.com/7.x/notionists/svg?seed={{ $user->username }}&backgroundColor=dbeafe" class="w-full h-full object-cover">
            @endif
        </div>
        <h2 class="text-2xl font-bold text-slate-800 mb-1">{{ $user->name }}</h2>
        <p class="text-sm font-semibold text-emerald-600 bg-emerald-50 inline-block px-3 py-1 rounded-full mb-6 border border-emerald-100">Kasir Shift Pagi</p>

        <div class="bg-slate-50 rounded-2xl p-4 mb-8 text-left space-y-3 border border-slate-100">
           <div class="flex justify-between items-center">
             <span class="text-sm text-slate-500">Waktu Mulai Shift</span>
             <span class="text-sm font-semibold text-slate-800">{{ $user->created_at->format('H:i') }} WIB</span>
           </div>
           <div class="flex justify-between items-center">
             <span class="text-sm text-slate-500">Total Transaksi</span>
             <span class="text-sm font-semibold text-slate-800">{{ $sales->where('created_at', '>=', today())->count() }} Struk</span>
           </div>
           <div class="flex justify-between items-center">
             <span class="text-sm text-slate-500">Pendapatan Tunai</span>
             <span class="text-sm font-bold text-emerald-600">Rp {{ number_format($totalCash, 0, ',', '.') }}</span>
           </div>
        </div>

        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="w-full py-3.5 bg-rose-50 hover:bg-rose-100 text-rose-600 font-bold rounded-xl border border-rose-100 transition-colors flex items-center justify-center gap-2 click-pop">
                <i class="ph ph-sign-out text-xl"></i> Tutup Shift & Keluar
            </button>
        </form>
      </div>
    </div>
  </main>

  <!-- MOBILE BOTTOM NAV -->
  <nav class="md:hidden fixed bottom-0 w-full h-16 bg-white border-t border-slate-200 flex items-center justify-around px-1 z-30 shadow-[0_-10px_20px_rgba(0,0,0,0.05)] pb-safe">
    <button class="mobile-tab-btn active flex flex-col items-center justify-center w-14 h-full text-emerald-600 transition-colors" data-target="panel-produk" data-view="view-transaksi">
      <i class="ph-fill ph-squares-four text-2xl mb-1"></i>
      <span class="text-[10px] font-bold">Produk</span>
    </button>
    <button class="mobile-tab-btn relative flex flex-col items-center justify-center w-14 h-full text-slate-400 hover:text-slate-800 transition-colors" data-target="panel-keranjang" data-view="view-transaksi">
      <i class="ph ph-shopping-cart text-2xl mb-1"></i>
      <span class="text-[10px] font-bold">Keranjang</span>
      <span id="mobile-cart-badge" class="absolute top-2 right-2 w-4 h-4 bg-rose-500 text-white text-[9px] font-bold flex items-center justify-center rounded-full hidden border border-white">0</span>
    </button>
    <button class="mobile-tab-btn flex flex-col items-center justify-center w-14 h-full text-slate-400 hover:text-slate-800 transition-colors" data-target="view-riwayat" data-view="view-riwayat">
      <i class="ph ph-receipt text-2xl mb-1"></i>
      <span class="text-[10px] font-bold">Riwayat</span>
    </button>
    <button class="mobile-tab-btn flex flex-col items-center justify-center w-14 h-full text-slate-400 hover:text-slate-800 transition-colors" data-target="view-profil" data-view="view-profil">
      <i class="ph ph-user text-2xl mb-1"></i>
      <span class="text-[10px] font-bold">Profil</span>
    </button>
  </nav>

  <!-- MODAL: STRUK BERHASIL -->
  <div id="modal-sukses" class="fixed inset-0 z-50 hidden flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
    <div class="bg-white rounded-2xl w-full max-w-sm relative z-10 p-6 text-center transform scale-95 opacity-0 transition-all duration-300 shadow-2xl" id="modal-sukses-content">
      <div class="w-20 h-20 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-sm">
        <i class="ph-fill ph-check-circle text-4xl text-emerald-500"></i>
      </div>
      <h3 class="text-xl font-bold text-slate-800 mb-1">Pembayaran Berhasil!</h3>
      <p class="text-sm text-slate-500 mb-6">Transaksi <span class="font-bold text-slate-700" id="modal-invoice-num">#TRX-000</span> selesai diproses.</p>
      
      <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 mb-6 text-left space-y-2">
        <div class="flex justify-between text-sm">
          <span class="text-slate-500">Total Belanja</span>
          <span class="font-bold text-slate-800" id="struk-total">Rp 0</span>
        </div>
        <div class="flex justify-between text-sm">
          <span class="text-slate-500">Tunai</span>
          <span class="font-bold text-slate-800" id="struk-bayar">Rp 0</span>
        </div>
        <div class="flex justify-between text-sm pt-2 border-t border-slate-200">
          <span class="font-semibold text-blue-600">Kembalian</span>
          <span class="font-bold text-blue-600" id="struk-kembalian">Rp 0</span>
        </div>
      </div>

      <div class="grid grid-cols-2 gap-3">
        <button id="btn-tutup-modal" class="py-2.5 text-sm font-bold text-slate-600 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition-colors">Tutup</button>
        <button class="py-2.5 text-sm font-bold text-white bg-emerald-500 border border-emerald-500 rounded-xl hover:bg-emerald-600 shadow-md shadow-emerald-500/20 transition-colors flex items-center justify-center gap-1">
          <i class="ph ph-printer"></i> Cetak Struk
        </button>
      </div>
    </div>
  </div>

  <!-- MODAL: DETAIL PEMBAYARAN NON-TUNAI -->
  <div id="modal-pembayaran-detail" class="fixed inset-0 z-50 hidden flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-slate-900/60 backdrop-blur-md"></div>
    <div class="bg-white rounded-[2.5rem] w-full max-w-md relative z-10 p-8 transform scale-95 opacity-0 transition-all duration-300 shadow-2xl" id="modal-pembayaran-detail-content">
      <div class="text-center mb-6">
        <div id="icon-pembayaran-modal" class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-sm">
            <i class="ph-fill ph-qr-code text-4xl"></i>
        </div>
        <h3 class="text-2xl font-black text-slate-800 tracking-tight" id="title-pembayaran-modal">Detail Pembayaran</h3>
        <p class="text-sm text-slate-500 mt-1" id="subtitle-pembayaran-modal">Selesaikan pembayaran sesuai instruksi di bawah.</p>
      </div>

      <div id="modal-payment-info-container">
          <!-- QRIS Detail -->
          <div id="modal-detail-qris" class="hidden">
              <div class="bg-slate-50 border-2 border-dashed border-slate-200 rounded-3xl p-6 flex flex-col items-center text-center">
                  <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-4">Scan QRIS Untuk Bayar</p>
                  <div class="bg-white p-4 rounded-3xl shadow-md border border-slate-100 mb-4">
                      <img src="{{ asset('qris_mockup.png') }}" alt="QRIS QR Code" class="w-48 h-48 object-contain">
                  </div>
                  <p class="text-xs font-bold text-slate-500">Merchant: <span class="text-slate-800">Apotek Sejahtera</span></p>
                  <p class="text-[10px] text-slate-400">NMID: ID102938475657</p>
              </div>
          </div>

          <!-- Transfer Detail -->
          <div id="modal-detail-transfer" class="hidden">
              <div class="bg-blue-50 border border-blue-100 rounded-3xl p-6">
                  <p class="text-[10px] font-bold text-blue-600 uppercase tracking-widest mb-4">Rekening Tujuan</p>
                  <div class="space-y-3">
                      <div class="bg-white p-4 rounded-2xl border border-blue-100 flex items-center justify-between group">
                          <div class="flex items-center gap-3">
                              <div class="w-12 h-8 bg-blue-600 rounded-lg flex items-center justify-center text-xs text-white font-black">BCA</div>
                              <div>
                                  <p class="text-sm font-black text-slate-800">1234567890</p>
                                  <p class="text-[10px] text-slate-400 font-bold uppercase">A.N. APOTEK SEJATEREA</p>
                              </div>
                          </div>
                          <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-colors"><i class="ph-bold ph-copy"></i></button>
                      </div>
                      <div class="bg-white p-4 rounded-2xl border border-blue-100 flex items-center justify-between group">
                          <div class="flex items-center gap-3">
                              <div class="w-12 h-8 bg-emerald-600 rounded-lg flex items-center justify-center text-xs text-white font-black">BNI</div>
                              <div>
                                  <p class="text-sm font-black text-slate-800">0987654321</p>
                                  <p class="text-[10px] text-slate-400 font-bold uppercase">A.N. APOTEK SEJATEREA</p>
                              </div>
                          </div>
                          <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-xl transition-colors"><i class="ph-bold ph-copy"></i></button>
                      </div>
                  </div>
              </div>
          </div>

          <!-- Debit Detail -->
          <div id="modal-detail-debit" class="hidden">
              <div class="bg-slate-50 border border-slate-200 rounded-3xl p-6">
                  <p class="text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-4">Informasi Mesin EDC</p>
                  <div class="space-y-4">
                      <div>
                          <label class="text-[10px] font-bold text-slate-400 uppercase mb-1.5 block ml-1">Pilih Mesin EDC</label>
                          <select class="w-full bg-white border-2 border-slate-100 rounded-2xl px-4 py-3 text-sm font-bold focus:outline-none focus:border-emerald-500 transition-all">
                              <option>BCA EDC - POS-01</option>
                              <option>MANDIRI EDC - POS-01</option>
                              <option>BRI EDC - POS-01</option>
                          </select>
                      </div>
                      <div>
                          <label class="text-[10px] font-bold text-slate-400 uppercase mb-1.5 block ml-1">No. Reff / Trace ID</label>
                          <input type="text" placeholder="Masukkan 6 digit angka..." class="w-full bg-white border-2 border-slate-100 rounded-2xl px-4 py-3 text-sm font-bold focus:outline-none focus:border-emerald-500 transition-all">
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <div class="mt-8 flex flex-col gap-3">
          <button id="btn-konfirmasi-pembayaran" class="w-full py-4 bg-emerald-500 hover:bg-emerald-600 text-white font-bold rounded-2xl shadow-lg shadow-emerald-500/30 transition-all click-pop flex items-center justify-center gap-2">
            <span>Konfirmasi & Lanjutkan</span>
            <i class="ph ph-arrow-right font-bold"></i>
          </button>
          <button id="btn-batal-pembayaran" class="w-full py-3 text-sm font-bold text-slate-400 hover:text-slate-600 transition-colors">Ganti Metode</button>
      </div>
    </div>
  </div>

  <!-- MODAL: DETAIL RIWAYAT TRANSAKSI -->
  <div id="modal-detail-riwayat" class="fixed inset-0 z-50 hidden flex items-center justify-center px-4">
    <div class="absolute inset-0 bg-slate-900/40 backdrop-blur-sm"></div>
    <div class="bg-white rounded-2xl w-full max-w-sm relative z-10 p-6 text-center transform scale-95 opacity-0 transition-all duration-300 shadow-2xl" id="modal-detail-riwayat-content">
      <button id="btn-tutup-detail-riwayat" class="absolute top-4 right-4 p-1.5 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors focus:outline-none">
        <i class="ph ph-x text-lg"></i>
      </button>

      <h3 class="text-xl font-bold text-slate-800 mb-1 mt-2">Detail Transaksi</h3>
      <p class="text-sm font-mono text-emerald-600 mb-6 font-semibold" id="detail-invoice-num">#INV-000</p>
      
      <!-- Struk Mockup -->
      <div class="bg-slate-50 border border-slate-100 rounded-xl p-4 mb-6 text-left text-sm space-y-3 max-h-60 overflow-y-auto no-scrollbar">
        <div id="detail-items-list" class="space-y-3">
            <!-- Injected by JS -->
        </div>
        
        <div class="pt-3 border-t border-dashed border-slate-300 space-y-1">
            <div class="flex justify-between">
              <span class="text-slate-500">Total Belanja</span>
              <span class="font-bold text-slate-800" id="detail-total">Rp 0</span>
            </div>
            <div class="flex justify-between">
              <span class="text-slate-500">Tunai</span>
              <span class="font-bold text-slate-800" id="detail-bayar">Rp 0</span>
            </div>
            <div class="flex justify-between text-blue-600 font-semibold pt-2 border-t border-slate-200">
              <span>Kembalian</span>
              <span id="detail-kembalian">Rp 0</span>
            </div>
        </div>
      </div>

      <button class="w-full py-3 text-sm font-bold text-white bg-emerald-500 border border-emerald-500 rounded-xl hover:bg-emerald-600 shadow-md shadow-emerald-500/20 transition-colors flex items-center justify-center gap-2 click-pop">
        <i class="ph ph-printer text-lg"></i> Print Ulang Struk
      </button>
    </div>
  </div>

  @php
    $medicinesJson = $medicines->map(function($m) {
        // Map database categories to UI filter categories
        $dbCat = strtolower($m->category ?? '');
        $uiCategory = 'Bebas'; // Default
        
        if (str_contains($dbCat, 'resep') || str_contains($dbCat, 'antibiotik')) {
            $uiCategory = 'Resep';
        } elseif (str_contains($dbCat, 'vitamin') || str_contains($dbCat, 'suplemen')) {
            $uiCategory = 'Suplemen';
        } elseif (str_contains($dbCat, 'alkes') || str_contains($dbCat, 'alat kesehatan')) {
            $uiCategory = 'Alkes';
        } elseif (str_contains($dbCat, 'bebas')) {
            $uiCategory = 'Bebas';
        }

        // Set visual properties based on UI category
        $icon = 'ph-pill';
        $color = 'bg-emerald-50 text-emerald-500';
        
        if ($uiCategory === 'Resep') {
            $icon = 'ph-prescription';
            $color = 'bg-rose-50 text-rose-500';
        } elseif ($uiCategory === 'Suplemen') {
            $icon = 'ph-sparkle';
            $color = 'bg-amber-50 text-amber-500';
        } elseif ($uiCategory === 'Alkes') {
            $icon = 'ph-mask-happy';
            $color = 'bg-slate-100 text-slate-600';
        }
        
        return [
            'id' => $m->id,
            'name' => $m->name,
            'sku' => $m->sku,
            'category' => $uiCategory,
            'price' => (int)$m->selling_price,
            'stock' => $m->stock,
            'icon' => $icon,
            'color' => $color
        ];
    });
  @endphp

  <script>
    // Database Data from Laravel
    const dbProducts = @json($medicinesJson);

    const taxPercentage = {{ \App\Models\Setting::getValue('tax_percentage', 0) }};

    let cart = [];
    let currentTotal = 0;

    const gridEl = document.getElementById('product-grid');
    const cartEl = document.getElementById('cart-items');
    const searchInput = document.getElementById('search-obat');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const inputBayar = document.getElementById('input-bayar');
    const btnProses = document.getElementById('btn-proses');

    const formatRp = (num) => new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(num);

    function renderProducts(filter = 'all', searchStr = '') {
      gridEl.innerHTML = '';
      const filtered = dbProducts.filter(p => {
        const matchFilter = filter === 'all' || p.category === filter;
        const matchSearch = p.name.toLowerCase().includes(searchStr.toLowerCase()) || p.sku.toLowerCase().includes(searchStr.toLowerCase());
        return matchFilter && matchSearch;
      });

      if(filtered.length === 0) {
        gridEl.innerHTML = `<div class="col-span-full py-10 text-center text-slate-400 font-medium">Obat tidak ditemukan.</div>`;
        return;
      }

      filtered.forEach(p => {
        const isLowStock = p.stock <= 10;
        const card = document.createElement('div');
        card.className = `bg-white border ${isLowStock ? 'border-amber-200 bg-amber-50/10' : 'border-slate-200'} rounded-2xl p-3 sm:p-4 hover:border-emerald-400 hover:shadow-md cursor-pointer transition-all flex flex-col h-full click-pop select-none`;
        card.onclick = () => addToCart(p);
        card.innerHTML = `
          <div class="flex items-start justify-between mb-3">
            <div class="w-10 h-10 rounded-xl ${p.color} flex items-center justify-center shrink-0"><i class="ph-fill ${p.icon} text-xl"></i></div>
            <span class="text-[10px] font-bold ${p.category === 'Resep' ? 'text-rose-500 bg-rose-50' : 'text-slate-500 bg-slate-100'} px-2 py-0.5 rounded uppercase">${p.category}</span>
          </div>
          <div class="flex-1">
            <h3 class="text-xs sm:text-sm font-bold text-slate-800 leading-snug mb-1 line-clamp-2">${p.name}</h3>
            <p class="text-[10px] text-slate-400 font-mono">${p.sku}</p>
          </div>
          <div class="mt-3 pt-3 border-t border-slate-100 flex items-end justify-between">
            <div><p class="text-sm font-black text-emerald-600">${formatRp(p.price)}</p></div>
            <p class="text-[10px] font-bold ${isLowStock ? 'text-amber-500' : 'text-slate-400'}">${p.stock} sisa</p>
          </div>
        `;
        gridEl.appendChild(card);
      });
    }

    function addToCart(product) {
      if (product.stock <= 0) return alert('Stok habis!');
      const existing = cart.find(item => item.id === product.id);
      if (existing) {
        if(existing.qty < product.stock) existing.qty += 1;
      } else {
        cart.unshift({ ...product, qty: 1 });
      }
      renderCart();
      const badge = document.getElementById('mobile-cart-badge');
      badge.classList.remove('hidden');
      badge.classList.add('animate-bounce');
      setTimeout(() => badge.classList.remove('animate-bounce'), 1000);
    }

    function updateQty(id, delta) {
      const item = cart.find(i => i.id === id);
      if (!item) return;
      item.qty += delta;
      if (item.qty <= 0) { cart = cart.filter(i => i.id !== id); } 
      else if (item.qty > item.stock) { item.qty = item.stock; }
      renderCart();
    }

    function renderCart() {
      const totalItems = cart.reduce((sum, item) => sum + item.qty, 0);
      document.getElementById('cart-count').innerText = totalItems;
      document.getElementById('mobile-cart-badge').innerText = totalItems;
      if(totalItems === 0) document.getElementById('mobile-cart-badge').classList.add('hidden');

      if (cart.length === 0) {
        cartEl.innerHTML = `<div class="h-full flex flex-col items-center justify-center text-center opacity-60"><div class="w-20 h-20 bg-slate-100 rounded-full flex items-center justify-center mb-4"><i class="ph ph-shopping-cart text-4xl text-slate-400"></i></div><p class="text-sm font-semibold text-slate-600">Keranjang masih kosong</p></div>`;
        updateFinances();
        return;
      }

      cartEl.innerHTML = '';
      cart.forEach(item => {
        const div = document.createElement('div');
        div.className = 'bg-white border border-slate-200 rounded-xl p-3 shadow-sm flex gap-3 animate-[fade-in_0.2s_ease-out] relative group';
        div.innerHTML = `
          <div class="w-12 h-12 rounded-lg ${item.color} flex items-center justify-center shrink-0"><i class="ph-fill ${item.icon} text-xl"></i></div>
          <div class="flex-1 flex flex-col justify-between">
            <div class="pr-6"><h4 class="text-xs font-bold text-slate-800 leading-tight">${item.name}</h4><p class="text-[11px] font-semibold text-emerald-600 mt-0.5">${formatRp(item.price)}</p></div>
            <div class="flex items-center justify-between mt-2">
              <div class="flex items-center bg-slate-50 border border-slate-200 rounded-lg p-0.5">
                <button onclick="updateQty(${item.id}, -1)" class="w-7 h-7 flex items-center justify-center text-slate-500 hover:bg-white hover:shadow-sm rounded-md transition-all"><i class="ph ph-minus"></i></button>
                <span class="w-8 text-center text-xs font-bold text-slate-800">${item.qty}</span>
                <button onclick="updateQty(${item.id}, 1)" class="w-7 h-7 flex items-center justify-center text-slate-500 hover:bg-white hover:shadow-sm rounded-md transition-all"><i class="ph ph-plus"></i></button>
              </div>
              <p class="text-sm font-black text-slate-800">${formatRp(item.price * item.qty)}</p>
            </div>
          </div>
          <button onclick="updateQty(${item.id}, -${item.qty})" class="absolute top-2 right-2 p-1.5 text-slate-300 hover:text-rose-500 hover:bg-rose-50 rounded-lg transition-colors opacity-0 group-hover:opacity-100"><i class="ph ph-trash"></i></button>`;
        cartEl.appendChild(div);
      });
      updateFinances();
    }

    function updateFinances() {
      const subtotal = cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
      const tax = Math.round(subtotal * (taxPercentage / 100));
      currentTotal = subtotal + tax;
      document.getElementById('subtotal-val').innerText = formatRp(subtotal);
      document.getElementById('tax-val').innerText = formatRp(tax);
      document.getElementById('total-val').innerText = formatRp(currentTotal);
      
      if (selectedPaymentMethod !== 'Tunai' && currentTotal > 0) {
        inputBayar.value = currentTotal;
      }
      
      calculateChange();
    }

    function calculateChange() {
      const bayar = parseInt(inputBayar.value) || 0;
      const kembalian = bayar - currentTotal;
      const box = document.getElementById('box-kembalian');
      const val = document.getElementById('kembalian-val');
      if (cart.length === 0) {
        val.innerText = 'Rp 0';
        box.className = 'bg-slate-50 p-2.5 rounded-xl border border-slate-200 flex flex-col items-end justify-center';
        val.className = 'text-lg font-bold text-slate-400';
        btnProses.disabled = true;
        return;
      }
      if (bayar >= currentTotal && currentTotal > 0) {
        val.innerText = formatRp(kembalian);
        box.className = 'bg-emerald-50/50 p-2.5 rounded-xl border border-emerald-200 flex flex-col items-end justify-center transition-colors';
        val.className = 'text-lg font-bold text-emerald-600';
        btnProses.disabled = false;
      } else {
        val.innerText = 'Kurang ' + formatRp(Math.abs(kembalian));
        box.className = 'bg-rose-50/50 p-2.5 rounded-xl border border-rose-200 flex flex-col items-end justify-center transition-colors';
        val.className = 'text-sm font-bold text-rose-500';
        btnProses.disabled = true;
      }
    }

    inputBayar.addEventListener('input', calculateChange);
    filterBtns.forEach(btn => {
      btn.addEventListener('click', (e) => {
        filterBtns.forEach(b => { b.classList.remove('bg-slate-800', 'text-white'); b.classList.add('bg-white', 'text-slate-600'); });
        e.target.classList.add('bg-slate-800', 'text-white');
        e.target.classList.remove('bg-white', 'text-slate-600');
        renderProducts(e.target.dataset.filter, searchInput.value);
      });
    });
    searchInput.addEventListener('input', (e) => {
      const activeFilter = document.querySelector('.filter-btn.active')?.dataset.filter || 'all';
      renderProducts(activeFilter, e.target.value);
    });

    document.getElementById('btn-reset').addEventListener('click', () => {
      if(confirm('Kosongkan keranjang?')) { cart = []; inputBayar.value = ''; renderCart(); }
    });

    let currentSaleId = null;

    // Payment Method Selection
    let selectedPaymentMethod = 'Tunai';
    const payMethodBtns = document.querySelectorAll('.pay-method-btn');
    const modalPaymentDetail = document.getElementById('modal-pembayaran-detail');
    const modalPaymentContent = document.getElementById('modal-pembayaran-detail-content');
    const modalInfoQris = document.getElementById('modal-detail-qris');
    const modalInfoTransfer = document.getElementById('modal-detail-transfer');
    const modalInfoDebit = document.getElementById('modal-detail-debit');
    const modalTitle = document.getElementById('title-pembayaran-modal');
    const modalIcon = document.getElementById('icon-pembayaran-modal');

    function openPaymentModal(method) {
        // Reset all
        modalInfoQris.classList.add('hidden');
        modalInfoTransfer.classList.add('hidden');
        modalInfoDebit.classList.add('hidden');
        
        if (method === 'QRIS') {
            modalTitle.innerText = 'Bayar via QRIS';
            modalIcon.innerHTML = '<i class="ph-fill ph-qr-code text-4xl"></i>';
            modalIcon.className = 'w-20 h-20 bg-emerald-100 text-emerald-600 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-sm';
            modalInfoQris.classList.remove('hidden');
        } else if (method === 'Transfer Bank') {
            modalTitle.innerText = 'Transfer Bank';
            modalIcon.innerHTML = '<i class="ph-fill ph-bank text-4xl"></i>';
            modalIcon.className = 'w-20 h-20 bg-blue-100 text-blue-600 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-sm';
            modalInfoTransfer.classList.remove('hidden');
        } else if (method === 'Kartu Debit') {
            modalTitle.innerText = 'Kartu Debit';
            modalIcon.innerHTML = '<i class="ph-fill ph-credit-card text-4xl"></i>';
            modalIcon.className = 'w-20 h-20 bg-slate-100 text-slate-600 rounded-3xl flex items-center justify-center mx-auto mb-4 shadow-sm';
            modalInfoDebit.classList.remove('hidden');
        }

        modalPaymentDetail.classList.remove('hidden');
        setTimeout(() => {
            modalPaymentContent.classList.replace('scale-95', 'scale-100');
            modalPaymentContent.classList.replace('opacity-0', 'opacity-100');
        }, 10);
    }

    function closePaymentModal() {
        modalPaymentContent.classList.replace('scale-100', 'scale-95');
        modalPaymentContent.classList.replace('opacity-100', 'opacity-0');
        setTimeout(() => modalPaymentDetail.classList.add('hidden'), 300);
    }

    document.getElementById('btn-konfirmasi-pembayaran').addEventListener('click', closePaymentModal);
    document.getElementById('btn-batal-pembayaran').addEventListener('click', () => {
        closePaymentModal();
        // Reset to Tunai
        payMethodBtns[0].click();
    });

    payMethodBtns.forEach(btn => {
      btn.addEventListener('click', () => {
        payMethodBtns.forEach(b => {
          b.classList.remove('active', 'border-emerald-500', 'bg-emerald-50', 'text-emerald-700', 'shadow-sm');
          b.classList.add('border-slate-100', 'bg-slate-50', 'text-slate-500');
        });
        btn.classList.add('active', 'border-emerald-500', 'bg-emerald-50', 'text-emerald-700', 'shadow-sm');
        btn.classList.remove('border-slate-100', 'bg-slate-50', 'text-slate-500');
        selectedPaymentMethod = btn.dataset.method;

        // Handle non-cash modal
        if (selectedPaymentMethod !== 'Tunai') {
          if (currentTotal <= 0) {
              alert('Pilih obat terlebih dahulu!');
              payMethodBtns[0].click();
              return;
          }
          openPaymentModal(selectedPaymentMethod);
          inputBayar.value = currentTotal;
          inputBayar.readOnly = true;
          inputBayar.classList.add('opacity-50');
        } else {
          inputBayar.value = '';
          inputBayar.readOnly = false;
          inputBayar.classList.remove('opacity-50');
        }
        
        calculateChange();
      });
    });

    btnProses.addEventListener('click', async () => {
        const bayar = parseInt(inputBayar.value) || 0;
        
        // Disable button loading
        btnProses.disabled = true;
        btnProses.innerHTML = `<i class="ph ph-spinner-gap animate-spin text-2xl"></i> Memproses...`;

        try {
            const response = await fetch('{{ route("kasir.transaksi.store") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    items: cart.map(item => ({ id: item.id, qty: item.qty })),
                    total_amount: currentTotal,
                    paid_amount: bayar,
                    payment_method: selectedPaymentMethod
                })
            });

            const result = await response.json();

            if (result.success) {
                currentSaleId = result.sale.id;
                document.getElementById('modal-invoice-num').innerText = result.invoice_number;
                document.getElementById('struk-total').innerText = formatRp(currentTotal);
                document.getElementById('struk-bayar').innerText = formatRp(bayar);
                document.getElementById('struk-kembalian').innerText = formatRp(bayar - currentTotal);
                
                const modal = document.getElementById('modal-sukses');
                const content = document.getElementById('modal-sukses-content');
                modal.classList.remove('hidden');
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            } else {
                alert('Error: ' + result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Terjadi kesalahan server.');
        } finally {
            btnProses.innerHTML = `<i class="ph-fill ph-check-circle text-2xl"></i> Proses Pembayaran`;
            btnProses.disabled = false;
        }
    });

    function printReceipt(id) {
        const saleId = id || currentSaleId;
        if (!saleId) return;
        window.open(`/kasir/transaksi/${saleId}/print`, '_blank');
    }

    // Add event listeners for print buttons
    document.querySelector('#modal-sukses button.bg-emerald-500').addEventListener('click', () => printReceipt());
    document.querySelector('#modal-detail-riwayat button.bg-emerald-500').addEventListener('click', () => printReceipt());

    document.getElementById('btn-tutup-modal').addEventListener('click', () => {
      document.getElementById('modal-sukses-content').classList.replace('scale-100', 'scale-95');
      document.getElementById('modal-sukses-content').classList.replace('opacity-100', 'opacity-0');
      setTimeout(() => {
        document.getElementById('modal-sukses').classList.add('hidden');
        cart = []; inputBayar.value = ''; renderCart();
        window.location.reload(); // Refresh to update history & stock
      }, 300);
    });

    // View Switching
    const views = ['view-transaksi', 'view-riwayat', 'view-profil'];
    const deskNavBtns = document.querySelectorAll('.desk-nav-btn');
    const mobileTabs = document.querySelectorAll('.mobile-tab-btn');
    const panelProduk = document.getElementById('panel-produk');
    const panelKeranjang = document.getElementById('panel-keranjang');

    function switchView(targetView) {
      views.forEach(v => document.getElementById(v).classList.add('hidden'));
      const tgt = document.getElementById(targetView);
      tgt.classList.remove('hidden');
      if(targetView === 'view-transaksi') tgt.classList.add('flex');
      deskNavBtns.forEach(btn => {
        if (btn.dataset.target === targetView) btn.className = 'desk-nav-btn active px-4 py-1.5 text-sm font-bold bg-white text-emerald-600 rounded-lg shadow-sm';
        else btn.className = 'desk-nav-btn px-4 py-1.5 text-sm font-medium text-slate-500 hover:text-slate-800 hover:bg-slate-200 transition-colors rounded-lg';
      });
    }

    deskNavBtns.forEach(btn => btn.addEventListener('click', (e) => switchView(e.currentTarget.dataset.target)));
    mobileTabs.forEach(tab => {
      tab.addEventListener('click', (e) => {
        const targetId = e.currentTarget.dataset.target;
        const targetView = e.currentTarget.dataset.view;
        switchView(targetView);
        mobileTabs.forEach(t => { t.classList.replace('text-emerald-600', 'text-slate-400'); t.querySelector('i').classList.replace('ph-fill', 'ph'); });
        e.currentTarget.classList.replace('text-slate-400', 'text-emerald-600');
        e.currentTarget.querySelector('i').classList.replace('ph', 'ph-fill');
        if (targetView === 'view-transaksi') {
          if(targetId === 'panel-keranjang') { panelKeranjang.classList.remove('translate-x-full'); panelProduk.classList.add('-translate-x-1/2', 'opacity-50', 'pointer-events-none'); }
          else { panelKeranjang.classList.add('translate-x-full'); panelProduk.classList.remove('-translate-x-1/2', 'opacity-50', 'pointer-events-none'); }
        }
      });
    });

    async function showDetail(id) {
        currentSaleId = id; // Set global ID for printing
        const modal = document.getElementById('modal-detail-riwayat');
        const content = document.getElementById('modal-detail-riwayat-content');
        const itemsList = document.getElementById('detail-items-list');
        
        try {
            const response = await fetch(`/kasir/transaksi/${id}`);
            const result = await response.json();
            
            if (result.success) {
                const sale = result.sale;
                document.getElementById('detail-invoice-num').innerText = sale.invoice_number;
                document.getElementById('detail-total').innerText = formatRp(sale.total_amount);
                document.getElementById('detail-bayar').innerText = formatRp(sale.paid_amount);
                document.getElementById('detail-kembalian').innerText = formatRp(sale.change_amount);
                
                itemsList.innerHTML = '';
                sale.items.forEach(item => {
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'pb-3 border-b border-dashed border-slate-300';
                    itemDiv.innerHTML = `
                        <div class="flex justify-between mb-1">
                            <span class="font-semibold text-slate-800">${item.medicine.name}</span>
                            <span class="text-slate-800">${formatRp(item.total_price)}</span>
                        </div>
                        <div class="text-xs text-slate-500">${item.quantity}x @ ${formatRp(item.unit_price)}</div>
                    `;
                    itemsList.appendChild(itemDiv);
                });
                
                modal.classList.remove('hidden');
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Gagal mengambil detail transaksi.');
        }
    }

    document.getElementById('btn-tutup-detail-riwayat').addEventListener('click', () => {
        const modal = document.getElementById('modal-detail-riwayat');
        const content = document.getElementById('modal-detail-riwayat-content');
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        setTimeout(() => modal.classList.add('hidden'), 300);
        currentSaleId = null; // Reset when closed
    });

    setInterval(() => {
      const now = new Date();
      document.getElementById('clock').innerText = now.toLocaleTimeString('id-ID', {hour: '2-digit', minute:'2-digit'}) + ' WIB';
    }, 1000);

    renderProducts();
    renderCart();
  </script>
</body>
</html>
