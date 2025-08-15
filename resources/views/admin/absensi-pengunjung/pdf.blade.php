<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Kunjungan Perpustakaan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
        }
        
        .filter-info {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f8f9fa;
            border-radius: 5px;
        }
        
        .filter-info p {
            margin: 5px 0;
            font-size: 11px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
            font-size: 10px;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        
        .status-selesai {
            color: #28a745;
            font-weight: bold;
        }
        
        .status-berkunjung {
            color: #ffc107;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        
        .no-data {
            text-align: center;
            padding: 40px;
            color: #666;
            font-style: italic;
        }
        
        .summary {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #e9ecef;
            border-radius: 5px;
        }
        
        .summary p {
            margin: 5px 0;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>RIWAYAT KUNJUNGAN PERPUSTAKAAN</h1>
        <p>SMAN 1 Kefamenanu</p>
        <p>Periode: {{ $startDate ? \Carbon\Carbon::parse($startDate)->format('d/m/Y') : 'Semua' }} - {{ $endDate ? \Carbon\Carbon::parse($endDate)->format('d/m/Y') : 'Semua' }}</p>
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>

    @if($startDate || $endDate || $member)
    <div class="filter-info">
        <p><strong>Filter yang diterapkan:</strong></p>
        @if($startDate)
            <p>• Tanggal Mulai: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</p>
        @endif
        @if($endDate)
            <p>• Tanggal Akhir: {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
        @endif
        @if($member)
            <p>• Pencarian Anggota: {{ $member }}</p>
        @endif
    </div>
    @endif

    <div class="summary">
        <p><strong>Ringkasan:</strong></p>
        <p>• Total Data: {{ $data->count() }} kunjungan</p>
        <p>• Status Selesai: {{ $data->where('waktu_keluar', '!=', '-')->count() }} kunjungan</p>
        <p>• Status Berkunjung: {{ $data->where('waktu_keluar', '=', '-')->count() }} kunjungan</p>
    </div>

    @if($data->count() > 0)
    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 20%;">Nama Anggota</th>
                <th style="width: 12%;">Nomor Anggota</th>
                <th style="width: 10%;">Kelas</th>
                <th style="width: 10%;">Jurusan</th>
                <th style="width: 12%;">Waktu Masuk</th>
                <th style="width: 12%;">Waktu Keluar</th>
                <th style="width: 8%;">Durasi</th>
                <th style="width: 8%;">Status</th>
                <th style="width: 13%;">Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $index => $item)
            <tr>
                <td style="text-align: center;">{{ $index + 1 }}</td>
                <td>{{ $item['nama_lengkap'] }}</td>
                <td>{{ $item['nomor_anggota'] }}</td>
                <td>{{ $item['kelas'] }}</td>
                <td>{{ $item['jurusan'] }}</td>
                <td>{{ $item['waktu_masuk'] }}</td>
                <td>{{ $item['waktu_keluar'] }}</td>
                <td>{{ $item['durasi'] ?? '-' }}</td>
                <td style="text-align: center;">
                    @if($item['waktu_keluar'] != '-')
                        <span class="status-selesai">Selesai</span>
                    @else
                        <span class="status-berkunjung">Berkunjung</span>
                    @endif
                </td>
                <td>{{ $item['keterangan'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">
        <p>Tidak ada data kunjungan yang ditemukan untuk filter yang diberikan.</p>
    </div>
    @endif

    <div class="footer">
        <p>Dokumen ini dibuat secara otomatis oleh sistem perpustakaan SMAN 1 Kefamenanu</p>
        <p>Halaman 1 dari 1</p>
    </div>
</body>
</html>
