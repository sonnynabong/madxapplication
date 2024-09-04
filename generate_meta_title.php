<?php

//config on a private folder outside the main site directory
include __DIR__ . '/../private/config.php';

// Define the log file path
$log_file = 'request_log.txt';

// Get the origin (if available)
$origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : 'Unknown';

// Get the referer (if available)
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Unknown';

// Prepare log entry
$log_entry = sprintf(
    "[%s] Origin: %s, Referer: %s, POST Data: %s\n",
    date('Y-m-d H:i:s'),
    $origin,
    $referer,
    json_encode($_POST)
);

// Write the log entry to the log file
file_put_contents($log_file, $log_entry, FILE_APPEND);

//turn on allowed origin and referer security if its value is set
if($allowed_origin != '' && $allowed_referrer != ''){
    // Check the Referer header
    if (isset($_SERVER['HTTP_REFERER'])) {
        $referer_host = parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST);
        if ($referer_host !== parse_url($allowed_referrer, PHP_URL_HOST)) {
            header('HTTP/1.1 403 Forbidden');
            exit('Access denied.');
        }
    } else {
        // Check the Origin header if Referer is not set
        if (isset($_SERVER['HTTP_ORIGIN'])) {
            $origin = $_SERVER['HTTP_ORIGIN'];
            if ($origin !== $allowed_origin) {
                header('HTTP/1.1 403 Forbidden');
                exit('Access denied.');
            }
        } else {
            // If neither Referer nor Origin headers are present, deny access
            header('HTTP/1.1 403 Forbidden');
            exit('Access denied.');
        }
    }
    // Allow cross-origin requests from the allowed origin
    header('Access-Control-Allow-Origin: ' . $allowed_origin);

    // Optionally, specify other CORS headers if needed
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type');
}

// Function to generate the meta title using OpenAI API
function generateMetaTitle($keyword, $type) {
    global $openai_api_key;

    // Prepare the prompt for ChatGPT
    $prompt = "Generate an SEO-optimized meta title for a $type based on the keyword: \"$keyword\".";

    // Set up the OpenAI API request using gpt-4o model
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'model' => 'gpt-4o', // Ensure you are using a valid model name
        'messages' => [
            ['role' => 'system', 'content' => 'You are a helpful assistant.'],
            ['role' => 'user', 'content' => $prompt]
        ],
        'max_tokens' => 60
    ]));

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Bearer ' . $openai_api_key
    ]);

    $result = curl_exec($ch);

    // Check if the API call was successful
    if ($result === false) {
        $error = curl_error($ch);
        curl_close($ch);
        return json_encode(['meta_title' => "CURL error: $error"]);
    }

    curl_close($ch);

    $response = json_decode($result, true);

    // Check if the response contains the expected data
    if (isset($response['choices'][0]['message']['content'])) {
        $meta_title = trim($response['choices'][0]['message']['content']);
    } else {
        $meta_title = "No title generated.";
    }

    return json_encode(['meta_title' => $meta_title]);
}

// Handle the API request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $keyword = $_POST['keyword'] ?? '';
    $type = $_POST['type'] ?? 'Blog';

    if (!empty($keyword)) {
        echo generateMetaTitle($keyword, $type);
    } else {
        echo json_encode(['meta_title' => 'Keyword is required.']);
    }
} else {
    echo json_encode(['meta_title' => 'Invalid request method.']);
}
