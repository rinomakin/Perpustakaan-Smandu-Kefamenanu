<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode Multiple - {{ count($buku) }} Buku</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none !important; }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background: white;
        }
        
        .barcode-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin: 20px 0;
        }
        
        .barcode-container {
            text-align: center;
            border: 1px solid #ddd;
            padding: 15px;
            page-break-inside: avoid;
            background: white;
        }
        
        .barcode-label {
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
            line-height: 1.2;
        }
        
        .barcode-code {
            font-family: 'Courier New', monospace;
            font-size: 10px;
            margin-top: 5px;
            color: #666;
        }
        
        .book-info {
            font-size: 10px;
            margin-top: 8px;
            color: #666;
            line-height: 1.1;
        }
        
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            background: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            z-index: 1000;
        }
        
        .print-button:hover {
            background: #0056b3;
        }
        
        .header-info {
            text-align: center;
            margin-bottom: 20px;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 5px;
        }
        
        .header-info h2 {
            margin: 0;
            color: #333;
            font-size: 18px;
        }
        
        .header-info p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 14px;
        }
        
        @media print {
            .print-button {
                display: none;
            }
            
            .barcode-grid {
                grid-template-columns: repeat(2, 1fr);
                gap: 10px;
            }
            
            .barcode-container {
                border: 1px solid #000;
                padding: 10px;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">
        <i class="fas fa-print"></i> Cetak Semua
    </button>
    
    <div class="header-info">
        <h2>Barcode Buku Perpustakaan</h2>
        <p>Total: {{ count($buku) }} buku | Tanggal: {{ date('d/m/Y H:i') }}</p>
    </div>
    
    <div class="barcode-grid">
        @foreach($buku as $item)
        <div class="barcode-container">
            <div class="barcode-label">{{ $item->judul_buku }}</div>
            <svg id="barcode-{{ $item->id }}"></svg>
            <div class="barcode-code">{{ $item->barcode ?? 'Belum ada barcode' }}</div>
            <div class="book-info">
                <div>Penulis: {{ $item->penulis ?? '-' }}</div>
                <div>Penerbit: {{ $item->penerbit ?? '-' }}</div>
                <div>Kategori: {{ $item->kategori ? $item->kategori->nama_kategori : '-' }}</div>
                @if($item->isbn)
                    <div>ISBN: {{ $item->isbn }}</div>
                @endif
            </div>
        </div>
        @endforeach
    </div>

    <script>
        // Generate barcode untuk setiap buku
        @foreach($buku as $item)
        @if($item->barcode)
        JsBarcode("#barcode-{{ $item->id }}", "{{ $item->barcode }}", {
            format: "CODE128",
            width: 1.5,
            height: 40,
            displayValue: false,
            fontSize: 10,
            margin: 5
        });
        @endif
        @endforeach
        
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 1000);
        };
    </script>
</body>
</html> 