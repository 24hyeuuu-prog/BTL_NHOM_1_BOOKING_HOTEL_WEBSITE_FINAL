<?php
/**
 * Chatbot Configuration
 * File cấu hình cho Gemini AI Chatbot
 * 
 * Lưu ý: Nên chuyển API key vào environment variable trong production
 */

// Gemini AI API Configuration
return [
    'enabled' => true,
    'api_key' => getenv('GEMINI_API_KEY') ?: 'AIzaSyAUdt5CTF-YX70-SXa-oRXQjwyKVgvgxvA',
    'api_url' => 'https://generativelanguage.googleapis.com/v1beta/models/gemini-pro:generateContent',
    
    // Generation config
    'generation' => [
        'temperature' => 0.7,      // 0-1: cao = creative, thấp = focused
        'topK' => 40,              // Diversity
        'topP' => 0.95,            // Diversity
        'maxOutputTokens' => 1024, // Max response length
    ],
    
    // Safety settings
    'safety' => [
        'HARM_CATEGORY_HARASSMENT' => 'BLOCK_MEDIUM_AND_ABOVE',
        'HARM_CATEGORY_HATE_SPEECH' => 'BLOCK_MEDIUM_AND_ABOVE',
        'HARM_CATEGORY_SEXUALLY_EXPLICIT' => 'BLOCK_MEDIUM_AND_ABOVE',
        'HARM_CATEGORY_DANGEROUS_CONTENT' => 'BLOCK_MEDIUM_AND_ABOVE',
    ],
    
    // Input validation
    'validation' => [
        'min_length' => 1,
        'max_length' => 5000,
    ],
    
    // System prompt
    'system_prompt' => "Bạn là một trợ lý du lịch thông minh cho trang web LaValle - một trang web đặt phòng khách sạn và du lịch tại Việt Nam. 
Nhiệm vụ của bạn là:
1. Cung cấp thông tin về các điểm du lịch tại Việt Nam
2. Giúp khách hàng tìm kiếm và đặt phòng khách sạn phù hợp
3. Trả lời câu hỏi về các gói du lịch, giá cả, và dịch vụ
4. Cung cấp lời khuyên du lịch và thông tin hữu ích
5. Hỗ trợ khách hàng bằng tiếng Việt
6. Luôn thân thiện, chuyên nghiệp và hữu ích

Hãy trả lời ngắn gọn, rõ ràng và chính xác.",
    
    // Logging
    'logging' => [
        'enabled' => true,
        'directory' => __DIR__ . '/chat_history',
        'format' => 'txt', // 'txt' hoặc 'json'
    ],
    
    // Rate limiting (tùy chọn)
    'rate_limit' => [
        'enabled' => false,
        'requests_per_minute' => 10,
        'requests_per_hour' => 100,
    ],
    
    // CORS
    'cors' => [
        'enabled' => true,
        'allowed_origins' => ['*'], // Đổi thành domain cụ thể trong production
    ],
];
?>
