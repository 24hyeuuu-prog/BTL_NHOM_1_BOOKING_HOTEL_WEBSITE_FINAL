// script.js
document.addEventListener("DOMContentLoaded", function () {
  const chatbotContainer = document.getElementById("chatbot-container");
  const closeBtn = document.getElementById("close-btn");
  const sendBtn = document.getElementById("send-btn");
  const chatbotInput = document.getElementById("chatbot-input");
  const chatbotMessages = document.getElementById("chatbot-messages");

  const chatbotIcon = document.getElementById("chatbot-icon");
  const closeButton = document.getElementById("close-btn");

  // Biến để tracking loading state
  let isLoading = false;

  // Toggle chatbot visibility when clicking the icon
  chatbotIcon.addEventListener("click", function () {
    chatbotContainer.classList.remove("hidden");
    chatbotIcon.style.display = "none"; // Hide chat icon
    chatbotInput.focus(); // Focus vào input
  });

  // Also toggle when clicking the close button
  closeButton.addEventListener("click", function () {
    chatbotContainer.classList.add("hidden");
    chatbotIcon.style.display = "flex"; // Show chat icon again
  });

  sendBtn.addEventListener("click", sendMessage);
  chatbotInput.addEventListener("keypress", function (e) {
    if (e.key === "Enter" && !isLoading) {
      sendMessage();
    }
  });

  function sendMessage() {
    const userMessage = chatbotInput.value.trim();
    if (userMessage && !isLoading) {
      appendMessage("user", userMessage);
      chatbotInput.value = "";
      sendBtn.disabled = true;
      sendBtn.textContent = "Đang gửi...";
      isLoading = true;
      getBotResponse(userMessage);
    }
  }

  function appendMessage(sender, message) {
    const messageElement = document.createElement("div");
    messageElement.classList.add("message", sender);
    messageElement.textContent = message;
    messageElement.style.animation = "fadeInUp 0.3s ease-out";
    chatbotMessages.appendChild(messageElement);
    chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
  }

  function appendLoadingMessage() {
    const messageElement = document.createElement("div");
    messageElement.classList.add("message", "bot", "loading");
    messageElement.innerHTML = '<div class="typing"><span></span><span></span><span></span></div>';
    messageElement.id = "loading-message";
    chatbotMessages.appendChild(messageElement);
    chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
  }

  function removeLoadingMessage() {
    const loadingMessage = document.getElementById("loading-message");
    if (loadingMessage) {
      loadingMessage.remove();
    }
  }

  async function getBotResponse(userMessage) {
    appendLoadingMessage();

    try {
      const response = await fetch("models/chatbot_backend.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({
          message: userMessage,
        }),
      });

      const data = await response.json();

      removeLoadingMessage();

      if (data.success) {
        appendMessage("bot", data.message);
      } else {
        appendMessage(
          "bot",
          data.message || "Xin lỗi, tôi gặp lỗi khi xử lý yêu cầu của bạn."
        );
        console.error("Bot Error:", data.error);
      }
    } catch (error) {
      console.error("Error fetching bot response:", error);
      removeLoadingMessage();
      appendMessage(
        "bot",
        "Xin lỗi, có lỗi xảy ra khi kết nối đến server. Vui lòng thử lại."
      );
    } finally {
      sendBtn.disabled = false;
      sendBtn.textContent = "Gửi";
      isLoading = false;
      chatbotInput.focus();
    }
  }
});

