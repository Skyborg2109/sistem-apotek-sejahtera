<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - {{ $sale->invoice_number }}</title>
    <style>
        body {
            font-family: 'Courier New', Courier, monospace;
            width: 58mm; /* Standard thermal printer width */
            margin: 0 auto;
            padding: 5mm;
            font-size: 12px;
            line-height: 1.2;
            color: #000;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .divider {
            border-top: 1px dashed #000;
            margin: 3mm 0;
        }
        .header-title { font-size: 14px; margin-bottom: 1mm; }
        .item-row { margin-bottom: 2mm; }
        .flex { display: flex; justify-content: space-between; }
        @media print {
            body { width: 58mm; padding: 0; }
            @page { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="text-center">
        <div class="header-title bold uppercase">{{ $setting['app_name'] }}</div>
        <div>{{ $setting['app_address'] }}</div>
        <div>Telp: {{ $setting['app_phone'] }}</div>
    </div>

    <div class="divider"></div>

    <div>
        <div>No: {{ $sale->invoice_number }}</div>
        <div>Tgl: {{ $sale->created_at->format('d/m/Y H:i') }}</div>
        <div>Ksr: {{ $sale->cashier_name }}</div>
    </div>

    <div class="divider"></div>

    @foreach($sale->items as $item)
    <div class="item-row">
        <div>{{ $item->medicine->name }}</div>
        <div class="flex">
            <span>{{ $item->quantity }} x {{ number_format($item->unit_price, 0, ',', '.') }}</span>
            <span>{{ number_format($item->total_price, 0, ',', '.') }}</span>
        </div>
    </div>
    @endforeach

    <div class="divider"></div>

    <div class="flex">
        <span>TOTAL:</span>
        <span class="bold">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
    </div>
    <div class="flex">
        <span>TUNAI:</span>
        <span>Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</span>
    </div>
    <div class="flex">
        <span>KEMBALI:</span>
        <span>Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</span>
    </div>

    <div class="divider"></div>

    <div class="text-center" style="margin-top: 5mm;">
        *** TERIMA KASIH ***<br>
        Semoga Lekas Sembuh
    </div>

    <script>
        window.onload = function() {
            window.print();
            setTimeout(function() {
                window.close();
            }, 500);
        };
    </script>
</body>
</html>
