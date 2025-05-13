<?php
// เชื่อมต่อฐานข้อมูล
session_start();
include '/xampp/htdocs/Project_Final/server.php';

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// รับข้อมูลจากฟอร์ม

$name = $_POST['name'];
$email = $_POST['email'];


// อัปเดตข้อมูลในฐานข้อมูล
$sql = "UPDATE admins SET username = '$name', admin_email = '$email'";
if ($conn->query($sql) === TRUE) {
    echo "แก้ไขข้อมูลสำเร็จ";
    header("Location: admin_dashboard.php"); // เปลี่ยนหน้าไปยังหน้าหลัก
    exit();
} else {
    echo "เกิดข้อผิดพลาด: " . $conn->error;
}

$conn->close();
