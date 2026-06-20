<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once 'db.php'; 
header('Content-Type: application/json');

// --- CONFIGURATION ---
$groq_api_key = $_ENV['GROQ_API_KEY']; 
$model_name = "llama-3.3-70b-versatile";

if (!isset($_SESSION['user_id'])) {
    echo json_encode(array('success' => false, 'error' => 'Session expired.'));
    exit();
}

// --- FIX: READ BOTH FORM POST AND JSON INPUT ---
$userMessage = '';

// 1. Agar normal Form/POST se data aaye
if (isset($_POST['message'])) {
    $userMessage = trim($_POST['message']);
} 
// 2. Agar JavaScript fetch/JSON se data aaye (Aapke case mein yehi masla hai)
else {
    $inputJSON = file_get_contents('php://input');
    $input = json_decode($inputJSON, true);
    if (isset($input['message'])) {
        $userMessage = trim($input['message']);
    }
}

if (empty($userMessage)) {
    echo json_encode(array('success' => false, 'error' => 'Empty message received at server'));
    exit();
}

// STEP 1: LOCAL MODEL CALL (Python API)
$detected_disease = "Unknown";
$python_api_url = "http://127.0.0.1:5000/predict";

$ch = curl_init($python_api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array("message" => $userMessage)));
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
curl_setopt($ch, CURLOPT_TIMEOUT, 10);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

$model_response = curl_exec($ch);
curl_close($ch);

if ($model_response) {
    $model_data = json_decode($model_response, true);
    if (isset($model_data['success']) && $model_data['success']) {
        $detected_disease = $model_data['disease'];
    }
}

// STEP 2: GROQ API CALL
function getGroqResponse($message, $disease, $apiKey, $model) {
    $url = "https://api.groq.com/openai/v1/chat/completions";
    $prompt = ($disease !== "Unknown") 
        ? "The patient might have $disease. Symptoms: $message. Provide medical guide." 
        : "Patient says: $message. Provide general health advice.";

    $data = array(
        "model" => $model,
        "messages" => array(
            array("role" => "system", "content" => "You are a professional medical assistant."),
            array("role" => "user", "content" => $prompt)
        )
    );

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . $apiKey
    ));
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $err = curl_error($ch); // Error check
    curl_close($ch);
    
    if ($err) {
        return "CURL Error: " . $err; // Agar internet ya SSL ka masla hua to ye dikhega
    }

    $result = json_decode($response, true);
    
    if (isset($result['choices'][0]['message']['content'])) {
        return $result['choices'][0]['message']['content'];
    } else {
        // Agar API koi error bhej rahi hai (like Rate Limit or Invalid Key)
        return "Groq Error: " . (isset($result['error']['message']) ? $result['error']['message'] : "Unknown API issue");
    }
}
$final_response = getGroqResponse($userMessage, $detected_disease, $groq_api_key, $model_name);

// STEP 3: DATABASE SAVE & OUTPUT
try {
    $stmt = $pdo->prepare("INSERT INTO chat_messages (user_id, message, response) VALUES (?, ?, ?)");
    $stmt->execute(array($_SESSION['user_id'], $userMessage, $final_response));
    
    echo json_encode(array(
        'success' => true, 
        'response' => $final_response,
        'detected' => $detected_disease
    ));
} catch (Exception $e) {
    // Return response even if database fails, but include the error for debugging
    echo json_encode(array(
        'success' => true, 
        'response' => $final_response, 
        'db_error' => $e->getMessage()
    ));
}
?>