<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

if (!isset($_SESSION['username'])) {
    header("Location: user_login.php");
    exit();
}

$currentPassword = $_POST['current_password'];
$newPassword = $_POST['password'];
$confirmPassword = $_POST['confirm_password'];
$username = $_SESSION['username'];

// ตรวจสอบรหัสผ่านเดิม
$sql = "SELECT password FROM student WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!password_verify($currentPassword, $user['password'])) {
    echo "<script>alert('Current password is incorrect.'); window.history.back();</script>";
    exit();
}

// ตรวจสอบว่ารหัสผ่านใหม่และยืนยันรหัสผ่านตรงกัน
if ($newPassword !== $confirmPassword) {
    echo "<script>alert('New passwords do not match.'); window.history.back();</script>";
    exit();
}

// อัปเดตรหัสผ่านใหม่
$newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
$sql = "UPDATE student SET password = ? WHERE student_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $newPasswordHash, $username); // ใช้ $username แทน $student_id

if ($stmt->execute()) {
    echo "<script>alert('Password updated successfully.'); window.location.href = 'user_profile.php';</script>";
} else {
    echo "<script>alert('Failed to update password.'); window.history.back();</script>";
}

$stmt->close();
$conn->close();
?>