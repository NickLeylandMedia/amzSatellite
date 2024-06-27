<?php

declare(strict_types=1);

require __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

//Load Environment variables
$dotenv = Dotenv::createImmutable(__DIR__, "/../../.env")->load();

session_start();

// eBay credentials
$client_id = 'BlackHal-Shipstat-SBX-307721e0a-20d029b4';        // Your App ID
$client_secret = 'SBX-07721e0aeacd-e890-41fe-b311-1fef';   // Your Cert ID
$redirect_uri = 'https://yourdomain.com/ebay_auth.php';
$scope = 'https://api.ebay.com/oauth/api_scope';
$token_url = 'https://api.ebay.com/identity/v1/oauth2/token';

// Function to base64 encode the credentials
function base64url_encode($data)
{
    return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
}



// Step 2: Handle the callback and capture the authorization code
if (isset($_GET['code'])) {
    $authorization_code = $_GET['code'];

    // Step 3: Exchange the authorization code for access and refresh tokens
    $basic_auth = base64url_encode("$client_id:$client_secret");

    $headers = [
        "Content-Type: application/x-www-form-urlencoded",
        "Authorization: Basic $basic_auth"
    ];

    $data = [
        'grant_type' => 'authorization_code',
        'code' => $authorization_code,
        'redirect_uri' => $redirect_uri
    ];

    $options = [
        CURLOPT_URL => $token_url,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => http_build_query($data),
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true
    ];

    $ch = curl_init();
    curl_setopt_array($ch, $options);
    $response = curl_exec($ch);

    if ($response === false) {
        $error = curl_error($ch);
        echo "Error: $error";
        exit();
    } else {
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code == 200) {
            $response_data = json_decode($response, true);
            if (isset($response_data['access_token']) && isset($response_data['refresh_token'])) {
                $access_token = $response_data['access_token'];
                $refresh_token = $response_data['refresh_token'];

                // Store tokens for later use (e.g., in session or database)
                $_SESSION['access_token'] = $access_token;
                $_SESSION['refresh_token'] = $refresh_token;

                echo "Access Token: " . htmlspecialchars($access_token) . "<br>";
                echo "Refresh Token: " . htmlspecialchars($refresh_token) . "<br>";

                // Step 4: Use the refresh token to get a new access token
                $data = [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $refresh_token,
                    'scope' => 'https://api.ebay.com/oauth/api_scope' // Adjust scope as needed
                ];

                $options = [
                    CURLOPT_URL => $token_url,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => http_build_query($data),
                    CURLOPT_HTTPHEADER => $headers,
                    CURLOPT_RETURNTRANSFER => true
                ];

                curl_setopt_array($ch, $options);
                $refresh_response = curl_exec($ch);

                if ($refresh_response === false) {
                    $error = curl_error($ch);
                    echo "Error: $error";
                } else {
                    $refresh_response_data = json_decode($refresh_response, true);
                    if (isset($refresh_response_data['access_token'])) {
                        $new_access_token = $refresh_response_data['access_token'];
                        echo "New Access Token: " . htmlspecialchars($new_access_token) . "<br>";
                    } else {
                        echo "Error: Unable to refresh token. Response: " . htmlspecialchars($refresh_response);
                    }
                }
            } else {
                echo "Error: Unable to get tokens. Response: " . htmlspecialchars($response);
            }
        } else {
            echo "HTTP Error Code: $http_code<br>";
            echo "Response: " . htmlspecialchars($response);
        }
    }

    curl_close($ch);
} else {
    echo "Error: Authorization code not found or invalid state parameter.";
}
