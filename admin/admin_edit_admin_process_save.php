<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

if (!isset($_SESSION['username'])) {
    header("Location: admin_login.php");
    exit();
}

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $admin_id = intval($_POST['admin_id']);
    $username = $conn->real_escape_string($_POST['username']);
    $admin_email = $conn->real_escape_string($_POST['admin_email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // อัปเดตข้อมูล admin ในฐานข้อมูล
    $sql = "UPDATE admins SET username='$username', admin_email='$admin_email', password='$password' WHERE admin_id=$admin_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin_edit_admin.php?success=1");
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid request method";
}
