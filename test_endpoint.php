<?php
// File untuk test endpoint search anggota
echo "Testing search anggota endpoint...\n";

// URL untuk testing - sesuaikan dengan setup lokal Anda
$baseUrl = 'http://localhost'; // Ganti sesuai setup local Anda
$testQuery = 'ri'; // Query test

$testUrls = [
    "$baseUrl/test-anggota-basic",
    "$baseUrl/search-anggota-simple?query=$testQuery",
    "$baseUrl/admin/pengembalian/search-anggota?query=$testQuery"
];

foreach ($testUrls as $url) {
    echo "\n=== Testing: $url ===\n";
    
    // Test dengan curl jika tersedia
    if (function_exists('curl_init')) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        echo "HTTP Code: $httpCode\n";
        echo "Response: " . substr($response, 0, 200) . "...\n";
    } else {
        echo "cURL not available. Please test manually in browser.\n";
    }
}

echo "\n=== Manual Test URLs ===\n";
foreach ($testUrls as $url) {
    echo "- $url\n";
}

echo "\n=== Instructions ===\n";
echo "1. Buka browser\n";
echo "2. Login ke sistem\n";
echo "3. Akses URL-URL di atas untuk testing\n";
echo "4. Periksa response JSON yang dikembalikan\n";
?>
