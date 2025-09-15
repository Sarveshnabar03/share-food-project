<?php
// chatbot.php
header("Content-Type: application/json");

// Replace with your OpenRouter API key
$apiKey = "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";

// Read input
$data = json_decode(file_get_contents("php://input"), true);
$userMessage = $data["message"] ?? "";

// If no message, return error
if (!$userMessage) {
    echo json_encode(["reply" => "No message received."]);
    exit;
}

// Prepare API request
$ch = curl_init("https://openrouter.ai/api/v1/chat/completions");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $apiKey",
    "Content-Type: application/json"
]);

$payload = [
    "model" => "openai/gpt-4o-mini", // you can change the model
    "messages" => [
        ["role" => "system", "content" => "You are a helpful NGO assistant chatbot."],
        ["role" => "user", "content" => $userMessage]
    ]
];

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));

$response = curl_exec($ch);
curl_close($ch);

if ($response === false) {
    echo json_encode(["reply" => "Error contacting AI API."]);
    exit;
}

$result = json_decode($response, true);
$reply = $result["choices"][0]["message"]["content"] ?? "No reply.";

// Return AI reply
echo json_encode(["reply" => $reply]);
