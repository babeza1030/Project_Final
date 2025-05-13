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
    $student_id = intval($_POST['student_id']);
    $student_code = $conn->real_escape_string($_POST['student_code']);
    $f_name = $conn->real_escape_string($_POST['f_name']);
    $l_name = $conn->real_escape_string($_POST['l_name']);
    $address = $conn->real_escape_string($_POST['address']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // อัปเดตข้อมูลนักเรียนในฐานข้อมูล
    $sql = "UPDATE student SET student_code='$student_code',password='$password', f_name='$f_name', l_name='$l_name', address='$address' WHERE student_id=$student_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin_edit_student.php?success=1");
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid request method";
}
