<?php
require_once 'config/config.php';

// Hủy session
session_unset();
session_destroy();

// Redirect về trang chủ
header('Location: index.php');
exit;
?>