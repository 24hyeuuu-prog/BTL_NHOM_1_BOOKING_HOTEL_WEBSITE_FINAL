<?php require_once 'config/config.php'; ?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Lavalle</title>
    <link rel="stylesheet" href="CSS/signup.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Chatbot -->

    <!-- Notification Container -->
    <div class="notification-container" id="notificationContainer"></div>

    <div class="signup-container">
        <!-- Right Section -->
        <div class="right-section">
            <div class="signup-form-container">
                <!-- Header -->
                <div class="form-header">
                    <div class="welcome-text">
                        <span class="welcome">Welcome to </span>
                        <span class="brand">LAVALLE</span>
                    </div>
                    <h2 class="form-title">ĐĂNG KÝ</h2>
                </div>

                <!-- Signup Form -->
                <form class="signup-form" id="signupForm">
                        <div class="form-group">
                            <label class="form-label">Nhập tên đăng nhập hoặc địa chỉ email</label>
                            <input type="email" name="email" class="form-input" placeholder="Tên đăng nhập hoặc địa chỉ email" required>
                        </div>

                        <div class="form-row">
                            <div class="form-group half">
                                <label class="form-label">Tên của bạn</label>
                                <input type="text" name="name" class="form-input" placeholder="Tên" required>
                            </div>
                            <div class="form-group half">
                                <label class="form-label">Số điện thoại</label>
                                <input type="tel" name="phone" class="form-input" placeholder="Số điện thoại">
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Mật khẩu của bạn</label>
                            <div class="password-input-container">
                                <input type="password" name="password" class="form-input password-input" placeholder="Mật khẩu" required>
                                <button type="button" class="password-toggle" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="passwordIcon"></i>
                                </button>
                            </div>
                            <div class="password-strength" id="passwordStrength"></div>
                        </div>
                        <button type="submit" class="signup-btn">Đăng ký</button>
                        <div class="login-link">
                            <span>Đã có tài khoản ? </span>
                            <a href="login.php" class="login-account">Đăng nhập</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Notification System (same as login page)
        function showNotification(type, message) {
            const container = document.getElementById('notificationContainer');
            
            const notification = document.createElement('div');
            notification.className = `notification ${type}`;
            
            let icon = '';
            switch(type) {
                case 'success':
                    icon = '<i class="fas fa-check-circle"></i>';
                    break;
                case 'error':
                    icon = '<i class="fas fa-exclamation-triangle"></i>';
                    break;
                case 'warning':
                    icon = '<i class="fas fa-exclamation-circle"></i>';
                    break;
                case 'info':
                    icon = '<i class="fas fa-info-circle"></i>';
                    break;
            }
            
            notification.innerHTML = `
                <div class="notification-content">
                    <div class="notification-icon">${icon}</div>
                    <div class="notification-message">${message}</div>
                    <button class="notification-close" onclick="closeNotification(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            
            container.appendChild(notification);
            
            setTimeout(() => {
                notification.classList.add('show');
            }, 100);
            
            setTimeout(() => {
                closeNotification(notification.querySelector('.notification-close'));
            }, 5000);
        }

        function closeNotification(closeBtn) {
            const notification = closeBtn.closest('.notification');
            notification.classList.remove('show');
            setTimeout(() => {
                notification.remove();
            }, 300);
        }

        // Initialize on page load
        function togglePassword() {
            const passwordInput = document.querySelector('.password-input');
            const passwordIcon = document.getElementById('passwordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                passwordIcon.className = 'fas fa-eye';
            }
        }

        // Password strength checker
        function checkPasswordStrength(password) {
            const strengthIndicator = document.getElementById('passwordStrength');
            let strength = 0;

            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            let strengthText = '';
            let strengthClass = '';

            if (strength <= 2) {
                strengthText = 'Yếu';
                strengthClass = 'weak';
            } else if (strength <= 3) {
                strengthText = 'Trung bình';
                strengthClass = 'medium';
            } else if (strength <= 4) {
                strengthText = 'Mạnh';
                strengthClass = 'strong';
            } else {
                strengthText = 'Rất mạnh';
                strengthClass = 'very-strong';
            }

            if (password.length > 0) {
                strengthIndicator.innerHTML = `
                    <div class="strength-bar ${strengthClass}">
                        <div class="strength-fill" style="width: ${(strength / 5) * 100}%"></div>
                    </div>
                    <span class="strength-text ${strengthClass}">Mật khẩu ${strengthText}</span>
                `;
            } else {
                strengthIndicator.innerHTML = '';
            }
        }

        // Form validation and submission
        document.getElementById('signupForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('.signup-btn');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span> Đang đăng ký...';
            
            fetch('signup_backend.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                if (data.success) {
                    showNotification('success', data.message);
                    setTimeout(() => {
                        window.location.href = data.redirect || 'index.php';
                    }, 1500);
                } else {
                    showNotification('error', data.message);
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                console.error('Error:', error);
                showNotification('error', 'Có lỗi xảy ra khi đăng ký');
            });
        });

        // Password strength real-time checking
        document.querySelector('.password-input').addEventListener('input', function(e) {
            checkPasswordStrength(e.target.value);
        });

        // Phone number formatting
        document.querySelector('input[type="tel"]').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 0) {
                if (value.length <= 3) {
                    value = value;
                } else if (value.length <= 6) {
                    value = value.slice(0, 3) + ' ' + value.slice(3);
                } else if (value.length <= 10) {
                    value = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6);
                } else {
                    value = value.slice(0, 3) + ' ' + value.slice(3, 6) + ' ' + value.slice(6, 10);
                }
            }
            e.target.value = value;
        });

        // Page ready (no overlay open/close - same behavior as login)
        document.addEventListener('DOMContentLoaded', function() {
            // no-op; CSS handles form slide-in and overlay dimming
        });
    window.addEventListener('load', function() {
    document.body.style.animation = 'none';
    setTimeout(() => { document.body.style.animation = 'fadeIn 1s forwards'; }, 10);
});
    </script>
    
    <!-- Scroll Animations -->
    <script src="JS/scroll-animations.js"></script>
</body>
</html>