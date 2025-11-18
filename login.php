
<?php 
require_once 'config/config.php';

// Nếu đã đăng nhập, chuyển về trang chủ
if (isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$message = getMessage();
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - LaValle</title>
    <link rel="stylesheet" href="CSS/login.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <!-- Chatbot -->
    <!-- Notification Container -->
    <div class="notification-container" id="notificationContainer">
        <?php if($message): ?>
            <div class="notification <?php echo $message['type']; ?> show">
                <div class="notification-content">
                    <div class="notification-icon">
                        <?php if($message['type'] == 'success'): ?>
                            <i class="fas fa-check-circle"></i>
                        <?php elseif($message['type'] == 'error'): ?>
                            <i class="fas fa-exclamation-triangle"></i>
                        <?php endif; ?>
                    </div>
                    <div class="notification-message"><?php echo $message['text']; ?></div>
                    <button class="notification-close" onclick="closeNotification(this)">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="login-container">
        <!-- Right Side (Overlay) -->
        <div class="right-section">
            <div class="login-form-container">
                <!-- Header -->
                <div class="form-header">
                    <div class="welcome-text">
                        <span class="welcome">Welcome to </span>
                        <span class="brand">LAVALLE</span>
                    </div>
                    <h2 class="form-title">ĐĂNG NHẬP</h2>
                </div>

                <!-- Login Form -->
                <form class="login-form" id="loginForm">
                    <div class="form-group">
                        <label class="form-label">Nhập tên đăng nhập hoặc địa chỉ email</label>
                        <div class="input-container">
                            <i class="fas fa-envelope input-icon"></i>
                            <input type="text" name="login" class="form-input" placeholder="Tên đăng nhập hoặc địa chỉ email" required aria-label="Tên đăng nhập hoặc email">
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mật khẩu của bạn</label>
                        <div class="password-input-container">
                            <i class="fas fa-lock input-icon"></i>
                            <input type="password" name="password" class="form-input password-input" placeholder="Mật khẩu" required aria-label="Mật khẩu">
                            <button type="button" class="password-toggle" onclick="togglePassword()" aria-label="Toggle password visibility">
                                <i class="fas fa-eye" id="passwordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-options">
                        <div class="remember-me">
                            <input type="checkbox" id="remember" name="remember" class="checkbox-input">
                            <label for="remember" class="checkbox-label">Ghi nhớ tôi</label>
                            <!-- <a href="#" class="forgot-password">Quên mật khẩu?</a> -->
                        </div>
                        <!-- <a href="#" class="forgot-password">Quên mật khẩu?</a> -->
                    </div>
                    <button type="submit" class="login-btn">Đăng nhập</button>
                    <!-- <a href="#" class="forgot-password">Quên mật khẩu?</a> -->
                    
                    <!-- Divider -->
                    <div class="divider">
                        <span>Hoặc</span>
                    </div>

                    <!-- Google Sign In Button -->
                    <button type="button" class="google-signin-btn" id="googleSignInBtn">
                        <svg class="google-icon" viewBox="0 0 24 24" width="20" height="20">
                            <path fill="#EA4335" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92c-.26 1.37-1.04 2.53-2.21 3.31v2.77h3.57c2.08-1.92 3.28-4.74 3.28-8.09z"/>
                            <path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84C3.99 20.53 7.7 23 12 23z"/>
                            <path fill="#4285F4" d="M5.84 14.09c-.22-.66-.35-1.36-.35-2.09s.13-1.43.35-2.09V7.07H2.18C1.43 8.55 1 10.22 1 12s.43 3.45 1.18 4.93l2.85-2.22.81-.62z"/>
                            <path fill="#FBBC05" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.07l3.66 2.84c.87-2.6 3.3-4.53 6.16-4.53z"/>
                        </svg>
                        Đăng nhập với Google
                    </button>

                    <div class="signup-link">
                        <span>Chưa có tài khoản ? </span>
                        <a href="signup.php" class="create-account">Tạo tài khoản</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Firebase SDK -->
    <script type="module">
        // Import Firebase functions
        import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-app.js";
        import { getAuth, signInWithPopup, GoogleAuthProvider } from "https://www.gstatic.com/firebasejs/10.7.0/firebase-auth.js";

        // Firebase configuration
        const firebaseConfig = {
            apiKey: "AIzaSyDqtly0LTv0FEpTkdv8drTHUyLh5LAxV3k",
            authDomain: "lavalle-a09df.firebaseapp.com",
            projectId: "lavalle-a09df",
            storageBucket: "lavalle-a09df.firebasestorage.app",
            messagingSenderId: "577464739498",
            appId: "1:577464739498:web:10d56942d7c62a8fa1d2d3"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const googleProvider = new GoogleAuthProvider();
        const auth = getAuth();

        // Google Sign In Button Handler
        document.getElementById('googleSignInBtn').addEventListener('click', async function(e) {
            e.preventDefault();
            const btn = this;
            const originalText = btn.innerHTML;
            
            try {
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
                
                // Sign in with Google popup
                const result = await signInWithPopup(auth, googleProvider);
                
                // Get user info from Firebase
                const user = result.user;
                const credential = GoogleAuthProvider.credentialFromResult(result);
                const token = credential.accessToken;
                
                // Send to backend for session creation
                const response = await fetch('api/google-login.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        uid: user.uid,
                        email: user.email,
                        displayName: user.displayName,
                        photoURL: user.photoURL,
                        token: token
                    })
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showNotification('success', 'Đăng nhập thành công!');
                    setTimeout(() => {
                        window.location.href = data.redirect || 'index.php';
                    }, 1500);
                } else {
                    showNotification('error', data.message || 'Đăng nhập thất bại');
                    btn.disabled = false;
                    btn.innerHTML = originalText;
                }
            } catch (error) {
                console.error('Google Sign In Error:', error);
                let errorMessage = 'Đăng nhập Google thất bại';
                
                if (error.code === 'auth/popup-closed-by-user') {
                    errorMessage = 'Bạn đã đóng cửa sổ đăng nhập';
                } else if (error.code === 'auth/popup-blocked') {
                    errorMessage = 'Cửa sổ đăng nhập bị chặn. Vui lòng kiểm tra cài đặt popup';
                }
                
                showNotification('error', errorMessage);
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        });
    </script>

    <script>
        // Notification System
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
        // Notification System
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

        // Password toggle functionality
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

        // Form submission
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('.login-btn');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner"></span> Đang đăng nhập...';
            
            fetch('login_backend.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Đăng nhập';
                if (data.success) {
                    showNotification('success', data.message);
                    setTimeout(() => {
                        window.location.href = data.redirect || 'home2.php';
                    }, 1500);
                } else {
                    showNotification('error', data.message);
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = 'Đăng nhập';
                console.error('Error:', error);
                showNotification('error', 'Có lỗi xảy ra khi đăng nhập');
            });
        });
    </script>
    
    <!-- Scroll Animations -->
    <script src="JS/scroll-animations.js"></script>
</body>
</html>
</DOCUMENT>