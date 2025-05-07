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
    // $officer_id = intval($_POST['officer_id']);
    $f_name = $conn->real_escape_string($_POST['f_name']);
    $l_name = $conn->real_escape_string($_POST['l_name']);
    $campus = $conn->real_escape_string($_POST['campus']);
    $room_number = $conn->real_escape_string($_POST['room_number']);
    $position = $conn->real_escape_string($_POST['position']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

    // อัปเดตข้อมูลนักเรียนในฐานข้อมูล
    $sql = "UPDATE teacher SET password='$password',f_name='$f_name', l_name='$l_name', campus='$campus',, room_number='$room_number', position='$position' WHERE officer_id=$officer_id";

    if ($conn->query($sql) === TRUE) {
        header("Location: admin_edit_teacher.php?success=1");
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $conn->close();
} else {
    echo "Invalid request method";
}
?>  