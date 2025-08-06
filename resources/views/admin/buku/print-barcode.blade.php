<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Barcode - {{ $buku->judul_buku }}</title>
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
        
        .barcode-container {
            text-align: center;
            margin: 20px 0;
            page-break-inside: avoid;
        }
        
        .barcode-label {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 10px;
            color: #333;
        }
        
        .barcode-code {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            margin-top: 5px;
            color: #666;
        }
        
        .book-info {
            font-size: 12px;
            margin-top: 10px;
            color: #666;
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
        }
        
        .print-button:hover {
            background: #0056b3;
        }
        
        @media print {
            .print-button {
                display: none;
            }
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
</head>
<body>
    <button class="print-button no-print" onclick="window.print()">
        <i class="fas fa-print"></i> Cetak
    </button>
    
    <div class="barcode-container">
        <div class="barcode-label">{{ $buku->judul_buku }}</div>
        <svg id="barcode-{{ $buku->id }}"></svg>
        <div class="barcode-code">{{ $buku->barcode }}</div>
        <div class="book-info">
            <div>Penulis: {{ $buku->penulis ? $buku->penulis->nama_penulis : '-' }}</div>
            <div>Penerbit: {{ $buku->penerbit ? $buku->penerbit->nama_penerbit : '-' }}</div>
            <div>Kategori: {{ $buku->kategori ? $buku->kategori->nama_kategori : '-' }}</div>
            @if($buku->isbn)
                <div>ISBN: {{ $buku->isbn }}</div>
            @endif
        </div>
    </div>

    <script>
        // Generate barcode menggunakan JsBarcode
        JsBarcode("#barcode-{{ $buku->id }}", "{{ $buku->barcode }}", {
            format: "CODE128",
            width: 2,
            height: 50,
            displayValue: false,
            fontSize: 12,
            margin: 10
        });
        
        // Auto print when page loads
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html> 