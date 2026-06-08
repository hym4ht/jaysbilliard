<!DOCTYPE html>
<html>
<head>
    <title>Laporan Stok F&B — Jay's Billiard</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; font-size: 18px; }
        .header p { margin: 5px 0; color: #666; }
        
        .section-title { font-size: 14px; font-weight: bold; margin-bottom: 10px; border-bottom: 1px solid #000; padding-bottom: 5px; margin-top: 20px; }
        
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        
        .type-in { color: green; font-weight: bold; }
        .type-out { color: red; font-weight: bold; }
        
        .footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; color: #999; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN STOK MAKANAN & MINUMAN</h1>
        <p>Jay's Billiard — Tanggal Cetak: {{ date('d M Y, H:i') }}</p>
    </div>

    <div class="section-title">STOK SAAT INI (PER ITEM)</div>
    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Nama Item</th>
                <th>Kategori</th>
                <th style="text-align: right;">Stok Terakhir</th>
            </tr>
        </thead>
        <tbody>
            @foreach($menus as $index => $menu)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $menu->name }}</td>
                    <td>{{ $menu->category }}</td>
                    <td style="text-align: right; font-weight: bold;">{{ $menu->stock }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">RIWAYAT TRANSAKSI TERAKHIR</div>
    <table>
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Item</th>
                <th>Jenis</th>
                <th style="text-align: right;">Jumlah</th>
                <th>Tanggal</th>
                <th>Catatan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions->take(50) as $index => $transaction)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $transaction->menu->name }}</td>
                    <td>
                        <span class="{{ $transaction->type === 'in' ? 'type-in' : 'type-out' }}">
                            {{ $transaction->type === 'in' ? 'MASUK' : 'KELUAR' }}
                        </span>
                    </td>
                    <td style="text-align: right;">{{ $transaction->quantity }}</td>
                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $transaction->note ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        Dicetak secara otomatis oleh Sistem Jay's Billiard Dashboard
    </div>
</body>
</html>
