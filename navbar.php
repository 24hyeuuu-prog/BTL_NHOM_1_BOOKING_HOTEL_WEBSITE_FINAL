<?php
$current_page = basename($_SERVER['PHP_SELF'], '.php');
?>
<nav class="navbar">
    <div><a href="index.php"><img src='img/logo.jpg' alt="logo" class="logo"></a></div>
    <div class="nav-links">
        <a href="index.php" class="nav-link <?php echo ($current_page == 'index') ? 'active' : ''; ?>">TRANG CHỦ</a>
        <a href="khachsan2.php" class="nav-link <?php echo ($current_page == 'khachsan2') ? 'active' : ''; ?>">KHÁCH SẠN</a>
        <a href="diemden.php" class="nav-link <?php echo ($current_page == 'diemden') ? 'active' : ''; ?>">ĐIỂM ĐẾN</a>
        <a href="vechungtoi.php" class="nav-link <?php echo ($current_page == 'vechungtoi') ? 'active' : ''; ?>">VỀ CHÚNG TÔI</a>
       
    </div>
    <div class="user-section">
        <?php if(isset($_SESSION['user_id']) && isset($_SESSION['username'])): ?>
            <div class="user-dropdown">
               
                <div class="user-icon" onclick="toggleUserMenu()">
                    <?php if(isset($_SESSION['avatar']) && $_SESSION['avatar']): ?>
                        <img src="<?php echo $_SESSION['avatar']; ?>" alt="User Avatar" class="user-avatar">
                    <?php else: ?>
                        <span><?php echo strtoupper(substr($_SESSION['username'], 0, 1)); ?></span>
                    <?php endif; ?>
                </div>
                <div class="user-menu" id="userMenu">
                    <div class="user-info">
                        <span class="username"><?php echo $_SESSION['username']; ?></span>
                        <?php if(isset($_SESSION['email'])): ?>
                            <span class="user-email"><?php echo $_SESSION['email']; ?></span>
                        <?php endif; ?>
                    </div>
                    <hr>
                    <a href="userpro.php" class="menu-item">
                        <i class="fas fa-user"></i> Hồ sơ
                    </a>
                    <a href="phongdadat_new.php" class="menu-item">
                        <i class="fa-solid fa-hotel"></i> Xem phòng đã đặt
                    </a>
                    <a href="phongdahuy_new.php" class="menu-item">
                        <i class="fas fa-times-circle"></i> Xem phòng đã hủy
                    </a>
                    <a href="phongdahoanthanh_new.php" class="menu-item">
                        <i class="fas fa-check-circle"></i> Xem phòng đã sử dụng qua
                    </a>
                    <a href="logout.php" class="menu-item logout">
                        <i class="fas fa-sign-out-alt"></i> Đăng xuất
                    </a>
                </div>
            </div>
        <?php else: ?>
            <div class="auth-buttons">
                <a href="login.php" class="login-btn">Đăng nhập</a>
                <a href="signup.php" class="signup-btn">Đăng ký</a>
            </div>
        <?php endif; ?>
    </div>
</nav>

<style>
    /* Navigation Bar */

.navbar {
    width: 100%; 
    max-width: 1728px;
    height: 60px;
    background-color: #FFFFFF;
    position: fixed; /* giữ thanh điều hướng ở trên cùng  khi cuộn */
    top: 0;
    left: 50%; /* căn giữa thanh điều hướng */
    transform: translateX(-50%); /* dịch chuyển về giữa */
    z-index: 1000; /* đảm bảo thanh điều hướng nằm trên các phần tử khác khi cuộn */
    display: flex; /* sử dụng kiểu hiển thị linh họat để căn chỉnh các phần tử bên trong */
    align-items: center; /* đảm bảo các phần tử bên trong được căn chỉnh theo chiều dọc */
    box-shadow: 0 2px 4px rgba(0,0,0,0.1); /* thêm bóng đổ nhẹ */
    padding: 0 20px; /* thêm khoảng cách bên trái và bên phải */
}

.logo {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: linear-gradient(45deg, #5DCCF9, #23A6F0);
    margin-left: clamp(20px, 15%, 283px); /* căn giữa logo với khoảng cách tối thiểu 20px và tối đa 283px */
    display: flex;
    align-items: center;
    justify-content: center; /* căn giữa nội dung bên trong logo */
    color: white;
    font-weight: bold;
    font-size: 20px;
}

.nav-links {
    display: flex;
    align-items: center;
    margin-left: clamp(20px, 10%, 163px); /* căn giữa các liên kết với khoảng cách tối thiểu 20px và tối đa 163px */
    flex: 1; /* cho phép các liên kết chiếm không gian còn lại */
}

.nav-link {
    font-family: Arial, sans-serif;
    font-size: 16px;
    color: #3D3E48;
    text-decoration: none; /* loại bỏ gạch chân */
    margin-right: clamp(20px, 4%, 61px); /* khoảng cách giữa các liên kết */
}




.nav-link.active {
    font-weight: bold;
    color: #2196F3;
}
 


.user-section {
    margin-left: auto;
    margin-right: clamp(20px, 15%, 277px);
    display: flex;
    align-items: center;
}

.user-icon {
    width: 42px;
    height: 42px;
    border-radius: 50%;
    background-color: #C4C4C4;
    margin-right: 21px;
}

.language {
    font-family: Poppins, sans-serif;
    font-weight: bold;
    font-size: 16px;
    color: #3D3E48;
}
.user-dropdown {
    position: relative;
    cursor: pointer;

}

.user-icon {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #2196F3;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
    cursor: pointer;
    transition: all 0.3s ease;
}

.user-icon:hover {
    background: #1976D2;
    transform: scale(1.05);
}

.user-avatar {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

.user-menu {
    position: absolute;
    top: 50px;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 8px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    min-width: 260px;
    z-index: 1000;
    display: none;
    overflow: hidden;
}

.user-menu.show {
    display: block;
    animation: fadeInDown 0.3s ease;
}

.user-info {
    padding: 15px;
    background: #f8f9fa;
}

.username {
    font-weight: bold;
    color: #333;
    display: block;
}

.user-email {
    font-size: 12px;
    color: #666;
    display: block;
    margin-top: 2px;
}

.menu-item {
    display: block;
    padding: 12px 10px;
    color: #333;
    text-decoration: none;
    transition: background 0.3s ease;
}

.menu-item:hover {
    background: #f8f9fa;
}

.menu-item.logout {
    color: #dc3545;
    border-top: 1px solid #eee;
}

.menu-item.logout:hover {
    background: #fee;
}

.menu-item i {
    margin-right: 8px;
    width: 16px;
}

.auth-buttons {
    display: flex;
    gap: 10px;
}

.login-btn, .signup-btn {
    padding: 8px 16px;
    border-radius: 20px;
    text-decoration: none;
    font-size: 14px;
    transition: all 0.3s ease;
}

.login-btn {
    color: #2196F3;
    border: 1px solid #2196F3;
}

.login-btn:hover {
    background: #2196F3;
    color: white;
}

.signup-btn {
    background: #2196F3;
    color: white;
    border: 1px solid #2196F3;
}

.signup-btn:hover {
    background: #1976D2;
    border-color: #1976D2;
}

@keyframes fadeInDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
function toggleUserMenu() {
    const menu = document.getElementById('userMenu');
    menu.classList.toggle('show');
}

// Close menu when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.querySelector('.user-dropdown');
    const menu = document.getElementById('userMenu');
    
    if (dropdown && !dropdown.contains(event.target)) {
        menu.classList.remove('show');
    }
});
</script>