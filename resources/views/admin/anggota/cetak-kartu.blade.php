<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kartu Perpustakaan - {{ $anggota->nama_lengkap }}</title>
    <style>
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
        
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background: #f5f5f5;
        }
        
        .card-container {
            width: 210mm;
            height: 148mm;
            margin: 0 auto;
            background: white;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            display: flex;
            overflow: hidden;
        }
        
        .card-left {
            flex: 2;
            padding: 20px;
            position: relative;
            background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
        }
        
        .card-middle {
            flex: 1;
            padding: 20px;
        }
        
        .card-right {
            flex: 1.5;
            padding: 20px;
            background: #ffffff;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 15px;
        }
        
        .logo-circle {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 10px;
            color: white;
            font-weight: bold;
            font-size: 12px;
        }
        
        .school-name {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 5px;
            color: #333;
        }
        
        .school-address {
            font-size: 10px;
            text-align: center;
            color: #666;
            line-height: 1.3;
        }
        
        .member-name {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin: 30px 0;
            color: #333;
        }
        
        .barcode-container {
            text-align: center;
            margin: 20px auto;
            padding: 10px;
            background: white;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            max-width: 200px;
        }
        
        .barcode-image {
            width: 100%;
            height: auto;
            max-width: 180px;
            margin-bottom: 5px;
        }
        
        .barcode-text {
            font-family: 'Courier New', monospace;
            font-size: 8px;
            color: #333;
            text-align: center;
        }
        
        .member-type {
            background: #000;
            color: white;
            padding: 8px 15px;
            font-weight: bold;
            font-size: 14px;
            margin-bottom: 15px;
            text-align: center;
        }
        
        .member-id {
            font-size: 20px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 10px;
            color: #333;
        }
        
        .validity {
            font-size: 10px;
            text-align: center;
            color: #666;
            margin-bottom: 20px;
        }
        
        .photo-placeholder {
            width: 60px;
            height: 60px;
            background: #e9ecef;
            border: 2px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px auto;
            color: #666;
            font-size: 12px;
        }
        
        .book-icon {
            width: 40px;
            height: 40px;
            background: #e9ecef;
            border: 2px solid #dee2e6;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 10px auto;
            color: #666;
            font-size: 12px;
        }
        
        .rules-title {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 15px;
            color: #333;
        }
        
        .rules-list {
            font-size: 9px;
            line-height: 1.4;
            color: #333;
        }
        
        .rules-list ol {
            margin: 0;
            padding-left: 15px;
        }
        
        .rules-list li {
            margin-bottom: 5px;
        }
        
        .signature {
            text-align: center;
            margin-top: 20px;
        }
        
        .signature-line {
            width: 100px;
            height: 1px;
            background: #333;
            margin: 5px auto;
        }
        
        .signature-name {
            font-size: 10px;
            font-weight: bold;
            color: #333;
        }
        
        .signature-title {
            font-size: 8px;
            color: #666;
        }
        
        .wave-pattern {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background: linear-gradient(135deg, 
                rgba(40, 167, 69, 0.1) 0%, 
                rgba(32, 201, 151, 0.2) 50%, 
                rgba(40, 167, 69, 0.3) 100%);
            clip-path: polygon(0 100%, 100% 100%, 100% 0, 0 60%);
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
    </style>
</head>
<body>
    <button onclick="window.print()" class="print-button no-print">
        <i class="fas fa-print"></i> Cetak Kartu
    </button>
    
    <div class="card-container">
        <!-- Left Panel - Main Card -->
        <div class="card-left">
            <div class="wave-pattern"></div>
            
            <div class="logo">
                <div class="logo-circle">
                    SMK
                </div>
                <div class="school-name">Kartu Perpustakaan</div>
                <div class="school-name">SMK Negeri 1 Kefamenanu</div>
                <div class="school-address">
                    Jl. Soekarno-Hatta, Kefamenanu, Timor Tengah Utara<br>
                    Telp. (0388) 21001. NPSN 50100101
                </div>
            </div>
            
            <div class="member-name">{{ $anggota->nama_lengkap }}</div>
            
            <div class="barcode-container">
                <img src="data:image/png;base64,{{ \App\Helpers\BarcodeHelper::generateBarcodeImage($anggota->barcode_anggota, 'C128') }}" 
                     alt="Barcode" class="barcode-image">
                <div class="barcode-text">{{ $anggota->barcode_anggota }}</div>
            </div>
        </div>
        
        <!-- Middle Panel - Member Details -->
        <div class="card-middle">
            <div class="member-type">{{ strtoupper($anggota->jenis_anggota) }}</div>
            <div class="member-id">{{ $anggota->nomor_anggota }}</div>
            <div class="validity">Berlaku Hingga Selama Menjadi {{ ucfirst($anggota->jenis_anggota) }}</div>
            
            <div class="photo-placeholder">
                @if($anggota->foto)
                    <img src="{{ asset('storage/anggota/' . $anggota->foto) }}" 
                         alt="Foto" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <i class="fas fa-user"></i>
                @endif
            </div>
            
            <div class="book-icon">
                <i class="fas fa-book"></i>
            </div>
        </div>
        
        <!-- Right Panel - Library Rules -->
        <div class="card-right">
            <div class="rules-title">PERATURAN PERPUSTAKAAN</div>
            
            <div class="rules-list">
                <ol>
                    <li>Kartu dibawa setiap berkunjung ke Perpustakaan.</li>
                    <li>Kartu tidak dapat dipinjamkan kepada orang lain.</li>
                    <li>Kartu berlaku selama menjadi anggota perpustakaan.</li>
                    <li>Bersedia mengembalikan buku yang dipinjam sebelum batas waktu.</li>
                    <li>Bersedia membayar denda jika terlambat mengembalikan buku.</li>
                    <li>Bersedia mengganti buku yang dihilangkan.</li>
                    <li>Kartu ini dapat dicabut apabila yang bersangkutan tidak memenuhi ketentuan diatas</li>
                </ol>
            </div>
            
            <div class="signature">
                <div class="signature-title">Kepala Perpustakaan</div>
                <div class="signature-line"></div>
                <div class="signature-name">Suhartono</div>
            </div>
        </div>
    </div>
    
    <script>
        // Auto print when page loads
        window.onload = function() {
            // Uncomment the line below to auto-print
            // window.print();
        }
    </script>
</body>
</html> 