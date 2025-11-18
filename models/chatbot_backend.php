<?php
/**
 * Chatbot Backend - Gemini AI Integration
 * File: chatbot_backend.php
 * Xử lý API requests từ frontend và gọi Gemini AI API
 */

// Set response header TRƯỚC TẤT CẢ OUTPUT
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Xóa bộ đệm output nếu có
if (ob_get_level()) {
    ob_clean();
}

// Handle preflight requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Configuration
$GEMINI_API_KEY = 'AIzaSyAUdt5CTF-YX70-SXa-oRXQjwyKVgvgxvA';
// $GEMINI_API_URL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent';
// $GEMINI_API_URL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-1.5-flash:generateContent';
$GEMINI_API_URL = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash:generateContent';

// System prompt cho chatbot du lịch
$SYSTEM_PROMPT = "Bạn là một trợ lý du lịch thông minh của LaValle - một ứng dụng đặt phòng khách sạn và tour du lịch Việt Nam. 
Hãy trả lời các câu hỏi về:
- Du lịch tại Việt Nam (các điểm du lịch nổi tiếng, thời điểm đi, chi phí...)
- Gợi ý khách sạn, resort, homestay
- Thông tin tour du lịch
- Giá cả, khuyến mãi
- Dịch vụ của LaValle

Lưu ý:
- Luôn trả lời bằng tiếng Việt
- Thân thiện, chuyên nghiệp
- Nếu không biết, hãy nói rõ và gợi ý nơi họ có thể tìm thêm thông tin
- Giới hạn câu trả lời dưới 300 từ
- Hãy tích cực gợi ý sử dụng dịch vụ của LaValle khi phù hợp";

try {
    // Check request method
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        throw new Exception('Phương thức không được hỗ trợ');
    }

    // Get POST data
    $input = file_get_contents('php://input');
    $data = json_decode($input, true);

    // Debug: Log input
    error_log('Input: ' . $input);
    error_log('Decoded data: ' . print_r($data, true));

    // Validate input
    if (!$data || !isset($data['message']) || empty(trim($data['message']))) {
        throw new Exception('Tin nhắn không được để trống');
    }

    $user_message = trim($data['message']);

    // Validate message length
    if (strlen($user_message) < 1) {
        throw new Exception('Tin nhắn quá ngắn');
    }

    if (strlen($user_message) > 5000) {
        throw new Exception('Tin nhắn quá dài (tối đa 5000 ký tự)');
    }

    // Prepare API request to Gemini
    $request_payload = [
        'contents' => [
            [
                'parts' => [
                    [
                        'text' => $SYSTEM_PROMPT . "\n\nNgười dùng: " . $user_message
                    ]
                ]
            ]
        ],
        'generationConfig' => [
            'temperature' => 0.7,
            'topK' => 40,
            'topP' => 0.95,
            'maxOutputTokens' => 1024,
        ],
        'safetySettings' => [
            [
                'category' => 'HARM_CATEGORY_HARASSMENT',
                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE',
            ],
            [
                'category' => 'HARM_CATEGORY_HATE_SPEECH',
                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE',
            ],
            [
                'category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT',
                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE',
            ],
            [
                'category' => 'HARM_CATEGORY_DANGEROUS_CONTENT',
                'threshold' => 'BLOCK_MEDIUM_AND_ABOVE',
            ],
        ]
    ];

    // Call Gemini API using cURL
    $curl = curl_init();
    
    curl_setopt_array($curl, [
        CURLOPT_URL => $GEMINI_API_URL . '?key=' . $GEMINI_API_KEY,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => json_encode($request_payload),
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
        ],
    ]);

    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    $curl_error = curl_error($curl);
    curl_close($curl);

    // Check for curl errors
    if ($curl_error) {
        throw new Exception('Lỗi kết nối: ' . $curl_error);
    }

    // Parse API response
    $api_response = json_decode($response, true);

    // Check HTTP status
    if ($http_code !== 200) {
        $error_message = isset($api_response['error']['message']) 
            ? $api_response['error']['message'] 
            : 'Lỗi từ Gemini API (HTTP ' . $http_code . ')';
        throw new Exception($error_message);
    }

    // Check if response is valid
    if (!isset($api_response['candidates'][0]['content']['parts'][0]['text'])) {
        throw new Exception('Định dạng phản hồi không hợp lệ từ API');
    }

    $bot_message = $api_response['candidates'][0]['content']['parts'][0]['text'];

    // Log conversation
    logConversation($user_message, $bot_message);

    // Return success response
    http_response_code(200);
    echo json_encode([
        'success' => true,
        'message' => $bot_message,
        'timestamp' => date('Y-m-d H:i:s'),
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $e) {
    // Return error response
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage(),
        'message' => 'Xin lỗi, đã xảy ra lỗi: ' . $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s'),
    ], JSON_UNESCAPED_UNICODE);
}

/**
 * Log conversation to file
 * @param string $user_message
 * @param string $bot_message
 */
function logConversation($user_message, $bot_message) {
    $log_dir = __DIR__ . '/chat_logs';
    
    // Create directory if it doesn't exist
    if (!is_dir($log_dir)) {
        mkdir($log_dir, 0755, true);
    }

    $log_file = $log_dir . '/chat_' . date('Y-m-d') . '.txt';
    
    $timestamp = date('Y-m-d H:i:s');
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    
    $log_entry = "[{$timestamp}] IP: {$ip_address}\n";
    $log_entry .= "User: " . substr($user_message, 0, 100) . (strlen($user_message) > 100 ? '...' : '') . "\n";
    $log_entry .= "Bot: " . substr($bot_message, 0, 100) . (strlen($bot_message) > 100 ? '...' : '') . "\n";
    $log_entry .= "---\n";
    
    error_log($log_entry, 3, $log_file);
}

?>

