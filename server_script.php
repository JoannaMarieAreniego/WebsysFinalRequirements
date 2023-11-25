<?php

$userMessage = $_GET['message'];

// Replace 'YOUR_API_KEY' with your actual OpenAI API key
$apiKey = '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';

$response = openaiRequest($apiKey, $userMessage);

echo json_encode(['response' => $response]);

function openaiRequest($apiKey, $userMessage) {
    // Update the API endpoint if necessary
    $apiEndpoint = 'https://api.openai.com/v1/engines/davinci-codex/completions';

    $headers = [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey,
    ];

    $data = [
        'prompt' => $userMessage,
        'max_tokens' => 50,
    ];

    $ch = curl_init($apiEndpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);

    if (curl_errno($ch)) {
        return 'Error making API request: ' . curl_error($ch);
    }

    curl_close($ch);

    $responseData = json_decode($response, true);

    // Check for errors in the API response
    if (isset($responseData['error'])) {
        return 'OpenAI API Error: ' . $responseData['error']['message'];
    }

    // Check if 'choices' key exists in the response
    if (isset($responseData['choices'][0]['text'])) {
        return $responseData['choices'][0]['text'];
    } else {
        return json_encode($responseData);
    }
}
?>
