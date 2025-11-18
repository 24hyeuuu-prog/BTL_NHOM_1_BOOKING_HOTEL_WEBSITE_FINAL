<?php require_once 'config/config.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LaValle Chatbot - Hỗ trợ Du lịch</title>
    <link rel="stylesheet" href="CSS/styles.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <!-- Floating Chat Icon -->
    <div id="chatbot-icon" title="Mở trợ lý du lịch">💬</div>

    <!-- Chatbot Container -->
    <div id="chatbot-container" class="hidden">
      <div id="chatbot-header">
        <span>🏨 LaValle Assistant</span>
        <button id="close-btn" title="Đóng">&times;</button>
      </div>
      <div id="chatbot-body">
        <div id="chatbot-messages">
          <!-- Messages will appear here -->
        </div>
      </div>
      <div id="chatbot-input-container">
        <input
          type="text"
          id="chatbot-input"
          placeholder="Hỏi tôi điều gì đó..."
          autocomplete="off"
        />
        <button id="send-btn" title="Gửi tin nhắn">Gửi</button>
      </div>
    </div>

    <script src="JS/script.js"></script>
</body>
</html>
