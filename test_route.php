<?php

// Test route pengembalian search-anggota
$url = 'http://127.0.0.1:8000/admin/pengembalian/search-anggota?query=ri';

echo "Testing URL: $url\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_NOBODY, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

echo "HTTP Code: $httpCode\n";
if ($error) {
    echo "cURL Error: $error\n";
} else {
    echo "Response Headers:\n$response\n";
}

// Test route debug
echo "\n--- Testing debug route ---\n";
$url2 = 'http://127.0.0.1:8000/debug-search?query=ri';

$ch2 = curl_init();
curl_setopt($ch2, CURLOPT_URL, $url2);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_TIMEOUT, 10);

$response2 = curl_exec($ch2);
$httpCode2 = curl_getinfo($ch2, CURLINFO_HTTP_CODE);
$error2 = curl_error($ch2);
curl_close($ch2);

echo "HTTP Code: $httpCode2\n";
if ($error2) {
    echo "cURL Error: $error2\n";
} else {
    echo "Response: $response2\n";
}
?>
