<?php

// Test form submission with admin login using cURL
$baseUrl = 'http://localhost:8000';

// Initialize cURL with cookie support
$ch = curl_init();
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookies.txt');
curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookies.txt');

echo "Step 1: Getting login page...\n";
// Get login page to get CSRF token
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
$loginPage = curl_exec($ch);

// Extract CSRF token from login page
// Try multiple patterns to find CSRF token
preg_match('/<meta name="csrf-token" content="([^"]+)"/', $loginPage, $matches);
if (!isset($matches[1])) {
    preg_match('/<input[^>]*name="_token"[^>]*value="([^"]+)"/', $loginPage, $matches);
}
if (!isset($matches[1])) {
    preg_match('/name="_token"[^>]*value="([^"]+)"/', $loginPage, $matches);
}
if (!isset($matches[1])) {
    preg_match('/value="([^"]+)"[^>]*name="_token"/', $loginPage, $matches);
}

if (isset($matches[1])) {
    $csrfToken = $matches[1];
    echo "CSRF Token: $csrfToken" . PHP_EOL;
} else {
    echo "CSRF Token: Not found" . PHP_EOL;
    echo "Login page response length: " . strlen($loginPage) . PHP_EOL;
    echo "First 500 chars: " . substr($loginPage, 0, 500) . PHP_EOL;
    exit(1);
}

echo "Step 2: Logging in as admin...\n";
// Login as admin
$loginData = [
    '_token' => $csrfToken,
    'email' => 'admin@admin.com', // Default admin email
    'password' => 'password' // Default admin password
];

// Configure curl to follow redirects
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 5);

curl_setopt($ch, CURLOPT_URL, $baseUrl . '/login');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($loginData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded',
    'X-CSRF-TOKEN: ' . $csrfToken
]);

$loginResponse = curl_exec($ch);
$loginHttpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$finalUrl = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

echo "Login HTTP Code: " . $loginHttpCode . "\n";
echo "Final URL after redirects: " . $finalUrl . "\n";

// Check if we're redirected to admin dashboard
if (strpos($finalUrl, 'admin') !== false && $loginHttpCode == 200) {
    echo "Login successful - redirected to admin area\n";
} else {
    echo "Login failed or not redirected to admin area\n";
    echo "Response: " . substr($loginResponse, 0, 500) . "\n";
    exit(1);
}

echo "Step 3: Getting pembimbing page...\n";
// Get pembimbing page to get new CSRF token
curl_setopt($ch, CURLOPT_URL, $baseUrl . '/admin/pembimbing');
curl_setopt($ch, CURLOPT_POST, false);
curl_setopt($ch, CURLOPT_HTTPHEADER, []);
$pembimbingPage = curl_exec($ch);

// Extract new CSRF token
preg_match('/<meta name="csrf-token" content="([^"]+)"/', $pembimbingPage, $matches);
$newCsrfToken = $matches[1] ?? null;

echo "New CSRF Token: " . ($newCsrfToken ? $newCsrfToken : 'Not found') . "\n";

if (!$newCsrfToken) {
    echo "Failed to get new CSRF token\n";
    exit(1);
}

echo "Step 4: Submitting pembimbing form...\n";
// Submit pembimbing form
$postData = [
    '_token' => $newCsrfToken,
    'nama' => 'Test Pembimbing',
    'email' => 'testpembimbing@example.com',
    'password' => 'password123'
];

curl_setopt($ch, CURLOPT_URL, $baseUrl . '/admin/pembimbing');
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/x-www-form-urlencoded',
    'X-CSRF-TOKEN: ' . $newCsrfToken
]);

$submitResponse = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

echo "Submit HTTP Code: " . $httpCode . "\n";
echo "Response length: " . strlen($submitResponse) . "\n";

if ($httpCode == 302) {
    echo "Form submitted successfully (redirect)\n";
} else {
    echo "Form submission failed\n";
    echo "Response: " . substr($submitResponse, 0, 1000) . "\n";
}

curl_close($ch);

// Clean up
if (file_exists('cookies.txt')) {
    unlink('cookies.txt');
}

echo "\nChecking database after submission...\n";
require_once 'check_pembimbing.php';
?>