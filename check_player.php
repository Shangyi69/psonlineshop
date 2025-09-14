<?php
// Set the content type to JSON so the browser knows how to read it
header('Content-Type: application/json');

// Allow requests from any origin (for development). 
// For security, in production, you should restrict this to your website's domain, e.g., header('Access-Control-Allow-Origin: https://yourwebsite.com');
header('Access-Control-Allow-Origin: *');

// --- SETUP ---
// 1. Make sure you have the API library files on your server.
// 2. Update the path below to point to the correct location of 'Games.php'.
// Example: If 'Games.php' is in a folder named 'api_library', the path would be 'api_library/src/Games.php'
require('src/Games.php');

use Aditdev\ApiGames;

// Check if userID and zoneID are provided from the website's JavaScript
if (isset($_GET['userID']) && isset($_GET['zoneID'])) {
    $userID = $_GET['userID'];
    $zoneID = $_GET['zoneID'];

    try {
        $api = new ApiGames;
        
        // Call the API function to get the player name
        $apiResponse = $api->MOBILE_LEGENDS($userID, $zoneID);
        
        // The API might return a JSON string with details. We try to decode it.
        $data = json_decode($apiResponse, true);

        // Check if decoding worked and if there is a 'username' key.
        // Some APIs use 'nickname' or other keys, so you might need to adjust 'username'.
        if (json_last_error() === JSON_ERROR_NONE && isset($data['username'])) {
             // If successful, send a success message with the username back to the website
             echo json_encode(['success' => true, 'username' => $data['username']]);
        } else {
            // If the response is not a recognized JSON, it might be a simple error message string.
            // We send it back as is, assuming it could be the username or an error.
             echo json_encode(['success' => true, 'username' => $apiResponse]);
        }

    } catch (Exception $e) {
        // If the API library itself throws an error (e.g., cannot connect)
        echo json_encode(['success' => false, 'error' => 'API Error: ' . $e->getMessage()]);
    }
} else {
    // If the request is missing parameters
    echo json_encode(['success' => false, 'error' => 'Missing userID or zoneID']);
}
?>
