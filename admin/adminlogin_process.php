<?php
session_start();
include '/xampp/htdocs/Project_Final/server.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $query = "SELECT * FROM admins WHERE username='$username'";
    $result = mysqli_query($conn, $query);

    // ตรวจสอบข้อผิดพลาดของคำสั่ง SQL
    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);
        $hashed_password = $row['password'];

        if (password_verify($password, $hashed_password)) {
            $_SESSION['username'] = $username;
            header("Location: ../admin/admin_dashboard.php");
        } else {
            $_SESSION['login_error'] = "Invalid username or password";
            $_SESSION['username'] = $username;
            header("Location: ../admin/adminlogin.php");
        }
    } else {
        $_SESSION['login_error'] = "Invalid username or password";
        $_SESSION['username'] = $username;
        header("Location: ../admin/adminlogin.php");
    }
    exit();
}
