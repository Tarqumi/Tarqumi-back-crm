<?php

/**
 * Simple authentication test script
 * Run: php test_auth.php
 */

$baseUrl = 'http://127.0.0.1:8000/api/v1';

echo "=== Testing Tarqumi CRM Authentication ===\n\n";

// Test 1: Login
echo "1. Testing Login...\n";
$loginData = json_encode([
    'email' => 'admin@tarqumi.com',
    'password' => 'password'
]);

$ch = curl_init($baseUrl . '/login');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $loginData);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Accept: application/json'
]);

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

echo "Status Code: $httpCode\n";
echo "Response: " . substr($response, 0, 200) . "...\n\n";

if ($httpCode === 200) {
    $data = json_decode($response, true);
    $token = $data['data']['token'] ?? null;
    
    if ($token) {
        echo "✅ Login successful! Token received.\n\n";
        
        // Test 2: Get User
        echo "2. Testing Get User...\n";
        $ch = curl_init($baseUrl . '/user');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        echo "Status Code: $httpCode\n";
        echo "Response: " . substr($response, 0, 200) . "...\n\n";
        
        if ($httpCode === 200) {
            echo "✅ Get User successful!\n\n";
        } else {
            echo "❌ Get User failed!\n\n";
        }
        
        // Test 3: Get Permissions
        echo "3. Testing Get Permissions...\n";
        $ch = curl_init($baseUrl . '/permissions');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        echo "Status Code: $httpCode\n";
        echo "Response: " . substr($response, 0, 300) . "...\n\n";
        
        if ($httpCode === 200) {
            echo "✅ Get Permissions successful!\n\n";
        } else {
            echo "❌ Get Permissions failed!\n\n";
        }
        
        // Test 4: Logout
        echo "4. Testing Logout...\n";
        $ch = curl_init($baseUrl . '/logout');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $token,
            'Accept: application/json'
        ]);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        echo "Status Code: $httpCode\n";
        echo "Response: $response\n\n";
        
        if ($httpCode === 200) {
            echo "✅ Logout successful!\n\n";
        } else {
            echo "❌ Logout failed!\n\n";
        }
        
    } else {
        echo "❌ Login failed - no token received!\n\n";
    }
} else {
    echo "❌ Login failed!\n\n";
}

echo "=== Test Complete ===\n";
